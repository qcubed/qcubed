<template OverwriteFlag="false" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS__  ?>" TargetFileName="<?php echo $objTable->ClassName  ?>ListDetailView.class.php"/>
<?php print("<?php\n"); ?>
	require(__META_CONTROLS_GEN__ . '/<?php echo $objTable->ClassName  ?>ListDetailViewGen.class.php');

	/**
	 * This file is intended to be modified.  Subsequent code regenerations will NOT modify
	 * or overwrite this file.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage MetaControls
	 */
	class <?php echo $objTable->ClassName ?>ListDetailView extends <?php echo $objTable->ClassName ?>ListDetailViewGen {
	}
?>
