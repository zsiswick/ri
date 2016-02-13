<?php
class Timer_model extends CI_Model {
	
	public $session_data;
	
	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_quotes($uid)
	{		
		$this->db->select("q.id as iid, q.uid, q.cid, q.amount, q.status, q.date_issued, client.company", false);
		$this->db->select("DATE_FORMAT(q.date_issued, '%b %d, %Y') AS pdate", false);
		$this->db->from('quotes q');
		$this->db->join('client', 'client.id = q.cid', 'left');
		$this->db->where('q.uid', $uid);
		//$this->db->group_by('c.uid');
		//$this->db->order_by("date_issued", "desc");
		$query = $this->db->get();
				
		return $query->result_array();
	}
	
	public function create_timer() 
	{
		$description = $this->input->post('description');
		$time_total = $this->input->post('time_total');
		$common_id = $this->input->post('iid');
		
		$timer_data = array('uid' => $uid, 'cid' => $cid, 'description' => $description, 'time_total' => $time_total);
		
	}
	
	public function get_timer() 
	{
		
	}
	
}
