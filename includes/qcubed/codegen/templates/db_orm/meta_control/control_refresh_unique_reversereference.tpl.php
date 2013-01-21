			if ($this-><?php echo $strControlId  ?>) {
				if ($this-><?php echo $strObjectName ?> && $this-><?php echo $strObjectName ?>-><?php echo $objReverseReference->ObjectPropertyName ?>) {
					$this-><?php echo $strControlId  ?>->SetSelection($this-><?php echo $strObjectName ?>-><?php echo $objReverseReference->ObjectPropertyName ?>);
<?php if ($objReverseReference->NotNull) { ?>
					// Because <?php echo $objReverseReference->VariableType  ?>'s <?php echo $objReverseReference->PropertyName  ?> is not null, if a value is already selected, it cannot be changed.
					$this-><?php echo $strControlId  ?>->Enabled = false;
<?php } ?>
				} else {
					$this-><?php echo $strControlId  ?>->Clear();
				}

			}
			if ($this-><?php echo $strLabelId  ?>) $this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objReverseReference->ObjectPropertyName  ?>) ? $this-><?php echo $strObjectName  ?>-><?php echo $objReverseReference->ObjectPropertyName  ?>->__toString() : null;