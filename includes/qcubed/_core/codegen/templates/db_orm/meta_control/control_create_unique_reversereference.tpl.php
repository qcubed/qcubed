		/**
		 * Create and setup QListBox <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param QQCondition $objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return QListBox
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null, QQCondition $objCondition = null, $objOptionalClauses = null) {
			$this-><?php echo $strControlId  ?> = new QListBox($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objReverseReference->ObjectPropertyName)  ?>');
			$this-><?php echo $strControlId  ?>->AddItem(QApplication::Translate('- Select One -'), null);

			// Setup and perform the Query
			if (is_null($objCondition)) $objCondition = QQ::All();
			$<?php echo $objReverseReference->VariableName  ?>Cursor = <?php echo $objReverseReference->VariableType  ?>::QueryCursor($objCondition, $objOptionalClauses);

			// Iterate through the Cursor
			while ($<?php echo $objReverseReference->VariableName  ?> = <?php echo $objReverseReference->VariableType  ?>::InstantiateCursor($<?php echo $objReverseReference->VariableName  ?>Cursor)) {
				$objListItem = new QListItem($<?php echo $objReverseReference->VariableName  ?>->__toString(), $<?php echo $objReverseReference->VariableName  ?>-><?php echo $objCodeGen->GetTable($objReverseReference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?>);
				if ($<?php echo $objReverseReference->VariableName  ?>-><?php echo $objReverseReference->PropertyName  ?> == $this-><?php echo $strObjectName  ?>-><?php echo $objTable->PrimaryKeyColumnArray[0]->PropertyName  ?>)
					$objListItem->Selected = true;
				$this-><?php echo $strControlId  ?>->AddItem($objListItem);
			}

<?php if ($objReverseReference->NotNull) { ?>
			// Because <?php echo $objReverseReference->VariableType  ?>'s <?php echo $objReverseReference->ObjectPropertyName  ?> is not null, if a value is already selected, it cannot be changed.
			if ($this-><?php echo $strControlId  ?>->SelectedValue)
				$this-><?php echo $strControlId  ?>->Enabled = false;

<?php } ?>
			// Return the QListBox
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