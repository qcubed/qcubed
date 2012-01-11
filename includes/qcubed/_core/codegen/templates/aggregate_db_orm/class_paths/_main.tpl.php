<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __MODEL_GEN__  ?>" TargetFileName="_class_paths.inc.php"/>
<?php print("<?php\n"); ?>
<?php foreach ($objTableArray as $objTable) { ?>
	// ClassPaths for the <?php echo $objTable->ClassName  ?> class
<?php if (__MODEL__) { ?>
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>'] = __MODEL__ . '/<?php echo $objTable->ClassName  ?>.class.php';
		QApplicationBase::$ClassFile['qqnode<?php echo strtolower($objTable->ClassName)  ?>'] = __MODEL__ . '/<?php echo $objTable->ClassName  ?>.class.php';
		QApplicationBase::$ClassFile['qqreversereferencenode<?php echo strtolower($objTable->ClassName)  ?>'] = __MODEL__ . '/<?php echo $objTable->ClassName  ?>.class.php';
<?php } ?><?php if (__META_CONTROLS__) { ?>
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>metacontrol'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>MetaControl.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>datagrid'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>DataGrid.class.php';
<?php } ?>

<?php } ?>
?>