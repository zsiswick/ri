<div class="row">
	<div class="large-6 columns large-centered">
		<h1 class="text-center">Contact Us</h1>
	</div>
</div>
<div class="row">
	<div class="large-3 columns" style="margin-top: 3rem;">
		<h3>Looking for Answers?</h3>
		<p class="big-p light">Ruby Invoice is always interested in hearing from our customers. We will get back to you with any technical questions within 24 hours or less.</p>
		<hr class="light-bg"/>
		<h3>Feature Request?</h3>
		<p class="light">If you have any feature requests or ideas on how to improve your invoicing experience, you can post them here as well.</p>
	</div>
	<div class="large-8 columns">
		<div class="">
			<?php echo validation_errors(); ?>
			
			<?php 
				$attributes = array('data-abide'=>'', 'class'=>'form-wrap invoice-form light-bg');
				echo form_open('contact', $attributes); 
			?>
			<?php
				if ($this->session->flashdata('error')) { ?>
						<div class="alert-box radius text-center">
							<?php echo($this->session->flashdata('error')); ?>
						</div>
			<?php }?>
				<div class="row">
					<div class="small-12 columns">
						<label for="name">Your Name</label>
						<input type="text" name="name" value="<?php echo set_value('name'); ?>" required />
						<small class="error">Your Name is required.</small>
					</div>
					<div class="small-12 columns">
						<label for="email">Email Address</label>
						<input type="email" name="email" value="<?php echo set_value('email'); ?>" required pattern="email" />
						<small class="error">Email Address is required.</small>
					</div>
					<div class="small-12 columns">
						<label for="phone">Phone</label>
						<input type="text" name="phone" value="<?php echo set_value('phone'); ?>" />
					</div>
					<div class="small-12 columns">
						<label for="message">Message</label>
						<textarea name="message" id="message" cols="30" rows="5" placeholder="Leave your remarks here" required ><?php echo set_value('message'); ?></textarea>
						<small class="error"></small>
					</div>
					
				</div>
				
				<div class="row">
					<div class="large-12 columns text-right small-only-text-center">
						<input type="submit" name="submit" value="Send Message" class="button round" />
					</div>
				</div>		
				
			</form>
				
		</div>		
	</div>
	
</div>
