<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Ruby Invoice</title>
	
	<style type="text/css">
		
		@font-face {
		    font-family: 'robotoregular';
		    src: url('fonts/roboto/roboto-regular-webfont.eot');
		    src: url('fonts/roboto/roboto-regular-webfont.eot?#iefix') format('embedded-opentype'),
		         url('fonts/roboto/roboto-regular-webfont.woff') format('woff'),
		         url('fonts/roboto/roboto-regular-webfont.ttf') format('truetype'),
		         url('fonts/roboto/roboto-regular-webfont.svg#robotoregular') format('svg');
		    font-weight: normal;
		    font-style: normal;
		
		}
	
		body, p, h1, h2, h3, h4, h5, h3.top-rule {
			color: #000 !important;
			background: #fff;
			font-family: "robotoregular", Helvetica, Arial, sans-serif;
		}
		.light-bg .ruled, .info-block.last, h3.top-rule, .list_header, hr {
			border-color: #ccc;
		}
		table thead th{
			background: #fff;
			color: #000;
		}
		table tr, table td {
			background: #fff;
		}
		
		.rule {
			padding: 0px;
			margin: 0px;
			line-height: 0;
		}
		h5 {
			text-transform: uppercase;
			font-size: 10px;
			padding: 0;
			margin: 0;
			line-height: 0;
		}
		div.info-block {
			margin-top: 20px;
			margin-bottom: 40px;
		}
		th {
			text-transform: uppercase;
			font-size: 10px;
		}
		hr {
			border-bottom: 1px solid #fff;
		}
		
	</style>
</head>

<body>
	
	<div id="" class="light-bg">
					<table style="width: 100%;">
						<tr>
							<td style="width: 35%;" valign="top">
								<hr class="rule" />
								<h5>Sender Information</h5>
								<hr class="rule" />
								
								<p><?php echo($name); ?></p>
								<div class="info-block">
										<?php echo($address) ?>
								</div>
							</td>
							<td style="width: 5%;" valign="top">&nbsp;</td>
							<td style="width: 30%;" valign="top">
									
									<hr class="rule" />
									<h5>Billing Information</h5>
									<hr class="rule" />
								
									<div class="info-block">
									<?php echo $client_name.'<br/>'; ?>
									<?php echo($client_address); ?> <br /><br />
								</div>
							</td>
							<td style="width: 0%;" valign="top">&nbsp;</td>
							<td style="width: 30%;" valign="top">
								<hr class="rule" />
								<h5>
										Invoice ID
								</h5>
								<hr class="rule" />
								<div class="info-block">	
									<?php echo($invoice_num); ?>
									<br /><br />
								</div>
								
								<hr class="rule" />
								<h5>
										Creation Date
								</h5>
								<hr class="rule" />
								
								<div class="info-block">	
									<?php 
										$date = new DateTime($send_date);
										echo ($date->format('F j, Y'));
									?>
									<br /><br />
								</div>
								
									<hr class="rule" />
									<h5>
											Due Date
									</h5>
									<hr class="rule" />
									
									<div class="info-block last">
										<?php 
											$date2 = new DateTime($due_date);
											echo ($date2->format('F j, Y'));
										?>
										<br /><br />
								</div>
							</td>
						</tr>
						
						<?php
							if (!empty($inv_description)) { ?>
								<tr>
									<td colspan="3" style="width: 35%;" valign="top">
										<hr class="rule" />
										<h5 >Description</h5>
										<hr class="rule" />
										
										<div class="info-block">
											<?php echo($inv_description);?>
										</div>
									</td>
									<td style="width: 5%;" valign="top"></td>
								</tr>
								<tr>
									<tr>
										<td colspan="3" style="height: 25px;">&nbsp;</td>
									</tr>
								</tr>
							<?php } ?>
							
					</table>
					
					<table style="width: 100%;" cellpadding="0" cellspacing="0" border-collapse="collapse">
						<thead class="invoice-create list_header">
							<tr><th colspan="4"><hr /></th></tr>
							<tr>
								<th style="text-align: left;">Qty</th>
								<th style="text-align: left;">Description</th>
								<th style="text-align: right;">Price</th>
								<th style="text-align: right;">Total</th>
							</tr>
							<tr><th colspan="4"><hr /></th></tr>
						</thead>
						<?php 
							$i = 0;
							$sumTotal = 0; 
							foreach ($qty as $item_qts): 
							 
							$number = $item_qts * $unit_cost[$i]; 
							$sumTotal = $sumTotal + $number;
							
						?>
						<tr>
							<td style="text-align: left;">
								<?php echo $qty[$i]; ?>
							</td>
							<td style="text-align: left;">
								<?php echo $description[$i]; ?>
							</td>
							<td style="text-align: right;">
								<?php echo '$'.$unit_cost[$i]; ?>
							</td>
							<td style="text-align: right;" data-totalsum="<?php echo number_format((float)$number, 2, '.', ','); ?>">
								$<?php 
									echo number_format((float)$number, 2, '.', ','); 
								?>
							</td>
						</tr>	
						<tr>
							<td colspan="4"><hr /></td>
						</tr>
						
					<?php 
						$i++;
						endforeach 
					?>
				</table>	
				
				<table style="width: 100%;">
					<tr>
						<td valign="top" style="width: 70%;"></td>
						<td style="width: 30%;">
							<table style="width: 250px;">
								<tr>
									<td style="width: 40%; text-align: left;">
										<h3>Due:</h3>
									</td>
									<td style="width: 60%; text-align: right;">
										<h3>$<span id="invoiceTotal"></span><?php echo number_format((float)($sumTotal), 2, '.', ',');?></h3>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="height: 50px;">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3>Payment Terms</h3>
							<p><?php echo($terms_conditions); ?></p>
						</td>
					</tr>
				</table>				
</div>
	
					
</body>

</html>