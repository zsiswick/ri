<?php
	$hidden = array('iid' => $item[0]['iid']);
	$address_1 = $item['client'][0]['address_1'];
	$address_2 = $item['client'][0]['address_2'];
	$city = $item['client'][0]['city'];
	$state = $item['client'][0]['state'];
	$zip = $item['client'][0]['zip'];
	$inv_num = $item[0]['prefix'].'-'.$item[0]['inv_num'];
	//////////////////////////////////
	$logo = $item['settings'][0]['logo'];
	$company_name = $item['settings'][0]['company_name'];
	$cust_email = $item['settings'][0]['email'];
	$p_address_1 = $item['settings'][0]['address_1'];
	$p_address_2 = $item['settings'][0]['address_2'];
	$p_city = $item['settings'][0]['city'];
	$p_state = $item['settings'][0]['state'];
	$p_zip = $item['settings'][0]['zip'];
	$invoice_id = $this->uri->segment(3, 0);
	$client_key = $this->uri->segment(4, 0);
	$this->load->helper('currency_helper');
	$currency = currency_method($item[0]['currency']);
	$this->load->helper('status_flag_classes_helper');
?>

<div id="invoiceContainer">
	<div id="container">
		<div class="row">
			<div class="large-12 columns">
					<div class="row">
						<div class="small-12 columns text-center">
							<p></p><a href="<?php echo base_url(); ?>index.php/invoices/pdf/<?php echo $item[0]['iid']?>" class="button round"><i class="fi-download"></i> Download PDF</a>
							<?php
								if($item['settings'][0]['enable_payments'] == 1 && $item[0]['status'] != 3 && $item['settings'][0]['stripe_cust_token'] == true): echo anchor('#', 'Pay Invoice', 'title="Pay Invoice" data-reveal-id="paymentModal" class="button round"'); endif
							?>
						</div>
					</div>

					<div class="invoice-wrap">
						<div class="ribbon <?= status_flag_classes($item[0]); ?>"><div><?php echo($status_flags[$item[0]['status']]);?></div></div>
						<div class="invoice-form light-bg">


								<?php
									if ($this->session->flashdata('error')) { ?>
										<div class="row">
											<div class="small-12 columns">
											<div class="alert-box radius text-center">
												<?php echo($this->session->flashdata('error')); ?>
											</div>
										</div>
										</div>
								<?php }?>

								<div class="row invoice-info">
									<div class="medium-5 small-centered large-uncentered columns">
											<?php if(!empty($logo)): echo'<img class="company-logo" src="'.base_url().'uploads/logo/'.$item[0]['uid']."/".$logo.'" />'; endif ?>
										<?php if(!empty($company_name)): echo '<h3>'.$company_name.'</h3>'; endif ?>
											<ul>
												<?php if( !empty($item['settings'][0]['address_1']) ): echo('<li>'.$item['settings'][0]['address_1'].'</li>'); endif ?>
												<?php if( !empty($item['settings'][0]['address_2']) ): echo('<li>'.$item['settings'][0]['address_2'].'</li>'); endif ?>
												<?php if( !empty($item['settings'][0]['city']) || !empty($item['settings'][0]['state']) || !empty($item['settings'][0]['zip']) ): echo('<li>'); endif ?><?php echo($item['settings'][0]['city'].' '.$item['settings'][0]['state'].' '.$item['settings'][0]['zip']); ?><?php if( !empty($item['settings'][0]['city']) || !empty($item['settings'][0]['state']) || !empty($item['settings'][0]['zip']) ): echo('</li>'); endif ?>
												<?php if( !empty($item['settings'][0]['country']) ): echo('<li>'.$item['settings'][0]['country'].'</li>'); endif ?>
											</ul>
									</div>
									<div class="large-7 small-centered large-uncentered columns">
										<div class="row">
											<div class="medium-6 columns">
												<h5 class="caps ruled on-paper">Billing Information</h5>
													<div class="info-block">
														<ul id="clientAddress">
															<li><?php echo $item['client'][0]['company']; ?></li>
															<li><?php echo $item['client'][0]['contact']; ?></li>
															<li><?php if(!empty($address_1)): echo $address_1.'<br/>'; endif ?></li>
															<li><?php if(!empty($address_2)): echo $address_2.'<br/>'; endif ?></li>
															<li><?php if(!empty($city)): echo $city.' '; endif ?> <?php if(!empty($state)): echo $state.' '; endif ?> <?php if(!empty($zip)): echo $zip; endif ?></li>
														</ul>
												</div>
											</div>

											<div class="medium-6 columns">
												<h5 class="caps ruled on-paper">
														Invoice Num
												</h5>
												<div class="info-block">
													<?php if(!empty($item[0]['prefix'])): echo $item[0]['prefix'].'-'; endif ?><?php echo($item[0]['inv_num']) ?>
												</div>

												<h5 class="caps ruled on-paper">
														Creation Date
												</h5>
												<div class="info-block">
													<?php echo($theDate['month'].' '.$theDate['day'].', '.$theDate['year']);?>
												</div>

													<h5 class="caps ruled on-paper">
															Due Date
													</h5>
													<div class="info-block last">
														<?php

															$today = new DateTime(date('Ymd'));
															$due = new DateTime($item[0]['due_date']);
															// Calculate the difference between today's date, and the invoice due date
															$diff = $today->diff($due);

															if ($item[0]['status'] == 3){ ?>
																INVOICE PAID
															<?php }

															else if ($item[0]['status'] == 4) { ?>
																<?php echo $diff->format('%a DAYS'); ?> PAST DUE

														<?php	} else { ?>

																<?php

																	$date = new DateTime($item[0]['due_date']);
																	echo ($date->format('F j, Y')); ?>


														<?php	} ?>
												</div>
											</div>
										</div>
										<?php
											if (!empty($item[0]['description'])) { ?>
												<div class="row">
											<div class="columns small-12">
												<h5 class="ruled caps">Description</h5>
												<div class="info-block">
													<?php echo($item[0]['description']);?>
												</div>
											</div>
										</div>
											<?php } ?>
									</div>
								</div>

								<?php include APPPATH.'views/templates/view-invoice-rows.php'; ?>

						</div>

					</div>

			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="columns small-12 text-center">
		<p></p><a href="https://www.positivessl.com" style="font-family: arial; font-size: 10px; color: #212121; text-decoration: none;"><img src="https://www.positivessl.com/images-new/PositiveSSL_tl_trans2.png" alt="SSL Certificate" title="SSL Certificate" border="0"></a>
	</div>
