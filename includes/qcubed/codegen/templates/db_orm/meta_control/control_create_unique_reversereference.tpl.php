		/**
		 * Create and setup QListBox <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param QQCondition $objCondition override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return <?php echo $objReverseReference->VariableType ?>ObjectSelector
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null, QQCondition $objCondition = null, $objOptionalClauses = null) {
			$this-><?php echo $strControlId  ?> = new <?php echo $objReverseReference->VariableType ?>ObjectSelector($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo str_replace(' Object', '', QConvertNotation::WordsFromCamelCase($objReverseReference->ObjectPropertyName))  ?>');

			if ($this-><?php echo $strObjectName ?> && $this-><?php echo $strObjectName ?>-><?php echo $objReverseReference->ObjectPropertyName ?>) {
				$this-><?php echo $strControlId  ?>->SetSelection($this-><?php echo $strObjectName ?>-><?php echo $objReverseReference->ObjectPropertyName ?>);
<?php if ($objReverseReference->NotNull) { ?>
				// Because <?php echo $objReverseReference->VariableType  ?>'s <?php echo $objReverseReference->PropertyName  ?> is not null, if a value is already selected, it cannot be changed.
				$this-><?php echo $strControlId  ?>->Enabled = false;
<?php } ?>
			} else {
				$this-><?php echo $strControlId  ?>->Clear();
			}

			// Return the <?php echo $objReverseReference->VariableType ?>ObjectSelector
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objReverseReference->ObjectPropertyName)  ?>');
			$this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objReverseReference->ObjectPropertyName  ?>) ? $this-><?php echo $strObjectName  ?>-><?php echo $objReverseReference->ObjectPropertyName  ?>->__toString() : null;
			return $this-><?php echo $strLabelId  ?>;
		}
