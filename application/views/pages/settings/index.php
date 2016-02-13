<?php
	$uid = $this->tank_auth_my->get_user_id();
	//print("<pre>".print_r($settings,true)."</pre>");
?>
<div class="row">
	<div class="large-6 columns large-centered">
		<h1 class="text-center">Settings</h1>
	</div>
</div>
<div class="row">
	<div class="large-8 columns large-centered">
		<div class="form-wrap invoice-form light-bg">
			<?php echo validation_errors(); ?>
			<?php
				if (isset($upload_error))
				{
					echo($upload_error);
				}
			?>
			<?php
				if ($this->session->flashdata('error')) { ?>
						<div class="alert-box radius text-center">
							<?php echo($this->session->flashdata('error')); ?>
						</div>
			<?php }?>
			<?php
				$hidden = array('uid' => $uid);
				$attributes = array('data-abide'=>'');
				echo form_open_multipart('settings', $attributes, $hidden);
			?>
			<ul class="tabs" data-tab role="tablist">
			  <li class="tab-title active" role="presentational" ><a href="#panel2-1" role="tab" tabindex="0" aria-selected="true" controls="panel2-1">Basic Info</a></li>
			  <li class="tab-title" role="presentational" ><a href="#panel2-2" role="tab" tabindex="0"aria-selected="false" controls="panel2-2">Payment Settings</a></li>
			</ul>
			<div class="tabs-content">
			  <section role="tabpanel" aria-hidden="false" class="content active" id="panel2-1">

			    <div class="row">
			    	<div class="small-12 columns">
			    		<label for="companyName">Company Name</label>
			    		<input type="text" name="company_name" value="<?php echo($settings[0]['company_name']) ?>" required />
			    		<small class="error">Company Name is required.</small>
			    	</div>

			    </div>

			    <div class="row">
			    	<div class="small-6 columns">
			    		<label for="fullname">Full Name</label>
			    		<input type="text" name="full_name" value="<?php echo($settings[0]['full_name']) ?>" required />
			    		<small class="error">Full Name is required.</small>
			    	</div>
			    	<div class="small-6 columns">
			    		<label for="email">Email</label>
			    		<input type="email" name="email" value="<?php echo $settings[0]['email'] ?>" required pattern="email" />
			    		<small class="error">Valid email is required.</small>
			    	</div>
			    </div>

			    <div class="row">
			    	<div class="small-6 columns">
			    		<label for="address_1">Address 1</label>
			    		<input type="text" name="address_1" value="<?php echo $settings[0]['address_1'] ?>"/>
			    	</div>
			    	<div class="small-6 columns">
			    		<label for="address_2">Address 2</label>
			    		<input type="text" name="address_2" value="<?php echo $settings[0]['address_2'] ?>"/>
			    	</div>
			    </div>

			    <div class="row">
			    	<div class="small-6 columns">
			    		<label for="city">City</label>
			    		<input type="text" name="city" value="<?php echo $settings[0]['city'] ?>"/>
			    	</div>
			    	<div class="small-3 columns">
			    		<label for="state">State</label>
			    		<input type="text" name="state" value="<?php echo $settings[0]['state'] ?>" maxlength="2" />
			    	</div>
			    	<div class="small-3 columns">
			    		<label for="zip">Zip</label>
			    		<input type="text" name="zip" value="<?php echo $settings[0]['zip'] ?>"/>
			    	</div>
			    </div>
			    <div class="row">
			    	<div class="small-6 columns">
			    		<label for="country">Country</label>
			    		<input type="text" name="country" value="<?php echo $settings[0]['country'] ?>" />
			    	</div>

			    	<div class="small-6 columns">
			    		<label for="currency">Default Currency</label>
			    		<?php
			    			$currency_options = array(
			    		    'en_AU'    => 'AUD (Australia)',
			    		    'pt_BR'  => 'BRL (Brazil)',
			    		    'en_CA'   => 'CAD (Canadian)',
			    		    'cs_CZ'    => 'CZK (Czech Republic)',
			    		    'da_DK'   => 'DKK (Denmark)',
			    		    'nl_NL'    => 'EUR (Euro Member Countries)',
			    		    'de_DE' => 'German (Germany)',
			    		    'el_GR' => 'Greek (Greece)',
			    		    'hu_HU'    => 'HUF (Hungary)',
			    		    'he_IL'   => 'ILS (Israel)',
			    		    'it_IT' => 'Italian (Italy)',
			    		    'ja_JP'  => 'JPY (Japan)',
			    		    'ko_KR' => 'Korean (South Korea)',
			    		    'en_NZ'   => 'NZD (New Zealand)',
			    		    'pl_PL'   => 'PLN (Poland)',
			    		    'ru_RU' => 'Russian (Russia)',
			    		    'de_CH'    => 'SHF (Switzerland)',
			    		    'en_GB'   => 'GBP (United Kingdom)',
			    		    'en_US'  => 'USD (United States)'
			    		  );
			    			echo form_dropdown('currency', $currency_options, $settings[0]['currency']);
			    		?>
			    	</div>
			    </div>
			    <div class="row">
			    	<div class="small-12 columns">
			    		<div class="info-block">
				    		<?php
				    			if (!empty($filename))
				    			{
				    				echo('<img src="'.base_url().'uploads/logo/'.$uid."/".$filename.'" class="logo thumb" />');
				    				echo('<a class="small-type" href="'.base_url().'index.php/settings/remove_logo/'.$uid.'">Remove</a>');
				    			}
				    		?>
			    			<label for="userfile">Upload a Logo</label>
			    			<input type="file" name="userfile" size="20" />
			    			<p class="small-type">Max image size 700x700 px.<br/>Max file size is 300kb.<br/>Allowed formats: gif, png, or jpg.</p>
			    		</div>
			    	</div>
			    </div>

			  </section>
			  <section role="tabpanel" aria-hidden="true" class="content" id="panel2-2">

			    <div class="row">
			    	<div class="columns medium-6">
			    		<input type="hidden" name="sid" value="" />
			    		<label for="due">Invoices Due</label>


			    		<?php
			    			$options = array(
			    		                '15'  => '15 Days',
			    		                '30'    => '30 Days',
			    		                '45'   => '45 Days',
			    		              );


			    			echo form_dropdown('due', $options, $settings[0]['due']);
			    		?>
			    	</div>

			    	<div class="columns medium-6">
			    		<label for="due">Send a Reminder Every</label>


			    		<?php
			    			$options = array(
			    		                '7'  => '7 Days',
			    		                '15'  => '15 Days',
			    		                '30'    => '30 Days',
			    		                '45'   => '45 Days',
			    		              );


			    			echo form_dropdown('remind', $options, $settings[0]['remind']);
			    		?>
			    	</div>
			    </div>


			    <div class="row">
			    	<div class="columns small-12">
			    		<label for="notes">Payment Terms</label>
			    		<textarea placeholder="Please remit full payment <?php echo($settings[0]['due']); ?> days from receipt of invoice. Make check payable to <?php echo($settings[0]['company_name']); ?>" name="notes" cols="30" rows="5"><?php echo($settings[0]['notes']) ?></textarea>
			    	</div>
			    </div>
			    <div class="row">
			    	<div class="medium-4 columns">
			    		<label for="tax_1">Tax 1 %</label>
			    		<input type="text" name="tax_1" value="<?php echo($settings[0]['tax_1']) ?>" pattern="integer"/>
			    		<small class="error">Number is required.</small>
			    		<label for="tax_2">Tax 2 %</label>
			    		<input type="text" name="tax_2" value="<?php echo($settings[0]['tax_2']) ?>" pattern="integer" />
			    		<small class="error">Number is required.</small>
			    	</div>
			    </div>

			    <div class="row">
			    	<div class="columns small-12 end">
			    		<hr />
			    		<h3>Invoice Payments <i class="fi-info size-18" data-tooltip title="Get paid quicker! Once you've linked a payment gateway with your Ruby Invoice account, your clients will be able to pay invoices directly."></i></h3>
			    		<label>Enable Payments</label>
			    		<div class="switch round">
			    		  <input id="enable_payments" name="enable_payments" type="checkbox" <?php if( $settings[0]['enable_payments'] == 1) {?> checked="checked" <?php } ?> value="1"/>
			    		  <label for="enable_payments"></label>
			    		</div>
			    	</div>
			    </div>



			    <div id="payment_settings" class="row">
			    	<div class="columns medium-6">
			    		<h5 class="ruled caps">Connect to Stripe</h5>
			    		<div class="info-block">
			    			<?php
			    				if ( $settings[0]['stripe_cust_token'] == false ) {

			    					echo anchor('https://connect.stripe.com/oauth/authorize?response_type=code&client_id='.STRIPE_CLIENT_ID.'&scope=read_write&stripe_user[email]='.$settings[0]['email'].'&stripe_user[business_name]='.$settings[0]['company_name'].'&stripe_user[street_address]='.$settings[0]['address_1'].'&stripe_user[city]='.$settings[0]['city'].'&stripe_user[state]='.$settings[0]['state'].'&stripe_user[zip]='.$settings[0]['zip'], 'Connect Account', 'title="Connect Account" class="button small round"');

			    				} else {

			    					echo anchor('settings/disconnect_stripe', 'Disconnect Account', 'title="Disconnect Account" class="button small round"');

			    				}
			    			?>
			    		</div>

			    		<ul>
			    			<li><i class="fi-credit-card" data-tooltip title="Learn more at: www.stripe.com/us/pricing"></i> Stripe Fee: 2.9% + 30¢</li>
			    			<li><hr /></li>
			    			<li><i class="fi-dollar-bill"></i> Ruby Invoice Fee: <?php echo(RUBY_TRANSACTION_FEE)?>%</li>
			    		</ul>
			    	</div>
			    	<div class="columns medium-6">
			    		<h5 class="ruled caps">Stripe Settings</h5>
			    		<div class="info-block">
			    			<p class="small-type">You must have a <a href="https://stripe.com"><strong>Stripe Account</strong></a> and you must grant Ruby Invoice third-party API access to your Stripe account.</p>
			    			<ol class="small-type">
			    				<li>Clicking <strong>Connect Account</strong> will redirect to a Stripe connect page.</li>
			    				<li>If you already have a Stripe account, sign in, otherwise you can create an account on that page.</li>
			    				<li>After granting permissions, you will be redirected back to this page with your Stripe account connected.</li>
			    			</ol>
			    		</div>

			    	</div>
			    	<div class="columns small-12">
			    		<hr />
			    		<input type="checkbox" id="payment_notification" name="payment_notification" <?php if( $settings[0]['payment_notification'] == 1) {?> checked="checked" <?php } ?> value="1" /><label for="payment_notification">Send me a notification email upon payment</label>
			    	</div>
			    </div>

			  </section>
			</div>

					<div class="row">
						<div class="large-12 columns text-right small-only-text-center">
							<input type="submit" name="submit" value="Save Changes" class="button round" />
						</div>
					</div>

				</form>

		</div>
	</div>
</div>
