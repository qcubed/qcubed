		protected function <?php echo $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference);  ?>_Update() {
			if ($this-><?php echo $strControlId  ?>) {
				$this-><?php echo $strObjectName  ?>->UnassociateAll<?php echo $objManyToManyReference->ObjectDescriptionPlural  ?>();
				$temp = <?php echo $objManyToManyReference->VariableType  ?>::QueryArray(QQ::In(QQN::<?php echo $objManyToManyReference->VariableType  ?>()-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?>, $this-><?php echo $strControlId  ?>->SelectedValues));
				foreach($temp as $item) {
					$this-><?php echo $strObjectName  ?>->Associate<?php echo $objManyToManyReference->ObjectDescription  ?>($item);
				}
			}
		}
