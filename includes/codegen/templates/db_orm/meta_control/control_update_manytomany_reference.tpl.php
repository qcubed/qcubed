	<?php if ($objManyToManyReference->IsTypeAssociation):?>
		protected function <?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?>_Update() {
			if ($this-><?php echo $strControlId  ?>) {
				$this-><?php echo $strObjectName  ?>->UnassociateAll<?php echo $objManyToManyReference->ObjectDescriptionPlural  ?>();
				$intIdArray = $this-><?php echo $strControlId  ?>->SelectedValues;
				$this-><?php echo $strObjectName  ?>->Associate<?php echo $objManyToManyReference->ObjectDescription  ?>($intIdArray);
			}
		}
	<?php else:?>
		protected function <?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?>_Update() {
			if ($this-><?php echo $strControlId  ?>) {
				$changedIds = $this->col<?php echo $objManyToManyReference->ObjectDescription  ?>Selected->GetChangedIds();
				$temp = <?php echo $objManyToManyReference->VariableType  ?>::QueryArray(QQ::In(QQN::<?php echo $objManyToManyReference->VariableType  ?>()-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?>, array_keys($changedIds)));
				$changedItems = array();
				foreach($temp as $item) {
					$changedItems[$item-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?>] = $item;
				}

				foreach($changedIds as $id=>$blnSelected) {
					$item = $changedItems[$id];
					if($blnSelected) {
						// Associate this <?php echo $objManyToManyReference->VariableType  ?>

						$this-><?php echo $strObjectName  ?>->Associate<?php echo $objManyToManyReference->ObjectDescription  ?>($item);
					} else {
						// Unassociate this <?php echo $objManyToManyReference->VariableType  ?>

						$results = $this-><?php echo $strObjectName  ?>->Unassociate<?php echo $objManyToManyReference->ObjectDescription  ?>($item);
					}
				}
			}
		}
	<?php endif;?>
