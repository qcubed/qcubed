	/** @var <?= $strPropertyName ?>Connector */
	public $mct<?= $strPropertyName  ?>;

	// Controls for <?= $strPropertyName  ?>'s Data Fields
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php	if (isset($objColumn->Options['FormGen']) && $objColumn->Options['FormGen'] == QFormGen::None) continue; ?>

	/** @var <?= $objCodeGen->ModelConnectorControlClass($objColumn) ?> */
	protected $<?= $objCodeGen->ModelConnectorVariableName($objColumn);  ?>;
<?php } ?>

<?php
	$blnHasUniqueReverse = false;
	foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
		if ($objReverseReference->Unique) {
			$blnHasUniqueReverse = true;
			break;
		}
	}
	if ($blnHasUniqueReverse) {?>
	// Controls to edit unique reverse references

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
	/** @var <?= $objCodeGen->ModelConnectorControlClass($objReverseReference) ?> */
	protected $<?= $objCodeGen->ModelConnectorVariableName($objReverseReference);  ?>;
<?php } ?>
<?php } ?>
<?php if ($objTable->ManyToManyReferenceArray) {?>
	// Controls to edit many-to-many relationships

<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
	/**  @var <?= $objCodeGen->ModelConnectorControlClass($objManyToManyReference) ?>  */
	protected $<?= $objCodeGen->ModelConnectorVariableName($objManyToManyReference);  ?>;
<?php }