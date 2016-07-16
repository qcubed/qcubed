<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;

	$strPropertyName = QCodeGen::DataListPropertyName($objTable);

	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => false,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __PANEL__,
		'TargetFileName' => $strPropertyName . 'ListPanel.class.php'
	);

	$listCodegenerator = $objCodeGen->GetDataListCodeGenerator($objTable);

?>
<?php print("<?php\n"); ?>
require(__PANEL_GEN__ . '/<?= $strPropertyName ?>ListPanelGen.class.php');
require(__MODEL_CONNECTOR__ . '/<?= $strPropertyName ?>List.class.php');

/**
 * This is the customizable subclass for the list panel functionality
 * of the <?= $strPropertyName ?> class.
 *
 * This file is intended to be modified. Subsequent code regenerations will NOT modify
 * or overwrite this file.
 *
 * @package <?= QCodeGen::$ApplicationName; ?>

 * @subpackage Panels
 *
 */
class <?= $strPropertyName ?>ListPanel extends <?= $strPropertyName ?>ListPanelGen {
	public function __construct($objParent, $strControlId = null) {
		parent::__construct($objParent, $strControlId);

		/**
		 * Default is just to render everything generic. Comment out the AutoRenderChildren line, and uncomment the
		 * template line to use a template for greater customization of how the panel draws its contents.
		 **/
		$this->AutoRenderChildren = true;
		//$this->Template =  __PANEL_GEN__ . '/<?= $strPropertyName ?>ListPanel.tpl.php';
	}

<?= $listCodegenerator->DataListSubclassOverrides($objCodeGen, $objTable); ?>



}
