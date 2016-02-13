<?php
	$totalAmount = $quote[0]['amount'];
	$subTotal = 0;
	$payment_amount = 0;
	$hidden = array('iid' => $quote[0]['iid']);
	$address_1 = $quote[0]['address_1'];
	$address_2 = $quote[0]['address_2'];
	$city = $quote[0]['city'];
	$state = $quote[0]['state'];
	$zip = $quote[0]['zip'];
	$inv_num = $quote[0]['prefix'].'-'.$quote[0]['inv_num'];
	$invoice_tax_1 = $quote[0]['invoice_tax_1'];
	$invoice_tax_2 = $quote[0]['invoice_tax_2'];
	$tax_1_amnt = 0;
	$tax_2_amnt = 0;
	$discount = $quote[0]['discount'];
	//////////////////////////////////
	$logo = $quote[0]['logo'];
	$company_name = $quote[0]['company_name'];
	$p_address_1 = $quote[0]['my_address_1'];
	$p_address_2 = $quote[0]['my_address_2'];
	$p_city = $quote[0]['my_city'];
	$p_state = $quote[0]['my_state'];
	$p_zip = $quote[0]['my_zip'];
	$this->load->helper('status_flag_classes_helper');

	if (!empty($quote[0]['item_id'])) {
		$is = explode(",", $quote[0]['item_id']);
		$item_ids = array_merge($is);
	}

	$desc = explode(",", $quote[0]['idescription']);
	$idescriptions = array_merge($desc);

	$qs = explode(",", $quote[0]['iqty']);
	$iqtys = array_merge($qs);

	$cos = explode(",", $quote[0]['icost']);
	$icosts = array_merge($cos);

	$tx = explode(",", $quote[0]['itax']);
	$itax = array_merge($tx);

	$un = explode(",", $quote[0]['iunit']);
	$iunit = array_merge($un);

	$this->load->helper('currency_helper');
	$currency = currency_method($quote[0]['currency']);

	//print("<pre>".print_r($quote, true )."</pre>");
?>

<!-- Load Header -->
<?php if ($edit === TRUE): echo('<h1 class="text-center">Edit Quote #'.$quote[0]['iid'].'</h1>'); endif ?>

<!-- Load Quote Actions -->
<?php if ($this->uri->segment(2, 0) === "view"): $this->load->view('widgets/quote-actions'); endif ?>
<?php if ($this->uri->segment(2, 0) === "review"): echo('<p></p><div class="row"><div class="small-12 columns text-center"><a id="declineQuoteBtn" class="button round" data-reveal-id="emailModal2"><i class="fi-dislike"></i> Decline</a> <a id="approveQuoteBtn" class="button round" data-reveal-id="emailModal"><i class="fi-like"></i></i> Accept</a></div></div>'); endif ?>

