<?php
	/** @var QSqlTable[] $objTableArray */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL_GEN__,
		'TargetFileName' => '_type_class_paths.inc.php'
	);
?>
<?php print("<?php\n"); ?>
<?php foreach ($objTableArray as $objTable) { ?>
	// ClassPaths for the <?= $objTable->ClassName ?> type class
<?php if (__MODEL__) { ?>
		QApplicationBase::$ClassFile['<?= strtolower($objTable->ClassName) ?>'] = __MODEL__ . '/<?= $objTable->ClassName ?>.class.php';
		QApplicationBase::$ClassFile['qqnode<?= strtolower($objTable->ClassName) ?>'] = __MODEL__ . '/<?= $objTable->ClassName ?>.class.php';<?php } ?>
<?php } ?>