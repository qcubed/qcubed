<?php
	$strPageTitle = "Plugin Manager";
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<h1><?php _t('Plugin Manager'); ?></h1>
		<?php $this->RenderBegin() ?>
	<p> QCubed uses Composer to install plugins. To install a plugin, simply require it
	   in your list of files in your root composer.json file, then execute the Composer 
	   install command.
	</p>
	<?php $this->dtgPlugins->Render(); ?>
	
	<hr />
	
	<p><a target="_blank" href="<?= QPluginInstaller::ONLINE_PLUGIN_REPOSITORY ?>">
	Online repository of QCubed plugins</a></p>
	
	<?php $this->dlgUpload->Render(); ?>
	
	<?php $this->RenderEnd() ?>
	
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>