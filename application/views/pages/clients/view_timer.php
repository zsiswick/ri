

		<?php
			$attributes = array('class' => 'light-bg text-center', 'data-abide'=>'');
			echo form_open('clients/create_timer/'.$task[0]['id'], $attributes);
		?>

			<div class="row">
				<div class="small-12 columns">
					<h3><?php echo($task[0]['task_name']); ?></h3>
				</div>
				<div class="small-12 columns">
					<textarea type="text" id="description" name="description" rows="4" placeholder="What are you working on?"></textarea>
				</div>
				<div class="columns small-12">
					<input type="hidden" name="task_id" value="<?php echo($task[0]['id']) ?>"/>
					<input type="hidden" name="project_id" value="<?php echo($task[0]['project_id']) ?>"/>
					<input type="hidden" class="timer" name="timer"/>
					<div class="timer round">
							<span class="hour">00</span>:<span class="minute">00</span>:<span class="second">00</span>
					</div>
					<div class="control">
							<a id="t-play" class="button small round secondary"><i class="fi-play"></i></a>
							<a id="t-pause" class="button small round secondary"><i class="fi-pause"></i></a>
							<!--<a onClick="timer.reset(0)" class="button small round">Reset</a>
							<a onClick="timer.mode(1)" class="button small round">Count up</a>
							<a onClick="timer.mode(0)" class="button small round">Count down</a>-->

							<a id="t-add-time" class="button small round secondary">+15 Mins</a>
							<a id="t-minus-time" class="button small round secondary">-15 Mins</a>
					</div>
					<hr/>
					<?php
						$attributes = array(
							'class' => 'button radius',
						);
						echo form_submit($attributes, 'Save to Task');
					?>
				</div>
			</div>

		</form>

<? if (isset($js_to_load)) :
		foreach ($js_to_load as $js) : ?>
			<script type="text/javascript" src="<?php echo base_url();?>assets/js/<?=$js;?>"></script>
<?php endforeach;?>

<? endif;?>
