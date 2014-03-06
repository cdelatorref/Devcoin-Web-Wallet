
<div id="container">
	<h1>Account configuration</h1>

	<div id="body">
		<?=form_open("/account"); ?> 
		<?=heading("Password change",3); ?>
		
		<div>In order to change your password, you must insert the following information:</div>
		
		<div>
		<?=form_label("New password:", "password"); ?>
		
		<?=form_password("password",""); ?>
		
		</div>
		<div>
		<?=form_label("Repeat your password:", "password2"); ?>
		
		<?=form_password("password2",""); ?>
		
		</div>
		<div>
		<?=form_label("Current password:", "password3"); ?>
		
		<?=form_password("password3",""); ?>
		
		</div>
		<div>
		<?=form_label("2FA Auth Code:", "authCode"); ?>
		
		<?=form_input("authCode", ""); ?>
		
		</div>
		<div>
		<?=form_submit("changepassword", "Change Password"); ?>
		
		</div>

		<?= $success;?>
		<?= $errors;?>
		<?= form_close(); ?>

	</div>
	
	<div id="body">
		<?=form_open("/account"); ?> 
		<?=heading("Basic information",3); ?>

		<div>
		<?=form_label("Nickname:", "nickname"); ?>
		
		<?=form_input("nickname",$nickname); ?>
		
		<br>
		<?=form_label("Note: When you choose a nickname it can't be changed", ""); ?>
		
		</div>
		
		<div>
		<?=form_label("Personal message:", "personalmessage"); ?>
		
		<br>
		<?=form_textarea("personalmessage",$personalmessage); ?>

		</div>
		
		<?=form_submit("changebasicinfo", "Change Basic Information"); ?>
		
		</div>

		<?= $successBasicInfo;?>
		<?= $errorsBasicInfo;?>
		<?= form_close(); ?>

	<div id="body">
		<?=form_open("/account"); ?> 
		<?=heading("Activate Google 2FA",3); ?>
		<?=form_label("Instructions: You must download yo your smartphone the Google's Authenticator app, then you must scan the QR code showed bellow, it will give you an automatic generated code, insert it in the bottom field to activate the 2FA Authentication", ""); ?>
		<div>
		<?= $qrcode;?>
		</div>
		<div>
		<?=form_label("Generated code:", "newcode"); ?>
		
		<?=form_input("newcode",""); ?>
		
		</div>
		<div>
		<?=form_submit("activate2FACode", "Activate 2FA Code"); ?>
		
		</div>

		<?= $successGoogle2fa;?>
		<?= $errorsGoogle2fa;?>
		<?= form_close(); ?>

	</div>
	
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>