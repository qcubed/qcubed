<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
	 * @property<?php if ($objColumn->Identity || $objColumn->Timestamp) print '-read'; ?> <?= $objColumn->VariableType ?> $<?= $objColumn->PropertyName ?> <?php if ($objColumn->Comment) print $objColumn->Comment; else print 'the value for '.$objColumn->VariableName; ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Timestamp) print '(Read-Only Timestamp)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

<?php } ?>
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
	 * @property<?php if ($objColumn->Identity) print '-read'; ?> <?= $objColumn->Reference->VariableType ?> $<?= $objColumn->Reference->PropertyName ?> the value for the <?= $objColumn->Reference->VariableType ?> object referenced by <?= $objColumn->VariableName ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
	 * @property <?= $objReverseReference->VariableType ?> $<?= $objReverseReference->ObjectPropertyName ?> the value for the <?= $objReverseReference->VariableType ?> object that uniquely references this <?= $objTable->ClassName ?>

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
<?php 
	$objAssociatedTable = $objCodeGen->GetTable($objReference->AssociatedTable);
	$varPrefix = (is_a($objAssociatedTable, 'QTypeTable') ? '_int' : '_obj');
?>
	 * @property-read <?= $objReference->VariableType ?> $_<?= $objReference->ObjectDescription ?> the value for the protected <?= $varPrefix . $objReference->ObjectDescription ?> (Read-Only) if set due to an expansion on the <?= $objReference->Table ?> association table
	 * @property-read <?= $objReference->VariableType ?>[] $_<?= $objReference->ObjectDescription ?>Array the value for the protected <?= $varPrefix . $objReference->ObjectDescription ?>Array (Read-Only) if set due to an ExpandAsArray on the <?= $objReference->Table ?> association table
<?php } ?><?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
	* @property-read <?= $objReference->VariableType ?> $_<?= $objReference->ObjectDescription ?> the value for the protected _obj<?= $objReference->ObjectDescription ?> (Read-Only) if set due to an expansion on the <?= $objReference->Table ?>.<?= $objReference->Column ?> reverse relationship
	* @property-read <?= $objReference->VariableType ?> $<?= $objReference->ObjectDescription ?> the value for the protected _obj<?= $objReference->ObjectDescription ?> (Read-Only) if set due to an expansion on the <?= $objReference->Table ?>.<?= $objReference->Column ?> reverse relationship
	* @property-read <?= $objReference->VariableType ?>[] $_<?= $objReference->ObjectDescription ?>Array the value for the protected _obj<?= $objReference->ObjectDescription ?>Array (Read-Only) if set due to an ExpandAsArray on the <?= $objReference->Table ?>.<?= $objReference->Column ?> reverse relationship
	* @property-read <?= $objReference->VariableType ?>[] $<?= $objReference->ObjectDescription ?>Array the value for the protected _obj<?= $objReference->ObjectDescription ?>Array (Read-Only) if set due to an ExpandAsArray on the <?= $objReference->Table ?>.<?= $objReference->Column ?> reverse relationship
<?php } ?><?php } ?>
	 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)