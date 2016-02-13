<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	var $userdata;

	public function __construct() {
		parent::__construct();
		$this->load->library('tank_auth_my');
		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		}
		$this->load->model('client_model');
	}

	public function index() {

		$uid = $this->tank_auth_my->get_user_id();
		$data['clients'] = $this->client_model->get_clients($uid);
		$data['first_name']	= $this->tank_auth_my->get_username();
		//print("<pre>".print_r($data['clients'],true)."</pre>");
		$this->load->view('templates/header');
		$this->load->view('pages/clients/index', $data);
		$this->load->view('templates/footer');
	}

	public function create() {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$uid = $this->tank_auth_my->get_user_id();

		$cl_data = array(
			'company'=>$this->input->post('company'),
			'contact'=> $this->input->post('contact'),
			'address_1'=>$this->input->post('address_1'),
			'address_2'=>$this->input->post('address_2'),
			'zip'=>$this->input->post('zip'),
			'city'=>$this->input->post('city'),
			'state'=>$this->input->post('state'),
			'country'=>$this->input->post('country'),
		);

		$data['title'] = 'Add a client';
		$this->form_validation->set_rules('company', 'Company', 'required|xss_clean');
		$this->form_validation->set_rules('contact', 'Contact Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('address_1', 'Address 1', 'xss_clean');
		$this->form_validation->set_rules('address_2', 'Address 2', 'xss_clean');
		$this->form_validation->set_rules('zip', 'Zip Code', 'alpha_dash|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'xss_clean');
		$this->form_validation->set_rules('state', 'State', 'xss_clean');
		$this->form_validation->set_rules('country', 'Country', 'xss_clean');
		$this->form_validation->set_rules('tax_id', 'Tax ID', 'xss_clean');
		$this->form_validation->set_rules('notes', 'Notes', 'xss_clean');

		if($this->input->is_ajax_request()){
			$respond=array();
			if($this->form_validation->run()==false){
			   $respond['result'] = 'false';
			   $respond['errors'] = validation_errors();
			} else {
			  $respond['result'] = 'true';
				$respond['errors'] = 'The client was added!';
				$respond['records'] = $cl_data;
				$this->client_model->set_client($uid);
			}
			return $this->output->set_output(json_encode($respond));
		}


		if ($this->form_validation->run() === FALSE) {
			$this->load->view('templates/header', $data);
			$this->load->view('pages/clients/create');
			$this->load->view('templates/footer');
		} else {
			$this->client_model->set_client($uid);
			redirect('/clients', 'refresh');

		}
	}

	public function create_ajax()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$uid = $this->tank_auth_my->get_user_id();
		$data['title'] = 'Add a client';

		$this->load->view('pages/clients/create-ajax');
	}

	public function edit($id = false) {

		$client_id = $this->uri->segment(3, 0);
		$data['client'] = $this->client_model->get_client($client_id);
		if (empty($data['client']))
		{
			show_404();
		}
			else
		{
			$this->load->helper('form');
			$this->load->library('form_validation');

			$data['title'] = 'Edit Client';
			$this->form_validation->set_rules('company', 'Company', 'required|xss_clean');
			$this->form_validation->set_rules('contact', 'Contact Name', 'required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('address_1', 'Address 1', 'xss_clean');
			$this->form_validation->set_rules('address_2', 'Address 2', 'xss_clean');
			$this->form_validation->set_rules('zip', 'Zip Code', 'alpha_dash|xss_clean');
			$this->form_validation->set_rules('city', 'City', 'xss_clean');
			$this->form_validation->set_rules('state', 'State', 'xss_clean');
			$this->form_validation->set_rules('country', 'Country', 'xss_clean');
			$this->form_validation->set_rules('tax_id', 'Tax ID', 'xss_clean');
			$this->form_validation->set_rules('notes', 'Notes', 'xss_clean');

			if ($this->form_validation->run() === FALSE) {
				$this->load->view('templates/header', $data);
				$this->load->view('pages/clients/edit', $data);
				$this->load->view('templates/footer', $data);
			} else {
				$this->client_model->update_client();
				redirect('/clients/', 'refresh');
			}
		}
	}

	public function delete($id = FALSE)
	{

		if ( $id === FALSE ) {
				show_404();
		} else {

			$this->client_model->delete_client($id);
			redirect('/clients', 'refresh');
		}

	}

	public function invoices($cid = FALSE)
	{
		if ( $cid === FALSE ) {
				show_404();
		} else {
			$data['title'] = 'client invoices';
			$data['client'] = $this->client_model->get_client($cid);
			if (empty($data['client']))
			{
				show_404();
			} else {
				$this->load->model('invoice_model');
				$this->invoice_model->get_set_due_invoices();
				$id = $this->tank_auth_my->get_user_id();

				$this->load->library('pagination');
				$config['base_url'] = "http://localhost/rubyinvoice/index.php/clients/invoices/".$cid."/";
				$config['total_rows'] = $this->invoice_model->get_invoices_rows($id, $cid);
				$config['per_page'] = 12;
				$config['num_links'] = 20;
				$config['uri_segment'] = 2;
				$config['full_tag_open'] = '<ul class="pagination">';
				$config['full_tag_close'] = '</ul>';
				$config['num_tag_open'] = '<li>';
				$config['num_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="current"><a href="#">';
				$config['cur_tag_close'] = '</a></li>';
				$config['next_tag_open'] = '<li class="arrow">';
				$config['next_tag_close'] = '</li>';
				$config['prev_tag_open'] = '<li class="arrow">';
				$config['prev_tag_close'] = '</li>';

				$this->pagination->initialize($config);

				$data['invoices'] = $this->invoice_model->get_invoices($id, $config['per_page'], $this->uri->segment(2), $cid);
				$data['payments'] = $this->invoice_model->get_invoices_payments($id);

				$data['user_id'] = $this->tank_auth_my->get_user_id();
				$data['username']	= $this->tank_auth_my->get_username();
				$data['status_flags'] = unserialize(STATUS_FLAGS);

				$this->load->view('templates/header', $data);
				$this->load->view('pages/invoices/index', $data);
				$this->load->view('templates/footer', $data);
			}

		}
	}

	public function quotes($cid = FALSE)
	{
		//CHECK IF USER IS LOGGED IN
		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['client'] = $this->client_model->get_client($cid);
			if (empty($data['client']))
			{
				show_404();
			} else {
				$this->load->model('quote_model');
				$uid = $this->tank_auth_my->get_user_id();
				$data['quotes'] = $this->quote_model->get_quotes($uid, $cid);
				$data['quote_flags'] = unserialize(QUOTE_FLAGS);

				$this->load->view('templates/header', $data);
				$this->load->view('pages/quotes/index', $data);
				$this->load->view('templates/footer', $data);
			}
		}

	}

	public function projects($cid = FALSE)
	{
		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$data['client'] = $this->client_model->get_client($cid);
			$data['title'] = 'client projects';
			$this->load->model('project_model');
			$data['projects'] = $this->project_model->get_projects($cid);
			$jsfiles = array('project.js', 'mm-foundation-0.4.0.min.js', 'foundation/foundation.joyride.js', 'vendor/jquery.cookie.js', 'helpers/start-joyride.js');
			$data['js_to_load'] = $jsfiles;

			$this->load->view('templates/header', $data);
			$this->load->view('pages/clients/projects', $data);
			$this->load->view('templates/footer', $data);
		}
	}

	public function get_project_json($cid = FALSE)
	{
		$this->load->model('project_model');
		$data['projects'] = $this->project_model->get_projects($cid);
		print json_encode($data['projects']);
	}

	public function set_project()
	{
		$this->load->model('project_model');
		$data['project_id'] = $this->input->post('project_id');
		$data['prj_name'] = $this->input->post('prj_name');
		$data['status'] = $this->input->post('status');
		$data['cid'] = $this->input->post('cid');

		if (empty($data['project_id']) ) {
			$data['project_id'] = $this->project_model->set_project($data);
			print json_encode($data['project_id']);
		} else {
			$return = $this->project_model->update_project($data);
			print json_encode($return);
		}
	}

	public function delete_project()
	{
		$this->load->model('project_model');
		$data['project_id'] = $this->input->post('project_id');
		$data['cid'] = $this->input->post('cid');
		$return = $this->project_model->delete_project($data);
		print json_encode($return);
	}

	public function set_task()
	{
		$this->load->model('project_model');
		$data['id'] = $this->input->post('id');
		$data['task_name'] = $this->input->post('task_name');
		$data['cid'] = $this->input->post('cid');
		$data['project_id'] = $this->input->post('project_id');
		$data['time_estimate'] = $this->input->post('time_estimate');
		$data['rate'] = $this->input->post('rate');
		$data['update'] = $this->input->post('update');

		if ($data['update'] == "false" || empty($data['update']) ) {
			$return = $this->project_model->set_task($data);
		} else {
			$return = $this->project_model->update_task($data);
		}

		print json_encode($return);
	}

	public function delete_task()
	{
		$this->load->model('project_model');
		$data['id'] = $this->input->post('id');
		$return = $this->project_model->delete_task($data);
		print json_encode($return);
	}

	public function view_timer($task_id = FALSE)
	{
		$this->load->helper('form');
		$this->load->model('project_model');
		$data['task'] = $this->project_model->get_task($task_id);
		$jsfiles = array('timer.js');
		$data['js_to_load'] = $jsfiles;
		$this->load->view('pages/clients/view_timer', $data);
	}

	public function create_timer($task_id = FALSE)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('project_model');
		$data['task'] = $this->project_model->get_task($task_id);
		$this->form_validation->set_rules('timer', 'Timer', 'xss_clean|numeric');
		$this->form_validation->set_rules('description', 'Description', 'xss_clean');

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('pages/clients/view_timer', $data);
		} else {
			$this->project_model->set_timer();
			redirect('/clients/projects/'.$data['task'][0]['cid'], 'refresh');
		}
	}

	public function delete_timer()
	{
		$this->load->model('project_model');
		$data['timer_id'] = $this->input->post('timer_id');
		$return = $this->project_model->delete_timer($data);
		print json_encode($return);
	}

	public function convert_invoice($pid, $cid)
	{
		$this->load->model('project_model');
		$return = $this->project_model->convert_to_invoice($pid, $cid);

		if (!empty($return) ) {
			redirect('/invoices/view/'.$return['item'][0]['common_id'], 'refresh');
		}
	}
}

/* End of file clients.php */
/* Location: ./application/controllers/clients.php */
