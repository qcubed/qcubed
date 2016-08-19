<?php
/** @var QSqlTable $objTable */
/** @var QDatabaseCodeGen $objCodeGen */
global $_TEMPLATE_SETTINGS;

$strPropertyName = QCodeGen::DataListPropertyName($objTable);
$strPropertyNamePlural = QCodeGen::DataListPropertyNamePlural($objTable);

$_TEMPLATE_SETTINGS = array(
	'OverwriteFlag' => true,
	'DocrootFlag' => true,
	'DirectorySuffix' => '',
	'TargetDirectory' => __FORMS__,
	'TargetFileName' => QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) . '_edit.tpl.php'
);
?>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for the <?= QConvertNotation::UnderscoreFromCamelCase($strPropertyName) ?>_edit.php
	// Feel free to edit this as needed.
	global $gObjectName;
	global $gObjectNamePlural;

	$gObjectName =  QApplication::Translate('<?= $strPropertyName ?>');
	$gObjectNamePlural =  QApplication::Translate('<?= $strPropertyNamePlural ?>');

	$strPageTitle = QApplication::Translate('<?= $strPropertyName ?>');
	require(__CONFIGURATION__ . '/header.inc.php');
?>
<?php print("<?php"); ?> $this->RenderBegin() ?>

<h1><?php print("<?php"); ?> _t('<?= $strPropertyName ?>')?></h1>

<div class="form-controls">
	<?php print("<?="); ?> _r($this->pnl<?= $strPropertyName ?>); ?>
</div>

<div class="form-actions">
	<div class="form-save"><?php print("<?php"); ?> $this->btnSave->Render(); ?></div>
	<div class="form-cancel"><?php print("<?php"); ?> $this->btnCancel->Render(); ?></div>
	<div class="form-delete"><?php print("<?php"); ?> $this->btnDelete->Render(); ?></div>
</div>

<?php print("<?php"); ?> $this->RenderEnd() ?>

<?php print("<?php"); ?> require(__CONFIGURATION__ .'/footer.inc.php');