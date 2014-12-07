<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __PANEL_DRAFTS__,
		'TargetFileName' => $objTable->ClassName . 'EditPanel.tpl.php'
	);
?>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for <?= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>EditPanel.
	// Remember that this is a DRAFT.  It is MEANT to be altered/modified.
	// Be sure to move this out of the drafts/dashboard subdirectory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.
?>
	<div class="form-controls">
<?php
foreach ($objTable->ColumnArray as $objColumn) {
    if (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::None) {
		print('<?php $_CONTROL->'.$objCodeGen->MetaControlVariableName($objColumn).'->RenderWithName(); ?>' . "\n");
	}
}
foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
	if ($objReverseReference->Unique) {
		if (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] != QFormGen::None) {
			print('<?php $_CONTROL->'.$objCodeGen->MetaControlVariableName($objReverseReference).'->RenderWithName(); ?>' . "\n");
		}
	}
}
foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	if (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] != QFormGen::None) {
		print('<?php $_CONTROL->'.$objCodeGen->MetaControlVariableName($objManyToManyReference).'->RenderWithName(true); ?>' . "\n");
	}
}
?>
	</div>

	<div class="form-actions">
		<div class="form-save"><?php print("<?php"); ?> $_CONTROL->btnSave->Render(); ?></div>
		<div class="form-cancel"><?php print("<?php"); ?> $_CONTROL->btnCancel->Render(); ?></div>
		<div class="form-delete"><?php print("<?php"); ?> $_CONTROL->btnDelete->Render(); ?></div>
	</div>