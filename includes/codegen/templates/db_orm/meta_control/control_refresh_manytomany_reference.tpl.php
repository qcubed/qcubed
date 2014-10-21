		<?php if ($objManyToManyReference->IsTypeAssociation):?>
			if ($this-><?= $strControlId ?>) {
				$strAssociatedArray = $this-><?= $strObjectName ?>->Get<?= $objManyToManyReference->ObjectDescription; ?>Array();
				$this-><?= $strControlId ?>->SelectedValues = array_keys($strAssociatedArray);
			}
			if ($this-><?= $strLabelId ?>) {
				$strAssociatedArray = $this-><?= $strObjectName ?>->Get<?= $objManyToManyReference->ObjectDescription; ?>Array();
				$this-><?= $strLabelId ?>->Text = implode($this->str<?= $objManyToManyReference->ObjectDescription; ?>Glue, $strAssociatedArray);
			}
			
		<?php endif;?>