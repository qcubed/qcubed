<?php
	$strPageTitle = 'Basic Test';
 	require('../../../../../../project/includes/configuration/header.inc.php');

?>

<?php $this->RenderBegin(); ?>
<?php $this->txtText->RenderWithName(); ?>
<?php $this->txtText2->RenderWithName(); ?>
<?php $this->chkCheck->RenderWithName(); ?>
<?php $this->lstSelect->RenderWithName(); ?>
<?php $this->lstSelect2->RenderWithName(); ?>
<?php $this->lstCheck->RenderWithName(); ?>
<?php $this->lstCheck2->RenderWithName(); ?>
<?php $this->lstRadio->RenderWithName(); ?>
<fieldset>
	<legend>Radio Group</legend>
	<?php $this->rdoRadio1->RenderWithName(); ?>
	<?php $this->rdoRadio2->RenderWithName(); ?>
	<?php $this->rdoRadio3->RenderWithName(); ?>

</fieldset>
<?php $this->btnImage->RenderWithName(); ?>
<?php $this->btnServer->Render(); ?>
<?php $this->btnAjax->Render(); ?>
<?php $this->btnSetItemsAjax->Render(); ?>
<?php $this->RenderEnd(); ?>
<?php require('../../../../../../project/includes/configuration/footer.inc.php'); ?>