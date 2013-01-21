			if ($this-><?php echo $strControlId  ?>) {
				if (!$this-><?php echo $strObjectName ?>-><?php echo $objColumn->Reference->PropertyName ?>) {
					$this-><?php echo $strControlId  ?>->Clear();
				} else {
					$this-><?php echo $strControlId  ?>->SetSelection($this-><?php echo $strObjectName ?>-><?php echo $objColumn->Reference->PropertyName ?>);
				}
			}
			if ($this-><?php echo $strLabelId  ?>) $this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>) ? $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>->__toString() : null;
