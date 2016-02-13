<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotes extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth_my');

		$this->session_data = $this->session->userdata('logged_in');

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('quote_model');
		$this->load->model('invoice_model');
	}

	public function index()
	{
		//CHECK IF USER IS LOGGED IN
		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$uid = $this->tank_auth_my->get_user_id();
			$data['quotes'] = $this->quote_model->get_quotes($uid);

			$data['quote_flags'] = unserialize(QUOTE_FLAGS);

			$this->load->view('templates/header', $data);
			$this->load->view('pages/quotes/index', $data);
			$this->load->view('templates/footer', $data);
		}

	}

	public function create(){

		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$uid = $this->tank_auth_my->get_user_id();

			$data['settings'] = $this->invoice_model->get_invoice_settings($uid);
			$data['edit'] = FALSE;

			$this->form_validation->set_rules('client', 'Client', 'required|numeric|xss_clean|client');
			$this->form_validation->set_rules('description[]',  'Description', 'required|trim|xss_clean');
			$this->form_validation->set_rules('qty[]',  'Quantity', 'required|numeric');
			$this->form_validation->set_rules('unit_cost[]',  'Unit Cost', 'callback_numeric_money');
			$this->form_validation->set_message('numeric_money', 'Please enter an amount greater than $0.99');

			//print("<pre>".print_r($data['settings'],true)."</pre>");

			if ($this->form_validation->run() === FALSE){

			  $jsfiles = array('picker.js', 'picker.date.js', 'auto-numeric/autoNumeric.js', 'jquery-ui.min.js', 'invoice.js');
			  $cssfiles = array('default.css', 'default.date.css');
			  $data['css_to_load'] = $cssfiles;
			  $data['js_to_load'] = $jsfiles;
				$this->load->view('templates/header', $data);
				$this->load->view('pages/quotes/create', $data);
				$this->load->view('templates/footer', $data);
			}
			else {
				$this->quote_model->set_quote($uid);
				redirect('/quotes', 'refresh');
			}
		}

	}

	public function view()
	{
		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$id = $this->uri->segment(3, 0);
			$uid = $this->tank_auth_my->get_user_id();
			$data['quote'] = $this->quote_model->get_quote($id, $uid);
			$data['theDate'] = $this->_month_string($data['quote'][0]['date_issued']);
			$data['quote_flags'] = unserialize(QUOTE_FLAGS);
			$data['view_send_quote'] = $this->load->view('pages/quotes/email/view_send_quote', $data, TRUE);
			$data['edit'] = FALSE;
			//print("<pre>".print_r( $data['quote'], true )."</pre>");
			$jsfiles = array('invoice.js');
			$data['js_to_load'] = $jsfiles;

			$this->load->view('templates/header', $data);
			$this->load->view('pages/quotes/view', $data);
			$this->load->view('templates/footer', $data);
		}
	}

	public function edit($id){

		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			$uid = $this->tank_auth_my->get_user_id();
			$data['quote'] = $this->quote_model->get_quote($id, $uid);
			$data['settings'] = $this->invoice_model->get_invoice_settings($uid);
			$data['quote_flags'] = unserialize(QUOTE_FLAGS);
			$data['theDate'] = $this->_month_string($data['quote'][0]['date_issued']);
			$data['edit'] = TRUE;

			//$this->form_validation->set_rules('client', 'Client', 'required|numeric|xss_clean|client');
			$this->form_validation->set_rules('description[]',  'Description', 'required|trim|xss_clean');
			$this->form_validation->set_rules('qty[]',  'Quantity', 'required|numeric');
			$this->form_validation->set_rules('unit_cost[]',  'Unit Cost', 'callback_numeric_money');
			$this->form_validation->set_message('numeric_money', 'Please enter an amount greater than $0.99');

			//print("<pre>".print_r($data['quote'],true)."</pre>");

			if ($this->form_validation->run() === FALSE){

			  $jsfiles = array('picker.js', 'picker.date.js', 'auto-numeric/autoNumeric.js', 'jquery-ui.min.js', 'invoice.js');
			  $cssfiles = array('default.css', 'default.date.css');
			  $data['css_to_load'] = $cssfiles;
			  $data['js_to_load'] = $jsfiles;
				$this->load->view('templates/header', $data);
				$this->load->view('pages/quotes/view', $data);
				$this->load->view('templates/footer', $data);
			}
			else {
				$val = $this->quote_model->edit_quote($uid);
				//echo($val);
				redirect('/quotes/view/'.$id, 'refresh');
			}
		}
	}

	public function delete_quote($id = FALSE)
	{
		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		} else if ( $id === FALSE ) {
				show_404();
		} else {
			$this->quote_model->quote_delete($id);
			redirect('/quotes', 'refresh');
		}
	}

	public function review()
	{
		$id = $this->uri->segment(3, 0);
		$key = $this->uri->segment(4, 0);
		if (empty($id) || empty($key)) {

			show_404();

		} else {
			$data['quote'] = $this->quote_model->get_client_quote($id, $key);
			$data['edit'] = FALSE;

			if (!isset($data['quote'][0]['iid']))
			{
				show_404();
			} else {
				$data['theDate'] = $this->_month_string($data['quote'][0]['date_issued']);
				$data['quote_flags'] = unserialize(QUOTE_FLAGS);
				$data['view_approve_quote'] = $this->load->view('pages/quotes/email/view_approve_quote', $data, TRUE);
				$data['view_decline_quote'] = $this->load->view('pages/quotes/email/view_decline_quote', $data, TRUE);
				//print("<pre>".print_r( $data['quote'], true )."</pre>");
				$data['top_nav'] = false;
				$this->load->view('templates/client/header', $data);
				$this->load->view('pages/quotes/view', $data);
				$this->load->view('templates/client/footer', $data);
			}
		}
	}


	public function approve_quote($id, $key, $status)
	{
		if ( $id === FALSE || $key === FALSE || $status === FALSE ) {
				show_404();
		} else {
			$send_approval = $this->quote_model->approve_quote($id, $status);

			$email = $this->input->post('email');
			$my_email = $this->input->post('my_email');
			$emailSubject = $this->input->post('emailSubject');
			$emailMessage = $this->input->post('emailMessage');

			$from_email = RUBY_EMAIL;
			$this->load->library('email');
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from($email, $email);
			$this->email->to($my_email);
			$this->email->subject($emailSubject);
			$this->email->message(nl2br($emailMessage));


			if ($send_approval === TRUE && $status === '1' ) {
				$this->session->set_flashdata('error', "You have approved the project quote and a notification email has been sent");
				$this->email->send();
			} else if ($send_approval === TRUE && $status === '2') {
				$this->session->set_flashdata('error', "You have declined the project quote and a notification email has been sent");
				$this->email->send();
			} else {
				$this->session->set_flashdata('error', "There was an error sending the approval email. Please try again, or send us an email if the problem persists.");
			}
			redirect('/quotes/review/'.$id.'/'.$key, 'refresh');
		}
	}

	public function send_quote($id, $key)
	{
		if ( $id === FALSE || $key === FALSE ) {
				show_404();
		} else {
			//$send_approval = $this->quote_model->approve_quote($id, $status);

			$email = $this->input->post('email');
			$my_email = $this->input->post('my_email');
			$emailSubject = $this->input->post('emailSubject');
			$emailMessage = $this->input->post('emailMessage');

			$from_email = $my_email;
			$this->load->library('email');
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from($my_email, $my_email);
			$this->email->to($email);
			$this->email->subject($emailSubject);
			$this->email->message(nl2br($emailMessage));
			$this->email->send();

			$this->session->set_flashdata('error', "Your quote has been sent. You will recieve an email from the system when the quote is approved.");
			redirect('/quotes/view/'.$id, 'refresh');
		}
	}

	public function convert($id = FALSE)
	{
		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			if ( $id === FALSE) {
					show_404();
			} else {
				$data['quote'] = $this->quote_model->convert_quote($id);
				//print("<pre>".print_r($data['quote'], true)."</pre>");
				$this->session->set_flashdata('error', 'You have created an invoice from this quote. You will find a new draft in the invoices page.');
				redirect('/quotes/view/'.$id, 'refresh');
			}
		}
	}

	private function _month_string($date) {
	$month=array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
		$datePieces = explode("-", $date);

		$day = $datePieces[2];
		$month = $month[$datePieces[1]];
		$year = $datePieces[0];

		return $humanDate = array('day'=>$day, 'month'=>$month, 'year'=>$year);
	}

	function numeric_money($str) {

	    if (preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $str)) {
	        return true;
	    } else {
	        return false;
	    }
	}
}

/* End of file estimates.php */
/* Location: ./application/controllers/estimates.php */
