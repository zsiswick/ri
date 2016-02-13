<?php
$attributes = array('autocomplete'=>'off', 'data-abide' => '');
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'required' => ''
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'required' => ''
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>

		<?php echo form_open($this->uri->uri_string(), $attributes); ?>
		
				<div class="row">
					<div class="large-6 medium-10 small-centered columns">
						<div class="form-wrap invoice-form light-bg">
							
							<h1 class="text-center">Sign In</h1>
							<div class="row">
										<div class="large-12 small-centered columns">
											<?php echo form_label($login_label, $login['id']); ?>
											<?php echo form_input($login); ?>
											<?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
											<small class="error">Email or login is required.</small>
										</div>
									</div>
									
							<div class="row">
								<div class="large-12 small-centered columns">
									<?php echo form_label('Password', $password['id']); ?>
									<?php echo form_password($password); ?>
									<?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?>
									<small class="error">Password is required.</small>
								</div>
							</div>
							
							<?php if ($show_captcha) {
								if ($use_recaptcha) { ?>
								
							<div class="row">
								<div class="large-12 small-centered columns">
									<div id="recaptcha_image"></div>
									<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
									<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
									<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>	
								</div>
							</div>
							<div class="row">
								<div class="large-12 small-centered columns">
									<div class="recaptcha_only_if_image">Enter the words above</div>
									<div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
									<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
									<?php echo form_error('recaptcha_response_field'); ?>
								</div>
							</div>
							
							<?php } else { ?>
							
							<div class="row">
								<div class="large-12 small-centered columns">
									<p>Enter the code exactly as it appears:</p>
									<?php echo $captcha_html; ?>
								</div>
							</div>
							<div class="row">
								<div class="large-12 small-centered columns">
										<?php echo form_label('Confirmation Code', $captcha['id']); ?>
										<?php echo form_input($captcha); ?></td>
										<?php echo form_error($captcha['name']); ?>
								</div>
							</div>
							
							<?php }
							} ?>
							
							<div class="row">
								<div class="large-12 small-centered columns">
									<?php echo form_checkbox($remember); ?>
									<?php echo form_label('Remember me', $remember['id']); ?>
									<hr />
								</div>
							</div>
							
							<div class="row">
								<div class="columns large-12 small-centered text-center">
									<?php 
									$sdata = array(
									  'name' => 'submit',
									  'class' => 'button round',
									  'value' => 'Sign In',
									  'type' => 'submit',
									  'content' => 'Sign In'
									);
									echo form_submit($sdata);?> <br />
									<?php echo anchor('/auth/forgot_password/', 'Forgot Password'); ?> &nbsp;|&nbsp; 
									<?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Register'); ?>
								</div>
							</div>
												
												
						</div>
					</div>
				</div>
		<?php echo form_close(); ?>

