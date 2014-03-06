<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Devcoin Open Wallet</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>Devcoin Open Wallet</h1>

	<div id="body">
		<?=form_open("/"); ?> 
		<div>
		<?=heading("Create new Account",3); ?>
		
		</div>
		<div>
		<?=form_label("Email:", "email"); ?>
		
		<?=form_input("email", $email); ?>
		
		</div>
		<div>
		<?=form_label("Repeat your Email:", "email2"); ?>
		
		<?=form_input("email2", $email2); ?>
		
		</div>
		<div>
		<?=form_label("Password:", "password"); ?>
		
		<?=form_password("password",""); ?>
		
		</div>
		<div>
		<?=form_label("Repeat your password:", "password"); ?>
		
		<?=form_password("password2",""); ?>
		
		</div>
		<div>
		<?=$recaptcha_html ?>
		</div>
		<div>
		<?=form_label("Accept ", "tos"); ?>
		<a href="<?=site_url('tos');?>">Terms of Service (TOS)</a>
		<?=form_checkbox("tos", "1", FALSE);?>
		
		</div>
		<div>
		<?=form_submit("join", "Join"); ?>
		
		</div>
		<?= $errors; ?>
		<?= $success; ?>
		<?= form_close(); ?>
		<?=form_open("/"); ?> 
		<?=heading("Login",3); ?>
		<div>
		<?=form_label("Email:", "emailLogin"); ?>
		
		<?=form_input("emailLogin", $emailLogin); ?>
		
		</div>
		<div>
		<?=form_label("Password:", "passwordLogin"); ?>
		
		<?=form_password("passwordLogin",""); ?>
		
		</div>
		<div>
		<?=form_label("2FA Auth Code:", "authCode"); ?>
		
		<?=form_input("authCode", ""); ?>
		
		</div>
		<div>
		
		<a href="<?=site_url('forgotpassword');?>">I forgot the password</a>
		</div>
		<div>
		<?=form_submit("login", "Login"); ?>
		
		</div>
		<?= $errorsLogin; ?>
		<?= form_close(); ?>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>