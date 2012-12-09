<template OverwriteFlag="true" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<?php echo __PANEL_DRAFTS__  ?>" TargetFileName="<?php echo $objTable->ClassName ?>EditPanel.tpl.php"/>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>EditPanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard subdirectory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.
?>
	<div id="formControls">
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
	</div>

	<div id="formActions">
		<div id="save"><?php print("<?php"); ?> $_CONTROL->btnSave->Render(); ?></div>
		<div id="cancel"><?php print("<?php"); ?> $_CONTROL->btnCancel->Render(); ?></div>
		<div id="delete"><?php print("<?php"); ?> $_CONTROL->btnDelete->Render(); ?></div>
	</div>
