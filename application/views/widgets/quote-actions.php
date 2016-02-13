<div class="row">
	<div class="small-12 columns">
		<h3 class="small-only-text-center">Quote Actions</h3>
		<div class="row">
			<div class="medium-3 columns">
				<h5 class="ruled caps">
					Edit
				</h5>
				<div class="info-block">
					<a href="<?php echo base_url()?>index.php/quotes/edit/<?php echo $quote[0]['iid']?>" class="button small round secondary">Edit Quote</a>
				</div>
			</div>
			<div class="medium-3 columns">

				<h5 class="ruled caps">
					Send
				</h5>

				<div class="info-block">
					<a href="#" data-reveal-id="emailModal" class="button small round secondary">Send Quote</a>
				</div>

			</div>
			<div class="medium-3 columns">

				<h5 class="ruled caps">
					Permalink
				</h5>
				<div class="info-block">
					<a href="<?php echo base_url(); ?>index.php/quotes/review/<?php echo $quote[0]['iid']?>/<?php echo $quote[0]['key']?>" class="button round small secondary">View</a>
				</div>

			</div>
			<div class="columns medium-3">
				<h5 class="ruled caps">
					Create invoice from quote
				</h5>
				<div class="info-block">
					<a href="<?php echo base_url()?>index.php/quotes/convert/<?php echo $quote[0]['iid']?>" class="button small round secondary">Create Invoice</a>
				</div>
			</div>
		</div>

	</div>
</div>
