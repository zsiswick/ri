<?php
class Client_model extends CI_Model {
	public $session_data;
	public function __construct()
	{
		$this->load->database();
	}

	public function get_client_list()
	{
		$uid = $this->tank_auth_my->get_user_id();
		$query = $this->db->get_where('client', array('uid' => $uid));
		return $query->result_array();
	}


	public function get_clients()
	{
		$uid = $this->tank_auth_my->get_user_id();

		$this->db->select("cl.id as cid, cl.uid, cl.company, cl.contact, cl.email", false);

		$this->db->from('client cl');
		$this->db->where('cl.uid', $uid);
		$this->db->order_by("cid", "desc");
		$query = $this->db->get();
		$clients = $query->result_array();

		if ($query->num_rows() > 0)
		{

			$total_owing = 0;
			$total_unbilled = 0;

			foreach ($clients as &$client) {

				$owing = 0;
				$unbilled = 0;
				$payment_amnt = 0;

				$query = $this->db->order_by('id', 'desc')->get_where('common', array('cid' => $client['cid']));

				foreach ($query->result() as $invoice) {

					if (!empty($invoice)) {
						$client['invoice'][] = $invoice;

						if ($invoice->status != 0 && $invoice->status != 3) {
							$owing += $invoice->amount;
						}

						if ($invoice->status == 0) {
							$unbilled += $invoice->amount;
						}

						$query2 = $this->db->get_where('payments', array('common_id' => $invoice->id));

						foreach ($query2->result() as $payment) {
							$invoice->payments[] = $payment;
							$payment_amnt += $payment->payment_amount;
						}
					}
				}

				$client['owing'] = $owing - $payment_amnt;
				$client['unbilled'] = $unbilled;
				$client['payment_amount'] = $payment_amnt;
				$total_owing += $client['owing'];
				$total_unbilled += $unbilled;

			}
			$clients['totals']['total_unbilled'] = $total_unbilled;
			$clients['totals']['total_owing'] = $total_owing;
			return $clients;
		}

		else {

			return;
		}
	}

	public function get_client($client_id = FALSE)
	{
		$uid = $this->tank_auth_my->get_user_id();
		if (is_numeric($client_id)) {
			$this->db->select('*');
			$this->db->from('client');
			$this->db->where('id', $client_id);
			$this->db->where('uid', $uid);
		} else {
			$company = str_replace("%20", " ", $client_id);
			$this->db->select('*');
			$this->db->from('client');
			$this->db->where('company', $company);
			$this->db->where('uid', $uid);
		}
			$query = $this->db->get();
			return $query->result_array();
	}

	public function set_sample_client($uid)
	{
		$data = array(
			'uid' => $uid,
			'key' => substr(str_shuffle(MD5(microtime())), 0, 5), // 6c468
			'company' => 'Sample Company',
			'contact' => 'Sample Contact',
			'email' => 'sample@rubyinvoice.com',
			'address_1' => '123 Ruby Street',
			'address_2' => '',
			'zip' => '01234',
			'city' => 'Rubyville',
			'state' => 'MA',
			'country' => 'USA',
			'tax_id' => '',
			'notes' => 'VIP Client'
		);

		return $this->db->insert('client', $data);
	}

	public function set_client($uid)
	{
		$data = array(
			'uid' => $uid,
			'key' => substr(str_shuffle(MD5(microtime())), 0, 5), // 6c468
			'company' => $this->input->post('company'),
			'contact' => $this->input->post('contact'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'zip' => $this->input->post('zip'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'country' => $this->input->post('country'),
			'tax_id' => $this->input->post('tax_id'),
			'default_inv_prefix' => $this->input->post('default_inv_prefix'),
			'notes' => $this->input->post('notes')
		);

		return $this->db->insert('client', $data);
	}

	public function update_client()
	{
		$cdata = array(
			'id' => $this->input->post('cid'),
			'company' => $this->input->post('company'),
			'contact' => $this->input->post('contact'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'zip' => $this->input->post('zip'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'country' => $this->input->post('country'),
			'tax_id' => $this->input->post('tax_id'),
			'default_inv_prefix' => $this->input->post('default_inv_prefix'),
			'notes' => $this->input->post('notes')
		);
		$this->db->where('id', $cdata['id']);
		$this->db->update('client', $cdata);

		return;
	}

	public function delete_client($id)
	{
		$uid = $this->tank_auth_my->get_user_id();

		$this->db->start_cache();
		$this->db->select('*', false);
		$this->db->where('cid', $id);
		$this->db->where('uid', $uid);
		$this->db->from('common');
		$this->db->stop_cache();

		$query = $this->db->get();
		$common = $query->result_array();
		$this->db->delete('common');
		$this->db->flush_cache();

		foreach ($common as $invoice_id) {
			// Delete all associated items
			$this->db->where_in('common_id', $invoice_id['id']);
			$this->db->delete('item');

			// Delete all associated invoice payments
			$this->db->where_in('common_id', $invoice_id['id']);
			$this->db->delete('payments');
		}
		// TODO Delete all associated quotes

		$this->db->where('id', $id);
		$this->db->where('uid', $uid);
		$this->db->limit(1);
		$this->db->delete('client');

		return;
	}

}
