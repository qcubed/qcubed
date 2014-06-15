<?php
$strControlType = $objCodeGen->FormControlClassForColumn($objColumn);
$objReflection = new ReflectionClass ($strControlType);
$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaCreate');

if ($blnHasMethod) {
	echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
} else {

?>
/**
		 * Create and setup QListBox <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param QQCondition $objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return QListBox
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null, QQCondition $objCondition = null, $objOptionalClauses = null) {
			$this-><?php echo $strControlId  ?> = new QListBox($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->Required = true;
			if (!$this->blnEditMode)
				$this-><?php echo $strControlId  ?>->AddItem(QApplication::Translate('- Select One -'), null);
<?php } ?><?php if (!$objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->AddItem(QApplication::Translate('- Select One -'), null);
<?php } ?>

			$a = $this-><?php echo $strControlId  ?>_GetItems($objCondition, $objOptionalClauses);
			$this-><?php echo $strControlId  ?>->AddItems($a);

			// Return the QListBox
			return $this-><?php echo $strControlId  ?>;
		}
		
		/**
		 *	Create item list for use by <?php echo $strControlId  ?>
		 */
		 public function <?php echo $strControlId  ?>_GetItems($objCondition, $objOptionalClauses) {
			$a = array();
			if (is_null($objCondition)) $objCondition = QQ::All();
			$<?php echo $objColumn->Reference->VariableName  ?>Cursor = <?php echo $objColumn->Reference->VariableType  ?>::QueryCursor($objCondition, $objOptionalClauses);

			// Iterate through the Cursor
			while ($<?php echo $objColumn->Reference->VariableName  ?> = <?php echo $objColumn->Reference->VariableType  ?>::InstantiateCursor($<?php echo $objColumn->Reference->VariableName  ?>Cursor)) {
				$objListItem = new QListItem($<?php echo $objColumn->Reference->VariableName  ?>->__toString(), $<?php echo $objColumn->Reference->VariableName  ?>-><?php echo $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?>);
				if (($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>) && ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>-><?php echo $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?> == $<?php echo $objColumn->Reference->VariableName  ?>-><?php echo $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName  ?>))
					$objListItem->Selected = true;
				$a[] = $objListItem;
			}
		 	return $a;
		 }
		
		/**
		 * Create and setup QAutocomplete <?php echo $strControlId  ?>
		 
		 * This is an alternate to the list box control above.
		 * @param string $strControlId optional ControlId to use
		 * Once created, you should either pass (<?php echo $strControlId  ?>_GetItems()), or call ->SetDataBinder()
		 * @return QAutocomplete
		 */
		public function <?php echo $strControlId  ?>AutoComplete_Create($strControlId = null, $mustMatch = true, $aryItems = null) {
			$this-><?php echo $strControlId  ?> = new QAutocomplete($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->Required = true;
<?php } ?>

			$this-><?php echo $strControlId  ?>->MustMatch = $mustMatch;
			$this-><?php echo $strControlId  ?>->AutoFocus = $mustMatch;

			if ($aryItems) {
				$this->Source = $aryItems;
			}
			// Return the QAutocomplete
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			$this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>) ? $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->Reference->PropertyName  ?>->__toString() : null;
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strLabelId  ?>->Required = true;
<?php } ?>
			return $this-><?php echo $strLabelId  ?>;
		}
<?php } ?>