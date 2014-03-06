<div id="container">
	<h1>Site Administration</h1>

	<div id="body">
	<?=form_open("/admin"); ?> 
	<?=heading("Filter",3); ?>
	<div>
		<?=form_label("ID:", "id"); ?>
		
		<?=form_input("id", $id); ?>
		
	</div>
	<div>
		<?=form_label("E-mail:", "email"); ?>
		
		<?=form_input("email", $email); ?>
		
	</div>
	<div>
		<?=form_label("Nickname:", "nickname"); ?>
		
		<?=form_input("nickname", $nickname); ?>
		
	</div>
	<div>
	<?=form_submit("applyfilter", "Apply Filter"); ?>
	</div>
	<?= form_close(); ?>
	<?= $userstable;?>
	<?= $success;?>
	<?= $errors;?>
	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>