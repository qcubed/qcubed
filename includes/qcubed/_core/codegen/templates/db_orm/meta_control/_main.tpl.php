<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS_GEN__  ?>" TargetFileName="<?php echo $objTable->ClassName  ?>MetaControlGen.class.php"/>
<?php print("<?php\n"); ?>
	/**
	 * This is a MetaControl class, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality
	 * of the <?php echo $objTable->ClassName  ?> class.  This code-generated class
	 * contains all the basic elements to help a QPanel or QForm display an HTML form that can
	 * manipulate a single <?php echo $objTable->ClassName  ?> object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a <?php echo $objTable->ClassName  ?>MetaControl
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent
	 * code re-generation.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage MetaControls
<?php include("property_comments.tpl.php"); ?>

	 */

	class <?php echo $objTable->ClassName  ?>MetaControlGen extends QBaseClass {
		<?php include("variable_declarations.tpl.php"); ?>


		<?php include("constructor.tpl.php"); ?>




		///////////////////////////////////////////////
		// PUBLIC CREATE and REFRESH METHODS
		///////////////////////////////////////////////

<?php include("create_methods.tpl.php"); ?>


<?php include("refresh_methods.tpl.php"); ?>




		///////////////////////////////////////////////
		// PROTECTED UPDATE METHODS for ManyToManyReferences (if any)
		///////////////////////////////////////////////


<?php include("update_methods.tpl.php"); ?>



		///////////////////////////////////////////////
		// PUBLIC <?php echo strtoupper($objTable->ClassName);  ?> OBJECT MANIPULATORS
		///////////////////////////////////////////////

		<?php include("save_object.tpl.php"); ?>


		<?php include("delete_object.tpl.php"); ?>




		///////////////////////////////////////////////
		// PUBLIC GETTERS and SETTERS
		///////////////////////////////////////////////

		<?php include("property_get.tpl.php"); ?>


		<?php include("property_set.tpl.php"); ?>

	}
?>