<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>
	
	<div class="instructions">
		<h1 class="instruction_title">The QSelect2ListBox Control</h1>
		<b>QSelect2ListBox</b> wraps the QListBox control with the select2 jQuery plugin.
		<br/><br/>
		Below is the <a href="<?php echo __EXAMPLES__; ?>/basic_qform/listbox.php"> same example as the one for QListBox</a>, but uses QSelect2ListBox.
	</div>

	<div>
		<?php $this->lstPersons->Render(); ?><br/><br/>
		Currently Selected: <?php $this->lblMessage->Render(); ?>
	</div>

	<?php $this->RenderEnd(); ?>
<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/footer.inc.php'); ?>
