<template OverwriteFlag="false" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __MODEL__  ?>" TargetFileName="<?php echo $objTable->ClassName  ?>.class.php"/>
<?php print("<?php\n"); ?>
	require(__MODEL_GEN__ . '/<?php echo $objTable->ClassName  ?>Gen.class.php');

	/**
	 * The <?php echo $objTable->ClassName  ?> class defined here contains any
	 * customized code for the <?php echo $objTable->ClassName  ?> class in the
	 * Object Relational Model.  It represents the "<?php echo $objTable->Name  ?>" table
	 * in the database, and extends from the code generated abstract <?php echo $objTable->ClassName  ?>Gen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage DataObjects
	 *
	 */
	class <?php echo $objTable->ClassName  ?> extends <?php echo $objTable->ClassName  ?>Gen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $obj<?php echo $objTable->ClassName  ?>->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('<?php echo $objTable->ClassName  ?> Object <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>%s - <?php } ?><?php GO_BACK(3); ?>', <?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?> $this-><?php echo $objColumn->VariableName  ?>, <?php } ?><?php GO_BACK(2); ?>);
		}


		<?php include("example_load_methods.tpl.php"); ?>



		<?php include("example_properties.tpl.php"); ?>



		<?php include("example_initialization.tpl.php"); ?>
	}
?>