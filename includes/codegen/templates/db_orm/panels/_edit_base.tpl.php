
<?php
/** @var QSqlTable $objTable */
/** @var QDatabaseCodeGen $objCodeGen */
global $_TEMPLATE_SETTINGS;
$_TEMPLATE_SETTINGS = array(
	'OverwriteFlag' => true,
	'DocrootFlag' => false,
	'DirectorySuffix' => '',
	'TargetDirectory' => __PANEL_GEN__,
	'TargetFileName' => $objTable->ClassName . 'EditPanelGen.class.php'
);

$strPropertyName = QCodeGen::DataListPropertyName($objTable);
?>
<?php print("<?php\n"); ?>

require (__MODEL_CONNECTOR__ . '/<?= $strPropertyName ?>Connector.class.php');

/**
 * This is the base class for the the <?php echo $objTable->ClassName  ?>EditPanel class.  It uses the code-generated
 * <?php echo $objTable->ClassName  ?>ModelConnector class, which has methods to help with
 * easily creating/defining controls to modify the fields of a <?php echo $objTable->ClassName  ?> columns.
 *
 * Implement your customizations in the <?php echo $objTable->ClassName  ?>EditPanel.class.php file, not here.
 * This file is overwritten every time you do a code generation, so any changes you make here will be lost.
 *
 * @package <?php echo QCodeGen::$ApplicationName;  ?>

 */
class <?= $strPropertyName ?>EditPanelGen extends QPanel {
<?php include("edit_protected_member_variables.tpl.php"); ?>

<?php include("edit_constructor.tpl.php"); ?>

<?php include("edit_create_objects.tpl.php"); ?>

<?php include("edit_load.tpl.php"); ?>

<?php include("edit_refresh.tpl.php"); ?>

<?php include("edit_save.tpl.php"); ?>

<?php include("edit_delete.tpl.php"); ?>

<?php include("edit_validate_unique.tpl.php"); ?>
}
