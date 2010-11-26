<?php
	$strPageTitle = "Plugin Manager";
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<?php $this->RenderBegin() ?>

	<h1><?php _t('Plugin Manager'); ?></h1>

	<?php $this->dtgPlugins->Render(); ?>

	<div id="formActions">
		<?php $this->btnNewPlugin->Render(); ?>
	</div>
	
	<p><a target="_blank" href="<?php echo QPluginInstaller::ONLINE_PLUGIN_REPOSITORY ?>">
	Online repository of QCubed plugins</a></p>
	
	<?php $this->dlgUpload->Render(); ?>
	
	<?php $this->RenderEnd() ?>
	
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>