<div id="invoiceContainer" ng-app="invoiceEditApp" ng-controller="InvoiceEditController">
		<div class="row">
			<div class="large-12 columns">
						<div class="invoice-wrap">
								<div class="ribbon <?php echo(strtolower($quote_flags[$quote[0]['status']]));?>"><div><?php echo($quote_flags[$quote[0]['status']]);?></div></div>
								<?php
									$attributes = array('class' => 'invoice-form light-bg', 'id' => 'editForm', 'data-abide'=>'');
									$hidden = array('iid' => $quote[0]['iid'], 'new_client' => 0, 'invoice_currency' => $quote[0]['currency']);
									echo form_open('quotes/edit/'.$quote[0]['iid'], $attributes, $hidden);
								?>
									<div class="row">
										<div class="large-12 columns text-right small-only-text-left">
											<!-- Show email form messages if any -->
											<?php
												if ($this->session->flashdata('error')) { ?>
														<div class="alert-box radius text-center">
															<?php echo($this->session->flashdata('error')); ?>
														</div>
											<?php }?>
											<?php echo validation_errors();?>

										</div>
									</div>
									<div class="row invoice-info">
										<div class="medium-5 small-centered large-uncentered columns">
												<?php if(!empty($logo)): echo'<img class="company-logo" src="'.base_url().'uploads/logo/'.$quote[0]['uid']."/".$logo.'" />'; endif ?>
											<?php if(!empty($company_name)): echo '<h3>'.$company_name.'<h3/>'; endif ?>
											<div class="info-block">
												<ul>
													<?php if( !empty($quote[0]['my_address_1']) ): echo('<li>'.$quote[0]['my_address_1'].'</li>'); endif ?>
													<?php if( !empty($quote[0]['my_address_2']) ): echo('<li>'.$quote[0]['my_address_2'].'</li>'); endif ?>
													<?php if( !empty($quote[0]['my_city']) || !empty($quote[0]['my_state']) || !empty($quote[0]['my_zip']) ): echo('<li>'); endif ?><?php echo($quote[0]['my_city'].' '.$quote[0]['my_state'].' '.$quote[0]['my_zip']); ?><?php if( !empty($quote[0]['my_city']) || !empty($quote[0]['my_state']) || !empty($quote[0]['my_zip']) ): echo('</li>'); endif ?>
													<?php if( !empty($quote[0]['my_country']) ): echo('<li>'.$quote[0]['my_country'].'</li>'); endif ?>
												</ul>
											</div>
										</div>

										<div class="large-7 small-centered large-uncentered columns">


											<?php if( $edit === FALSE ) { ?>
												<div class="row">
												<div class="medium-6 columns">
													<h5 class="caps ruled">Billing Information</h5>
														<div class="info-block ">
															<ul id="clientAddress">
																<li><?php echo $quote[0]['company']; ?></li>
																<li><?php echo $quote[0]['contact']; ?></li>
																<li><?php if(!empty($address_1)): echo $address_1.'<br/>'; endif ?></li>
																<li><?php if(!empty($address_2)): echo $address_2.'<br/>'; endif ?></li>
																<li><?php if(!empty($city)): echo $city.' '; endif ?> <?php if(!empty($state)): echo $state.' '; endif ?> <?php if(!empty($zip)): echo $zip; endif ?></li>
															</ul>
													</div>
												</div>

												<div class="medium-6 columns">
													<h5 class="caps ruled">
															Quote Num
													</h5>
													<div class="info-block ">
														<?php if(!empty($quote[0]['prefix'])): echo $quote[0]['prefix'].'-'; endif ?><?php echo($quote[0]['inv_num']) ?>
													</div>

													<h5 class="caps ruled">
															Issue Date
													</h5>
													<div class="info-block">
														<?php echo($theDate['month'].' '.$theDate['day'].', '.$theDate['year']);?>
													</div>


												</div>


											</div>
											<?php } else { ?>
												<?php $this->load->view('templates/billing-info'); ?>
											<?php } ?>

										<?php
											if (!empty($quote[0]['description'])) { ?>
												<div class="row">
											<div class="columns small-12">
												<h5 class="ruled caps">Description</h5>
												<div class="info-block">
													<?php echo($quote[0]['description']);?>
												</div>
											</div>
										</div>
											<?php } ?>
										</div>
									</div>

									<?php if( $edit === TRUE ) { ?>
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
									<?php } ?>



											<?php if ($edit === TRUE ) {?>
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
											<?php } else { ?>
												<div class="invoice-create list_header clearfix">
													<div class="row">
														<div class="small-12 medium-2 large-2 columns">
															Qty
														</div>
														<div class="small-12 medium-4 large-6 columns">
															Description
														</div>
														<div class="small-12 medium-3 large-2 columns text-right">
															Price
														</div>
														<div class="small-12 medium-3 large-2 columns text-right">
															Total
														</div>
													</div>
												</div>
											<?php } ?>

											<div id="invoiceCreate" class="edit-list-container">
											<div class="tabbed list no-rules">

											<?php
												$i = 0;
												foreach ($iqtys as $item_qts):

												$number = $item_qts * $icosts[$i];
												$subTotal = $subTotal + $number;

												// CALCULATE TAX
												if ( $itax[$i] == $invoice_tax_1 )
												{
													$tx = ( $itax[$i] / 100 );
													$tax_1_amnt += $number * $tx;
												}
													else if ( $itax[$i] == $invoice_tax_2 )
												{
													$tx = ( $itax[$i] / 100 );
													$tax_2_amnt += $number * $tx;
												}

											?>

											<?php if( $edit === TRUE ) { // SHOW THE EDIT FORM ?>

													<div class="row item-row">
														<div class="qty small-12 medium-3 columns">
															<div class="row">
																<div class="small-6 medium-4 columns">
																	<input type="hidden" name="item_id[]" value="<?php echo $item_ids[$i] ?>" /><input type="text" class="qty sum" name="qty[]" value="<?php echo $item_qts ?>" required />
																	<small class="error">Quantity is required.</small>
																</div>
																<div class="small-6 medium-8 columns">

																	<select name="unit[]" class="unit">
																		<option value="{{unit.hours}}" ng-selected="{{unit.hours == '<?php print($iunit[$i]); ?>' && 'true' || 'false'}}">{{unit.hours}}</option>
																		<option value="{{unit.days}}" ng-selected="{{unit.days == '<?php print($iunit[$i]); ?>' && 'true' || 'false'}}">{{unit.days}}</option>
																		<option value="{{unit.services}}" ng-selected="{{unit.services == '<?php print($iunit[$i]); ?>' && 'true' || 'false'}}">{{unit.services}}</option>
																		<option value="{{unit.products}}" ng-selected="{{unit.products == '<?php print($iunit[$i]); ?>' && 'true' || 'false'}}">{{unit.products}}</option>
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
															<textarea type="text" name="description[]" rows="5"><?php echo $idescriptions[$i] ?></textarea>
														</div>

														<div class="price small-12 medium-2 columns">
															<div class="row">
																<div class="medium-12 columns">
																	<input type="text" class="unitCost sum" name="unit_cost[]" value="<?php echo $icosts[$i] ?>" required />
																</div>
															</div>
															<div class="row">
																<div class="medium-12 columns">
																	<select name="tax[]" class="tax">
																		<option value="0"></option>
																		<option value="{{invoice_tax_1}}" ng-selected="{{invoice_tax_1 == <?= $itax[$i] ?> && 'true' || 'false'}}">Tax 1: {{invoice_tax_1}}%</option>
																		<option value="{{invoice_tax_2}}" ng-selected="{{invoice_tax_2 == <?= $itax[$i] ?> && 'true' || 'false'}}">Tax 2: {{invoice_tax_2}}%</option>
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

											<?php } else { // SHOW THE STANDARD VIEW ?>

												<div class="row">
													<div class="small-12 medium-2 large-2 columns hide-for-small-only">
														<?php echo $item_qts ?> <?php echo $iunit[$i] ?>
													</div>
													<div class="small-12 medium-6 columns small-only-text-center">
														<?php echo $idescriptions[$i] ?>
													</div>
													<div class="small-12 small-only-text-center medium-2 columns text-right hide-for-small-only">
														<?php echo $icosts[$i]; ?>
													</div>

													<div class="small-12 small-only-text-center medium-2 columns text-right totalSum" data-totalsum="<?php echo number_format((float)$number, 2, '.', ','); ?>">
														<?php echo number_format((float)$number, 2, '.', ','); ?>
													</div>
													<div class="small-12 columns"><hr /></div>
												</div>


											<?php } ?>

										<?php
											$i++;
											endforeach
										?>

										<div id="invoiceRows" class="row tabbed" ng-repeat="item in items" ng-include="getIncludeFile()">

										</div>



									</div>
								</div> <!-- tabbed list -->
								<?php if($edit === TRUE ) { ?>
									<div class="row">
										<div class="large-12 columns text-left small-only-text-center">
											<button data-dropdown="drop1" aria-controls="drop1" aria-expanded="false" class="button dropdown add-dropdown radius">Add new item</button>

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


								<section id="invoiceTotals">
									<div class="row">
										<div class="medium-5 medium-offset-7">
											<div class="small-6 columns medium-text-right">
												<!--<h3>Total Due: <span id="invoiceTotal">$<?php echo number_format((float)$subTotal, 2, '.', ',');?></span></h3>-->
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
												<h3><span class="currency">{{selectedVal}}</span><span id="invoiceTotal" data-a-dec="." data-a-sep=''><?php echo number_format((float)$subTotal, 2, '.', ',');?></span></h3>

												<input type="hidden" ng-model="selectedVal" ng-init="selectedVal='<?php echo($currency);?>'" />

											</div>

										</div>
									</div>
								</section>

								<?php } else {?>


								<section id="invoiceTotals">
								<div class="row">

									<div class="medium-5 medium-push-7 columns">

										<div class="row">
											<div class="small-6 columns medium-text-right">
												<h4>Subtotal</h4>
											</div>
											<div class="small-6 columns text-right">
												<h4>
													<span id="invoiceSubtotal">
														<span id="amtLeft"><?php echo(number_format((float)($subTotal), 2, '.', ',')); ?>
													</span>
												</h4>
											</div>


											<?php if( $tax_1_amnt > 0 ) { ?>
											<div class="small-6 columns medium-text-right" ng-show="invoice_tax_1">
												<h4>Tax (<?php echo($quote[0]['invoice_tax_1']);?>%)</h4>
											</div>
											<div class="small-6 columns text-right" ng-show="invoice_tax_1">
												<h4><span id="taxOne"><?= number_format((float)($tax_1_amnt), 2, '.', ','); ?></span></h4>
											</div>
											<?php } ?>


											<?php if( $tax_2_amnt > 0 ) { ?>

											<div class="small-6 columns medium-text-right" ng-show="invoice_tax_2">
												<h4>Tax (<?php echo($quote[0]['invoice_tax_2']);?>%)</h4>
											</div>
											<div class="small-6 columns text-right" ng-show="invoice_tax_2">
												<h4><span id="taxTwo"><?= number_format((float)($tax_2_amnt), 2, '.', ','); ?></span></h4>
											</div>

											<?php } ?>

											<?php if ( $quote[0]['discount'] > 0 ): ?>
												<div class="small-6 columns medium-text-right" ng-show="discount">
													<h4>Discount</h4>
												</div>
												<div class="small-6 columns text-right" ng-show="discount">
													<h4>-<span id="discount"><?php echo(number_format((float)$quote[0]['discount'], 2, '.', ','));?></span></h4>
												</div>
											<?php endif; ?>

											<div class="small-12 columns">
												<hr />
											</div>

											<div class="small-6 columns medium-text-right">
												<h3>Total Due</h3>
											</div>
											<div class="small-6 columns text-right">
												<h3><span class="currency"><?php echo($currency)?></span><span id="invoiceTotal"></span><?php echo number_format((float)($quote[0]['amount']), 2, '.', ',');?></span></h3>
											</div>

										</div>


									</div>
									<div class="medium-7 medium-pull-5 columns">
										<hr class="show-for-small-only">
										<?php
											if (!empty($item['settings'][0]['notes'])) { ?>
												<h3>Payment Terms</h3>
												<p><?php echo($item['settings'][0]['notes']); ?></p>
										<?php  } ?>
									</div>
								</div>
								</section>

								<?php } ?>

								<?php if( $edit === TRUE ) { ?>
									<div class="row actions">
										<div class="large-12 columns text-right small-only-text-center">
											<input type="submit" name="submit" value="Save Changes" class="button round"/>
										</div>
										<div class="large-12 columns text-right small-only-text-center">
											<a href="#" id="deleteQuoteBtn" data-reveal-id="editModal">Delete Quote</a>
										</div>
									</div>
								<?php } ?>
							</form>
						</div>

			</div>
		</div>
