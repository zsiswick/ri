<div class="row">
	<div class="large-6 columns large-centered">
		<h1 class="text-center"><?php echo $client[0]['company'] ?></h1>
	</div>
</div>

<?php $this->load->view('widgets/client-subnav');?>

<div class="row">
	<div class="large-8 columns large-centered">
		<div class="invoice-list-wrap invoice-form light-bg clearfix">
			<div class="">
				<?php echo validation_errors(); ?>

				<?php echo form_open('clients/edit/'.$client[0]['id']) ?>
					<div class="row">
						<div class="small-6 columns">
							<input type="hidden" name="cid" value="<?php echo $client[0]['id'] ?>" />
							<label for="company">Company</label>
							<input type="text" name="company" value="<?php echo $client[0]['company'] ?>"/>
						</div>
						<div class="small-6 columns">
							<label for="contact">Contact Name</label>
							<input type="text" name="contact" value="<?php echo $client[0]['contact'] ?>"/>
						</div>
					</div>

					<div class="row">
						<div class="small-6 columns">
							<label for="email">Email</label>
							<input type="email" name="email" value="<?php echo $client[0]['email'] ?>" />
						</div>
						<div class="small-6 columns">
							<label for="phone">Phone</label>
							<input type="tel" name="phone" value="<?php echo $client[0]['phone'] ?>" />
						</div>
					</div>

					<div class="row">
						<div class="small-6 columns">
							<label for="address_1">Address 1</label>
							<input type="text" name="address_1" value="<?php echo $client[0]['address_1'] ?>"/>
						</div>
						<div class="small-6 columns">
							<label for="address_2">Address 2</label>
							<input type="text" name="address_2" value="<?php echo $client[0]['address_2'] ?>"/>
						</div>
					</div>

					<div class="row">
						<div class="small-6 columns">
							<label for="city">City</label>
							<input type="text" name="city" value="<?php echo $client[0]['city'] ?>"/>
						</div>
						<div class="small-3 columns">
							<label for="state">State</label>
							<input type="text" name="state" max="2" value="<?php echo $client[0]['state'] ?>" />
						</div>
						<div class="small-3 columns">
							<label for="zip">Zip</label>
							<input type="text" name="zip" value="<?php echo $client[0]['zip'] ?>"/>
						</div>
					</div>

					<label for="country">Country</label>
					<input type="text" name="country" value="<?php echo $client[0]['country'] ?>" />

					<div class="row">
						<div  class="small-7 columns">
							<label for="tax_id">Tax Id</label>
							<input type="text" name="tax_id" value="<?php echo $client[0]['tax_id'] ?>"/>
						</div>
						<div  class="small-5 columns">
							<label for="default_inv_prefix">Invoice Prefix</label>
							<input type="text" name="default_inv_prefix" value="<?php echo $client[0]['default_inv_prefix'] ?>"/>
						</div>
					</div>

					<label for="notes">Notes</label>
					<textarea name="notes" id="" cols="30" rows="10"><?php echo $client[0]['notes'] ?></textarea>


					<div class="row">
						<div class="large-12 columns text-right small-only-text-center">
							<input type="submit" name="submit" value="Save Changes" class="button round" />
						</div>
					</div>
					<div class="row">
						<div class="large-12 columns text-right small-only-text-center">
							<a href="#" id="deleteClientBtn" data-reveal-id="editModal">Delete Client</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="small-12 medium-12 large-4 columns large-centered">
		<div id="editModal" class="reveal-modal small light-bg" data-reveal>
			<div class="row">
				<div class="small-10 columns text-center small-centered">
					<h3>Careful Now!</h3>
					<p>Are you sure you want to delete this client?</p>
					<p>When you delete clients, all of the invoices created for that client are deleted as well.</p>
					<hr />
					<a id="cancelDeleteBtn" href="#" class="button round secondary">No Thanks</a>
					<?php echo anchor('clients/delete/'.$client[0]['id'], 'Delete Client', 'class="button round"', 'id="delete-'.$client[0]['id'].'"'); ?>
				</div>
			</div>
			<a class="close-reveal-modal">&#215;</a>
		</div>
	</div>
</div>
