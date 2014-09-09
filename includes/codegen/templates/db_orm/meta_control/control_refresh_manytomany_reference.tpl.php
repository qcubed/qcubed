		<?php if ($objManyToManyReference->IsTypeAssociation):?>
			if ($this-><?php echo $strControlId  ?>) {
				$strAssociatedArray = $this-><?php echo $strObjectName  ?>->Get<?php echo $objManyToManyReference->ObjectDescription;  ?>Array();
				$this-><?php echo $strControlId  ?>->SelectedValues = array_keys($strAssociatedArray);
			}
			if ($this-><?php echo $strLabelId  ?>) {
				$strAssociatedArray = $this-><?php echo $strObjectName  ?>->Get<?php echo $objManyToManyReference->ObjectDescription;  ?>Array();
				$this-><?php echo $strLabelId  ?>->Text = implode($this->str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue, $strAssociatedArray);
			}
			
		<?php endif;?>