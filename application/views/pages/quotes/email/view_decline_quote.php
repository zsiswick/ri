<div class="row light-bg">
	<div class="small-12 columns">
		<h3 class="text-center">Quote declined email will be sent to:</h3>
		<p class="text-center"><?php echo($quote[0]['company_name'].' ('.$quote[0]['email'].')') ?></p>
		<?php
			$attributes = array('id' => 'sendQuoteDeclinedEmail', 'data-abide'=>'');
			$hidden = array('email' => $quote[0]['email'], 'my_email'=> $quote[0]['my_email']);
			echo form_open('quotes/approve_quote/'.$quote[0]['iid'].'/'.$quote[0]['key'].'/2', $attributes, $hidden); 
		?>
		<label for="emailSubject">Subject</label>
		<input type="text" name="emailSubject" value="Quote #<?php echo($quote[0]['prefix'].'-'.$quote[0]['inv_num']);?> is declined" required />
		<small class="error">Subject is required.</small>
		<label for="emailMessage">Message</label>
		<textarea name="emailMessage" id="" cols="30" rows="8" required >Hello, &#013;I have reviewed the quote and unfortunately must decline it:&#013;<?php echo(base_url().'index.php/'.uri_string());?>&#013;&#013;Let's regroup and discuss next steps, &#013;&#013;<?php echo($quote[0]['contact']);?>
 		</textarea>
 		<small class="error">Message is required.</small>
	 <div class="row">
	 	<div class="small-12 columns text-center">
	 		<input type="submit" name="submit" value="Decline and send email" class="button round"/>	
	 	</div>
	 </div>
	 
	 </form>
	</div>
</div>
<a class="close-reveal-modal">&#215;</a>