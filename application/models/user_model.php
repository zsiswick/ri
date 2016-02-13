<?php
class User_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
 
 function get_settings($uid) 
 {
    $this->db->select('*', false);
    $this->db->where('settings.uid', $uid);
    $this->db->limit(1);
    $this->db->from('settings');
    $query = $this->db->get();
    return $query->result_array();
  } 
  public function set_settings($udata)
  {	
  	$uid = $this->tank_auth_my->get_user_id();
  	$file_name = $udata['upload_data']['file_name'];
  	$data = array(
  		'uid' => $uid,
  		'full_name' => $this->input->post('full_name'),
  		'company_name' => $this->input->post('company_name'),
  		'logo' => $file_name,
  		'email' => $this->input->post('email'),
  		'address_1' => $this->input->post('address_1'),
  		'address_2' => $this->input->post('address_2'),
  		'zip' => $this->input->post('zip'),
  		'city' => $this->input->post('city'),
  		'state' => $this->input->post('state'),
  		'country' => $this->input->post('country'),
  		'currency' => $this->input->post('currency'),
  		'tax_1' => $this->input->post('tax_1'),
  		'tax_2' => $this->input->post('tax_2'),
  		'due' => $this->input->post('due'),
  		'remind' => $this->input->post('remind'),
  		'notes' => $this->input->post('notes'),
  		'enable_payments' => $this->input->post('enable_payments'),
  		'payment_notification' => $this->input->post('payment_notification')
  	);
  	$this->db->where('uid', $uid);
  	$this->db->update('settings', $data);
  	return; 
  }
  
  public function delete_logo($uid) 
  {
  	$data = array('logo' => '');
  	$this->db->where('uid', $uid);
  	$this->db->limit(1);
  	$this->db->update('settings', $data);
  }
  
  public function set_stripe_token($uid, $cust_token) 
  {
  	$data = array('uid' => $uid, 'stripe_cust_token' => $cust_token);
  	$this->db->where('uid', $uid);
  	$this->db->limit(1);
  	$this->db->update('settings', $data);
  }
  
  public function get_stripe_token($uid) 
  {
  	$this->db->select('stripe_cust_token', false);
  	$this->db->where('settings.uid', $uid);
  	$this->db->limit(1);
  	$this->db->from('settings');
  	$query = $this->db->get();
  	return $query->result_array();
  }
  
  public function unset_stripe_token($uid) 
  {
  	$data = array('uid' => $uid, 'stripe_cust_token' => '');
  	$this->db->where('uid', $uid);
  	$this->db->limit(1);
  	$this->db->update('settings', $data);
  }
  
}