<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

	
	 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('invoice_model');
		
	} 
	
	public function index() 
	{
		// Do Something
	}
	
	public function send_test_email() 
	{
		$emailSubject = 'CRON Job Test';
		$emailMessage = 'This is a simple test to see if CRON is working properly';
		$from_email = 'zsiswick@gmail.com';
		$this->load->library('email');
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		//$this->email->attach($pdfFilePath);
		$this->email->from($from_email, 'Zachary Siswick');
		$this->email->to('zsiswick@gmail.com'); 
		$this->email->subject($emailSubject);
		$this->email->message(nl2br($emailMessage));	
		
		$this->email->send();
	}
	
	public function send_reminder_email() 
	{
		$data['invoices'] = $this->invoice_model->get_auto_reminder_invoices();
		//print("<pre>".print_r($data['invoices'],true)."</pre>");
		

		
		foreach ($data['invoices'] as $invoice) {
			$emailSubject = 'Reminder: Invoice #'.$invoice['inv_num'].' from'.$invoice['full_name'];
			$emailMessage = 'Just a reminder that invoice #'.$invoice['prefix'].'-'.$invoice['inv_num'].' was due on '.$invoice['due_date'].'<br/> Please make a payment of $'.$invoice['amount'].' as soon as possible.<br/><br/> You can view the invoice online at:<br/>'.base_url().'index.php/invoice/view/'.$invoice['iid'].'/'.$invoice['key'].'<br/><br/>Best regards,<br/><br/>'.$invoice['full_name'];
			
			$clientEmail = $invoice['client_email'];
			$from_email = $invoice['user_email'];
			$this->load->library('email');
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from($from_email, $invoice['company_name']);
			$this->email->to($clientEmail); 
			$this->email->subject($emailSubject);
			$this->email->message(nl2br($emailMessage));	
			
			$this->email->send();
		}
				
	}
	
	public function set_due_invoices() 
	{
		$this->invoice_model->get_set_due_invoices();
	}
	
	public function get_auto_reminder_invoices_int() {
		$data['invoices'] = $this->invoice_model->get_auto_reminder_invoices_int();
		print("<pre>".print_r($data['invoices'],true)."</pre>");
	}
}

/* End of file cron.php */
/* Location: ./application/controllers/invoices.php */