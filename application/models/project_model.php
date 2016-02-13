<?php
class Project_model extends CI_Model {
  public $session_data;
  public function __construct()
  {
    $this->load->database();
  }

  public function get_projects($cid)
  {
    $uid = $this->tank_auth_my->get_user_id();
    $this->db->select("p.project_id, p.project_name, p.status", false);

    $this->db->from('projects p');
    $this->db->where('p.uid', $uid);
    $this->db->where('p.cid', $cid);
    $this->db->order_by("project_id", "desc");
    $query = $this->db->get();
    $projects = $query->result_array();

    if ($query->num_rows() > 0)
    {

      foreach ($projects as &$project) {

        $query = $this->db->order_by('id', 'desc')->get_where('tasks', array('project_id' => $project['project_id']));

        foreach ($query->result() as $task) {

          if (!empty($task)) {
            $project['tasks'][] = $task;

            $query2 = $this->db->get_where('timers', array('task_id' => $task->id));

            foreach ($query2->result() as $timer) {
              $task->timers[] = $timer;
            }
          }
        }
      }

      return $projects;
    }

    else {

      return;
    }
  }

  public function get_project($project_id = FALSE)
  {
    $this->db->select('*');
    $this->db->from('projects');
    $this->db->where('id', $project_id);

    $query = $this->db->get();
    return $query->result_array();
  }

  public function set_project($data)
  {
    $uid = $this->tank_auth_my->get_user_id();
    $data = array(
      'project_name' => $data['prj_name'], 'uid' => $uid, 'cid' => $data['cid']
    );
    $this->db->insert('projects', $data);
    $data['project_id'] = $this->db->insert_id();

    return $data;
  }

  public function update_project($data)
  {

    $project_id = $data['project_id'];
    $uid = $this->tank_auth_my->get_user_id();
    $pdata = array(
      'project_name' => $data['prj_name'], 'status' => $data['status']
    );

    $this->db->start_cache();
    $this->db->select('*', false);
    $this->db->where('project_id', $project_id);
    $this->db->where('uid', $uid);
    $this->db->from('projects');
    $this->db->stop_cache();

    $query = $this->db->get();

    $this->db->update('projects', $pdata);
    $this->db->flush_cache();

    return $query->result_array();
  }

  public function delete_project($data)
  {
    $project_id = $data['project_id'];
    $uid = $this->tank_auth_my->get_user_id();

    $this->db->start_cache();
    $this->db->select('*', false);
    $this->db->where('project_id', $project_id);
    $this->db->where('uid', $uid);
    $this->db->where('cid', $data['cid']);
    $this->db->from('projects');
    $this->db->limit(1);
    $this->db->stop_cache();

    $query = $this->db->get();
    $this->db->delete('projects');
    $this->db->flush_cache();

    if ($query->num_rows() > 0)
    {
      // Delete all associated tasks
      $this->db->where('project_id', $project_id);
      $this->db->delete('tasks');

      // Delete all associated timers
      $this->db->where('project_id', $project_id);
      $this->db->delete('timers');
    }

    return $query->result_array();
  }

  public function get_task($task_id = FALSE)
  {
    $uid = $this->tank_auth_my->get_user_id();
    $this->db->select('*');
    $this->db->from('tasks');
    $this->db->where('id', $task_id);
    $this->db->where('uid', $uid);

    $query = $this->db->get();
    return $query->result_array();
  }

  public function set_task($data)
  {
    $uid = $this->tank_auth_my->get_user_id();
    $data = array(
      'uid' => $uid, 'project_id' => $data['project_id'], 'cid' => $data['cid'], 'task_name' => $data['task_name'], 'time_estimate' => $data['time_estimate'], 'rate' => $data['rate'], 'date' => date('Y-m-d H:i:s')
    );

    $this->db->insert('tasks', $data);
    $data['id'] = $this->db->insert_id();
    return $data;
  }

  public function update_task($data)
  {
    $id = $data['id'];
    $uid = $this->tank_auth_my->get_user_id();
    $tdata = array(
      'task_name' => $data['task_name'], 'time_estimate' => $data['time_estimate'], 'rate' => $data['rate']
    );
    $this->db->where('id', $id);
    return $this->db->update('tasks', $tdata);
  }

  public function delete_task($data)
  {
    $id = $data['id'];
    $uid = $this->tank_auth_my->get_user_id();

    $this->db->start_cache();
    $this->db->select('*', false);
    $this->db->where('id', $id);
    $this->db->where('uid', $uid);
    $this->db->from('tasks');
    $this->db->limit(1);
    $this->db->stop_cache();

    $query = $this->db->get();
    $this->db->delete('tasks');
    $this->db->flush_cache();

    if ($query->num_rows() > 0)
    {
      // Delete all associated timers
      $this->db->where('task_id', $id);
      $this->db->delete('timers');
    }
  }

