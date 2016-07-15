<?php
	/** @var QSqlTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */

	global $_TEMPLATE_SETTINGS;

	$strPropertyName = QCodeGen::DataListPropertyName($objTable);

	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => false,
		'DirectorySuffix' => '',
		'TargetDirectory' => __PANEL_GEN__,
		'TargetFileName' => $strPropertyName . 'ListPanel.tpl.php'
	);
	$listCodegenerator = $objCodeGen->GetDataListCodeGenerator($objTable);
	$strListVarName = $objCodeGen->DataListVarName($objTable);

?>
<?php print("<?php\n"); ?>
	/**
	 * This is a draft template file for the <?= $strPropertyName ?>ListPanel.
	 * This file will be overwritten every time you do a code generation. If you would like to make manual modifications
	 * to this file, you should move it out of this directory and into another location, and then modify the
     * Template property of the <?= $strPropertyName ?>ListPanel to point to the new location.
	 **/
?>

<?php
	if (!isset($objTable->Options['CreateFilter']) || $objTable->Options['CreateFilter'] !== false) {
		print('<?= _r($this->pnlFilter); ?>' . "\n");
	}
	print('<?= _r($this->' . $strListVarName . '); ?>' . "\n");
	print('<?= _r($this->pnlButtons); ?>' . "\n");

