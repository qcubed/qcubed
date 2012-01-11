<template OverwriteFlag="false" DocrootFlag="true" DirectorySuffix="" TargetDirectory="<?php echo __FORM_DRAFTS__  ?>" TargetFileName="<?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_list.php"/>
<?php print("<?php\n"); ?>
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');

	require(__FORMBASE_CLASSES__ . '/<?php echo $objTable->ClassName  ?>ListFormBase.class.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do the List All functionality
	 * of the <?php echo $objTable->ClassName  ?> class.  It uses the code-generated
	 * <?php echo $objTable->ClassName  ?>DataGrid control which has meta-methods to help with
	 * easily creating/defining <?php echo $objTable->ClassName  ?> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_list.php AND
	 * <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_list.tpl.php out of this Form Drafts directory.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage Drafts
	 */
	class <?php echo $objTable->ClassName  ?>ListForm extends <?php echo $objTable->ClassName  ?>ListFormBase {
		// Override Form Event Handlers as Needed
		protected function Form_Run() {
			parent::Form_Run();

			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();		    
		}

//		protected function Form_Load() {}

//		protected function Form_Create() {}
	}

	// Go ahead and run this form object to generate the page and event handlers, implicitly using
	// <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_list.tpl.php as the included HTML template file
	<?php echo $objTable->ClassName  ?>ListForm::Run('<?php echo $objTable->ClassName  ?>ListForm');
?>