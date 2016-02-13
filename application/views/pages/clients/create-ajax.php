<div class="clearfix light-bg">
	<?php echo validation_errors(); ?>
		
		<?php 
			$attributes = array('id' => 'addClient');			
			echo form_open('clients/create', $attributes) 
		?>
		<h2 class="text-center">Add a Client</h2>
		<div class="row">
			<div class="medium-6 columns">
				<input type="text" name="company" placeholder="Company" />
			</div>
			<div class="medium-6 columns">
				<input type="text" name="contact" placeholder="Contact Name" />
			</div>
			<div class="medium-12 columns end">
				<input type="text" name="email" placeholder="Email Address" />
			</div>
		</div>
		
		<div id="moreFields">
			<div class="row">
				<div class="medium-12 columns">
					<input type="text" name="phone" placeholder="Phone Number" />
				</div>
				<div class="medium-6 columns">
					<input type="text" name="address_1" placeholder="Address 1" />
				</div>
				<div class="medium-6 columns">
					<input type="text" name="address_2" placeholder="Address 2" />
				</div>
				<div class="medium-6 columns">
					<input type="text" name="city" placeholder="City"/>
				</div>
				<div class="medium-3 columns">
					<input type="text" name="state" placeholder="State"/>
				</div>
				<div class="medium-3 columns">
					<input type="text" name="zip" placeholder="zip"/>
				</div>
				<div class="medium-12 columns">
					<input type="text" name="country" placeholder="Country"/>
				</div>
				<div class="medium-6 columns">
					<input type="text" name="tax_id" placeholder="Tax ID" />
				</div>
				<div class="medium-6 columns">
					<input type="text" name="default_inv_prefix" placeholder="Invoice Prefix"/>
				</div>
				<div class="medium-12 columns">
					<textarea name="notes" id="" cols="30" rows="10" placeholder="Notes"></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns">
				<a id="showMoreFields" href="#">Add more info</a>
			</div>
		</div>
		
		<div class="row">
			<div class="small-12 columns text-center">
				<hr />
				<input type="submit" name="submit" value="Add Client" class="button round" />
			</div>
		</div>
		
		
		
		
	</form>
</div>		
<a class="close-reveal-modal">&#215;</a>
<script type="text/javascript">
	$(document).ready(function() {
			
		$('#showMoreFields').on("click", function() {
			$("#moreFields").show();
		});
	});
</script>