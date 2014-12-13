<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __META_CONTROLS__,
		'TargetFileName' => $objTable->ClassName . 'MetaControl.class.php'
	);
?>
<?php print("<?php\n"); ?>
	require(__META_CONTROLS_GEN__ . '/<?php echo $objTable->ClassName  ?>MetaControlGen.class.php');

	/**
	 * This is a MetaControl customizable subclass, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality of the
	 * <?php echo $objTable->ClassName  ?> class.  This code-generated class extends from
	 * the generated MetaControl class, which contains all the basic elements to help a QPanel or QForm
	 * display an HTML form that can manipulate a single <?php echo $objTable->ClassName  ?> object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a <?php echo $objTable->ClassName  ?>MetaControl
	 * class.
	 *
	 * This file is intended to be modified.  Subsequent code regenerations will NOT modify
	 * or overwrite this file.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage MetaControls
	 */
	class <?php echo $objTable->ClassName  ?>MetaControl extends <?php echo $objTable->ClassName  ?>MetaControlGen {
		<?php include("example_initialization.tpl.php"); ?>
	}
?>