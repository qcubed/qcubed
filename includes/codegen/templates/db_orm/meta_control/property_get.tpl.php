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
	if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == 'none') continue;
	$strControlId = $objCodeGen->MetaControlVariableName($objColumn);
	$strLabelId = $objCodeGen->MetaControlLabelVariableName($objColumn);
	$strPropertyName = $objColumn->PropertyName;
?><?php include("property_get_case.tpl.php"); ?>

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?><?php if ($objReverseReference->Unique) { ?><?php 
		$strControlId = $objCodeGen->MetaControlVariableName($objReverseReference);
		$strLabelId = $objCodeGen->MetaControlLabelVariableName($objReverseReference);
		$strPropertyName = $objReverseReference->ObjectDescription;
?><?php include("property_get_case.tpl.php"); ?>

<?php } ?><?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?><?php 
	$strControlId = $objCodeGen->MetaControlVariableName($objManyToManyReference);
	$strLabelId = $objCodeGen->MetaControlLabelVariableName($objManyToManyReference);
	$strPropertyName = $objManyToManyReference->ObjectDescription;
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