<div class="row">
	<div class="large-6 columns large-centered">
		<h1 class="text-center">Add a Client</h1>
	</div>
</div>
<div class="row">
	<div class="large-8 columns large-centered">
		<div class="">
			<div class="">
				<?php echo validation_errors(); ?>
				
				<?php 
					$attributes = array('data-abide'=>'', 'class'=>'invoice-list-wrap invoice-form light-bg clearfix');
					echo form_open('clients/create', $attributes); 
				?>
				
					<div class="row">
						<div class="small-6 columns">
							<label for="company">Company</label>
							<input type="text" name="company" value="<?php echo set_value('company'); ?>" placeholder="Acme Co." required />
							<small class="error">Company is required.</small>	
						</div>
						<div class="small-6 columns">
							<label for="contact">Contact Name</label>
							<input type="text" name="contact" value="<?php echo set_value('contact'); ?>" placeholder="Donnald Smith" required />
							<small class="error">Contact Name is required.</small>	
						</div>
					</div>
					
					<div class="row">
						<div class="small-6 columns">
							<label for="email">Email</label>
							<input type="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="sample@rubyinvoice.com" required />
							<small class="error">Email address is required.</small>	
						</div>
						<div class="small-6 columns">
							<label for="phone">Phone</label>
							<input type="tel" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="123-456-7890" />	
						</div>
					</div>   
					
					<div class="row">
						<div class="small-6 columns">
							<label for="address_1">Address 1</label>
							<input type="text" name="address_1" value="<?php echo set_value('address_1'); ?>" placeholder="123 Ruby Street" />	
						</div>
						<div class="small-6 columns">
							<label for="address_2">Address 2</label>
							<input type="text" name="address_2" value="<?php echo set_value('address_2'); ?>" placeholder="2nd Floor"/>
						</div>
					</div>
					
					<div class="row">
						<div class="small-6 columns">
							<label for="city">City</label>
							<input type="text" name="city" value="<?php echo set_value('city'); ?>" placeholder="Rubyville" />	
						</div>
						<div class="small-3 columns">
							<label for="state">State</label>
							<input type="text" name="state" max="2" value="<?php echo set_value('state'); ?>" placeholder="MA" />
						</div>
						<div class="small-3 columns">
							<label for="zip">Zip</label>
							<input type="text" name="zip" value="<?php echo set_value('zip'); ?>" placeholder="01234" />
						</div>
					</div>
					
					<label for="country">Country</label>
					<input type="text" name="country" value="<?php echo set_value('country'); ?>" placeholder="USA" />
					
					<div class="row">
						<div  class="small-7 columns">
							<label for="tax_id">Tax Id</label>
							<input type="text" name="tax_id" value="<?php echo set_value('tax_id'); ?>"/>
						</div>
						<div  class="small-5 columns">
							<label for="default_inv_prefix">Invoice Prefix</label>
							<input type="text" name="default_inv_prefix" maxlength="6" value="<?php echo set_value('default_inv_prefix'); ?>"/>
						</div>
					</div>
					
					
					
					
					<label for="notes">Notes</label>
					<textarea name="notes" id="" cols="30" rows="10"></textarea><br />
					
					<div class="row">
						<div class="large-12 columns text-right small-only-text-center">
							<input type="submit" name="submit" value="Add Client" class="button round" />
						</div>
					</div>	
				</form>
			</div>
		</div>		
	</div>
</div>
