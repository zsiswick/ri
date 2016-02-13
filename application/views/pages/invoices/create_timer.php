
<? if (!empty($css_to_load)) :
		foreach ($css_to_load as $css) : ?>
			<link rel="stylesheet" href="<?php echo base_url();?>assets/css/<?=$css;?>" />
<?php endforeach;?>

<? endif;?>

		<?php
			$attributes = array('class' => 'invoice-form light-bg', 'data-abide'=>'');
			echo form_open('invoices/create_timer/', $attributes);
		?>

			<div class="row">
				<div class="small-12 columns">
					<textarea type="text" id="description" name="description" rows="4" placeholder="What are you working on?"></textarea>
				</div>
			</div>
			<input type="hidden" class="timer" placeholder="0 sec"/>
			<div class="timer round">
					<span class="hour">00</span>:<span class="minute">00</span>:<span class="second">00</span>
			</div>
			<div class="control">
					<a onClick="timer.start(1000)" class="button small round secondary"><i class="fi-play"></i></a>
					<a onClick="timer.stop()" class="button small round secondary"><i class="fi-pause"></i></a>
					<!--<a onClick="timer.reset(0)" class="button small round">Reset</a>
					<a onClick="timer.mode(1)" class="button small round">Count up</a>
					<a onClick="timer.mode(0)" class="button small round">Count down</a>-->
			</div>
			<?php
				$attributes = array(
			    'class' => 'button radius',
				);
				echo form_submit($attributes, 'Save to Task');
			?>
		</form>

<? if (isset($js_to_load)) :
		foreach ($js_to_load as $js) : ?>
			<script type="text/javascript" src="<?php echo base_url();?>assets/js/<?=$js;?>"></script>
<?php endforeach;?>

<? endif;?>
