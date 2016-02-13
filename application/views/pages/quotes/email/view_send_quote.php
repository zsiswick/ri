<div class="row light-bg">
	<div class="small-12 columns">
		<h3 class="text-center">Quote will be sent to:</h3>
		<p class="text-center"><?php echo($quote[0]['company'].' ('.$quote[0]['email'].')') ?></p>
		<?php
			$attributes = array('id' => 'sendQuoteEmail', 'data-abide'=>'');
			$hidden = array('email' => $quote[0]['email'], 'my_email'=> $quote[0]['my_email']);
			echo form_open('quotes/send_quote/'.$quote[0]['iid'].'/'.$quote[0]['key'], $attributes, $hidden); 
		?>
		<label for="emailSubject">Subject</label>
		<input type="text" name="emailSubject" value="Your quote #<?php echo($quote[0]['prefix'].'-'.$quote[0]['inv_num']);?>" required />
		<small class="error">Subject is required.</small>
		<label for="emailMessage">Message</label>
		<textarea name="emailMessage" cols="30" rows="8" required >Hello <?php echo($quote[0]['contact'])?>, &#013;Here is the quote for the project we discussed:&#013;<?php echo(base_url().'index.php/quotes/review/'.$quote[0]['iid'].'/'.$quote[0]['key']);?>&#013;&#013;You can approve it by following the link provided. I look forward to hearing from you, &#013;&#013;<?php echo( $quote[0]['full_name'].'&#013;'.$quote[0]['company_name'] );?>
 		</textarea>
 		<small class="error">Message is required.</small>
	 <div class="row">
	 	<div class="small-12 columns text-center">
	 		<input type="submit" name="submit" value="Send Quote" class="button round"/>	
	 	</div>
	 </div>
	 
	 </form>
	</div>
</div>
<a class="close-reveal-modal">&#215;</a>