<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __FORM_DRAFTS__,
		'TargetFileName' => QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) . '_edit.tpl.php'
	);
?>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for the <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_edit.php
	// form DRAFT page.  Remember that this is a DRAFT.  It is MEANT to be altered/modified.

	// Be sure to move this out of the generated/ subdirectory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.

	$strPageTitle = QApplication::Translate('<?php echo $objTable->ClassName  ?>') . ' - ' . $this->mct<?php echo $objTable->ClassName  ?>->TitleVerb;
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<?php print("<?php"); ?> $this->RenderBegin() ?>

	<h1><?php print("<?php"); ?> _p($this->mct<?php echo $objTable->ClassName  ?>->TitleVerb); ?> <?php print("<?php"); ?> _t('<?php echo $objTable->ClassName  ?>')?></h1>

	<div class="form-controls">
<?php
	foreach ($objTable->ColumnArray as $objColumn) {
		if (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] == 'meta') {
			print('<?php $this->'.$objCodeGen->FormControlVariableNameForColumn($objColumn).'->RenderWithName(); ?>');
		}
	}
	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if ($objReverseReference->Unique) {
			print('<?php $this->'.$objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference).'->RenderWithName(); ?>');
		}
	}
	foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
		print('<?php $this->'.$objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference).'->RenderWithName(true); ?>');
	}
?>
	</div>

	<div class="form-actions">
		<div class="form-save"><?php print("<?php"); ?> $this->btnSave->Render(); ?></div>
		<div class="form-cancel"><?php print("<?php"); ?> $this->btnCancel->Render(); ?></div>
		<div class="form-delete"><?php print("<?php"); ?> $this->btnDelete->Render(); ?></div>
	</div>

	<?php print("<?php"); ?> $this->RenderEnd() ?>

<?php print("<?php"); ?> require(__CONFIGURATION__ .'/footer.inc.php'); ?>