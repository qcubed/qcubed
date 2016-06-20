<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;

	$strPropertyName = QCodeGen::DataListPropertyName($objTable);
	$strClassName = QCodeGen::DataListControlClass($objTable);
	$listCodegenerator = $objCodeGen->GetDataListCodeGenerator($objTable);
	$strListVarName = $objCodeGen->DataListVarName($objTable);
	$options = $objTable->Options;

	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __MODEL_CONNECTOR_GEN__,
		'TargetFileName' => $objTable->ClassName . 'ListGen.class.php'
	);
?>
<?php print("<?php\n"); ?>
/**
 * This is the generated connector class for the List functionality
 * of the <?= $objTable->ClassName ?> class.  This code-generated class
 * subclasses a <?= $strPropertyName ?> class and can be used to display
 * a collection of <?= $objTable->ClassName ?> objects.
 *
 * To take advantage of some (or all) of these control objects, you
 * must create an instance of this object in a QForm or QPanel.
 *
 * Any and all changes to this file will be overwritten with any subsequent re-
 * code generation.
 *
 * @package <?= QCodeGen::$ApplicationName; ?>

<?= $listCodegenerator->DataListConnectorGenComments($objCodeGen, $objTable); ?>
 * @subpackage ModelConnector
 *
 */

class <?= $objTable->ClassName ?>ListGen extends <?= $strClassName ?> {
<?= $listCodegenerator->DataListConnectorGen($objCodeGen, $objTable); ?>
}
