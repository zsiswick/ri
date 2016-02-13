<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Features extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}

	public function index()
	{
		$data['title'] = "Ruby Invoice - Invoicing Features for Busy Freelancers";
		$this->load->view('templates/client/header', $data);
		$this->load->view('pages/features/index');
		$this->load->view('templates/client/footer');
	}


}

/* End of file features.php */
/* Location: ./application/controllers/features.php */
