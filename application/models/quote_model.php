<?php
class Quote_model extends CI_Model {

	public $session_data;

	public function __construct()
	{
		$this->load->database();
	}

	public function get_quotes($uid, $cid = false)
	{
		$this->db->select("q.id as iid, q.uid, q.cid, q.amount, q.status, q.date_issued, q.currency, q.inv_num, client.company", false);
		$this->db->select("DATE_FORMAT(q.date_issued, '%b %d, %Y') AS pdate", false);
		$this->db->from('quotes q');
		$this->db->join('client', 'client.id = q.cid', 'left');
		$this->db->join('settings', 'settings.uid = q.uid', 'left');
		$this->db->where('q.uid', $uid);
		if (!empty($cid)) {
				$this->db->where('q.cid', $cid);
		}
		$query = $this->db->get();

		return $query->result_array();
	}

	public function set_quote($uid)
	{
		$cid = $this->input->post('client');
		$inv_num = $this->invoice_model->get_set_invoice_num($cid);
		//FORMAT THE DATE BEFORE PUTTING IN THE DATABASE
		$issue_date = $this->input->post('issue-date');
		$quantity = $this->input->post('qty');
		$description = $this->input->post('description');
		$currency = $this->input->post('currency');
		$quote_description = $this->input->post('quote_description');
		$unit_cost = $this->input->post('unit_cost');
		$unit = $this->input->post('unit');
		$invoice_tax_1 = $this->input->post('invoice_tax_1');
		$invoice_tax_2 = $this->input->post('invoice_tax_2');
		$tax = $this->input->post('tax');
		$discount = $this->input->post('discount');
		$prefix = $this->input->post('prefix');
		$item_count = count($quantity);
		$sumTotal = 0;

		//
		$common_data = array('uid' => $uid, 'cid' => $cid, 'prefix' => $prefix, 'currency' => $currency, 'description' => $quote_description, 'date_issued' => $issue_date, 'inv_num' => $inv_num, 'tax_1'=>$invoice_tax_1, 'tax_2'=>$invoice_tax_2, 'discount'=>$discount);
		$this->db->insert('quotes', $common_data);
		// Get the table id of the last row updated using insert_id() function
		$common_id = $this->db->insert_id();

		for ( $i = 0; $i < $item_count; $i++ )
		{
			$tx = ( $tax[$i] / 100 );
			$number = ($quantity[$i] * $unit_cost[$i]);
			$taxAmt = $number * $tx;
			$sumTotal = $sumTotal + $number + $taxAmt;

			$items[] = array(
				'quantity' => $quantity[$i],
				'unit' => $unit[$i],
				'description' => $description[$i],
				'unit_cost' => $unit_cost[$i],
				'tax' => $tax[$i],
				'common_id' => $common_id
				);
		}

		$this->db->insert_batch('quote_item', $items);

		$this->db->where('id', $common_id);
		$this->db->limit(1);
		$amount = array('amount' => $sumTotal);
		$this->db->update('quotes', $amount);
	}

