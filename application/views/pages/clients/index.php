<?php
	if ($clients) { ?>
		<section>
			<div class="row">
			  <div class="large-12 columns text-center">
			  <h1>Clients</h1></div>
			</div>
		</section>

		<div class="row">
			<div class="medium-3 medium-centered text-center columns">
				<div id="plus-button" class="svg-container">
					<a href="<?php echo base_url(); ?>index.php/clients/create" class="plus-button">
						<svg version="1.1" viewBox="0 0 100 100" class="svg-content">
						<path fill-rule="evenodd" clip-rule="evenodd" fill="#fff" d="M50,0C22.4,0,0,22.4,0,50s22.4,50,50,50s50-22.4,50-50S77.6,0,50,0
							z M68.6,51.8H51.5v17.4c0,0.8-0.7,1.5-1.5,1.5s-1.5-0.7-1.5-1.5V51.8H30.6c-0.8,0-1.5-0.7-1.5-1.5s0.7-1.5,1.5-1.5h17.9V31.2
							c0-0.8,0.7-1.5,1.5-1.5s1.5,0.7,1.5,1.5v17.6h17.1c0.8,0,1.5,0.7,1.5,1.5S69.4,51.8,68.6,51.8z"/>
						</svg>
					</a>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="columns small-12">
				<br/>
			</div>
		</div>

		<?php foreach ($clients as $client): ?>
			<?php if ( isset($client['cid']) ): ?>
				<div class="row">
					<div class="small-12 columns">
						<a href="<?php echo base_url(); ?>index.php/clients/projects/<?php echo $client['cid']; ?>">
							<div class="panel radius clickable minimal">
								<div class="row collapse">
									<div class="small-6 medium-8 columns">
										<div class="client-title">
											<span class="label secondary radius company left show-for-landscape"><?php echo substr($client['company'], 0, 2); ?></span>
											<h4 class="left no-bottom-margin"><?php echo($client['company']); ?></h4>
										</div>
									</div>
									<div class="small-3 medium-2 columns text-right">
										<figure>
											<h4 class="no-bottom-margin">$<?php echo $client['owing']; ?></h4><span class="small-type gray">Owed</span>
										</figure>
									</div>
									<div class="small-3 medium-2 columns text-right">
										<figure>
											<h4 class="no-bottom-margin">$<?php echo $client['unbilled']; ?></h4><span class="small-type gray">Unbilled</span>
										</figure>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		<div class="row">
			<div class="small-12 columns">
				<div class="row collapse">
					<div class="small-6 medium-8 columns">
						<div class="client-title">
							<h4 class="text-right gray">Total</h4>
						</div>
					</div>
					<div class="small-3 medium-2 columns text-right">
						<div class="panel minimal" style="border-right:none">
							<figure>
								<h4 class="no-bottom-margin">$<?php echo $clients['totals']['total_owing']; ?></h4><span class="small-type gray">Owed</span>
							</figure>
						</div>
					</div>
					<div class="small-3 medium-2 columns text-right">
						<div class="panel minimal" style="border-left:none">
							<figure>
								<h4 class="no-bottom-margin">$<?php echo $clients['totals']['total_unbilled']; ?></h4><span class="small-type gray">Unbilled</span>
							</figure>
						</div>
					</div>
				</div>
			</div>
		</div>

<?php	} else { ?>
	<div class="row">
	  <div class="large-12 columns text-center">
	  <h1>Clients</h1></div>
	</div>
	<div class="row">
		<div class="large-12 columns text-center">
			<h4>No clients yet? No worries, just add one and you'll be good to go!</h4>
			<div id="plus-button" class="svg-container">
				<a href="<?php echo base_url(); ?>index.php/clients/create" class="plus-button">
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
