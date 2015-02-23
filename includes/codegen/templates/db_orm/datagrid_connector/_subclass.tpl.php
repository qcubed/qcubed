<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL_CONNECTOR__,
		'TargetFileName' => $objTable->ClassName . 'DataGrid.class.php'
	);
?>
<?php print("<?php\n"); ?>
	require(__MODEL_CONNECTOR_GEN__ . '/<?= $objTable->ClassName ?>DataGridGen.class.php');

	/**
	 * This is the DataGrid Connector customizable subclass for the List functionality
	 * of the <?= $objTable->ClassName ?> class.  This code-generated class extends
	 * from the generated DataGrid connector class which contains a QDataGrid class which
	 * can be used by any QForm or QPanel, listing a collection of <?= $objTable->ClassName ?>

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
	class <?= $objTable->ClassName ?>DataGrid extends <?= $objTable->ClassName ?>DataGridGen {
	}
?>