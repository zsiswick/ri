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
