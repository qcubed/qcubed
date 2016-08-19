<?php
	/** @var QSqlTable $objTable */
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
	 * of the <?= $objTable->ClassName ?> class.  This class extends
	 * from the generated <?= $objTable->ClassName ?>Gen class, which lists a collection
	 * of <?= $objTable->ClassName ?> objects from the database.
	 *
	 * This file is intended to be modified. In this file, you can override the functions in the
	 * <?= $objTable->ClassName ?>Gen class, and implement new functionality as need.
	 * Subsequent code regenerations will NOT modify or overwrite this file.
	 *
	 * @package <?= QCodeGen::$ApplicationName; ?>

	 * @subpackage ModelConnector
	 *
	 */
	class <?= $objTable->ClassName ?>List extends <?= $objTable->ClassName ?>ListGen {
	}