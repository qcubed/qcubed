<?php
	require(__CONFIGURATION__ . '/header.inc.php');
?>

	<?php $this->RenderBegin() ?>

	<h1><?php _t('Plugin Editing Form')?></h1>

	<div id="formControls">
		<p>This is a placeholder; this page will show the details<br>
		of a given control (like a list of its components),<br>
		and will allow the user to uninstall it if they so choose. </p>
	</div>

	<?php $this->RenderEnd() ?>	

<?php require(__CONFIGURATION__ .'/footer.inc.php'); ?>