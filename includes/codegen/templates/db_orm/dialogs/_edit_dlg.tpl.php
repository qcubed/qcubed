<?php
/** @var QSqlTable $objTable */
/** @var QDatabaseCodeGen $objCodeGen */
global $_TEMPLATE_SETTINGS;

$strPropertyName = QCodeGen::DataListPropertyName($objTable);

$_TEMPLATE_SETTINGS = array(
	'OverwriteFlag' => true,
	'DocrootFlag' => false,
	'DirectorySuffix' => '',
	'TargetDirectory' => __DIALOG_GEN__,
	'TargetFileName' => $strPropertyName . 'EditDlgGen.class.php'
);


?>
<?php print("<?php\n"); ?>

include (__PANEL__ . '/<?= $strPropertyName ?>EditPanel.class.php');

/**
 * This is the <?= $strPropertyName ?>EditDlgGen class.  It uses the code-generated
 * <?= $strPropertyName ?>EditPanel class, which has all the controls for editing
 * a record in the <?= $objTable->Name ?> table.
 *
 *
 * @package <?php echo QCodeGen::$ApplicationName;  ?>

 * @subpackage Dialogs
 */
class <?= $strPropertyName ?>EditDlgGen extends QDialog {

<?php include ('dlg_protected_member_variables.tpl.php'); ?>

<?php include ('dlg_constructor.tpl.php'); ?>

<?php include ('dlg_create_buttons.tpl.php'); ?>

<?php include ('dlg_load.tpl.php'); ?>

<?php include ('dlg_button_click.tpl.php'); ?>

<?php include ('dlg_save.tpl.php'); ?>

}
