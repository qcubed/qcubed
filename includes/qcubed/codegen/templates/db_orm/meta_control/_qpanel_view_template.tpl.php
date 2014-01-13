<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS__  ?>" TargetFileName="<?php echo $objTable->ClassName ?>ViewPanel.tpl.php"/>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>ViewPanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard subdirectory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.
?>
<div class="ui-widget-content">
<fieldset class="left">
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
		<?php print("<?php"); ?> $_CONTROL-><?php echo $objCodeGen->FormLabelVariableNameForColumn($objColumn);  ?>->RenderWithName(); ?>

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
		<?php print("<?php"); ?> $_CONTROL-><?php echo $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference);  ?>->RenderWithName(); ?>

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
		<?php print("<?php"); ?> $_CONTROL-><?php echo $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference);  ?>->RenderWithName(true); ?>

<?php } ?>
</fieldset>
<fieldset class="right">
	<!-- Move some of the controls here to render them on the right panel -->
</fieldset>
<fieldset class="clear">
	<!-- Move wider controls here to render them across the two panels -->
</fieldset>
</div>