	public function edit_quote($uid)
	{
		$quote_id = $this->uri->segment(3, 0);
		$cid = $this->input->post('client');
		$inv_num = $this->invoice_model->get_set_invoice_num($cid);
		$new_client = $this->input->post('new_client');
		if ($new_client == 1) {
			$inv_num = $this->invoice_model->get_set_invoice_num($cid);
		}
		$id = $this->input->post('item_id');
		$common_id = $this->input->post('iid');
		$issue_date = $this->input->post('issue-date');
		$description = $this->input->post('description');
		$quote_description = $this->input->post('quote_description');
		$currency = $this->input->post('currency');
		$unit = $this->input->post('unit');
		$unit_cost = $this->input->post('unit_cost');
		$invoice_tax_1 = $this->input->post('invoice_tax_1');
		$invoice_tax_2 = $this->input->post('invoice_tax_2');
		$tax = $this->input->post('tax');
		$discount = $this->input->post('discount');
		$prefix = $this->input->post('prefix');
		$quantity = $this->input->post('qty');
		$item_count = count($quantity); // COUNT NUMBER OF ITEMS
		$delete_ids = $this->input->post('delete_ids');
		$delete_count = count($delete_ids); // COUNT NUMBER OF DELETED ITEMS
		$sumTotal = 0;


		if (!empty($delete_count)) // REMOVE DELETED ITEMS FROM RECORDS
		{
			for ($i = 0; $i < $delete_count; $i++)
			{
				$delete_id = $delete_ids[$i];
				$this->db->where('id', $delete_id);
				$this->db->where('common_id', $common_id);
				$this->db->limit(1);
				$this->db->delete('quote_item');
			}
		}

		for ( $i = 0; $i < $item_count; $i++ )
		{
			$tx = ( $tax[$i] / 100 );
			$number = ($quantity[$i] * $unit_cost[$i]);
			$taxAmt = $number * $tx;
			$sumTotal = $sumTotal + $number + $taxAmt;

			$items[] = array(
				'id' => $id[$i],
				'quantity' => $quantity[$i],
				'unit' => $unit[$i],
				'description' => $description[$i],
				'unit_cost' => $unit_cost[$i],
				'tax' => $tax[$i],
				'common_id' => $common_id
			);
		}


		$common_data = array('uid' => $uid, 'cid' => $cid, 'prefix' => $prefix, 'currency' => $currency, 'description' => $quote_description, 'date_issued' => $issue_date, 'inv_num' => $inv_num, 'amount' => $sumTotal - $discount, 'tax_1'=>$invoice_tax_1, 'tax_2'=>$invoice_tax_2, 'discount' => $discount);

		$this->db->where('id', $quote_id);
		$this->db->limit(1);
		$this->db->update('quotes', $common_data);

		// FILTER OUT ALL THE NEW ITEMS FROM EXISTING SO THEY CAN BE INSERTED INTO
		// THE DATABASE PROPERLY
		$new_items = array_filter($items, function($el) { return empty($el['id']); });

		$this->db->update_batch('quote_item', $items, 'id');

		if (!empty($new_items))
		{
			$this->db->insert_batch('quote_item', $new_items);
		}
	}

	public function get_quote($id, $uid)
	{

		$this->db->select('q.id as iid, q.uid, q.cid, q.amount, q.currency, q.tax_1 AS invoice_tax_1, q.tax_2 AS invoice_tax_2, q.discount, q.description, q.status, q.prefix, q.inv_num, q.date_issued, GROUP_CONCAT(quote_item.id) AS item_id, GROUP_CONCAT(quote_item.description) AS idescription, GROUP_CONCAT(quote_item.quantity) AS iqty, GROUP_CONCAT(quote_item.unit_cost) AS icost, GROUP_CONCAT(quote_item.unit) AS iunit, GROUP_CONCAT(quote_item.tax) AS itax, cl.company, cl.id, cl.company, cl.contact, cl.key, cl.email, cl.address_1, cl.address_2, cl.zip, cl.city, cl.state, cl.country, cl.tax_id, cl.notes, settings.logo, settings.full_name, settings.company_name, settings.email as my_email, settings.address_1 as my_address_1, settings.address_2 as my_address_2, settings.city as my_city, settings.state as my_state, settings.zip as my_zip, settings.country as my_country, settings.notes as terms', false);
		$this->db->where('q.id', $id);
		$this->db->from('quotes q');
		$this->db->join('client cl', 'cl.id = q.cid', 'left');
		$this->db->join('settings', 'settings.uid = q.uid', 'left');
		$this->db->join('quote_item', 'quote_item.common_id = q.id', 'left' );
		$this->db->where('q.uid', $uid);
		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_client_quote($id, $key)
	{

		$this->db->select('q.id as iid, q.uid, q.cid, q.amount, q.currency, q.tax_1 AS invoice_tax_1, q.tax_2 AS invoice_tax_2, q.discount, q.description, q.status, q.prefix, q.inv_num, q.date_issued, GROUP_CONCAT(quote_item.description) AS idescription, GROUP_CONCAT(quote_item.quantity) AS iqty, GROUP_CONCAT(quote_item.unit_cost) AS icost, GROUP_CONCAT(quote_item.tax) AS itax, GROUP_CONCAT(quote_item.unit) AS iunit, cl.company, cl.id, cl.company, cl.contact, cl.key, cl.email, cl.address_1, cl.address_2, cl.zip, cl.city, cl.state, cl.country, cl.tax_id, cl.notes, settings.logo, settings.full_name, settings.company_name, settings.email as my_email, settings.address_1 as my_address_1, settings.address_2 as my_address_2, settings.city as my_city, settings.state as my_state, settings.zip as my_zip, settings.country as my_country, settings.notes as terms', false);
		$this->db->where('q.id', $id);
		$this->db->from('quotes q');
		$this->db->join('client cl', 'cl.id = q.cid', 'left');
		$this->db->join('settings', 'settings.uid = q.uid', 'left');
		$this->db->join('quote_item', 'quote_item.common_id = q.id', 'left' );
		$this->db->where('cl.key', $key);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
		 return;
		}
	}

