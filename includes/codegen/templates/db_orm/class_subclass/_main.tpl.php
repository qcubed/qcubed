<?php
	/** @var QSqlTable $objTable */
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
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return '<?= $objTable->ClassName ?> Object ' . $this->PrimaryKey();
		}


		<?php include("example_load_methods.tpl.php"); ?>



		<?php include("example_properties.tpl.php"); ?>



		<?php include("example_initialization.tpl.php"); ?>
	}
