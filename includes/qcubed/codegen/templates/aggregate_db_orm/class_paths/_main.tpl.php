<?php
	/** @var QTable[] $objTableArray */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL_GEN__,
		'TargetFileName' => '_class_paths.inc.php'
	);
?>
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
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>datatable'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>DataTable.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>listdetailview'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>ListDetailView.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>objectselector'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>ObjectSelector.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>popup'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>Popup.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>listpanel'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>ListPanel.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>searchpanel'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>SearchPanel.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>updatepanel'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>UpdatePanel.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>viewpanel'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>ViewPanel.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>viewwithrelationships'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>ViewWithRelationships.class.php';
		QApplicationBase::$ClassFile['<?php echo strtolower($objTable->ClassName)  ?>toolbar'] = __META_CONTROLS__ . '/<?php echo $objTable->ClassName  ?>Toolbar.class.php';
<?php } ?>

<?php } ?>
?>