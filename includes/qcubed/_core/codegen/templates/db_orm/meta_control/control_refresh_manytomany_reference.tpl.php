			if ($this-><?php echo $strControlId  ?>) {
				$this-><?php echo $strControlId  ?>->RemoveAllItems();
				$objAssociatedArray = $this-><?php echo $strObjectName  ?>->Get<?php echo $objManyToManyReference->ObjectDescription;  ?>Array();
				$<?php echo $objManyToManyReference->VariableName  ?>Array = <?php echo $objManyToManyReference->VariableType  ?>::LoadAll();
				if ($<?php echo $objManyToManyReference->VariableName  ?>Array) foreach ($<?php echo $objManyToManyReference->VariableName  ?>Array as $<?php echo $objManyToManyReference->VariableName  ?>) {
					$objListItem = new QListItem($<?php echo $objManyToManyReference->VariableName  ?>->__toString(), $<?php echo $objManyToManyReference->VariableName  ?>-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?>);
					foreach ($objAssociatedArray as $objAssociated) {
						if ($objAssociated-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?> == $<?php echo $objManyToManyReference->VariableName  ?>-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?>)
							$objListItem->Selected = true;
					}
					$this-><?php echo $strControlId  ?>->AddItem($objListItem);
				}
			}
			if ($this-><?php echo $strLabelId  ?>) {
				$objAssociatedArray = $this-><?php echo $strObjectName  ?>->Get<?php echo $objManyToManyReference->ObjectDescription;  ?>Array();
				$strItems = array();
				foreach ($objAssociatedArray as $objAssociated)
					$strItems[] = $objAssociated->__toString();
				$this-><?php echo $strLabelId  ?>->Text = implode($this->str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue, $strItems);
			}