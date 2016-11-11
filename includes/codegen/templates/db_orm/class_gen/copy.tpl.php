/**
    * Copying an object creates a copy of the object with all external references nulled and null primary keys in
    * preparation for saving or further processing.
   	*/
   	public function Copy() {
		$objCopy = clone $this;
		$objCopy->__blnRestored = false;

		// Make sure all valid data is dirty so it will be saved
		foreach ($this->__blnValid as $key=>$val) {
			$objCopy->__blnDirty[$key] = $val;
		}

   		// Nullify primary keys so they will be saved as a different object
<?php foreach ($objTable->PrimaryKeyColumnArray as $objPkColumn) { ?>
		$objCopy-><?= $objPkColumn->VariableName ?> = null;
<?php } ?>

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php 	if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
<?php 		if ($objColumn->Unique) { ?>
		$objCopy-><?= $objColumn->VariableName ?> = self::<?= $objColumn->PropertyName ?>Default;
		$objCopy-><?= $objColumn->Reference->VariableName ?> = null;
<?php 		} 	// NOTE HERE: Non-unique forward references can persist here. ?>
<?php 	} ?>
<?php } ?>

<?php if ($objTable->ReverseReferenceArray) { ?>

   		// Reverse references
<?php 	foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php 		if ($objReverseReference->Unique) { ?>
		$objCopy-><?= $objReverseReference->ObjectMemberVariable ?> = null;
		$objCopy->blnDirty<?= $objReverseReference->ObjectPropertyName ?> = false;
<?php 		} else { ?>
		$objCopy->_obj<?= $objReverseReference->ObjectDescription ?> = null;
		$objCopy->_obj<?= $objReverseReference->ObjectDescription ?>Array = null;
<?php 		} ?>
<?php 	} ?>
<?php } ?>

<?php if ($objTable->ManyToManyReferenceArray) { ?>
   		// Many-to-many references
<?php 	foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
<?php
		$objAssociatedTable = $objCodeGen->GetTable($objReference->AssociatedTable);
		$varPrefix = (is_a($objAssociatedTable, 'QTypeTable') ? '_int' : '_obj');
?>
		$objCopy-><?= $varPrefix . $objReference->ObjectDescription ?> = null;
		$objCopy-><?= $varPrefix . $objReference->ObjectDescription ?>Array = null;
<?php 	} ?>
<?php } ?>
		return $objCopy;
	}

