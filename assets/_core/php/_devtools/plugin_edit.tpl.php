<?php
	$strPageTitle = "Plugin Editor";
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<?php $this->RenderBegin() ?>

	<h1><?php $this->lblName->Render();?> Plugin</h1>
	<p><?php $this->lblDescription->Render(); ?></p>
	
	<p><b>Version</b> <?php $this->lblPluginVersion->Render(); ?>,
		works with QCubed <?php $this->lblPlatformVersion->Render(); ?></p>
	
	<p><b>Author</b>: <?php $this->lblAuthorName->Render(); ?> 
		<?php $this->lblAuthorEmail->Render(); ?></p>

	<div id="formActions">
		<?php $this->btnInstall->Render() ?>
		<?php $this->btnCancelInstallation->Render() ?>
		<?php $this->btnUninstall->Render() ?>
		<?php $this->objDefaultWaitIcon->Render() ?>
	</div>
	<?php $this->dlgStatus->Render() ?>
	<?php $this->RenderEnd() ?>

<?php require(__CONFIGURATION__ .'/footer.inc.php'); ?>