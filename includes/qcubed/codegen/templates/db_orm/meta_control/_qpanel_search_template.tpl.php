<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS__  ?>" TargetFileName="<?php echo $objTable->ClassName ?>SearchPanel.tpl.php"/>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>SearchPanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard subdirectory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.
	/** @var <?php echo $objTable->ClassName ?>SearchPanel $_CONTROL */
	$strRenderMethod = 'RenderWithName';
	$strBtnRenderMethod = 'RenderWithName';
?>
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (!$objColumn->Reference || $objColumn->Reference->IsType) { ?>
	<div class="search-control"><?php print("<?php"); ?> $_CONTROL-><?php echo $objCodeGen->FormControlVariableNameForColumn($objColumn);  ?>->$strRenderMethod(); ?></div>
<?php } ?>
<?php } ?>
<div class="search-button"><?php print("<?php"); ?> $_CONTROL->btnSearch->$strBtnRenderMethod(); ?></div>
<div class="search-reset"><?php print("<?php"); ?> $_CONTROL->btnReset->$strBtnRenderMethod(); ?></div>
