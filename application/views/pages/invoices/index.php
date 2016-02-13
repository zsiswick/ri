<?php if (!empty($client)): $company_name = $client[0]['company']; endif ?>
<?php
	if ($invoices) { ?>



	<div class="row">
	 	<div class="large-12 columns text-center">
	 		<div class="row">
	 		  <div class="large-12 columns text-center">

						<?php if ($this->uri->segment(1, 0) === "clients"){ ?>
							<h1><?php echo $invoices[0]['company'] ?></h1>
						<?php $this->load->view('widgets/client-subnav'); ?>
						<?php } else { ?>
							<h1>Invoices</h1>
						<?php } ?>

				</div>
	 		</div>
	 		<div class="row">
	 			<div class="medium-3 medium-centered columns">
 					<div id="plus-button" class="svg-container">
 						<a href="<?php echo base_url(); ?>index.php/invoices/create" class="plus-button">
	 						<svg version="1.1" viewBox="0 0 100 100" class="svg-content">
	 						<path fill-rule="evenodd" clip-rule="evenodd" fill="#fff" d="M50,0C22.4,0,0,22.4,0,50s22.4,50,50,50s50-22.4,50-50S77.6,0,50,0
	 							z M68.6,51.8H51.5v17.4c0,0.8-0.7,1.5-1.5,1.5s-1.5-0.7-1.5-1.5V51.8H30.6c-0.8,0-1.5-0.7-1.5-1.5s0.7-1.5,1.5-1.5h17.9V31.2
	 							c0-0.8,0.7-1.5,1.5-1.5s1.5,0.7,1.5,1.5v17.6h17.1c0.8,0,1.5,0.7,1.5,1.5S69.4,51.8,68.6,51.8z"/>
	 						</svg>
 						</a>
 					</div>
	 			</div>
	 		</div>


	 	</div>
	 </div>

	<?php if ($this->uri->segment(1, 0) === "invoices"): $this->load->view('widgets/invoice-dashboard'); endif ?>

	<div class="row">
		<div class="columns small-12">
			<hr>
		</div>
		<div class="small-12 columns">
			<div id="invoiceList" class="light-bg invoice-form clearfix" style="display:none">
				<div class="large-12 columns">
					<div class="row">
						<div class="large-12 columns text-center large-text-left">
							<h3 class="text-center">
								Recent Invoices
							</h3>
						</div>
					</div>

					<div class="invoice-create list_header clearfix">

							<div class="small-12 medium-2 columns invoice-id">
								ID
							</div>
							<div class="small-12 medium-2 columns date">
								Created
							</div>
							<div class="small-12 medium-4 large-4 columns client">
								Client
							</div>
							<div class="small-12 medium-2 large-2 columns text-right amount">
								Amount
							</div>
							<div class="small-12 medium-2 large-2 columns text-center status">
								Status
							</div>
					</div>

							<?php foreach ($payments as $payment):
								// Get comma delimited payments and put them into an array so we can find the sum of their amount
								$path = explode(",", $payment['ipayments']);
								$exp = array_merge($path);
								$sum = array_sum( $exp );
								$percent = ($sum / $payment['amount']) * 100;
								endforeach
							?>


							<?php
								$this->load->helper('currency_helper');
								foreach ($invoices as $invoice_item):
							?>

							<?php
								// Get comma delimited payments and put them into an array so we can find the sum of their amount

								$path = explode(",", $invoice_item['ipayments']);
								$exp = array_merge($path);
								$sum = array_sum( $exp );
								$percent = ($sum / $invoice_item['amount']) * 100;
							?>
							<div class="tabbed list clearfix">
								<div class="small-12 small-only-text-center medium-2 large-2 columns invoice-id">
									<a href="<?php echo base_url(); ?>index.php/invoices/view/<?php echo $invoice_item['iid']; ?>" class="button round small">#<?php echo $invoice_item['iid'];?></a>
								</div>
								<div class="small-12 small-only-text-center medium-2 large-2 columns date">
									<?php echo $invoice_item['pdate']; ?>
								</div>
								<div class="small-12 small-only-text-center medium-4 large-4 columns client">
									<?php echo $invoice_item['company']; ?>
								</div>
								<div class="small-12 small-only-text-center medium-2 large-2 columns text-right amount">
									<?= currency_method($invoice_item['currency']); ?><?php echo number_format((float)$invoice_item['amount'], 2, '.', ',');?>
								</div>
								<div class="small-12 small-only-text-center medium-2 large-2 columns text-right status">

									<?php
									// Display additional information for partial payment status
									if ($invoice_item['status'] == 2) { ?>
										<div class="progress round">
											<span class="progress-label has-tip" data-tooltip title="<?php echo(money_format('%.2n', $sum));?> Paid"><?php echo(round($percent).'%');?></span>
											<span class="meter" style="width:<?php echo(round($percent).'%');?>"></span>
										</div>
									<?php } else { ?>

										<?php
											if ($invoice_item['status'] == 4) { // Invoice Due

												echo('<span class="round alert label">'.$status_flags[$invoice_item['status']].'</span>');

											}	else if ($invoice_item['status'] == 3) {?>

												<div class="progress round">
													<span class="progress-label"><?php echo($status_flags[$invoice_item['status']]);?></span>
													<span class="meter complete" style="width:<?php echo(round($percent).'%');?>"></span>
												</div>
											<?php
											} else if	($invoice_item['status'] == 0){ // Invoice Draft
												echo('<span class="round secondary label">'.$status_flags[$invoice_item['status']].'</span>');
											} else if ($invoice_item['status'] == 1) { ?>
													<div class="progress round">
														<span class="progress-label"><?php echo($status_flags[$invoice_item['status']]);?></span>
														<span class="meter complete" style="width: 0%;"></span>
													</div>
											<?php }

											} ?>
								</div>
							</div>

						<?php endforeach ?>







				</div>
			</div>

				<div id="mini-invoices" class="row">
					<?php
						$this->load->helper('status_flag_classes_helper');
						$count = count($invoices); $num = 0;
						foreach ($invoices as $invoice_item):
					?>

						<div class="medium-3 large-2 columns mini-invoice <?php if($num == $count-1){ echo('end'); } ?>">
							<a href="<?php echo base_url(); ?>index.php/invoices/view/<?php echo $invoice_item['iid']; ?>" >
								<div class="mini-invoice-inner">
									<div class="row">

										<div class="medium-4 columns">
											<span class="label secondary radius company">
												<?php echo substr($invoice_item['company'], 0, 2); ?>
											</span>
										</div>
										<div class="medium-8 columns">
											<span class="invoice-num"><?php echo '#'.$invoice_item['inv_num']; ?></span>
										</div>
										<div class="small-12 columns">
											<hr></hr>
										</div>
										<div class="small-12 columns">
											<span class="issue-date"><?php echo $invoice_item['pdate']; ?></span>
										</div>
										<div class="small-12 columns">
											<hr></hr>
										</div>
										<div class="small-12 columns">
											<div class="small-ribbon <?= status_flag_classes($invoice_item); ?>"><?php echo($status_flags[$invoice_item['status']]); ?></div>
										</div>
									</div>
									<div class="row">
										<div class="medium-12 columns text-right">
											<div class="amount"><span class="currency"><?= currency_method($invoice_item['currency']); ?></span><?php echo(str_replace(".00", "", (string)number_format ($invoice_item['amount'], 2, ".", ""))); ?>

											</div>

										</div>

									</div>

								</div>
							</a>
						</div>
					<?php
						$num++;
						endforeach
					?>
				</div>
				<div class="row">
					<div class="columns small-12">
						<div class="pagination-centered">
							<?php echo $this->pagination->create_links(); ?>
						</div>
					</div>
				</div>
		</div>
	</div>



<?php	} else { ?>
	<div class="row">
		<div class="large-12 columns text-center">

			<?php if ($this->uri->segment(1, 0) === "clients"){ ?>
				<h1><?php echo $company_name ?></h1>
				<?php $this->load->view('widgets/client-subnav'); ?>
			<?php } else { ?>
				<h1>Invoices</h1>
			<?php } ?>

			<h4>Looks like you haven't created any invoices yet...</h4>
			<div id="plus-button" class="svg-container">
				<a href="<?php echo base_url(); ?>index.php/invoices/create" class="plus-button">
					<svg version="1.1" viewBox="0 0 100 100" class="svg-content">
					<path fill-rule="evenodd" clip-rule="evenodd" fill="#fff" d="M50,0C22.4,0,0,22.4,0,50s22.4,50,50,50s50-22.4,50-50S77.6,0,50,0
						z M68.6,51.8H51.5v17.4c0,0.8-0.7,1.5-1.5,1.5s-1.5-0.7-1.5-1.5V51.8H30.6c-0.8,0-1.5-0.7-1.5-1.5s0.7-1.5,1.5-1.5h17.9V31.2
						c0-0.8,0.7-1.5,1.5-1.5s1.5,0.7,1.5,1.5v17.6h17.1c0.8,0,1.5,0.7,1.5,1.5S69.4,51.8,68.6,51.8z"/>
					</svg>
				</a>
			</div>
		</div>
	</div>
<?php	}
?>
