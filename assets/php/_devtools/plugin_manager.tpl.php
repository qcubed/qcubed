<?php
	$strPageTitle = "Plugin Manager";
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<h1><?php _t('Plugin Manager'); ?></h1>
		<?php $this->RenderBegin() ?>
	<p> QCubed uses Composer to install plugins. To install a plugin, simply execute the 'composer require plugin_name' command on your command line.
	</p>
	<p>Below is a list of your currently installed plugins.</p>
	<?php $this->dtgPlugins->Render(); ?>
	
	<hr />
	

	<?php $this->RenderEnd() ?>
	
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>