</div>
<?php
	if ($this->uri->segment(2, 0) === "review") { ?>

		<div class="row">
			<div class="small-12 medium-12 large-4 columns large-centered">
				<div id="emailModal" class="reveal-modal small light-bg" data-reveal>
					<div id="form-errors"></div>
					<div id="form-wrap"></div>
					<?php $this->load->view('pages/quotes/email/view_approve_quote'); ?>
				</div>
			</div>
		</div>

<?php	} ?>

<?php if ($this->uri->segment(2, 0) === "view") { ?>
<div class="row">
	<div class="small-12 medium-12 large-4 columns large-centered">
		<div id="emailModal" class="reveal-modal small light-bg" data-reveal>
			<div id="form-errors"></div>
			<div id="form-wrap"></div>
			<?php echo($view_send_quote); ?>
		</div>
	</div>
</div>
<?php } ?>
<div class="row">
	<div class="small-12 medium-12 large-4 columns large-centered">
		<div id="editModal" class="reveal-modal small light-bg" data-reveal>
			<div class="row">
				<div class="small-10 columns text-center small-centered">
					<h3>Blimey!</h3>
					<p>Are you sure you want to delete this quote?</p>
					<hr />
					<a id="cancelDeleteBtn" href="#" class="button round secondary">No Thanks</a>
					<?php echo anchor('quotes/delete_quote/'.$quote[0]['iid'], 'Delete It', 'class="button round"', 'id="delete-'.$quote[0]['iid'].'"'); ?>
				</div>
			</div>
			<a class="close-reveal-modal">&#215;</a>
		</div>
	</div>
</div>

<?php if( $edit === TRUE ) { ?>

	<div class="row">
		<div class="small-12 medium-12 large-4 columns large-centered">
			<div id="revealModal" class="reveal-modal small" data-reveal>
				<div id="form-errors"></div>
				<div id="loadingImg"><img src="<?php echo base_url();?>assets/images/ajax-loader.gif" alt="loading" /></div>
				<div id="form-wrap"></div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	  $(document).ready(function() {

	  	function init_autoNumeric() {
				$('.sum, .totalSum, #invoiceTotal, #invoiceSubtotal, #discount').autoNumeric('init', {aDec:'.', aSep:'', aForm: false});
			}

			$(document).on('click', "#addItems", function() {
				init_autoNumeric();
			});

			init_autoNumeric();

			$("#invoiceSettingsBtn").on("click", function () {
				$("#invoiceSettings").toggle();
			});

		});
	</script>
<?php } ?>
