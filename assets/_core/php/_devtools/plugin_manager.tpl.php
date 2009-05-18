<?php
	require(__CONFIGURATION__ . '/header.inc.php');
?>

	<?php $this->RenderBegin() ?>

	<h1><?php _t('Plugins'); ?></h1>

	<?php $this->dtgPlugins->Render(); ?>

	<div id="formActions">
		<?php $this->btnNewPlugin->Render(); ?>
	</div>
	
	<?php $this->dlgUpload->Render(); ?>
	
	<?php $this->RenderEnd() ?>
	
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>