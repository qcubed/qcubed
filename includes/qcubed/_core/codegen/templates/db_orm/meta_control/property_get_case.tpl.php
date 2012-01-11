				case '<?php echo $strPropertyName  ?>Control':
					if (!$this-><?php echo $strControlId  ?>) return $this-><?php echo $strControlId  ?>_Create();
					return $this-><?php echo $strControlId  ?>;
				case '<?php echo $strPropertyName  ?>Label':
					if (!$this-><?php echo $strLabelId  ?>) return $this-><?php echo $strLabelId  ?>_Create();
					return $this-><?php echo $strLabelId  ?>;