		/**
		 * Create and setup <?php echo $strClassName ?>ObjectSelector <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param QQCondition $objCondition override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return <?php echo $objColumn->Reference->VariableType ?>ObjectSelector
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null, QQCondition $objCondition = null, $objOptionalClauses = null) {
			$this-><?php echo $strControlId  ?> = new <?php echo $objColumn->Reference->VariableType ?>ObjectSelector($this->objParentObject, true, <?php echo ($objColumn->Unique) ? 'false' : 'true';?>);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo str_replace(' Object', '', QConvertNotation::WordsFromCamelCase($objColumn->Reference->PropertyName))  ?>');
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->Required = true;
<?php } ?>

			if ($this-><?php echo $strObjectName ?> && $this-><?php echo $strObjectName ?>-><?php echo $objColumn->Reference->PropertyName ?>) {
				$this-><?php echo $strControlId  ?>->SetSelection($this-><?php echo $strObjectName ?>-><?php echo $objColumn->Reference->PropertyName ?>);
			} else {
				$this-><?php echo $strControlId  ?>->Clear();
			}
			// Return the <?php echo $strClassName ?>ObjectSelector
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo str_replace(' Object', '', QConvertNotation::WordsFromCamelCase($objColumn->Reference->PropertyName))  ?>');
			$this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>) ? $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>->__toString() : null;
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strLabelId  ?>->Required = true;
<?php } ?>
			return $this-><?php echo $strLabelId  ?>;
		}
