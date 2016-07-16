<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __PANEL__,
		'TargetFileName' => $objTable->ClassName . 'EditPanel.class.php'
	);

$strPropertyName = QCodeGen::DataListPropertyName($objTable);

?>
<?php print("<?php\n"); ?>
require(__PANEL_GEN__ . '/<?= $strPropertyName ?>EditPanelGen.class.php');

/**
 * This is the customizable subclass for the edit panel functionality
 * of the <?= $strPropertyName ?> class. This is where you should create your customizations to the edit
 * panel that edits a <?= $objTable->Name ?> record.
 *
 * This file is intended to be modified. Subsequent code regenerations will NOT modify
 * or overwrite this file.
 *
 * @package <?= QCodeGen::$ApplicationName; ?>

 * @subpackage Panels
 *
 */
class <?= $strPropertyName ?>EditPanel extends <?= $strPropertyName ?>EditPanelGen {
	public function __construct($objParent, $strControlId = null) {
		parent::__construct($objParent, $strControlId);

		// Set AutoRenderChildren in order to use the PreferredRenderMethod attribute in each control
		// to render the controls. If you want more control, you can use the generated template
		// instead in your superclass and modify the template.
		$this->AutoRenderChildren = true;

		//$this->Template = __PANEL_GEN__ . '/<?php echo $strPropertyName  ?>EditPanel.tpl.php';
	}
}
