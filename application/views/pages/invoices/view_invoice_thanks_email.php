<?php
	$this->load->helper('currency_helper');
	$currency = currency_method($item[0]['currency']);
	$inv_num = $item[0]['prefix'].'-'.$item[0]['inv_num'];
?>
<div class="row light-bg">
	<div class="small-12 columns">
		<h3 class="text-center">The thank you will be sent to:</h3>
		<p class="text-center"><?php echo($item['client'][0]['contact']);?> (<?php echo($item['client'][0]['email'])?>)</p>
		<?php
			$hidden = array('client_email' => $item['client'][0]['email']);
			$attributes = array('class' => '', 'id' => 'sendThanksEmail');
			echo form_open('invoices/send_invoice_email/'.$item[0]['iid'], $attributes, $hidden);
		?>
		<label for="emailSubject">Subject</label>
		<input type="text" name="emailSubject" value="Thanks from <?php echo($item['settings'][0]['company_name']);?>" />
		<label for="emailMessage">Message</label>
		<textarea name="emailMessage" id="" cols="30" rows="15">Hello <?php echo($item['client'][0]['contact']);?>,&#013;

We received your payment of <?php echo($currency.$item[0]['amount']);?> for invoice #<?php if($item[0]['prefix']) : echo($item[0]['prefix'].'-'); endif ?><?php echo($item[0]['inv_num']); ?>.&#013;
Thanks so much for your continued business, we are looking forward to working with you again in the near future.&#013;

If you have any questions or comments about our service, please reach out to us at <?php echo($email);?>


Best regards,&#013;
<?php echo($item['settings'][0]['company_name']);?>
	 </textarea>
	 <div class="row">
	 	<div class="small-12 columns text-center">
	 		<input type="submit" name="submit" value="Send Thank You" class="button round"/>
	 	</div>
	 </div>

	 </form>
	</div>
</div>
<a class="close-reveal-modal">&#215;</a>