  public function set_timer()
  {
    $task_id = $this->input->post('task_id');
    $client_id = $this->input->post('client_id');
    $project_id = $this->input->post('project_id');
    $time = $this->input->post('timer');
    $description = $this->input->post('description');

    $data = array(
      'task_id' => $task_id,
      'project_id' => $project_id,
      'description' => $description,
      'time_started' => date('Y-m-d H:i:s'),
      'time' => $time
    );

    return $this->db->insert('timers', $data);
  }

  public function delete_timer($data)
  {
    $timer_id = $data['timer_id'];

    $this->db->select('*', false);
    $this->db->where('timer_id', $timer_id);
    $this->db->from('timers');
    $this->db->limit(1);
    $this->db->delete('timers');
    return $timer_id;

  }

  public function convert_to_invoice($pid, $cid)
  {
    $uid = $this->tank_auth_my->get_user_id();
    $this->db->select("p.project_id, p.project_name", false);

    $this->db->from('projects p');
    $this->db->where('p.uid', $uid);
    $this->db->where('p.project_id', $pid);
    $this->db->order_by("project_id", "desc");
    $query = $this->db->get();
    $projects = $query->result_array();
    $items_to_convert = array();
    $timers_combined = array();
    $timers_time = 0;
    $sumTotal = 0;
    $today = date('Y-m-d');
    $due_date = $this->_calc_due_date($uid, $today);

    $i = 0;


    if ($query->num_rows() > 0)
    {

      foreach ($projects as &$project) {

        $query = $this->db->order_by('id', 'desc')->get_where('tasks', array('project_id' => $project['project_id']));

        // CREATE AN INVOICE TABLE
        $common_data = array('uid' => $uid, 'cid' => $cid, 'date' => $today, 'due_date' => $due_date);

        $this->db->insert('common', $common_data);

        // Get the table id of the last row updated using insert_id() function
        $common_id = $this->db->insert_id();


        foreach ($query->result() as $task) {

          if (!empty($task)) {

            $items_to_convert['item'][]['description'] = $project['project_name'].' - '.$task->task_name."\n";

            $query2 = $this->db->get_where('timers', array('task_id' => $task->id));

            // CREATES AN EMPTY STRING TO CONCATINATE TO IN NEXT LOOP
            $timers_combined[$i] = '';

            foreach ($query2->result() as $timer) {

              // CONTAINER TO HOLD AND CONCATINATE TIMER DESCRIPTIONS
              $timers_combined[$i] .= $timer->description."\n";

              $timers_time += $timer->time;

              // UPDATE THE TIMER'S INVOICED TABLE
              $this->db->where('timer_id', $timer->timer_id);
              $invoiced = array('common_id' => $common_id);
              $this->db->update('timers', $invoiced);
            }
          }

          // ADD ITEM PROPERTIES
          $items_to_convert['item'][$i]['description'] .= $timers_combined[$i];
          $items_to_convert['item'][$i]['unit'] = 'hours';
          $items_to_convert['item'][$i]['unit_cost'] = $task->rate;
          $items_to_convert['item'][$i]['common_id'] = $common_id;

          // SUM OF THE TIMERS
          $hours = $this->_convertToHours($timers_time);
          $items_to_convert['item'][$i]['quantity'] = $hours;
          // CALCULATE THE TASK TOTALS
          $sumTotal += ($hours * $task->rate);

          $i++;
        }
        $this->db->insert_batch('item', $items_to_convert['item']);

        // UPDATE THE INVOICE WITH THE TOTALS FROM THE TASK TIMERS
        $this->db->where('id', $common_id);
        $this->db->limit(1);
        $amount = array('amount' => $sumTotal);
        $this->db->update('common', $amount);

      }

      return $items_to_convert;
    }

    else {

      return;
    }
  }

  public function get_user_settings($uid) {
    $this->db->select('*', false);
    $this->db->where('s.uid', $uid);
    $this->db->limit(1);
    $this->db->from('settings s');
    $query = $this->db->get();

    return $query->result_array();
  }

  private function _convertToHours($total_time)
  {
    $time_unit = 3600; // time measured in seconds
    $time_hours = round($total_time / $time_unit, 2);
    return $time_hours;
  }

  private function _calc_due_date($uid, $dateString)
  {
    // Calculate the due date based on the invoice creation date, and the user's "due in" settings
    $userSettings = $this->get_user_settings($uid);
    $date = new DateTime($dateString);
    $date->add(new DateInterval('P'.$userSettings[0]['due'].'D'));
    //
    return $date->format('Y-m-d');
  }

}
