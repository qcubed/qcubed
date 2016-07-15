<?php
	/**
	 * @var QSqlTable $objTable
	 * @var QCodeGenBase $objCodeGen
	 */
?>
/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Parent':
						$this->objParentObject = $mixValue;
						break;

					// Controls that point to <?= $objTable->ClassName ?> fields
<?php foreach ($objTable->ColumnArray as $objColumn) {
	if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;
	$strControlVarName = $objCodeGen->ModelConnectorVariableName($objColumn);
	$strPropertyName = $objColumn->PropertyName;

	$objControlCodeGenerator = $objCodeGen->GetControlCodeGenerator($objColumn);
	$strClassName = $objControlCodeGenerator->GetControlClass();
	$strLabelVarName = $objCodeGen->ModelConnectorLabelVariableName($objColumn);
	include("property_set_case.tpl.php");
	print($objControlCodeGenerator->ConnectorSet($objCodeGen, $objTable, $objColumn));
} ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?><?php if ($objReverseReference->Unique) { ?><?php
	if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;
	$strControlVarName = $objCodeGen->ModelConnectorVariableName($objReverseReference);
	$strPropertyName = $objReverseReference->ObjectDescription;
	$strClassName = $objCodeGen->GetControlCodeGenerator($objReverseReference)->GetControlClass();
	$strLabelVarName = $objCodeGen->ModelConnectorLabelVariableName($objReverseReference);
?><?php include("property_set_case.tpl.php"); ?>

<?php } ?><?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?><?php
	if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;
	$strControlVarName = $objCodeGen->ModelConnectorVariableName($objManyToManyReference);
	$strPropertyName = $objManyToManyReference->ObjectDescription;
	$strClassName = $objCodeGen->GetControlCodeGenerator($objManyToManyReference)->GetControlClass();
	$strLabelVarName = $objCodeGen->ModelConnectorLabelVariableName($objManyToManyReference);
?><?php include("property_set_case.tpl.php"); ?>

<?php } ?>
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}