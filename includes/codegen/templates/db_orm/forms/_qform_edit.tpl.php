<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;

	$strPropertyName = QCodeGen::DataListPropertyName($objTable);

	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,	// TODO: Change to false
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __FORMS__,
		'TargetFileName' => QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) . '_edit.php'
	);
?>
<?php print("<?php\n"); ?>
// Load the QCubed Development Framework
require('../qcubed.inc.php');

require(__PANEL__ . '/<?= $objTable->ClassName ?>EditPanel.class.php');

/**
 * This is a draft QForm object to do Create, Edit, and Delete functionality
 * of the <?= $objTable->ClassName ?> class.  It uses the code-generated
 * <?= $objTable->ClassName ?>Connector class, which has methods to help with
 * easily creating/defining controls to modify the fields of <?= $objTable->ClassName ?> columns.
 *
 * Any display customizations and presentation-tier logic can be implemented
 * here by overriding existing or implementing new methods, properties and variables.
 *
 * @package <?= QCodeGen::$ApplicationName; ?>

 * @subpackage Drafts
 */
class <?= $objTable->ClassName ?>EditForm extends QForm {

<?php include ('edit_protected_member_variables.tpl.php'); ?>

	// Override Form Event Handlers as Needed
	protected function Form_Run() {
		parent::Form_Run();

		// Security check for ALLOW_REMOTE_ADMIN
		// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
		QApplication::CheckRemoteAdmin();
	}

//	protected function Form_Load() {}

<?php include ('edit_form_create.tpl.php'); ?>

<?php include ('edit_create_buttons.tpl.php'); ?>

<?php include ('edit_button_click.tpl.php'); ?>

}

// Go ahead and run this form object to render the page and its event handlers, implicitly using
// <?= QConvertNotation::UnderscoreFromCamelCase($strPropertyName) ?>_edit.tpl.php as the included HTML template file
<?= $strPropertyName ?>EditForm::Run('<?= $strPropertyName ?>EditForm');
