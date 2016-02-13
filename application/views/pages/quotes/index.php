<?php if (!empty($client)): $company_name = $client[0]['company']; endif ?>

<?php
	if ($quotes) {
		$this->load->helper('currency_helper');
		$currency = currency_method($quotes[0]['currency']);
?>


	 <div class="row">

	 	<div class="large-12 columns text-center">
	 		<?php if ($this->uri->segment(1, 0) === "clients"){ ?>
				<h1><?php echo $quotes[0]['company'] ?></h1>
			<?php $this->load->view('widgets/client-subnav'); ?>
			<?php } else { ?>
				<h1>Quotes</h1>
			<?php } ?>
	 		<div class="row">
	 			<div class="medium-3 medium-centered columns">
 					<div id="plus-button" class="svg-container">
 						<a href="<?php echo base_url(); ?>index.php/quotes/create" class="plus-button">
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
		<div class="columns small-12">
			<hr>
		</div>
	 </div>

	<div id="mini-invoices" class="row">
		<?php
			$this->load->helper('status_flag_classes_helper');
			$count = count($quotes); $num = 0;
			foreach ($quotes as $quote_item):
		?>

			<div class="medium-3 large-2 columns mini-invoice <?php if($num == $count-1){ echo('end'); } ?>">
				<a href="<?php echo base_url(); ?>index.php/quotes/view/<?php echo $quote_item['iid']; ?>" >
					<div class="mini-invoice-inner">
						<div class="row">

							<div class="medium-4 columns">
								<span class="label secondary radius company">
									<?php echo substr($quote_item['company'], 0, 2); ?>
								</span>
							</div>
							<div class="medium-8 columns">
								<span class="invoice-num"><?php echo '#'.$quote_item['inv_num']; ?></span>
							</div>
							<div class="small-12 columns">
								<hr></hr>
							</div>
							<div class="small-12 columns">
								<span class="issue-date"><?php echo $quote_item['pdate']; ?></span>
							</div>
							<div class="small-12 columns">
								<hr></hr>
							</div>
							<div class="small-12 columns">
								<div class="small-ribbon <?php echo(strtolower($quote_flags[$quote_item['status']]));?>"><?php echo($quote_flags[$quote_item['status']]);?></div>
							</div>
						</div>
						<div class="row">
							<div class="medium-12 columns text-right">
								<div class="amount"><span class="currency"><?= currency_method($quote_item['currency']); ?></span><?php echo(str_replace(".00", "", (string)number_format ($quote_item['amount'], 2, ".", ""))); ?>

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



<?php	} else { ?>
	<div class="row">
		<div class="large-12 columns text-center">
			<?php if ($this->uri->segment(1, 0) === "clients"){ ?>
				<h1><?php echo $company_name ?></h1>
				<?php $this->load->view('widgets/client-subnav'); ?>
			<?php } else { ?>
				<h1>Quotes</h1>
			<?php } ?>
			<h4>Looks like you haven't created any quotes yet...</h4>
			<div id="plus-button" class="svg-container">
				<a href="<?php echo base_url(); ?>index.php/quotes/create" class="plus-button">
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
