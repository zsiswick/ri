	</section>
<footer class="hide-for-print">
	<div class="row">
		<div class="large-6 columns text-center small-centered">
			<div id="lil-ruby" class="svg-container">
				<svg version="1.1" viewBox="0 0 22.8 22.6" class="svg-content">
				<g>
					<path fill="#5c585b" d="M11.4,5.7L0,11.4l11.2,11.2l11.6-11.2L11.4,5.7z M12,12.2l-1.1,7.9l-4.6-7.9H12z M6.7,11.2l4.4-3.9l0.8,3.9
						H6.7z M13,12.2h3.5L12,19.4L13,12.2z M12.9,11.2l-0.7-3.5l3.9,3.5H12.9z M5.2,11.2H2.8l5.5-2.8L5.2,11.2z M5.1,12.2l4.1,7.1
						l-7.1-7.1H5.1z M17.7,12.2h2.9l-7.3,7L17.7,12.2z M17.6,11.2l-3.1-2.8l5.5,2.8H17.6z"/>
					<path fill="#5c585b" d="M3.7,8.1C3.8,8.2,3.9,8.2,4,8.2c0.1,0,0.3-0.1,0.4-0.2c0.2-0.2,0.2-0.5-0.1-0.7L2.1,5.5
						C1.9,5.3,1.6,5.3,1.4,5.5C1.3,5.7,1.3,6,1.5,6.2L3.7,8.1z"/>
					<path fill="#5c585b" d="M19.3,8.2c0.1,0,0.2,0,0.3-0.1l2.2-1.9C22,6,22,5.7,21.9,5.5c-0.2-0.2-0.5-0.2-0.7-0.1L19,7.4
						c-0.2,0.2-0.2,0.5-0.1,0.7C19,8.2,19.2,8.2,19.3,8.2z"/>
					<path fill="#5c585b" d="M4.8,2.9C4.9,3,5.1,3.1,5.3,3.1c0.1,0,0.2,0,0.2-0.1c0.2-0.1,0.3-0.4,0.2-0.7L5.6,2.2C5.5,2,5.2,1.9,5,2
						C4.7,2.1,4.6,2.4,4.7,2.7L4.8,2.9z"/>
					<path fill="#5c585b" d="M11.6,4.7c0.3,0,0.5-0.2,0.5-0.5v-2c0-0.3-0.2-0.5-0.5-0.5s-0.5,0.2-0.5,0.5v2C11.1,4.5,11.3,4.7,11.6,4.7z
						"/>
					<path fill="#5c585b" d="M11.6,1c0.1,0,0.3-0.1,0.3-0.1c0.1-0.1,0.2-0.2,0.2-0.4c0-0.1,0-0.3-0.2-0.4c-0.2-0.2-0.5-0.2-0.7,0
						c-0.1,0.1-0.1,0.2-0.1,0.4c0,0.1,0,0.3,0.1,0.4C11.3,0.9,11.4,1,11.6,1z"/>
					<path fill="#5c585b" d="M5.6,4.4l1.1,2c0.1,0.2,0.3,0.3,0.4,0.3c0.1,0,0.2,0,0.2-0.1C7.6,6.5,7.7,6.2,7.6,6l-1.1-2
						C6.4,3.7,6.1,3.6,5.8,3.7C5.6,3.9,5.5,4.2,5.6,4.4z"/>
					<path fill="#5c585b" d="M17.3,3.1c0.1,0,0.2,0.1,0.2,0.1c0.2,0,0.4-0.1,0.4-0.3l0.1-0.2c0.1-0.2,0-0.5-0.2-0.7
						c-0.2-0.1-0.5,0-0.7,0.2l-0.1,0.2C17,2.6,17.1,2.9,17.3,3.1z"/>
					<path fill="#5c585b" d="M15.5,6.6c0.1,0,0.2,0.1,0.2,0.1c0.2,0,0.4-0.1,0.4-0.3l1.1-2c0.1-0.2,0-0.5-0.2-0.7
						c-0.2-0.1-0.5,0-0.7,0.2l-1.1,2C15.1,6.2,15.2,6.5,15.5,6.6z"/>
				</g>
				</svg>
			</div>
			<h4>Say Hello</h4>
			<a href="mailto:hello@rubyinvoice.com">hello@rubyinvoice.com</a> or tweet us <a href="https://twitter.com/intent/tweet?screen_name=rubyinvoice&amp;text=Hi!" target="_blank">@rubyinvoice</a>
			<hr class="light-bg" />
		</div>

	</div>
	<div class="row">
		<div class="large-6 small-centered columns text-center">
			&#169; <?php echo(date('Y'))?> Ruby Invoice &nbsp;|&nbsp;  <?php echo anchor('contact', 'Contact Us'); ?> &nbsp;|&nbsp; <?php echo anchor('welcome/privacy', 'Privacy Policy'); ?>  &nbsp;|&nbsp; <?php echo anchor('welcome/terms', 'Terms'); ?>  &nbsp;|<!--&nbsp;--> <?php /* echo anchor('welcome/free_invoice_template', 'Free Invoice Template'); */?> <br/>Made by <a href="http://www.chromaloop.com">Chromaloop</a><p></p>
		</div>
	</div>
</footer>
</body>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/foundation.min.js"></script>
<? if (isset($js_to_load)) :
		foreach ($js_to_load as $js) : ?>
			<script type="text/javascript" src="<?php echo base_url();?>assets/js/<?=$js;?>"></script>
<?php endforeach;?>

<? endif;?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/scripts.js"></script>
<?php
	if (strpos(base_url(),'localhost') != true) { ?>
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/ga.js"></script>
<?php } ?>
</html>
