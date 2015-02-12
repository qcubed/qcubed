/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				// General MetaControlVariables
				case '<?= $objTable->ClassName ?>': return $this-><?= $objCodeGen->ModelVariableName($objTable->Name); ?>;
				case 'TitleVerb': return $this->strTitleVerb;
				case 'EditMode': return $this->blnEditMode;

				// Controls that point to <?= $objTable->ClassName ?> fields -- will be created dynamically if not yet created
<?php foreach ($objTable->ColumnArray as $objColumn) { ?><?php
	if ($objColumn->Options && isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue;
	$strControlVarName = $objCodeGen->MetaControlVariableName($objColumn);
	$strLabelVarName = $objCodeGen->MetaControlLabelVariableName($objColumn);
	$strPropertyName = $objColumn->PropertyName;
	$strClassName = $objCodeGen->MetaControlControlClass($objColumn);
?><?php include("property_get_case.tpl.php"); ?>

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?><?php if ($objReverseReference->Unique) { ?><?php
	if (isset($objReverseReference->Options['FormGen']) && $objReverseReference->Options['FormGen'] == QFormGen::None) continue;
	$strControlVarName = $objCodeGen->MetaControlVariableName($objReverseReference);
	$strLabelVarName = $objCodeGen->MetaControlLabelVariableName($objReverseReference);
	$strPropertyName = $objReverseReference->ObjectDescription;
	$strClassName = $objCodeGen->MetaControlControlClass($objReverseReference);
?><?php include("property_get_case.tpl.php"); ?>

<?php } ?><?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?><?php
	if (isset($objManyToManyReference->Options['FormGen']) && $objManyToManyReference->Options['FormGen'] == QFormGen::None) continue;
	$strControlVarName = $objCodeGen->MetaControlVariableName($objManyToManyReference);
	$strLabelVarName = $objCodeGen->MetaControlLabelVariableName($objManyToManyReference);
	$strPropertyName = $objManyToManyReference->ObjectDescription;
	$strClassName = $objCodeGen->MetaControlControlClass($objManyToManyReference);
?><?php include("property_get_case.tpl.php"); ?>

<?php } ?>
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}