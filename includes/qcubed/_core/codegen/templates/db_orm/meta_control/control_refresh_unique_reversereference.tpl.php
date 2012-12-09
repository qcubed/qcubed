			if ($this-><?php echo $strControlId  ?>) {
				$this-><?php echo $strControlId  ?>->RemoveAllItems();
				$this-><?php echo $strControlId  ?>->AddItem(QApplication::Translate('- Select One -'), null);
				$<?php echo $objReverseReference->VariableName  ?>Array = <?php echo $objReverseReference->VariableType  ?>::LoadAll();
				if ($<?php echo $objReverseReference->VariableName  ?>Array) foreach ($<?php echo $objReverseReference->VariableName  ?>Array as $<?php echo $objReverseReference->VariableName  ?>) {
					$objListItem = new QListItem($<?php echo $objReverseReference->VariableName  ?>->__toString(), $<?php echo $objReverseReference->VariableName  ?>-><?php echo $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?>);
					if ($<?php echo $objReverseReference->VariableName  ?>-><?php echo $objReverseReference->PropertyName  ?> == $this-><?php echo $strObjectName  ?>-><?php echo $objTable->PrimaryKeyColumnArray[0]->PropertyName  ?>)
						$objListItem->Selected = true;
					$this-><?php echo $strControlId  ?>->AddItem($objListItem);
				}
<?php if ($objReverseReference->NotNull) { ?>
				// Because <?php echo $objReverseReference->VariableType  ?>'s <?php echo $objReverseReference->ObjectPropertyName  ?> is not null, if a value is already selected, it cannot be changed.
				if ($this-><?php echo $strControlId  ?>->SelectedValue)
					$this-><?php echo $strControlId  ?>->Enabled = false;
				else
					$this-><?php echo $strControlId  ?>->Enabled = true;
<?php } ?>
			}
			if ($this-><?php echo $strLabelId  ?>) $this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objReverseReference->ObjectPropertyName  ?>) ? $this-><?php echo $strObjectName  ?>-><?php echo $objReverseReference->ObjectPropertyName  ?>->__toString() : null;