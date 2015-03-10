<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL__,
		'TargetFileName' => $objTable->ClassName . '.class.php'
	);
?>
<?php print("<?php\n"); ?>
	require(__MODEL_GEN__ . '/<?= $objTable->ClassName ?>Gen.class.php');

	/**
	 * The <?= $objTable->ClassName ?> class defined here contains any
	 * customized code for the <?= $objTable->ClassName ?> class in the
	 * Object Relational Model.  It represents the "<?= $objTable->Name ?>" table
	 * in the database, and extends from the code generated abstract <?= $objTable->ClassName ?>Gen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * @package <?= QCodeGen::$ApplicationName; ?>

	 * @subpackage DataObjects
	 *
	 */
	class <?= $objTable->ClassName ?> extends <?= $objTable->ClassName ?>Gen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $obj<?= $objTable->ClassName ?>->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('<?= $objTable->ClassName ?> Object <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>%s - <?php } ?><?php GO_BACK(3); ?>', <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?> $this-><?= $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>);
		}


		<?php include("example_load_methods.tpl.php"); ?>



		<?php include("example_properties.tpl.php"); ?>



		<?php include("example_initialization.tpl.php"); ?>
	}
?>