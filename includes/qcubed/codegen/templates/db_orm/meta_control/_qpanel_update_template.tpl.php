<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS__  ?>" TargetFileName="<?php echo $objTable->ClassName ?>UpdatePanel.tpl.php"/>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>UpdatePanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard subdirectory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.
?>
<fieldset class="ui-widget-content left">
	<legend class="ui-widget-header ui-corner-all">
		<?php print("<?php"); ?> echo $_CONTROL->MetaControl->EditMode ? sprintf(QApplication::Translate('<?php echo $objTable->ClassName ?> #%d'), $_CONTROL->MetaControl-><?php echo $objTable->ClassName ?>->__Id) :
		QApplication::Translate('<?php echo $objTable->ClassName ?>'); ?>
	</legend>
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		<?php print("<?php"); ?> $_CONTROL-><?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?>->RenderWithName(); ?>

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		<?php print("<?php"); ?> $_CONTROL-><?php echo $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);  ?>->RenderWithName(); ?>

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		<?php print("<?php"); ?> $_CONTROL-><?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?>->RenderWithName(true); ?>

<?php } ?>
</fieldset>

<fieldset class="clear">
	<?php print("<?php"); ?>  $_CONTROL->btnSave->Render(); ?>
	<?php print("<?php"); ?>  $_CONTROL->btnCancel->Render(); ?>
	<?php print("<?php"); ?>  $_CONTROL->btnDelete->Render(); ?>
</fieldset>
