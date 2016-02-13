<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller {
	
	var $userdata;
	 	 
	public function __construct() 
	{
		parent::__construct();
		$this->load->library('tank_auth_my');		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->session_data = $this->session->userdata('logged_in');
	} 
	
	public function index() 
	{
		$this->form_validation->set_rules('name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
		$this->form_validation->set_rules('message', 'Message', 'required|xss_clean');
		
		if ($this->form_validation->run() === FALSE){
			if (!$this->tank_auth_my->is_logged_in()) {
				$this->load->view('templates/client/header');
				$this->load->view('pages/contact/index');
				$this->load->view('templates/client/footer');
			} else {
				$this->load->view('templates/header');
				$this->load->view('pages/contact/index');
				$this->load->view('templates/footer');
			}
		} else {
			
			$message = array(
				'name' => $this->input->post('name'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'message' => $this->input->post('message') 
			);
			
			$this->_send_contact_email($message);
			
			if (!$this->tank_auth_my->is_logged_in()) {
				$this->load->view('templates/client/header');
				$this->load->view('pages/contact/index');
				$this->load->view('templates/client/footer');
			} else {
				$this->load->view('templates/header');
				$this->load->view('pages/contact/index');
				$this->load->view('templates/footer');
			}
		}
	}
	
	private function _send_contact_email($message) 
	{
		$this->load->library('email');
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from($message['email']);
		$this->email->to("hello@rubyinvoice.com"); 
		$this->email->subject("Message from a Customer");
		$this->email->message($message['message']);	
		
		$this->email->send();
		//echo $this->email->print_debugger();
	}
	
	
}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */