<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL_CONNECTOR__,
		'TargetFileName' => $objTable->ClassName . 'Connector.class.php'
	);
?>
<?php print("<?php\n"); ?>
	require(__MODEL_CONNECTOR_GEN__ . '/<?= $objTable->ClassName ?>ConnectorGen.class.php');

	/**
	 * This is a ModelConnector customizable subclass, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality of the
	 * <?= $objTable->ClassName ?> class.  This code-generated class extends from
	 * the generated ModelConnector class, which contains all the basic elements to help a QPanel or QForm
	 * display an HTML form that can manipulate a single <?= $objTable->ClassName ?> object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a <?= $objTable->ClassName ?>ModelConnector
	 * class.
	 *
	 * This file is intended to be modified.  Subsequent code regenerations will NOT modify
	 * or overwrite this file.
	 *
	 * @package <?= QCodeGen::$ApplicationName; ?>

	 * @subpackage ModelConnector
	 */
	class <?= $objTable->ClassName ?>Connector extends <?= $objTable->ClassName ?>ConnectorGen {
		<?php include("example_initialization.tpl.php"); ?>
	}