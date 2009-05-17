<?php
	require(__CONFIGURATION__ . '/header.inc.php');
?>

	<?php $this->RenderBegin() ?>

	<h1><?php _t('Plugins'); ?></h1>

	<?php $this->dtgPlugins->Render(); ?>

	<p class="create">
		<a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS__) ?>/plugin_edit.php"><?php _t('Install a New'); ?> <?php _t('Plugin');?></a>
	</p>

	<?php $this->RenderEnd() ?>
	
<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>