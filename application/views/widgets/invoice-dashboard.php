<?php
	$this->load->helper('currency_helper');
	$currency = currency_method($invoices[0]['currency_setting']);
	$drafts_sum = 0;
	$due_sum = 0;
	$paid_sum = 0;
	//var_dump($invoices);

	function filter_drafts($var)
	{
	    return (is_array($var) && $var['status'] == 0);
	}

	function filter_due($var)
	{
	    return (is_array($var) && $var['status'] == 4);
	}
	function filter_pay($var)
	{
	    return (is_array($var) && $var['status'] == 3);
	}

	$filtered_drafts = array_filter($payments, "filter_drafts");
	$filtered_due = array_filter($payments, "filter_due");
	$filtered_paid = array_filter($payments, "filter_pay");

	foreach ($filtered_drafts as $k) {
	  $drafts_sum+=$k['amount'];
	}

	foreach ($filtered_due as $k) {
	  $due_sum+=$k['amount'];
	}

	foreach ($filtered_paid as $k) {
	  $paid_sum+=$k['amount'];
	}
?>

<section class="dashboard text-left">

	<div class="row">

		<div class="columns medium-4 text-center large-text-left">
			<div class="dashb-item clearfix">
				<h5 class="caps ruled">Uninvoiced</h5>
				<div class="small-12 large-4 columns">
					<figure>
						<svg height="75" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 viewBox="0 0 61 75" enable-background="new 0 0 61 75" xml:space="preserve">
						<path fill="#24000f" d="M57,75H4c-2.2,0-4-1.8-4-4V4c0-2.2,1.8-4,4-4h53c2.2,0,4,1.8,4,4v67C61,73.2,59.2,75,57,75z M4,2
							C2.9,2,2,2.9,2,4v67c0,1.1,0.9,2,2,2h53c1.1,0,2-0.9,2-2V4c0-1.1-0.9-2-2-2H4z M22.5,13.5h-15v2h15V13.5z M7.5,18.5h18v-2h-18V18.5z
							 M23.5,10.5h-16v2h16V10.5z M7.5,7.5v2h18v-2H7.5z M9.9,42.5v-10h2.9c0.7,0,1.3,0.1,1.8,0.3c0.5,0.2,1,0.5,1.4,1
							c0.4,0.4,0.7,0.9,0.9,1.5c0.2,0.6,0.3,1.2,0.3,2v0.5c0,0.7-0.1,1.4-0.3,2c-0.2,0.6-0.5,1.1-0.9,1.5c-0.4,0.4-0.9,0.7-1.4,1
							c-0.6,0.2-1.2,0.3-1.9,0.3H9.9z M11.6,33.9v7.2h1.1c0.5,0,0.9-0.1,1.2-0.2c0.4-0.2,0.7-0.4,0.9-0.7c0.2-0.3,0.4-0.6,0.5-1.1
							c0.1-0.4,0.2-0.9,0.2-1.4v-0.5c0-1.1-0.2-1.9-0.7-2.5c-0.5-0.6-1.2-0.9-2-0.9H11.6z M22.7,38.6h-1.9v3.8H19v-10h3.5
							c0.6,0,1.1,0.1,1.5,0.2c0.4,0.1,0.8,0.3,1.1,0.6s0.5,0.6,0.7,0.9c0.2,0.4,0.2,0.8,0.2,1.3c0,0.7-0.2,1.2-0.5,1.7
							c-0.3,0.4-0.8,0.8-1.3,1l2.2,4.1v0.1h-1.9L22.7,38.6z M20.8,37.3h1.8c0.3,0,0.6,0,0.8-0.1s0.4-0.2,0.6-0.3c0.2-0.1,0.3-0.3,0.3-0.5
							c0.1-0.2,0.1-0.4,0.1-0.7c0-0.3,0-0.5-0.1-0.7c-0.1-0.2-0.2-0.4-0.3-0.5c-0.2-0.1-0.3-0.3-0.6-0.3c-0.2-0.1-0.5-0.1-0.8-0.1h-1.8
							V37.3z M33.4,40.2h-3.9l-0.8,2.3h-1.8l3.8-10h1.6l3.8,10h-1.8L33.4,40.2z M30,38.8h2.9l-1.4-4.1L30,38.8z M42.8,38.3h-4v4.2h-1.7
							v-10h6.3v1.4h-4.6v3h4V38.3z M52.1,33.9H49v8.6h-1.7v-8.6h-3.1v-1.4h7.9V33.9z"/>
						</svg>
					</figure>
				</div>
				<div class="small-12 large-8 columns">
					<h2><?= $currency ?><?php echo(str_replace(".00", "", (string)number_format ($drafts_sum, 2, ".", ""))); ?></h2>
				</div>
			</div>
		</div>

		<div class="columns medium-4 text-center large-text-left">
			<div class="dashb-item clearfix">
				<h5 class="caps ruled">Invoices Due</h5>
				<div class="small-12 large-4 columns">
					<figure>
						<svg height="75" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 viewBox="0 0 76 76" enable-background="new 0 0 76 76" xml:space="preserve">
						<path fill="#24000f" d="M38,76C17,76,0,59,0,38S17,0,38,0s38,17,38,38S59,76,38,76z M38,3C18.7,3,3,18.7,3,38s15.7,35,35,35
							s35-15.7,35-35S57.3,3,38,3z M38.5,37.5v-28h-2v30h21v-2H38.5z M6,38.5v-1h5v1H6z M65,38.5v-1h5v1H65z M15,15.7l0.7-0.7l3.5,3.5
							l-0.7,0.7L15,15.7z M56.7,57.4l0.7-0.7l3.5,3.5L60.3,61L56.7,57.4z M15.7,61L15,60.3l3.5-3.5l0.7,0.7L15.7,61z M57.4,19.3l-0.7-0.7
							l3.5-3.5l0.7,0.7L57.4,19.3z M38.5,70h-1v-5h1V70z"/>
						</svg>
					</figure>
				</div>
				<div class="small-12 large-8 columns">
					<h2><?= $currency ?><?php echo(str_replace(".00", "", (string)number_format ($due_sum, 2, ".", ""))); ?></h2>
				</div>
			</div>
		</div>

		<div class="columns medium-4 text-center large-text-left">
			<div class="dashb-item clearfix last">
				<h5 class="caps ruled">Invoices Paid</h5>
				<div class="small-12 large-4 columns">
					<figure>
						<svg height="75" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 viewBox="0 0 36 73" enable-background="new 0 0 36 73" xml:space="preserve">
						<path fill-rule="evenodd" clip-rule="evenodd" fill="#24000f" d="M25,65v8h-3v-8h-8v8h-3v-8H0v-3h11V39H0v-1v-2V12v-2V9h11V0h3v9h8
							V0h3v9h11v3H25v24h11v3v23v2v1H25z M11,12H3v24h8V12z M22,12h-8v24h8V12z M22,39h-8v23h8V39z M33,39h-8v23h8V39z"/>
						</svg>
					</figure>
				</div>
				<div class="small-12 large-8 columns">
					<h2><?= $currency ?><?php echo(str_replace(".00", "", (string)number_format ($paid_sum, 2, ".", ""))); ?></h2>
				</div>
			</div>
		</div>
	</div>
</section>
