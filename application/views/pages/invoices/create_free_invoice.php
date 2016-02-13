<div class="row">
	<div class="columns small-12 hide-for-print">
		<h1 class="text-center">Create a Free Invoice</h1>
		<p class="big-p light">Hate commitments? No worries, that's why we created this free invoice template for you to use without having to register. You can download a PDF or print out a copy to mail. If you want to track invoices online, send auto-reminder emails, and more, <a href="<?php echo(base_url()); ?>index.php/auth/register">register for an account</a>!</p>
	</div>
</div>

<?php 
	$attributes = array('id' => 'createForm', 'data-abide'=>'');
	echo form_open('welcome/pdf', $attributes); 
?>
<div class="row">
	<div class="large-9 columns print-full-width">
		<div id="invoiceCreate" class="invoice-list-wrap invoice-form light-bg">
				<?php echo validation_errors(); ?>
					
					<div class="row invoice-info">
						<div class="large-9 columns">
							<div class="row">
								<div class="large-6 columns">
									<h5 class="caps ruled">Your Information</h5>
									<div class="info-block">
										<input type="text" name="name" placeholder="Company Name" required />
										<small class="error">Company name is required</small>
										<textarea name="address" id="" cols="30" rows="2" placeholder="Address"></textarea>
									</div>
										
								</div>
								<div class="large-6 columns">
										<h5 class="caps ruled">
												Billing Information
											</h5>
											<div class="info-block">	
												<input type="text" name="client_name" placeholder="Company or Contact Name" required />
												<small class="error">Company or contact name is required</small>
												<textarea name="client_address" id="" cols="30" rows="2" placeholder="Address"></textarea>
											</div> 
								</div>
									<div class="columns large-8 end">
										<h5 class="ruled caps">Description</h5>
										<div class="info-block">
											<textarea name="inv_description" id="" cols="30" rows="2"></textarea>
										</div>
									</div>
							</div>
							
						</div>
						<div class="large-3 columns">
							<div class="row">
								
								
								<div class="large-12 columns">
									<div class="">
										<h5 class="caps ruled">
											Invoice ID
										</h5>
										<div class="info-block">
											<div class="row">
												<div class="large-12 columns">
													<input type="text" name="invoice_num" placeholder="INV-1234" />
													<small class="error">Invoice ID is required</small>
												</div>
											</div>
										</div>	
									</div>
									
									<div class="">
										<h5 class="caps ruled">
											Creation Date
										</h5>
										<div class="info-block">
											<div class="row">
											<div class="large-12 columns">
												<input type="text" id="send_date" name="send_date" data-value="<?php echo( date('Y-m-d')); ?>" required />
												<small class="error">Creation date is required.</small>
											</div>	
											</div>	
										</div>
									</div>
									<div class="">
										<h5 class="caps ruled">
											Due Date
										</h5>
										<div class="info-block">
											<div class="row">
												<div class="large-12 columns">
													<input type="text" id="due_date" name="due_date" value="<?php echo( date('d F, Y', strtotime(date('Y-m-d'). ' + 15 days'))) ?>" required />
													<small class="error">Due date is required.</small>
												</div>
											</div>
										</div>	
									</div>
								</div>
							</div>
							
						</div>
					</div>
					<div class="list_header">
						<div class="row">
							<div class="small-12 large-2 columns qty">
								Qty
							</div>
							<div class="small-12 large-5 columns description">
								Description
							</div>
							<div class="small-12 large-2 columns price">
								Price
							</div>
							<div class="small-12 large-2 large-only-text-right columns totalSum">
								Total
							</div>
							<div class="small-12 large-1 large-only-text-right columns delete">
							</div>
						</div>
					</div>
					
					<div class="edit-list-container">
						<div class="tabbed list no-rules">
							<div class="row">
								<div class="qty small-12 large-2 columns">
									<input class="qty sum" type="text" name="qty[]" value="<?php echo set_value('qty[]'); ?>" placeholder="1.5" required />
									<small class="error">Quantity is required.</small>
								</div>
								<div class="description small-12 large-5 columns">
									<input type="text" name="description[]" value="<?php echo set_value('description[]'); ?>" placeholder="Client Meeting" />
								</div>
								<div class="price small-12 large-2 columns">
									<input class="unitCost sum" type="text" name="unit_cost[]" value="<?php echo set_value('unit_cost[]'); ?>" placeholder="65" required />
									<small class="error">Price is required.</small>
								</div>
								<div class="totalSum small-12 large-2 large-only-text-right columns" >
									0.00
								</div>
								<div class="delete small-12 large-1 columns large-only-text-right small-text-center">
									<a class="delete-row button small round">x</a>
								</div>
								<div class="small-12 columns"><hr /></div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="large-12 columns text-left small-only-text-center">
							<a id="addItems" class="button small round">Add Another Item</a>
						</div>
					</div>
					<hr />
					<div class="row">
						<div class="large-12 columns text-right small-only-text-center">
							<h3>Total Due: <span id="invoiceTotal">0.00</span></h3>
						</div>
					</div>
					<div class="row">
						<div class="columns large-12">
							<div class="info-block">
								<textarea name="terms_conditions" id="" cols="30" rows="2" placeholder="Terms and Conditions"></textarea>
							</div>
						</div>
					</div>
		</div>
	</div>
	<div class="large-3 columns hide-for-print">
		<div class="sidebar">
			<!--<input type="submit" name="submit" value="Download" class="button expand radius light"/> -->
			<a id="download" class="button radius expand light"><i class="fi-download"></i> Download Invoice</a>
			<a href='javascript:window.print();' class="button radius expand light"><i class="fi-print"></i> Print Invoice</a>
			
			<div class="panel">
			  <p class="large-p text-center light">Want to send invoices by email, get paid by credit card, and more?<br/> Check out our online <a href="<?php echo(base_url()); ?>index.php/features">invoicing features</a> or <a href="<?php echo(base_url()); ?>index.php/auth/register">register for a free account</a>.</p>
			</div>
			
			<div class="panel">
			  <div class="title text-center">Like this tool? Let us know!</div>
			  <div class="social-likes social-likes_vertical">
			  	<div class="facebook" title="Share link on Facebook">Facebook</div>
			  	<div class="twitter" title="Share link on Twitter">Twitter</div>
			  	<div class="plusone" title="Share link on Google+">Google+</div>
			  </div>
			</div>
			
			
		</div>	
	</div>
</div>
</form>
<div class="row">
	<div class="small-12 medium-12 large-4 columns large-centered">
		<div id="sendModal" class="reveal-modal small light-bg" data-reveal>
			<div id="form-errors"></div>
			<div id="loadingImg"><img src="<?php echo base_url();?>assets/images/ajax-loader.gif" alt="loading" /></div>
			<div id="form-wrap">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
  	$('#send_date, #due_date').pickadate({
		    formatSubmit: 'yyyy-mm-dd',
		    hiddenName: true
		});
		
		$('#download').click(function() {       
		    $('#createForm').submit();
		});
		
		function init_autoNumeric() {
			$('.sum, .totalSum, #invoiceTotal').autoNumeric('init', {aDec:'.', aSep:'', aForm: false});
		}
		
		$(document).on('click', "#addItems", function() { 
			init_autoNumeric();
		});
		
		init_autoNumeric();
	});
</script>