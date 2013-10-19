<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
	 * @property<?php if ($objColumn->Identity || $objColumn->Timestamp) print '-read'; ?> <?php echo $objColumn->VariableType  ?> $<?php echo $objColumn->PropertyName  ?> <?php if ($objColumn->Comment) print $objColumn->Comment; else print 'the value for '.$objColumn->VariableName; ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Timestamp) print '(Read-Only Timestamp)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

<?php } ?>
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
	 * @property<?php if ($objColumn->Identity) print '-read'; ?> <?php echo $objColumn->Reference->VariableType  ?> $<?php echo $objColumn->Reference->PropertyName  ?> the value for the <?php echo $objColumn->Reference->VariableType  ?> object referenced by <?php echo $objColumn->VariableName  ?> <?php if ($objColumn->Identity) print '(Read-Only PK)'; else if ($objColumn->PrimaryKey) print '(PK)'; else if ($objColumn->Unique) print '(Unique)'; else if ($objColumn->NotNull) print '(Not Null)'; ?>

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php if ($objReverseReference->Unique) { ?>
	 * @property <?php echo $objReverseReference->VariableType  ?> $<?php echo $objReverseReference->ObjectPropertyName  ?> the value for the <?php echo $objReverseReference->VariableType  ?> object that uniquely references this <?php echo $objTable->ClassName  ?>

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
	 * @property-read <?php echo $objReference->VariableType  ?> $_<?php echo $objReference->ObjectDescription ?> the value for the private _obj<?php echo $objReference->ObjectDescription ?> (Read-Only) if set due to an expansion on the <?php echo $objReference->Table ?> association table
	 * @property-read <?php echo $objReference->VariableType  ?>[] $_<?php echo $objReference->ObjectDescription ?>Array the value for the private _obj<?php echo $objReference->ObjectDescription ?>Array (Read-Only) if set due to an ExpandAsArray on the <?php echo $objReference->Table ?> association table
<?php } ?><?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?><?php if (!$objReference->Unique) { ?>
	 * @property-read <?php echo $objReference->VariableType  ?> $_<?php echo $objReference->ObjectDescription ?> the value for the private _obj<?php echo $objReference->ObjectDescription ?> (Read-Only) if set due to an expansion on the <?php echo $objReference->Table ?>.<?php echo $objReference->Column ?> reverse relationship
	 * @property-read <?php echo $objReference->VariableType  ?>[] $_<?php echo $objReference->ObjectDescription ?>Array the value for the private _obj<?php echo $objReference->ObjectDescription ?>Array (Read-Only) if set due to an ExpandAsArray on the <?php echo $objReference->Table ?>.<?php echo $objReference->Column ?> reverse relationship
<?php } ?><?php } ?>
	 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)