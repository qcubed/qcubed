<?php
    /** @var QTable $objTable */
    /** @var QTable[] $objTableArray */
    /** @var QDatabaseCodeGen $objCodeGen */
    global $_TEMPLATE_SETTINGS;
    $_TEMPLATE_SETTINGS = array(
        'OverwriteFlag' => false,
        'DocrootFlag' => false,
        'DirectorySuffix' => '',
        'TargetDirectory' => __META_CONTROLS__,
        'TargetFileName' => $objTable->ClassName.'ViewPanel.class.php'
    );
?>
<?php print("<?php\n"); ?>
	require(__META_CONTROLS_GEN__ . '/<?php echo $objTable->ClassName  ?>ViewPanelGen.class.php');

	/**
	 * This file is intended to be modified.  Subsequent code regenerations will NOT modify
	 * or overwrite this file.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage MetaControls
	 */
	class <?php echo $objTable->ClassName ?>ViewPanel extends <?php echo $objTable->ClassName ?>ViewPanelGen {
	}
?>
