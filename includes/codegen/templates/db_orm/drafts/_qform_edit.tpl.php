<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __FORM_DRAFTS__,
		'TargetFileName' => QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) . '_edit.php'
	);
?>
<?php print("<?php\n"); ?>
	// Load the QCubed Development Framework
	require('../qcubed.inc.php');

	require(__FORMBASE_CLASSES__ . '/<?php echo $objTable->ClassName  ?>EditFormBase.class.php');

	/**
	 * This is a quick-and-dirty draft QForm object to do Create, Edit, and Delete functionality
	 * of the <?php echo $objTable->ClassName  ?> class.  It uses the code-generated
	 * <?php echo $objTable->ClassName  ?>MetaControl class, which has meta-methods to help with
	 * easily creating/defining controls to modify the fields of a <?php echo $objTable->ClassName  ?> columns.
	 *
	 * Any display customizations and presentation-tier logic can be implemented
	 * here by overriding existing or implementing new methods, properties and variables.
	 * 
	 * NOTE: This file is overwritten on any code regenerations.  If you want to make
	 * permanent changes, it is STRONGLY RECOMMENDED to move both <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_edit.php AND
	 * <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_edit.tpl.php out of this Form Drafts directory.
	 *
	 * @package <?php echo QCodeGen::$ApplicationName;  ?>

	 * @subpackage Drafts
	 */
	class <?php echo $objTable->ClassName  ?>EditForm extends <?php echo $objTable->ClassName  ?>EditFormBase {
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

	// Go ahead and run this form object to render the page and its event handlers, implicitly using
	// <?php echo QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName)  ?>_edit.tpl.php as the included HTML template file
	<?php echo $objTable->ClassName  ?>EditForm::Run('<?php echo $objTable->ClassName  ?>EditForm');
?>