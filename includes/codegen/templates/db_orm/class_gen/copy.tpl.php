/**
    * Copying an object creates a copy of the object with all external references nulled and null primary keys in
    * preparation for saving or further processing.
   	*/
   	public function Copy() {
   		$this->__blnRestored = false;
   		// Nullify primary keys
<?php foreach ($objTable->PrimaryKeyColumnArray as $objPkColumn) { ?>
		$this-><?= $objPkColumn->VariableName ?> = null;
<?php } ?>

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php 	if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
<?php 		if ($objColumn->Unique) { ?>
		$this-><?= $objColumn->VariableName ?> = self::<?= $objColumn->PropertyName ?>Default;
		$this-><?= $objColumn->Reference->VariableName ?> = null;
<?php 		} 	// NOTE HERE: Non-unique forward references can persist here. ?>
<?php 	} ?>
<?php } ?>

<?php if ($objTable->ReverseReferenceArray) { ?>

   		// Reverse references
<?php 	foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php 		if ($objReverseReference->Unique) { ?>
		$this-><?= $objReverseReference->ObjectMemberVariable ?> = null;
   		$this->blnDirty<?= $objReverseReference->ObjectPropertyName ?> = false;
<?php 		} else { ?>
		$this->_obj<?= $objReverseReference->ObjectDescription ?> = null;
		$this->_obj<?= $objReverseReference->ObjectDescription ?>Array = null;
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
		$this-><?= $varPrefix . $objReference->ObjectDescription ?> = null;
		$this-><?= $varPrefix . $objReference->ObjectDescription ?>Array = null;
<?php 	} ?>
<?php } ?>
	}

