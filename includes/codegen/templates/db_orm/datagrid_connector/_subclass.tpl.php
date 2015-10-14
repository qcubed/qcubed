<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL_CONNECTOR__,
		'TargetFileName' => $objTable->ClassName . 'List.class.php'
	);
?>
<?php print("<?php\n"); ?>
	require(__MODEL_CONNECTOR_GEN__ . '/<?= $objTable->ClassName ?>ListGen.class.php');

	/**
	 * This is the connector class for the List functionality
	 * of the <?= $objTable->ClassName ?> class.  This code-generated class extends
	 * from the generated <?= $objTable->ClassName ?>Gen  class, listing a collection
	 * of <?= $objTable->ClassName ?>
	 * objects.  It includes functionality to perform pagination and sorting on columns.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create an instance of this DataGrid in a QForm or QPanel.
	 *
	 * This file is intended to be modified.  Subsequent code regenerations will NOT modify
	 * or overwrite this file.
	 *
	 * @package <?= QCodeGen::$ApplicationName; ?>

	 * @subpackage ModelConnector
	 *
	 */
	class <?= $objTable->ClassName ?>List extends <?= $objTable->ClassName ?>ListGen {
	}
?>