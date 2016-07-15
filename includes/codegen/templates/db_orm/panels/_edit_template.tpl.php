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
		'TargetFileName' => $strPropertyName . 'EditPanel.tpl.php'
	);
?>
<?php print("<?php\n"); ?>
	/**
	 * This is a draft template file for the <?= $strPropertyName ?>EditPanel.
	 * This file will be overwritten every time you do a code generation. If you would like to make manual modifications
	 * to this file, you should move it out of this directory and into another location, and then modify the
     * Template property of the <?= $strPropertyName ?>EditPanel to point to the new location.
	 **/
?>

<?php
foreach ($objTable->ColumnArray as $objColumn) {
    if (!isset($objColumn->Options['FormGen']) || $objColumn->Options['FormGen'] != QFormGen::None) {
		print('<?= _r($this->' . $objCodeGen->ModelConnectorVariableName($objColumn) . '); ?>' . "\n");
	}
}
foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
	if ($objReverseReference->Unique) {
		if (!isset($objReverseReference->Options['FormGen']) || $objReverseReference->Options['FormGen'] != QFormGen::None) {
			print('<?= _r($this->' . $objCodeGen->ModelConnectorVariableName($objReverseReference) . '); ?>' . "\n");
		}
	}
}
foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) {
	if (!isset($objManyToManyReference->Options['FormGen']) || $objManyToManyReference->Options['FormGen'] != QFormGen::None) {
		print('<?= _r($this->' . $objCodeGen->ModelConnectorVariableName($objManyToManyReference) . '); ?>' . "\n");
	}
}

