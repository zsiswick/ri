<?php
	$invoice_tax_1 = $settings[0]['tax_1'];
	$invoice_tax_2 = $settings[0]['tax_2'];
?>
<div class="row">
	<div class="large-12 columns">
		<h1 class="text-center">New Invoice</h1>
		<div class="invoice-wrap">
		<div class="ribbon draft"><div>DRAFT</div></div>
		<?php
			$hidden = array('remind' => $settings[0]['remind'], 'invoice_currency' => $settings[0]['currency']);
			$attributes = array('class' => 'invoice-form light-bg', 'id' => 'createForm', 'data-abide'=>'');
			echo form_open('invoices/create', $attributes, $hidden);
			$this->load->helper('currency_helper');
			$currency = currency_method($settings[0]['currency']);
		?>



			<div id="invoiceCreate" class="invoice-list-wrap" ng-app="invoiceEditApp" ng-controller="InvoiceEditController">
					<?php echo validation_errors(); ?>

						<div class="row invoice-info">
							<div class="medium-5 columns">
								<div class="row">
									<div class="large-12 columns">
										<div class="customer-logo">
											<?php if(!empty($settings[0]['logo'])): echo'<img class="company-logo" src="'.base_url().'uploads/logo/'.$this->tank_auth_my->get_user_id()."/".$settings[0]['logo'].'" />'; endif ?>
										</div>
										<?php if(!empty($settings[0]['company_name'])): echo '<h3>'.$settings[0]['company_name'].'<h3/>'; endif ?>
										<div class="info-block">
											<ul>
												<?php if( !empty($settings[0]['my_address_1']) ): echo('<li>'.$settings[0]['my_address_1'].'</li>'); endif ?>
												<?php if( !empty($settings[0]['my_address_2']) ): echo('<li>'.$settings[0]['my_address_2'].'</li>'); endif ?>
												<?php if( !empty($settings[0]['my_city']) || !empty($settings[0]['my_state']) || !empty($settings[0]['my_zip']) ): echo('<li>'); endif ?><?php echo($settings[0]['my_city'].' '.$settings[0]['my_state'].' '.$settings[0]['my_zip']); ?><?php if( !empty($settings[0]['my_city']) || !empty($settings[0]['my_state']) || !empty($settings[0]['my_zip']) ): echo('</li>'); endif ?>
												<?php if( !empty($settings[0]['my_country']) ): echo('<li>'.$settings[0]['my_country'].'</li>'); endif ?>
											</ul>
										</div>

									</div>
								</div>

							</div>
							<div class="medium-7 columns">
								<div class="row">
									<div class="medium-6 columns">
										<div class="">
											<!--
											<select ng-model="filterItem.theID" ng-options="company.company for company in settings" ng-init="filterItem.theID='Champion Brand'">
											<option value="">Add New Client</option>
											</select>
											<ul>
												<li data-ng-repeat="content in settings | filter:customFilter">
													{{content.company}}<br/>
													{{content.address_1}}<br/>
													{{content.address_2}}</br>
													{{content.city}} {{content.state}} {{content.country}}
												</li>
											</ul>
											-->
											<h5 class="caps ruled">
													Billing Information
												</h5>
												<div class="info-block">

													<?php
														if ($settings) {
															// Map select option values to the list of clients available
															$clientList = array_map(function ($ar) {
																return $ar['company'];
															}, $settings);
															$clientID = array_map(function ($ar) {
																return $ar['id'];
															}, $settings);
															$clientList = array_combine($clientID, $clientList);
															$clientList['add_new_client'] = 'Add New Client';
															echo form_dropdown('client', $clientList, 1);
														} else {
															//echo anchor('clients/create', 'Add a Client', 'class="button round"', 'id="addClient"');
															$clientList = array('choose' => 'Choose...', 'add_new_client' => 'Add New Client');
															$attributes = 'required="" type="number"';
															echo form_dropdown('client', $clientList, 1, $attributes);
														}
													?>
													<small class="error">Client is required.</small>
														<ul id="client_data">
															<li id="contactName"></li>
															<li id="addressOne"></li>
															<li id="addressTwo"></li>
															<li id="cityStateZip"></li>
														</ul>

													<script type="text/javascript">
														$(document).ready(function() {

															var client_data = <?php echo json_encode($settings); ?>;
															var client_val = $('[name="client"]').val();
															var count = 0;

															function update_address(count, client_val)
															{
																if($.isNumeric(client_val)) {
																	$('#contactName').html( client_data[count]['contact'] );
																	$('#addressOne').html( client_data[count]['address_1'] );
																	$('#addressTwo').html( client_data[count]['address_2'] );
																	$('#cityStateZip').html( client_data[count]['city']+' '+client_data[count]['state']+' '+client_data[count]['zip'] );
																	$('input[name="prefix"]').attr('value', client_data[count]['default_inv_prefix']);
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

															update_address(count, client_val);

															if (client_data.length) {
																$('input[name="prefix"]').attr('value', client_data[count]['default_inv_prefix']);
															}

															$('#send-date, #due-date').pickadate({
																	formatSubmit: 'yyyy-mm-dd',
																	hiddenName: true
															});

															$("#invoiceSettingsBtn").on("click", function () {
																$("#invoiceSettings").toggle();
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
											</div>
										</div>
									</div>
									<div class="medium-6 columns">
										<div class="">
											<h5 class="caps ruled">
												Invoice ID
											</h5>
											<div class="info-block">
												<div class="row">
													<div class="small-4 columns"><input type="text" name="prefix" placeholder="Prefix" maxlength="6"/></div>
													<div class="small-8 columns"><input type="text" readonly="readonly" name="invoice_num" placeholder="Invoice Number" /></div>
												</div>
											</div>
										</div>

										<div class="">
											<h5 class="caps ruled">
												Creation Date
											</h5>
											<div class="info-block">
												<div class="row">
												<div class="small-12 columns">
													<input type="text" id="send-date" name="send-date" data-value="<?php echo( date('Y-m-d')); ?>" required />
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
													<div class="small-12 columns">
														<input type="text" id="due-date" name="due-date" value="<?php if(!empty($settings[0]['due']))
														{
															echo( date('d F, Y', strtotime(date('Y-m-d'). ' + '.$settings[0]['due'].' days')));
														} else {
															echo( date('d F, Y', strtotime(date('Y-m-d'). ' + 15 days')));
														}?>
														" required />
														<small class="error">Due date is required.</small>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="columns small-12">
										<h5 class="ruled caps">Description</h5>
										<div class="info-block">
											<textarea name="inv_description" id="" cols="30" rows="2"></textarea>
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
													<small class="error">Number is required.</small>
												</div>
											</div>
											<div class="row">
												<div class="small-12 columns">
													<label for="tax_2">Tax 2 %</label>
													<input id="invoice_tax_2" type="text" name="invoice_tax_2" ng-model="invoice_tax_2" ng-init="invoice_tax_2='<?php echo($invoice_tax_2) ?>'" maxlength="3" />
													<small class="error">Number is required.</small>
												</div>
											</div>
											<label for="discount">Discount Amount</label>
											<div class="row">
												<div class="small-6 medium-8 columns">
													<input id="invoiceDiscount" type="text" name="discount" ng-model="discount" />
													<small class="error">Number is required.</small>
												</div>

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
								<div class="row item-row">

									<div class="qty small-12 medium-3 columns">
										<div class="row">
											<div class="small-6 medium-4 columns">
												<input class="qty sum" type="text" name="qty[]" value="<?php echo set_value('qty[]'); ?>" placeholder="1.5" data-a-pad="false" required />
											</div>
											<div class="small-6 medium-8 columns">
												<select name="unit[]" class="unit">
													<option value="{{unit.hours}}">{{unit.hours}}</option>
													<option value="{{unit.days}}">{{unit.days}}</option>
													<option value="{{unit.services}}">{{unit.services}}</option>
													<option value="{{unit.products}}">{{unit.products}}</option>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="medium-12 columns">
												<div class="small-type"><a class="delete-row">Remove</a> &nbsp;|&nbsp; <a class="add-favorite" href="">Save Item</a></div>
												<small class="error">Quantity is required.</small>
											</div>
										</div>
									</div>

									<div class="description small-12 medium-5 columns">
										<textarea name="description[]" value="<?php echo set_value('description[]'); ?>" placeholder="Client Meeting" rows="5"></textarea>
									</div>

									<div class="price small-12 medium-2 columns">
										<div class="row">
											<div class="medium-12 columns">
												<input data-a-dec="." data-a-sep='' class="unitCost sum" type="text" name="unit_cost[]" value="<?php echo set_value('unit_cost[]'); ?>" placeholder="65" required />
											</div>
										</div>
										<div class="row">
											<div class="medium-12 columns">
												<select name="tax[]" class="tax">
													<option value=""></option>
													<option value="{{invoice_tax_1}}">Tax 1: {{invoice_tax_1}}%</option>
													<option value="{{invoice_tax_2}}">Tax 2: {{invoice_tax_2}}%</option>
													<!--<option value="{{invoice_tax_1*1 + invoice_tax_2*1}}" ng-selected="{{invoice_tax_1*1 + invoice_tax_2*1 == <?= $invoice_item['tax'] ?> && 'true' || 'false'}}">Both: {{invoice_tax_1*1 + invoice_tax_2*1}}%</option>-->
												</select>
												<small class="error">Price is required.</small>
											</div>
										</div>
									</div>
									<div class="totalSum small-12 medium-2 text-right columns">
										0.00
									</div>
									<div class="small-12 columns"><hr /></div>
								</div>
								<div id="invoiceRows" class="row tabbed item-row" ng-repeat="item in items" ng-include="getIncludeFile()">

								</div>
							</div>
						</div>

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
										<h3><span class="currency">{{selectedVal}}</span><span id="invoiceTotal" data-a-dec="." data-a-sep=''></span></h3>

										<input type="hidden" ng-model="selectedVal" ng-init="selectedVal='<?php echo($currency);?>'" />
									</div>

								</div>
							</div>
						</section>



				<div class="row actions">
					<div class="large-12 columns text-right small-only-text-center">
						<input type="submit" name="submit" value="Create Invoice" class="button round" />
					</div>
				</div>
			</div>

		</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-4 columns large-centered">
		<div id="revealModal" class="reveal-modal small" data-reveal>
			<div id="form-errors" class="light-bg"></div>
			<div id="loadingImg"><img src="<?php echo base_url();?>assets/images/ajax-loader.gif" alt="loading" /></div>
			<div id="form-wrap"></div>
		</div>
	</div>
</div>
