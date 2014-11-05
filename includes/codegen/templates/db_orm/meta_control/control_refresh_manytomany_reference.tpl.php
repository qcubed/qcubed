		<?php if ($objManyToManyReference->IsTypeAssociation):?>
			if ($this-><?= $strControlId ?>) {
				$strAssociatedArray = $this-><?= $strObjectName ?>->Get<?= $objManyToManyReference->ObjectDescription; ?>Array();
				$this-><?= $strControlId ?>->SelectedValues = array_keys($strAssociatedArray);
			}
			if ($this-><?= $strLabelId ?>) {
				$strAssociatedArray = $this-><?= $strObjectName ?>->Get<?= $objManyToManyReference->ObjectDescription; ?>Array();
				$this-><?= $strLabelId ?>->Text = implode($this->str<?= $objManyToManyReference->ObjectDescription; ?>Glue, $strAssociatedArray);
			}
			
		<?php else:?>
			if ($this-><?= $strControlId ?>) {
				$this-><?= $strControlId ?>->RemoveAllItems();
				$objAssociatedArray = $this-><?= $strObjectName ?>->Get<?= $objManyToManyReference->ObjectDescription; ?>Array();
				$<?= $objManyToManyReference->VariableName ?>Array = <?= $objManyToManyReference->VariableType ?>::LoadAll();
				if ($<?= $objManyToManyReference->VariableName ?>Array) foreach ($<?= $objManyToManyReference->VariableName ?>Array as $<?= $objManyToManyReference->VariableName ?>) {
					$objListItem = new QListItem($<?= $objManyToManyReference->VariableName ?>->__toString(), $<?= $objManyToManyReference->VariableName ?>-><?= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName ?>);
					foreach ($objAssociatedArray as $objAssociated) {
						if ($objAssociated-><?= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName ?> == $<?= $objManyToManyReference->VariableName ?>-><?= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName ?>)
							$objListItem->Selected = true;
					}
					$this-><?= $strControlId ?>->AddItem($objListItem);
				}
			}
			if ($this-><?= $strLabelId ?>) {
				$objAssociatedArray = $this-><?= $strObjectName ?>->Get<?= $objManyToManyReference->ObjectDescription; ?>Array();
				$strItems = array();
				foreach ($objAssociatedArray as $objAssociated)
					$strItems[] = $objAssociated->__toString();
				$this-><?= $strLabelId ?>->Text = implode($this->str<?= $objManyToManyReference->ObjectDescription; ?>Glue, $strItems);
			}
		<?php endif;?>