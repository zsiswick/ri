<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
	}

	function index()
	{
		$this->load->library('tank_auth_my');
		$data['title'] = "Free Mobile-Friendly Invoicing for Freelancers";
		if (!$this->tank_auth_my->is_logged_in()) {
			$this->load->view('templates/client/header', $data);
			$this->load->view('welcome', $data);
			$this->load->view('templates/client/footer');
		} else {
			redirect('/invoices', 'refresh');
		}

	}

	public function privacy()
	{
		$data['title'] = "Privacy Policy | Ruby Invoice";
		$this->load->view('templates/client/header', $data);
		$this->load->view('pages/privacy');
		$this->load->view('templates/client/footer');
	}

	public function terms()
	{
		$data['title'] = "Terms of Use | Ruby Invoice";
		$this->load->view('templates/client/header', $data);
		$this->load->view('pages/terms-of-service');
		$this->load->view('templates/client/footer');
	}

	public function free_invoice_template()
	{
		$this->load->helper('form');
		$this->load->helper('date_input_helper');
		$this->load->library('form_validation');
		$this->load->model('invoice_model');

		$jsfiles = array('picker.js', 'picker.date.js', 'social-likes/social-likes.min.js', 'auto-numeric/autoNumeric.js', 'jquery-ui.min.js');
		$cssfiles = array('default.css', 'default.date.css', 'social-likes_flat.css');
		$data['css_to_load'] = $cssfiles;
		$data['js_to_load'] = $jsfiles;
		$data['title'] = "Create a Free Invoice";
		$data['register_form'] = $this->load->view('auth/register_form', $data, TRUE);

		$this->load->view('templates/client/header', $data);
		$this->load->view('pages/invoices/create_free_invoice', $data);
		$this->load->view('templates/client/footer', $data);
	}

	public function pdf()
	{
		$data['name'] = $this->input->post('name');
		$data['address'] = $this->input->post('address');
		$data['client_name'] = $this->input->post('client_name');
		$data['client_address'] = $this->input->post('client_address');
		$data['inv_description'] = $this->input->post('inv_description');
		$data['invoice_num'] = $this->input->post('invoice_num');
		$data['send_date'] = $this->input->post('send_date');
		$data['due_date'] = $this->input->post('due_date');
		$data['qty'] = $this->input->post('qty');
		$data['description'] = $this->input->post('description');
		$data['unit_cost'] = $this->input->post('unit_cost');
		$data['terms_conditions'] = $this->input->post('terms_conditions');

		//$this->load->view('pages/invoices/view_free_pdf', $data, false);
		//print("<pre>".print_r($data,true)."</pre>");


		$filename = "ruby_invoice_".$data['client_name'];

		ini_set('memory_limit','32M'); // boost the memory limit if it's low
    $html = $this->load->view('pages/invoices/view_free_pdf', $data, true); // render the view into HTML

    $this->load->library('pdf');
    $pdf = $this->pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($filename, 'D');

		//redirect("../downloads/reports/$filename.pdf");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
