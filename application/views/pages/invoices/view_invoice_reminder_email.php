<?php
	$date = new DateTime($item[0]['due_date']);
	$this->load->helper('currency_helper');
	$currency = currency_method($item[0]['currency']);
	$inv_num = $item[0]['prefix'].'-'.$item[0]['inv_num'];
?>
<div class="row light-bg">
	<div class="small-12 columns">
		<h3 class="text-center">The reminder will be sent to:</h3>
		<p class="text-center"><?php echo($item['client'][0]['contact']);?> (<?php echo($item['client'][0]['email'])?>)</p>
		<?php
			$hidden = array('client_email' => $item['client'][0]['email']);
			$attributes = array('id' => 'sendInvoiceEmail');
			echo form_open('invoices/send_invoice_email/'.$item[0]['iid'], $attributes, $hidden);
		?>
		<label for="emailSubject">Subject</label>
		<input type="text" name="emailSubject" value="Reminder: Invoice #<?php echo($inv_num);?> from <?php echo($item['settings'][0]['company_name']);?>" />
		<label for="emailMessage">Message</label>
		<textarea name="emailMessage" id="" cols="30" rows="15">Hello <?php echo($item['client'][0]['contact']);?>,&#013;

Just a reminder that invoice #<?php echo($inv_num);?> was due on <?php echo ($date->format('F j, Y'));?>.&#013;Please make a payment of <?php echo($currency)?><?php echo($item[0]['amount']);?> as soon as possible.&#013;&#013;
You can view the invoice online at:&#013;
<?php echo base_url(); ?>index.php/invoice/view/<?php echo $item[0]['iid']."/".$item['client'][0]['key']?>&#013;

Best regards,&#013;

<?php echo($item['settings'][0]['company_name']);?>
	 </textarea>
	 <div class="row">
	 	<div class="small-12 columns text-center">
	 		<input type="submit" name="submit" value="Send Reminder" class="button round"/>
	 	</div>
	 </div>

	 </form>
	</div>
</div>
<a class="close-reveal-modal">&#215;</a>
