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
	if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue;
	$strControlId = $objCodeGen->FormControlVariableNameForColumn($objColumn);
	$strPropertyName = $objColumn->PropertyName . 'Control';
	$strClassName = $objCodeGen->FormControlTypeForColumn($objColumn);
	include("property_set_case.tpl.php");
} ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?><?php if ($objReverseReference->Unique) { ?><?php 
		$strControlId = $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference);
		$strPropertyName = $objReverseReference->ObjectDescription . 'Control';
		$strClassName = 'QListBox';
?><?php include("property_set_case.tpl.php"); ?>

<?php } ?><?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?><?php 
	$strControlId = $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);
	$strPropertyName = $objManyToManyReference->ObjectDescription . 'Control';
	$strClassName = 'QListBox';
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