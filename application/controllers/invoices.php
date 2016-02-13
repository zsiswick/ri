<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoices extends CI_Controller {

	public $thisDay = 0 ;
	public $thisMonth = 0 ;
	public $thisYear = 0 ;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('tank_auth_my');
		if (!$this->tank_auth_my->is_logged_in()) {
			redirect('/auth/login/');
		}
		$this->thisDay = date("j");
		$this->thisMonth = date("n");
		$this->thisYear = date("Y");

		$this->load->helper('form');
		$this->load->helper('date_input_helper');
		$this->load->library('form_validation');

		$this->load->model('invoice_model');
		$this->load->model('client_model');
	}

	public function index()
	{

		$this->invoice_model->get_set_due_invoices();
		$id = $this->tank_auth_my->get_user_id();

		$this->load->library('pagination');
		$config['base_url'] = "http://localhost/rubyinvoice/index.php/invoices/";
		$config['total_rows'] = $this->invoice_model->get_invoices_rows($id);
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

		$data['invoices'] = $this->invoice_model->get_invoices($id, $config['per_page'], $this->uri->segment(2));
		$data['payments'] = $this->invoice_model->get_invoices_payments($id);

		$data['user_id']	= $this->tank_auth_my->get_user_id();
		$data['username']	= $this->tank_auth_my->get_username();
		$data['status_flags'] = unserialize(STATUS_FLAGS);

		$this->load->view('templates/header', $data);
		$this->load->view('pages/invoices/index', $data);
		$this->load->view('templates/footer');

	}

	public function view($id = FALSE)
	{
		$uid = $this->tank_auth_my->get_user_id();
		$data['item'] = $this->invoice_model->get_invoice($id, $uid);
		$data['status_flags'] = unserialize(STATUS_FLAGS);
		//print("<pre>".print_r($data['item'],true)."</pre>");

		if (empty($data['item']))
		{
			show_404();
		}
			else
		{
			$data['dob_dropdown_day'] = buildDayDropdown('day', $this->thisDay);
			$data['dob_dropdown_month'] = buildMonthDropdown('month', $this->thisMonth);
			$data['dob_dropdown_year'] = buildYearDropdown('year', $this->thisYear);
			$data['theDate'] = $this->_month_string($data['item'][0]['date']);
			$data['title'] = $data['item']['client'][0]['company'];
			$jsfiles = array('foundation/foundation.joyride.js', 'vendor/jquery.cookie.js', 'helpers/start-joyride.js');
			$data['js_to_load'] = $jsfiles;

			$this->load->view('templates/header', $data);
			$this->load->view('pages/invoices/view', $data);
			$this->load->view('templates/footer');
		}
	}

	public function create(){

		$data['title'] = 'Create an invoice';

		$uid = $this->tank_auth_my->get_user_id();
		$data['settings'] = $this->invoice_model->get_invoice_settings($uid);

		$this->form_validation->set_rules('client', 'Client', 'required|numeric|xss_clean|client');
		$this->form_validation->set_rules('description[]',  'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('qty[]',  'Quantity', 'required|numeric');
		$this->form_validation->set_rules('unit_cost[]',  'Unit Cost', 'callback_numeric_money');
		$this->form_validation->set_message('numeric_money', 'Please enter an amount greater than $0.99');

		//print("<pre>".print_r($data['settings'],true)."</pre>");

		// CHECK THE FORM TO SEE IF SUBMITTED VIA AJAX
		if($this->input->is_ajax_request()){
		   $respond=array();

		   if($this->form_validation->run()==false){
		      $respond['result'] = 'false';
		      $respond['errors'] = validation_errors();
		   } else {
		      $respond['result'] = 'true';
		      $respond['errors'] = 'The invoice was added!';
		      $this->invoice_model->set_invoice($uid);
		      $respond['redirect'] = base_url().'/index.php/invoices';
		   }
		   return $this->output->set_output(json_encode($respond));
		}

		if ($this->form_validation->run() === FALSE){

	    $jsfiles = array('picker.js', 'picker.date.js', 'auto-numeric/autoNumeric.js', 'jquery-ui.min.js', 'invoice.js');
	    $cssfiles = array('default.css', 'default.date.css');
	    $data['css_to_load'] = $cssfiles;
	    $data['js_to_load'] = $jsfiles;
			$this->load->view('templates/header', $data);
			$this->load->view('pages/invoices/create', $data);
			$this->load->view('templates/footer', $data);
		}
		else {
			$this->invoice_model->set_invoice($uid);
			redirect('/invoices', 'refresh');
		}
	}

	public function edit($id = FALSE) {

		$invoice_id = $this->uri->segment(3, 0);
		$data['title'] = 'Edit this invoice';
		$uid = $this->tank_auth_my->get_user_id();
		$data['clients'] = $this->client_model->get_client_list();
		$data['item'] = $this->invoice_model->get_invoice($id, $uid);
		$data['status_flags'] = unserialize(STATUS_FLAGS);

		//print("<pre>".print_r( $data['item'], true )."</pre>");

		if (empty($data['item'])) {
				show_404();

		} else {

			$data['iid'] = $data['item'][0]['iid'];
			// BREAK APART THE DATE STORED IN THE DATABASE AND PUT IT BACK INTO THE INPUT FIELDS
			$datePieces = explode("-", $data['item'][0]['date']);
			$data['dob_dropdown_day'] = buildDayDropdown('day', $datePieces[2]);
			$data['dob_dropdown_month'] = buildMonthDropdown('month', $datePieces[1]);
			$data['dob_dropdown_year'] = buildYearDropdown('year', $datePieces[0]);
			$data['theDate'] = $this->_month_string($data['item'][0]['date']);
			$this->form_validation->set_rules('client', 'Client', 'required|numeric|xss_clean');
			$this->form_validation->set_rules('qty[]',  'Quantity', 'required|numeric');
			$this->form_validation->set_rules('description[]',  'Description', 'required|trim|xss_clean');
			$this->form_validation->set_rules('unit_cost[]',  'Unit Cost', 'callback_numeric_money');
			$this->form_validation->set_message('numeric_money', 'Please enter an amount greater than $0.99');
			$this->form_validation->set_message('due-date', 'Please enter a due date', 'valid_date');
			$this->form_validation->set_message('send-date', 'Please enter a creation date', 'valid_date');

			if ( $data['item'][0]['uid'] === $uid ) {

				if ($this->form_validation->run() === FALSE) {
						$jsfiles = array('picker.js', 'picker.date.js', 'auto-numeric/autoNumeric.js', 'jquery-ui.min.js', 'invoice.js');
						$cssfiles = array('default.css', 'default.date.css');
						$data['css_to_load'] = $cssfiles;
						$data['js_to_load'] = $jsfiles;
						$this->load->view('templates/header', $data);
						$this->load->view('pages/invoices/edit', $data);
						$this->load->view('templates/footer', $data);
					} else {
						$this->invoice_model->edit_invoice($uid);
						redirect('/invoices/view/'.$invoice_id, 'refresh');
					}
			} else {
				show_404();
			}
		}
	}

	public function add_payment($id)
	{
		$uid = $this->tank_auth_my->get_user_id();
		$data['item'] = $this->invoice_model->get_invoice($id, $uid);
		$common_id = $data['item'][0]['iid'];
		$pamount = $this->input->post('pamount');
		$paymentDate = $this->_format_date_string($this->input->post('year'), $this->input->post('month'), $this->input->post('day'));
		$pdata = array(
			'payment_amount'=>$pamount,
			'pdate'=> $paymentDate,
			'common_id'=>$common_id
		);

		if (empty($data['item'])) {
				show_404();
		} else {

			$this->form_validation->set_rules('pamount', 'Payment Amount', 'required|callback_numeric_money|greater_than[0]|xss_clean');
			$this->form_validation->set_rules('day', 'Day', 'required|numeric|greater_than[0]');
			$this->form_validation->set_rules('month', 'Month', 'required|numeric');
			$this->form_validation->set_rules('year', 'Year', 'required|numeric|valid_selectsdate[month,day]');
			$this->form_validation->set_message('numeric_money', 'Please enter an amount greater than $0.99');
			// CHECK THE FORM TO SEE IF SUBMITTED VIA AJAX
			if($this->input->is_ajax_request()){
				$respond=array();
				if($this->form_validation->run()==false){
				   $respond['result'] = 'false';
				   $respond['errors'] = validation_errors();
				} else {
				  $respond['result'] = 'true';
					$respond['errors'] = 'The payment was added!';
					$this->invoice_model->add_payment($pdata, $id);
					$respond['records'] = array_merge($pdata, $data['item'][0]);
				}
				return $this->output->set_output(json_encode($respond));
			}
			// If NO AJAX, VALIDATE AND SUBMIT NORMALLY
			if ($this->form_validation->run() === FALSE){
				$this->load->view('templates/header', $data);
				$this->load->view('pages/invoices/view', $data);
				$this->load->view('templates/footer', $data);
			}
			else {
				$this->invoice_model->add_payment($pdata, $id);
				redirect('/invoices/view/'.$common_id, 'refresh');
			}

		}
	}

	public function delete_payment()
	{
		$uid = $this->tank_auth_my->get_user_id();
		$delete_id = $this->input->get('pid');
		$iuid = $this->input->get('iuid');
		$id = $this->input->get('common_id');

		if (is_numeric($id) && strpos( $id, '.' ) === false) {

			if ($uid === $iuid) {
				$this->invoice_model->payment_delete($delete_id, $id);
				redirect($_SERVER['HTTP_REFERER']);

			} else {
				return show_404();
			}
		} else {
			return show_404();
		}
	}

	public function item_delete()
	{
		$uid = $this->tank_auth_my->get_user_id();
		$delete_id = $this->input->get('id');
		$iuid = $this->input->get('iuid');
		$id = $this->input->get('common_id');

		if (is_numeric($id) && strpos( $id, '.' ) === false) {

			if ($uid === $iuid) {
				$this->invoice_model->item_delete($delete_id, $id);
				redirect($_SERVER['HTTP_REFERER']);

			} else {
				return show_404();
			}
		} else {
			return show_404();
		}
	}

	public function delete_invoice($id = FALSE)
	{
		if ( $id === FALSE ) {
				show_404();
		} else {
			$this->invoice_model->invoice_delete($id);
			redirect('/invoices', 'refresh');
		}
	}

	public function view_payments($id = FALSE)
	{
		if ( $id === FALSE ) {
				show_404();
		} else {
			$data['first_name']	= $this->tank_auth_my->get_username();
			$uid = $this->tank_auth_my->get_user_id();
			$data['item'] = $this->invoice_model->get_invoice($id, $uid);

			$data['dob_dropdown_day'] = buildDayDropdown('day', $this->thisDay);
			$data['dob_dropdown_month'] = buildMonthDropdown('month', $this->thisMonth);
			$data['dob_dropdown_year'] = buildYearDropdown('year', $this->thisYear);
			$common_id = $data['item'][0]['iid'];

			$this->load->view('pages/invoices/view_payments', $data);
		}
	}

	public function pdf($id = FALSE)
	{
		if ( $id === FALSE ) {
				show_404();
		} else {
			$data['status_flags'] = unserialize(STATUS_FLAGS);
			$uid = $this->tank_auth_my->get_user_id();
			$data['item'] = $this->invoice_model->get_invoice($id, $uid);
			$data['theDate'] = $this->_month_string($data['item'][0]['date']);

			$filename = "invoice-".$data['item'][0]['iid'];
			$pdfFilePath = FCPATH."downloads/reports/$filename.pdf";

			if (file_exists($pdfFilePath) == FALSE)
			{
		    ini_set('memory_limit','32M'); // boost the memory limit if it's low
		    $html = $this->load->view('pages/invoices/view_pdf', $data, true); // render the view into HTML

		    $this->load->library('pdf');
		    $pdf = $this->pdf->load();
		    $pdf->WriteHTML($html);
		    $pdf->Output($filename, 'D');
			}

			redirect("../downloads/reports/$filename.pdf");
		}
	}

	public function send_invoice() {

		$id = $this->input->get('iid');
		$client = $this->input->get('client');
		$uid = $this->tank_auth_my->get_user_id();
		$data['client'] = $this->client_model->get_client($client);
		$data['item'] = $this->invoice_model->get_invoice($id, $uid);

		// Setup invoice PDF vars
		$data['theDate'] = $this->_month_string($data['item'][0]['date']);
		$filename = "invoice-".$data['item'][0]['iid'];
		$pdfFilePath = FCPATH."downloads/reports/$filename.pdf";
		if (file_exists($pdfFilePath) == FALSE) {
		  ini_set('memory_limit','32M'); // boost the memory limit if it's low
		  $html = $this->load->view('pages/invoices/view_pdf', $data, true); // render the view into HTML
		  $this->load->library('pdf');
		  $pdf = $this->pdf->load();
		  $pdf->WriteHTML($html);
		  $pdf->Output($pdfFilePath, 'F');
		}
		$from_email = $this->tank_auth_my->get_email();
		$this->load->library('email');
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->attach($pdfFilePath);
		$this->email->from($from_email, $this->tank_auth_my->get_username());
		$this->email->to($data['client'][0]['email']);
		$this->email->subject('New Invoice for ' . $data['client'][0]['company']);
		$this->email->message('Hello ' . $data['client'][0]['contact'] . ',<br/><br/>There is a new invoice #' . $data['item'][0]['iid'] . ' of ready for you to review');

		$this->email->send();
		// UPDATE THE INVOICE SENT FLAG
		$this->invoice_model->set_invoice_flag($id, 'inv_sent', 1);

		//echo $this->email->print_debugger();
		redirect('/invoices/view/'.$id, 'refresh');
	}

	public function view_invoice_email($id = FALSE, $type = FALSE) {

		if ( $id === FALSE || $type === FALSE ) {
				show_404();
		} else {
			$data['first_name']	= $this->tank_auth_my->get_username();
			$data['email'] = $this->tank_auth_my->get_email();
			$uid = $this->tank_auth_my->get_user_id();
			$data['item'] = $this->invoice_model->get_invoice($id, $uid);
			$emailType = array('0'=>'pages/invoices/view_invoice_email', '1'=>'pages/invoices/view_invoice_reminder_email', '2'=>'pages/invoices/view_invoice_thanks_email');

			$this->load->view($emailType[$type], $data);
		}
	}

	public function send_invoice_email($id = FALSE) {

		if ( $id === FALSE ) {
				show_404();
		} else {
			$emailSubject = $this->input->post('emailSubject');
			$emailMessage = $this->input->post('emailMessage');
			$clientEmail = $this->input->post('client_email');

			$from_email = $this->tank_auth_my->get_email();
			$this->load->library('email');
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			//$this->email->attach($pdfFilePath);
			$this->email->from($from_email, $this->tank_auth_my->get_username());
			$this->email->to($clientEmail);
			$this->email->subject($emailSubject);
			$this->email->message(nl2br($emailMessage));

			$this->email->send();
			// UPDATE THE INVOICE SENT FLAG
			$this->invoice_model->set_invoice_flag($id, 'inv_sent', 1);
			$this->invoice_model->get_set_invoice_status($id);
			redirect('/invoices/view/'.$id, 'refresh');
		}
	}

	public function get_invoice_number($cid = FALSE) {
		if ( $cid === FALSE ) {
				show_404();
		} else {
			// CHECK THE FORM TO SEE IF SUBMITTED VIA AJAX
			if($this->input->is_ajax_request()){
					$respond=array();

			   	$data['inv_num'] = $this->invoice_model->get_invoice_num($cid);
					$respond['cid'] = $cid;
					//$respond['errors'] = $data['inv_num'][0]['inv_num'];
					$respond['inv_num'] = $data['inv_num'][0]['inv_num'];

					return $this->output->set_output(json_encode($respond));

			} else {
				$data['inv_num'] = $this->invoice_model->get_invoice_num($cid);

				return $data['inv_num'];
			}
		}
	}

	public function invoice_settings()
	{
		$uid = $this->tank_auth_my->get_user_id();
		$data['settings'] = $this->invoice_model->get_invoice_settings($uid);
		header('Content-Type: application/json');
		echo json_encode($data['settings']);
	}

	public function set_auto_reminder($id, $checked)
	{
		if($this->input->is_ajax_request())
		{
				$respond=array();
				$respond['result'] = 'true';
				$respond['errors'] = 'Auto-reminder has been set';
				$this->invoice_model->set_auto_reminder($id, $checked);
			}
			return $this->output->set_output(json_encode($respond));
	}

	public function add_favorite_invoice_item()
	{

		$description = $_POST['description'];
		$qty = $_POST['qty'];
		$unit = $_POST['unit'];
		$unit_cost = $_POST['unit_cost'];

		$this->invoice_model->add_favorite_invoice_item($unit, $qty, $description, $unit_cost);

	}

	public function remove_favorite_invoice_item($id)
	{
		if ($id === FALSE) {
			$data['success'] = false;
			$data['errors']  = "sorry, there was an error. Try again later...";
			return $this->output->set_output(json_encode($data));
		} else {
			$this->invoice_model->remove_favorite_invoice_item($id);
			$data['success'] = true;
			$data['message'] = 'You have deleted a saved invoice item.';
			return $this->output->set_output(json_encode($data));
		}
	}

	public function get_favorite_invoice_items()
	{

		$data['items'] = $this->invoice_model->get_favorite_invoice_items();
		header('Content-Type: application/json');
		echo json_encode($data['items']);
	}

	public function mark_invoice_as_sent($id)
	{
		$this->invoice_model->set_invoice_flag($id, 'inv_sent', 1);
		$this->invoice_model->get_set_invoice_status($id);
		redirect('/invoices/view/'.$id, 'refresh');
	}

	public function mark_invoice_as_draft($id)
	{
		$this->invoice_model->set_invoice_flag($id, 'inv_sent', 0);
		$this->invoice_model->get_set_invoice_status($id);
		redirect('/invoices/view/'.$id, 'refresh');
	}

	private function _searchArray($items, $searchKey, $val)
	{
	   foreach($items as $key => $product)
	   {
	      if ( $product[$searchKey] === $val )
	         return true;
	   }
	   return false;
	}

	private function _format_date_string($year, $month, $day)
	{
		return $year.'-'.$month.'-'.$day;
	}

	private function _month_string($date)
	{
	$month=array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
		$datePieces = explode('-', $date);

		$day = $datePieces[2];
		$month = $month[$datePieces[1]];
		$year = $datePieces[0];

		return $humanDate = array('day'=>$day, 'month'=>$month, 'year'=>$year);
	}

	function valid_date()
	{
    if (!checkdate($this->input->post('month'), $this->input->post('day'), $this->input->post('year')))
    {
      $this->validation->set_message('valid_date', 'The %s field is invalid.');
      return FALSE;
     }
	}

	function numeric_money($str) {

	    if (preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $str)) {
	        return true;
	    } else {
	        return false;
	    }
	}
}

/* End of file invoices.php */
/* Location: ./application/controllers/invoices.php */
