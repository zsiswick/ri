<div class="row">
	<div class="medium-6 columns">
			<h5 class="caps ruled">
					Billing Information
				</h5>
				<div class="info-block">	
			
					<?php 
						if ($settings) {
							// Map select option values to the list of clients available
							$clientList = array_map(function ($ar) {
								return $ar['company'];
							}, $settings);
							$clientID = array_map(function ($ar) {
								return $ar['id'];
							}, $settings);
							$clientList = array_combine($clientID, $clientList);
							$clientList['add_new_client'] = 'Add New Client';
							echo form_dropdown('client', $clientList, 1);
						} else {
							$clientList = array('choose' => 'Choose...', 'add_new_client' => 'Add New Client');
							$attributes = 'required="" type="number"';
							echo form_dropdown('client', $clientList, 1, $attributes);
						}
					?>
					<small class="error">Client is required.</small>
						<ul id="client_data">
							<li id="contactName"></li>
							<li id="addressOne"></li>
							<li id="addressTwo"></li>
							<li id="cityStateZip"></li>
						</ul>
					
					<script type="text/javascript">
				    $(document).ready(function() {
				    
				    	var client_data = <?php echo json_encode($settings); ?>;
				    	var client_val = $('[name="client"]').val();
				    	var count = 0;
					    
					    function update_address(count, client_val) 
					    {
					    	if($.isNumeric(client_val)) {
					    		$('#contactName').html( client_data[count]['contact'] );
					    		$('#addressOne').html( client_data[count]['address_1'] );
					    		$('#addressTwo').html( client_data[count]['address_2'] );
					    		$('#cityStateZip').html( client_data[count]['city']+' '+client_data[count]['state']+' '+client_data[count]['zip'] );
					    		$('input[name="prefix"]').attr('value', client_data[count]['default_inv_prefix']);
					    	} else {
					    		$('#contactName').html('');
					    		$('#addressOne').html('');
					    		$('#addressTwo').html('');
					    		$('#cityStateZip').html('');
					    	}
					    }
						    
							$('[name="client"]').on( "change", function() {
								var count = $(this)[0].selectedIndex;
								
							  client_val =  $( this ).val();
							  update_address(count, client_val);
							});
							
							update_address(count, client_val);
							
							if (client_data.length) {
								$('input[name="prefix"]').attr('value', client_data[count]['default_inv_prefix']);
							}
							$('#send-date, #due-date').pickadate({
							    formatSubmit: 'yyyy-mm-dd',
							    hiddenName: true
							});
						});
				  </script>
			</div> 
	</div>
	<div class="medium-6 columns">
		<div class="">
			<h5 class="caps ruled">
				Quote ID
			</h5>
			<div class="info-block">
				<div class="row">
					<div class="small-4 columns"><input type="text" name="prefix" placeholder="Prefix" maxlength="6"/></div>
					<div class="small-8 columns"><input type="text" readonly="readonly" name="invoice_num" placeholder="Quote Number" /></div>
				</div>
			</div>	
		</div>
		<h5 class="caps ruled">
				Issue Date
			</h5>
			<div class="info-block">
				<div class="row">
				<div class="small-12 columns">
					<input type="text" id="send-date" name="issue-date" data-value="<?php echo( date('Y-m-d')); ?>" required />
					<small class="error">Issue date is required.</small>
				</div>	
				</div>	
			</div>
	</div>
</div>