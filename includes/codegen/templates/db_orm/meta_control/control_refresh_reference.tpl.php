<?php
$strControlType = $objCodeGen->FormControlClassForColumn($objColumn);
$objReflection = new ReflectionClass ($strControlType);
$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaRefresh');

if ($blnHasMethod) {
	echo $strControlType::Codegen_MetaRefresh($objCodeGen, $objTable, $objColumn);
} else {

?>


if ($this-><?php echo $strControlId  ?>) {
					$this-><?php echo $strControlId  ?>->RemoveAllItems();
<?php if ($objColumn->NotNull) { ?>
				if (!$this->blnEditMode)
					$this-><?php echo $strControlId  ?>->AddItem(QApplication::Translate('- Select One -'), null);
<?php } ?><?php if (!$objColumn->NotNull) { ?>
				$this-><?php echo $strControlId  ?>->AddItem(QApplication::Translate('- Select One -'), null);
<?php } ?>
				$<?php echo $objColumn->Reference->VariableName  ?>Array = <?php echo $objColumn->Reference->VariableType  ?>::LoadAll();
				if ($<?php echo $objColumn->Reference->VariableName  ?>Array) foreach ($<?php echo $objColumn->Reference->VariableName  ?>Array as $<?php echo $objColumn->Reference->VariableName  ?>) {
					$objListItem = new QListItem($<?php echo $objColumn->Reference->VariableName  ?>->__toString(), $<?php echo $objColumn->Reference->VariableName  ?>-><?php echo $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?>);
					if (($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>) && ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>-><?php echo $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?> == $<?php echo $objColumn->Reference->VariableName  ?>-><?php echo $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?>))
						$objListItem->Selected = true;
					$this-><?php echo $strControlId  ?>->AddItem($objListItem);
				}
			} 
			if ($this-><?php echo $strLabelId  ?>) $this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>) ? $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>->__toString() : null;
<?php }?>