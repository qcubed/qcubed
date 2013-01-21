		/**
		 * Create and setup QSelect2ListBox <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param QQCondition $objCondition override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return QSelect2ListBox
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null, QQCondition $objCondition = null, $objOptionalClauses = null) {
			$this-><?php echo $strControlId  ?> = new QSelect2ListBox($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->SelectionMode = QSelectionMode::Multiple;
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural)  ?>');


			$objAssociatedArray = $this-><?php echo $strObjectName  ?>->Get<?php echo $objManyToManyReference->ObjectDescription;  ?>Array();
			$selectedIds = array();
			foreach ($objAssociatedArray as $obj) {
				$selectedIds[$obj-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName ?>] = true;
			}

			// Setup and perform the Query
			if (is_null($objCondition)) $objCondition = QQ::All();
			$obj<?php echo $objManyToManyReference->ObjectDescription  ?>Cursor = <?php echo $objManyToManyReference->VariableType  ?>::QueryCursor($objCondition, $objOptionalClauses);

			// Iterate through the Cursor
			while ($obj<?php echo $objManyToManyReference->ObjectDescription  ?> = <?php echo $objManyToManyReference->VariableType  ?>::InstantiateCursor($obj<?php echo $objManyToManyReference->ObjectDescription  ?>Cursor)) {
				$objListItem = new QListItem($obj<?php echo $objManyToManyReference->ObjectDescription  ?>->__toString(), $obj<?php echo $objManyToManyReference->ObjectDescription  ?>-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName ?>);
				if (array_key_exists($obj<?php echo $objManyToManyReference->ObjectDescription  ?>-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName ?>, $selectedIds))
					$objListItem->Selected = true;
				$this-><?php echo $strControlId  ?>->AddItem($objListItem);
			}

			// Return the QSelect2ListBox
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param string $strGlue glue to display in-between each associated object
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null, $strGlue = ', ') {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural)  ?>');
			$this->str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue = $strGlue;

			$objAssociatedArray = $this-><?php echo $strObjectName  ?>->Get<?php echo $objManyToManyReference->ObjectDescription;  ?>Array();
			$strItems = array();
			foreach ($objAssociatedArray as $objAssociated) {
				$strItems[] = $objAssociated->__toString();
			}
			$this-><?php echo $strLabelId  ?>->Text = implode($this->str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue, $strItems);
			return $this-><?php echo $strLabelId  ?>;
		}