	public function approve_quote($id, $status)
	{
		$data = array('status' => $status);
		$this->db->start_cache();
		$this->db->select('q.id as iid', false);
		$this->db->where('id', $id);
		$this->db->from('quotes q');
		$this->db->limit(1);
		$this->db->stop_cache();

		$query = $this->db->get();
		$this->db->update('quotes', $data);
		$this->db->flush_cache();

		if ($query->num_rows() > 0) {
			return TRUE;
		}
	}

	function quote_delete($id)
	{
		$uid = $this->tank_auth_my->get_user_id();

		$this->db->start_cache();
		$this->db->select('*', false);
		$this->db->where('id', $id);
		$this->db->where('uid', $uid);
		$this->db->from('quotes');
		$this->db->limit(1);
		$this->db->stop_cache();

		$query = $this->db->get();
		$this->db->delete('quotes');
		$this->db->flush_cache();

		if ($query->num_rows() > 0)
		{
			// Delete all associated quote items
			$this->db->where('common_id', $id);
			$this->db->delete('quote_item');
		}
	}

	function convert_quote($id)
	{
		$uid = $this->tank_auth_my->get_user_id();

		$this->db->select('q.id as iid, q.uid, q.cid, q.amount, q.currency, q.discount, q.tax_1 AS invoice_tax_1, q.tax_2 AS invoice_tax_2, q.description, q.status, q.prefix, q.inv_num, q.date_issued, settings.due', false);
		$this->db->where('q.id', $id);
		$this->db->from('quotes q');
		$this->db->join('settings', 'settings.uid = q.uid', 'left');
		$this->db->where('q.uid', $uid);
		$query = $this->db->get();
		$quote = $query->result_array();

		if ($query->num_rows() > 0)
		{

			//$due_date = new DateTime($quote[0]['date_issued']);
			//$due_date->add(new DateInterval('P'.$quote[0]['due'].'D'));

			$due_date = date('Y-m-d', strtotime($quote[0]['date_issued']. ' + '.$quote[0]['due'].' days'));

			$common_data = array('uid' => $uid, 'cid' => $quote[0]['cid'], 'prefix' => $quote[0]['prefix'], 'inv_num' => $quote[0]['inv_num'], 'description' => $quote[0]['description'], 'amount' => $quote[0]['amount'], 'currency' => $quote[0]['currency'], 'tax_1' => $quote[0]['invoice_tax_1'], 'tax_2' => $quote[0]['invoice_tax_2'], 'discount' => $quote[0]['discount'], 'date' => $quote[0]['date_issued'], 'due_date'=>$due_date, 'remind_date'=>$due_date);


			$this->db->insert('common', $common_data);
			$common_id = $this->db->insert_id(); // Get the table id of the last row updated using insert_id() function

			$this->db->select('*', false);
			$this->db->where('i.common_id', $id);
			$this->db->from('quote_item i');
			//$this->db->order_by("id", "asc");
			$query2 = $this->db->get();
			$items = $query2->result_array();

			foreach ($items as &$item) {
				$item['common_id'] = $common_id;
				$item['id'] = '';
			}

			$this->db->insert_batch('item', $items);


			//return $query->result_array();
			//return $query2->result_array();
			return $common_data;

		} else {
			return FALSE;
		}

	}
}
