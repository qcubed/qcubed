<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;

	$strPropertyName = QCodeGen::DataListPropertyName($objTable);
	$strPropertyNamePlural = QCodeGen::DataListPropertyNamePlural($objTable);
	$strVarName = 'pnl' . $strPropertyName . 'List';

	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __FORMS__,
		'TargetFileName' => QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) . '_list.tpl.php'
	);

	$codegenerator = $objCodeGen->GetDataListCodeGenerator($objTable);

?>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for the <?= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>_list.php
	// form DRAFT page.  Remember that this is a DRAFT.  It is MEANT to be altered/modified.

	// Be sure to move this file out of this directory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.

	global $gObjectName;
	global $gObjectNamePlural;

	$gObjectName =  QApplication::Translate('<?= $strPropertyName ?>');
	$gObjectNamePlural =  QApplication::Translate('<?= $strPropertyNamePlural ?>');

	$strPageTitle = $gObjectName . ' ' . QApplication::Translate('List');
	require(__CONFIGURATION__ . '/header.inc.php');
?>

<?php print("<?php"); ?> $this->RenderBegin() ?>

<?php print("<?php"); ?> $this->pnlNav->Render(); ?>
<?php print("<?php"); ?> $this-><?= $strVarName ?>->Render(); ?>


<?php print("<?php"); ?> $this->RenderEnd() ?>

<?php print("<?php"); ?> require(__CONFIGURATION__ . '/footer.inc.php'); ?>