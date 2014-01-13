	 * @property-read <?php echo $objTable->ClassName  ?> $<?php echo $objTable->ClassName  ?> the actual <?php echo $objTable->ClassName  ?> data class being edited
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php 	if ($objColumn->Reference && !$objColumn->Reference->IsType) { ?>
	 * @property <?php echo $objCodeGen->TableArray[strtolower($objColumn->Reference->Table)]->ClassName ?>ObjectSelector $<?php echo $objColumn->PropertyName  ?>Control
	 * @property-read QLabel $<?php echo $objColumn->PropertyName  ?>Label
<?php 	} else {?>
	 * @property <?php echo $objCodeGen->FormControlClassForColumn($objColumn);  ?> $<?php echo $objColumn->PropertyName  ?>Control
	 * @property-read QLabel $<?php echo $objColumn->PropertyName  ?>Label
<?php 	} ?>
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?><?php if ($objReverseReference->Unique) { ?>
	 * @property <?php echo $objReverseReference->VariableType ?>ObjectSelector $<?php echo $objReverseReference->ObjectDescription ?>Control
	 * @property-read QLabel $<?php echo $objReverseReference->ObjectDescription ?>Label
<?php } ?><?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
	 * @property QSelect2ListBox $<?php echo $objManyToManyReference->ObjectDescription ?>Control
	 * @property-read QLabel $<?php echo $objManyToManyReference->ObjectDescription ?>Label
<?php } ?>
	 * @property-read string $TitleVerb a verb indicating whether or not this is being edited or created
	 * @property-read boolean $EditMode a boolean indicating whether or not this is being edited or created
