<?php

	$id = $item['client'][0]['id'];
	$company = $item['client'][0]['company'];
	$address_1 = $item['client'][0]['address_1'];
	$address_2 = $item['client'][0]['address_2'];
	$city = $item['client'][0]['city'];
	$state = $item['client'][0]['state'];
	$zip = $item['client'][0]['zip'];
	$prefix = $item[0]['prefix'];
	$invoice_tax_1 = $item[0]['invoice_tax_1'];
	$invoice_tax_2 = $item[0]['invoice_tax_2'];
	$discount = $item[0]['discount'];
	$discount_type = $item[0]['discount_type'];
	$inv_num = $item[0]['inv_num'];
	$date = $item[0]['date'];
	$due_date = $item[0]['due_date'];

	//////////////////////////////////
	$logo = $item['settings'][0]['logo'];
	$company_name = $item['settings'][0]['company_name'];
	$p_address_1 = $item['settings'][0]['address_1'];
	$p_address_2 = $item['settings'][0]['address_2'];
	$p_city = $item['settings'][0]['city'];
	$p_state = $item['settings'][0]['state'];
	$p_zip = $item['settings'][0]['zip'];
	$invoice_sent = $item[0]['inv_sent'];
?>
<div class="row">
	<div class="large-8 columns large-centered">
		<h1 class="text-center">Edit Invoice #<?php echo($item[0]['iid']);?></h1>

	</div>
</div>

