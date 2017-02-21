	/**
	 * Call ModelConnector's methods to create QControls based on <?= $strPropertyName ?>'s data fields
	 **/
	protected function CreateObjects() {
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php	if (isset ($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == 'none' || $objColumn->Options['FormGen'] == 'meta')) continue; ?>
		$this-><?php echo $objCodeGen->ModelConnectorVariableName($objColumn);  ?> = $this->mct<?= $strPropertyName  ?>-><?php echo $objCodeGen->ModelConnectorVariableName($objColumn);  ?>_Create();
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php	if (isset ($objReverseReference->Options['FormGen']) && ($objReverseReference->Options['FormGen'] == 'none' || $objReverseReference->Options['FormGen'] == 'meta')) continue; ?>
<?php if ($objReverseReference->Unique) { ?>
		$this-><?php echo $objCodeGen->ModelConnectorVariableName($objReverseReference);  ?> = $this->mct<?= $strPropertyName ?>-><?php echo $objCodeGen->ModelConnectorVariableName($objReverseReference);  ?>_Create();
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
<?php	if (isset ($objManyToManyReference->Options['FormGen']) && ($objManyToManyReference->Options['FormGen'] == 'none' || $objManyToManyReference->Options['FormGen'] == 'meta')) continue; ?>
		$this-><?php echo $objCodeGen->ModelConnectorVariableName($objManyToManyReference);  ?> = $this->mct<?= $strPropertyName ?>-><?php echo $objCodeGen->ModelConnectorVariableName($objManyToManyReference);  ?>_Create();
<?php } ?>
	}
