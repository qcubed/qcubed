<?php
	$strPageTitle = 'Basic Test';
 	require('../../../../../../project/includes/configuration/header.inc.php');

?>

<?php $this->RenderBegin(); ?>
<?php $this->dtg->Render(); ?>
<?php $this->lblVars->Render(); ?>
<?php $this->RenderEnd(); ?>
<?php require('../../../../../../project/includes/configuration/footer.inc.php'); ?>