	<?php
		$invoicAmount = $item[0]['amount'];
		$payment_amount = 0;
		$sumTotal = 0;
		$amount = 0;
		$hidden = array('iid' => $item[0]['iid']);

		if ( !empty($item['payments'][0]['pdate']) ) {
			$date = new DateTime($item['payments'][0]['pdate']);
		}


		//print("<pre>".print_r( $item, true )."</pre>");
	?>

	<?php
		foreach ($item['payments'] as $payments){
			$number = $payments['payment_amount'];
			$amount = $amount + $number;
		}
	?>

	<?php
		$sumTotal = max($invoicAmount - $amount,0);
		$attributes = array('class' => 'light-bg', 'id' => 'addPayment');
		echo form_open('invoices/add_payment/'.$item[0]['iid'], $attributes, $hidden);
	?>
		<div class="row">
			<div class="columns large-12">
				<label for="payment_amount[]">Amount</label>
				<input type="text" id="pamount" name="pamount" class="amt" value="<?php echo(number_format((float)($sumTotal), 2, '.', '')); ?>"/>
			</div>
		<?php if($sumTotal > 0) {?>

				<div class="columns large-12">
					<label>Date:</label>
				</div>
				<div class="columns large-3 small-3">
					<?php echo $dob_dropdown_day; ?>
				</div>
				<div class="columns large-5 small-5">
					<?php echo $dob_dropdown_month; ?>
				</div>
				<div class="columns large-4 small-4">
					<?php echo$dob_dropdown_year; ?>
				</div>
				<div class="small-12 columns">
					<hr />
				</div>
				<div class="columns small-12 text-center">
					<input type="submit" name="submit" value="Add Payment" class="button round"/>
				</div>
		<?php }?>

		<div class="columns large-12">



			<div id="invoicePayments" class="invoice-create">
				<?php
						if (!empty($item['payments'])) {
					?>
					<div class="invoice-create list_header">
						<div class="row">
							<div class="small-12 medium-2 columns small-only-text-center">
								Amount
							</div>
							<div class="small-12 medium-10 columns small-only-text-center">
								Date
							</div>
						</div>

					</div>

						<div class="tabbed list no-rules">
						<?php
							foreach ($item['payments'] as $payment){
						?>


							<div class="row">
								<div class="small-12 medium-2 columns small-only-text-center">
									<input type="hidden" name="payment_amount[]" class="amt" value="<?php echo $payment['payment_amount']; ?>" /><?php echo $payment['payment_amount']; ?>
								</div>
								<div class="small-12 medium-8 columns small-only-text-center">
									<?php echo ($date->format('F j, Y'));  ?>
								</div>
								<div class="small-12 medium-2 columns small-only-text-center text-right">
									<a href="<?php echo base_url(); ?>index.php/invoices/delete_payment?pid=<?php echo $payment["pid"].'&common_id='.$payment["common_id"].'&iuid='.$item[0]['uid']; ?>" class="button small round secondary"><i class="fi-x"></i></a>
								</div>
								<div class="small-12 columns"><hr /></div>
							</div>

						<?php	} ?>
						</div>
				<?php } ?>
			</div>

		</div>
		</div>
	</form>
	<a class="close-reveal-modal">&#215;</a>