</div>
<div class="row">
	<div class="small-12 medium-12 large-4 columns large-centered">
		<div id="paymentModal" class="reveal-modal small light-bg text-center" data-reveal>
			<h3>Pay Invoice</h3>
			<hr />
			<div id="form-errors" class="alert-box round"></div>
			<div id="form-wrap">

				<?php require_once(APPPATH.'config/stripe.php'); ?>
				<form action="<?php echo(base_url());?>index.php/invoice/stripe_payment" method="POST">
					<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						data-key="<?php echo(PUBLISHABLE_KEY); ?>"
						data-image="<?php echo base_url();?>assets/images/ruby-invoice-logo.jpg"
						data-name="Ruby Invoice"
						data-description="Invoice: #<?php echo($inv_num);?> (<?php echo($currency)?><?php echo number_format((float)($item[0]['amount'] - $payment_amount), 2, '.', ',');?>)">
					</script>
					<input type="hidden" name="uid" value="<?php echo($item[0]['uid']);?>" />
					<input type="hidden" name="invoice_num" value="<?php echo($inv_num);?>" />
					<input type="hidden" name="invoice_amount" value="<?php echo($item[0]['amount'] - $payment_amount);?>" />
					<input type="hidden" name="iid" value="<?php echo($item[0]['iid']); ?>"/>
					<input type="hidden" name="client_key" value="<?php echo($client_key); ?>"/>
					<input type="hidden" name="cust_email" value="<?php echo($cust_email); ?>"/>
					<input type="hidden" name="payment_notification" value="<?php echo($item['settings'][0]['payment_notification']); ?>"/>
				</form>
			</div>
		</div>
	</div>
</div>