<div class="row" ng-app="invoiceEditApp" ng-controller="InvoiceEditController">
	<div class="large-12 columns">
		<div class="invoice-wrap">
			<div class="ribbon <?php if ($status_flags[$item[0]['status']] == 'DRAFT'): echo('draft'); endif ?> <?php if ($status_flags[$item[0]['status']] == 'OPEN'): echo('open'); endif ?> <?php if ($status_flags[$item[0]['status']] == 'PAID'): echo('paid'); endif ?> <?php if ($status_flags[$item[0]['status']] == 'DUE'): echo('due'); endif ?> <?php if ($status_flags[$item[0]['status']] == 'PARTIAL'): echo('partial'); endif ?>"><div><?php echo($status_flags[$item[0]['status']]);?></div></div>
		<?php
			$attributes = array('class' => 'invoice-form light-bg', 'data-abide'=>'');
			$hidden = array('iid' => $item[0]['iid'], 'new_client' => 0, 'invoice_currency' => $item[0]['currency']);
			echo form_open('invoices/edit/'.$item[0]['iid'], $attributes, $hidden);
			$this->load->helper('currency_helper');
			$currency = currency_method($item[0]['currency']);
		?>
		<?php $sumTotal = 0 ?>
				<?php echo validation_errors(); ?>

				<div class="row invoice-info">
					<div class="medium-5 small-centered large-uncentered columns invoice-info">
							<?php if(!empty($logo)): echo'<img class="company-logo" src="'.base_url().'uploads/logo/'.$this->tank_auth_my->get_user_id()."/".$logo.'" />'; endif ?>
							<?php if(!empty($company_name)): echo '<h3>'.$company_name.'<h3/>'; endif ?>
							<div class="info-block">
								<ul>
									<?php if( !empty($item['settings'][0]['address_1']) ): echo('<li>'.$item['settings'][0]['address_1'].'</li>'); endif ?>
									<?php if( !empty($item['settings'][0]['address_2']) ): echo('<li>'.$item['settings'][0]['address_2'].'</li>'); endif ?>
									<?php if( !empty($item['settings'][0]['city']) || !empty($item['settings'][0]['state']) || !empty($item['settings'][0]['zip']) ): echo('<li>'); endif ?><?php echo($item['settings'][0]['city'].' '.$item['settings'][0]['state'].' '.$item['settings'][0]['zip']); ?><?php if( !empty($item['settings'][0]['city']) || !empty($item['settings'][0]['state']) || !empty($item['settings'][0]['zip']) ): echo('</li>'); endif ?>
									<?php if( !empty($item['settings'][0]['country']) ): echo('<li>'.$item['settings'][0]['country'].'</li>'); endif ?>
								</ul>
							</div>
					</div>
					<div class="large-7 small-centered large-uncentered columns">
						<div class="row">
							<div class="medium-6 columns">

									<h5 class="caps ruled on-paper">
											Billing Information
									</h5>
									<div class="info-block last">
									<?php
										if ($clients) {
											// Map select option values to the list of clients available
											$clientList = array_map(function ($ar) {
												return $ar['company'];
											}, $clients);
											$clientID = array_map(function ($ar) {
												return $ar['id'];
											}, $clients);
											$clientList = array_combine($clientID, $clientList);
											$clientList['add_new_client'] = 'Add New Client';
											$attributes = 'required="" type="number"';
											echo form_dropdown('client', $clientList, $id, $attributes);
										} else {
											echo anchor('clients/create', 'Add a Client', 'class="button round"', 'id="addClient"');
										}
									?>
									<small class="error">Client is required.</small>
									<ul id="client_data">
										<li id="contactName"><?php echo $item['client'][0]['contact']; ?></li>
										<li id="addressOne"><?php if(!empty($address_1)): echo $address_1.'<br/>'; endif ?></li>
										<li id="addressTwo"><?php if(!empty($address_2)): echo $address_2.'<br/>'; endif ?></li>
										<li id="cityStateZip"><?php if(!empty($city)): echo $city.' '; endif ?> <?php if(!empty($state)): echo $state.' '; endif ?> <?php if(!empty($zip)): echo $zip; endif ?></li>
									</ul>
									<script type="text/javascript">
									  $(document).ready(function() {

									  	var baseurl = window.location.protocol + "//" + window.location.host + "/" + "rubyinvoice/";
									  	var client_data = <?php echo json_encode($clients); ?>;
									  	var client_val = $('[name="client"]').val();
									  	var count = 0;
									  	var invoice_num = "<?php echo($inv_num); ?>";

									  	function update_address(count, client_val)
									    {
									    	//alert(client_data[client_val]['contact']);

									    	if($.isNumeric(client_val)) {
									    		$('#contactName').html( client_data[count]['contact'] );
									    		$('#addressOne').html( client_data[count]['address_1'] );
									    		$('#addressTwo').html( client_data[count]['address_2'] );
									    		$('#cityStateZip').html( client_data[count]['city']+' '+client_data[count]['state']+' '+client_data[count]['zip'] );
									    		$('input[name="prefix"]').attr('value', client_data[count]['default_inv_prefix']);


									    		$.get( baseurl+"index.php/invoices/get_invoice_number/"+client_data[count]['id'], function( data ) {
										    		obj = JSON.parse(data);

										    		if (<?php echo($id)?> == client_data[count]['id']) { // first check if the client selected is the original one assigned to the invoice so a new invoice number isn't used. That would be confusing.
								    		    	$('input[name="invoice_num"]').attr('value', invoice_num);
								    		    	$('input[name="new_client"]').attr('value', 0);
								    		    } else {
								    		    	$('input[name="invoice_num"]').attr('value', obj.inv_num);
								    		    	$('input[name="new_client"]').attr('value', 1);
								    		    }
									    		});


									    	} else {
									    		$('#contactName').html('');
									    		$('#addressOne').html('');
									    		$('#addressTwo').html('');
									    		$('#cityStateZip').html('');
									    	}
									    }

									    $('[name="client"]').on( "change", function() {
												var count = $(this)[0].selectedIndex;

											  client_val =  $( this ).val();
											  update_address(count, client_val);
											});

											$('#send-date, #due-date').pickadate({
											    formatSubmit: 'yyyy-mm-dd',
											    hiddenName: true,
											    today: 'today',
											    clear: 'Clear selection'
											});

											$("#invoiceSettingsBtn").on("click", function () {
												$("#invoiceSettings").toggle();
											});

											function init_autoNumeric() {
												$('.sum, .totalSum, #invoiceTotal, #invoiceSubtotal, #discount').autoNumeric('init', {aDec:'.', aSep:'', aForm: false});
											}

											$(document).on('click', "#addItems", function() {
												init_autoNumeric();
											});

											init_autoNumeric();
										});
									</script>

								</div>
							</div>

							<div class="medium-6 columns">
									<h5 class="caps ruled on-paper">
											Invoice Num
									</h5>
									<div class="info-block">
									<div class="row">
										<div class="small-4 columns"><input type="text" name="prefix" placeholder="Prefix" maxlength="6" value="<?php echo($prefix); ?>"/></div>
										<div class="small-8 columns"><input type="text" name="invoice_num" placeholder="Invoice Number" value="<?php echo($inv_num); ?>"/></div>
									</div>
								</div>
									<h5 class="caps ruled on-paper">
											Creation Date
									</h5>
									<div class="info-block">
									<div class="row">
										<div class="small-12 columns">
											<input type="text" id="send-date" name="send-date" data-value="<?php echo($date) ?>" required />
											<small class="error">Creation date is required.</small>
										</div>
									</div>
								</div>
									<h5 class="caps ruled on-paper">
										Due Date
									</h5>
									<div class="info-block last">
										<div class="row">
											<div class="small-12 columns">
												<input type="text" id="due-date" name="due-date" data-value="<?php echo($due_date) ?>" required />
												<small class="error">Due date is required.</small>
											</div>
										</div>
									</div>
							</div>
						</div>
						<div class="row">
							<div class="columns small-12">
								<h5 class="ruled caps">Description</h5>
								<div class="info-block">
									<textarea name="inv_description" id="" cols="30" rows="2"><?php echo($item[0]['description']); ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="small-12 columns">
						<div class="small-only-text-center">
							<a id="invoiceSettingsBtn" class="button small secondary round"><i class="fi-widget"></i> Settings</a>
						</div>
						<div class="panel" id="invoiceSettings">
							<div class="row">
								<div class="small-12 medium-6 columns large-centered">
									<div class="row">
										<div class="small-12 columns">
											<label for="tax_1">Tax 1 %</label>
											<input id="invoice_tax_1" type="text" name="invoice_tax_1" ng-model="invoice_tax_1" ng-init="invoice_tax_1='<?php echo($invoice_tax_1) ?>'" maxlength="3" />
										</div>
									</div>
									<div class="row">
										<div class="small-12 columns">
											<label for="tax_2">Tax 2 %</label>
											<input id="invoice_tax_2" type="text" name="invoice_tax_2" ng-model="invoice_tax_2" ng-init="invoice_tax_2='<?php echo($invoice_tax_2) ?>'" maxlength="3" />
										</div>
									</div>
									<label for="discount">Discount Amount</label>

									<div class="row">
										<div class="small-6 medium-8 columns">
											<input id="invoiceDiscount" type="text" name="discount" ng-model="discount" ng-init="discount='<?php echo($discount) ?>'" />
										</div>
										<!--
										<div class="small-4 columns">
											<?php
												$discount_options = array(
											    'per'    => 'Percentage',
											    'amt'  => 'Amount'
											  );
												echo form_dropdown('discount_type', $discount_options, $discount_type, 'class="discount_type" id="discount_type"');
											?>
										</div>
										-->
									</div>
									<div class="row">
										<div class="small-12 medium-5 columns">
											<label for="currencies">Currency</label>
											<select name="currency" ng-model="selectedCurrency" ng-options="currency.label for currency in currencies track by currency.code" ng-change="onCurrenciesOptionChange()">
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<section id="invoiceCreate">

					<div class="list_header">
						<div class="row">
							<div class="small-12 medium-3 columns qty">
								Qty
							</div>
							<div class="small-12 medium-5 columns description">
								Description
							</div>
							<div class="small-12 medium-2 columns price">
								Price
							</div>
							<div class="small-12 medium-2 text-right columns totalSum">
								Total
							</div>
						</div>
					</div>

					<div class="edit-list-container">
						<div class="tabbed list no-rules">
							<?php foreach ($item['items'] as $invoice_item): ?>
								<?php
									$number = $invoice_item['quantity'] * $invoice_item['unit_cost'];
									$sumTotal = $sumTotal + $number;
								?>
							<div class="row item-row">
								<div class="qty small-12 medium-3 columns">
									<div class="row">
										<div class="small-6 medium-4 columns">
											<input type="hidden" name="item_id[]" value="<?php echo $invoice_item['id'] ?>" />
											<input type="text" class="qty sum" name="qty[]" value="<?php echo $invoice_item['quantity'] ?>" required />
										</div>
										<div class="small-6 medium-8 columns">

											<select name="unit[]" class="unit">
												<option value="{{unit.hours}}" ng-selected="{{unit.hours == '<?php print($invoice_item['unit']); ?>' && 'true' || 'false'}}">{{unit.hours}}</option>
												<option value="{{unit.days}}" ng-selected="{{unit.days == '<?php print($invoice_item['unit']); ?>' && 'true' || 'false'}}">{{unit.days}}</option>
												<option value="{{unit.services}}" ng-selected="{{unit.services == '<?php print($invoice_item['unit']); ?>' && 'true' || 'false'}}">{{unit.services}}</option>
												<option value="{{unit.products}}" ng-selected="{{unit.products == '<?php print($invoice_item['unit']); ?>' && 'true' || 'false'}}">{{unit.products}}</option>
											</select>

										</div>
									</div>
									<div class="row">
										<div class="medium-12 columns">
											<div class="small-type"><a class="delete-row">Remove</a> &nbsp;|&nbsp; <a class="add-favorite">Save Item</a></div>
											<small class="error">Quantity is required.</small>
										</div>
									</div>


								</div>
								<div class="description small-12 medium-5 columns">
									<textarea type="text" name="description[]" rows="5"><?php echo $invoice_item['description'] ?></textarea>
								</div>
								<div class="price small-12 medium-2 columns">
									<div class="row">
										<div class="medium-12 columns">
											<input type="text" class="unitCost sum" name="unit_cost[]" value="<?php echo $invoice_item['unit_cost'] ?>" required />
										</div>
									</div>
									<div class="row">
										<div class="medium-12 columns">
											<select name="tax[]" class="tax">
												<option value="0"></option>
												<option value="{{invoice_tax_1}}" ng-selected="{{invoice_tax_1 == <?= $invoice_item['tax'] ?> && 'true' || 'false'}}">Tax 1: {{invoice_tax_1}}%</option>
												<option value="{{invoice_tax_2}}" ng-selected="{{invoice_tax_2 == <?= $invoice_item['tax'] ?> && 'true' || 'false'}}">Tax 2: {{invoice_tax_2}}%</option>
												<!--<option value="{{invoice_tax_1*1 + invoice_tax_2*1}}" ng-selected="{{invoice_tax_1*1 + invoice_tax_2*1 == <?= $invoice_item['tax'] ?> && 'true' || 'false'}}">Both: {{invoice_tax_1*1 + invoice_tax_2*1}}%</option>-->
											</select>
											<small class="error">Price is required.</small>
										</div>
									</div>
								</div>
								<div class="totalSum small-12 medium-2 text-right columns">
									<?php echo number_format((float)$number, 2, '.', ','); ?>
								</div>
								<div class="small-12 columns"><hr /></div>
							</div>
							<?php endforeach ?>
							<div id="invoiceRows" class="row tabbed" ng-repeat="item in items" ng-include="getIncludeFile()">

							</div>
						</div>
					</div>

				</section>

				<div class="row">
					<div class="large-12 columns text-left small-only-text-center">
						<button data-dropdown="drop1" aria-controls="drop1" aria-expanded="false" class="button dropdown add-dropdown radius secondary">Add new item</button>

						<ul id="drop1" class="small f-dropdown" data-dropdown-content>
							<li><a ng-click="addInvoiceRow()">Add new line</a></li>
							<li><a data-reveal-id="favModal" ng-click="loadData(); showAlert(false)">Add from saved</a></li>
						</ul>

						<div id="favModal" class="reveal-modal small light-bg" data-reveal>
							<div class="alert-box alert small-10 small-centered columns text-center radius" ng-show="showMessage">{{message}}</div>
							<div ng-include="getFavModal()"></div>
							<a class="close-reveal-modal">&#215;</a>
						</div>

					</div>
				</div>

				<hr />

				<section id="invoiceTotals">
					<div class="row">
						<div class="medium-5 medium-offset-7">
							<div class="small-6 columns medium-text-right">
								<!--<h3>Total Due: <span id="invoiceTotal">$<?php echo number_format((float)$sumTotal, 2, '.', ',');?></span></h3>-->
								<h4>Subtotal</h4>
							</div>
							<div class="small-6 columns text-right">
								<h4><span id="invoiceSubtotal">&nbsp;</span></h4>
							</div>
							<div class="small-6 columns medium-text-right" ng-show="invoice_tax_1">
								<h4>Tax ({{invoice_tax_1}}%)</h4>
							</div>
							<div class="small-6 columns text-right" ng-show="invoice_tax_1">
								<h4><span id="taxOne">&nbsp;</span></h4>
							</div>

							<div class="small-6 columns medium-text-right" ng-show="invoice_tax_2">
								<h4>Tax ({{invoice_tax_2}}%)</h4>
							</div>
							<div class="small-6 columns text-right" ng-show="invoice_tax_2">
								<h4><span id="taxTwo">&nbsp;</span></h4>
							</div>

							<div class="small-6 columns medium-text-right" ng-show="discount">
								<h4>Discount</h4>
							</div>
							<div class="small-6 columns text-right" ng-show="discount">
								<h4>-<span id="discount">{{discount | number:2}}</span></h4>
							</div>

							<div class="small-6 columns medium-text-right">
								<h3>Total Due</h3>
							</div>
							<div class="small-6 columns text-right">
								<h3><span class="currency">{{selectedVal}}</span><span id="invoiceTotal" data-a-dec="." data-a-sep=''><?php echo number_format((float)$sumTotal, 2, '.', ',');?></span></h3>

								<input type="hidden" ng-model="selectedVal" ng-init="selectedVal='<?php echo($currency);?>'" />

							</div>

						</div>
					</div>
				</section>


			<div class="row actions">
				<div class="large-12 columns text-right small-only-text-center">
					<input type="submit" name="submit" value="Save Changes" class="button round"/>
				</div>
				<div class="large-12 columns text-right small-only-text-center">
					<a href="#" id="deleteInvoiceBtn" data-reveal-id="editModal">Delete Invoice</a>
				</div>
			</div>
		</form>
	</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-4 columns large-centered">
		<div id="editModal" class="reveal-modal small light-bg" data-reveal>
			<div class="row">
				<div class="small-10 columns text-center small-centered">
					<h3>Blimey!</h3>
					<p>Are you sure you want to delete this invoice?</p>
					<hr />
					<a id="cancelDeleteBtn" href="#" class="button round secondary">No Thanks</a>
					<?php echo anchor('invoices/delete_invoice/'.$item[0]['iid'], 'Delete It', 'class="button round"', 'id="delete-'.$item[0]['iid'].'"'); ?>
				</div>
			</div>
			<a class="close-reveal-modal">&#215;</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-4 columns large-centered">
		<div id="revealModal" class="reveal-modal small" data-reveal>
			<div id="form-errors"></div>
			<div id="loadingImg"><img src="<?php echo base_url();?>assets/images/ajax-loader.gif" alt="loading" /></div>
			<div id="form-wrap"></div>
		</div>
	</div>
</div>
