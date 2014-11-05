<?php
$strControlType = $objCodeGen->FormControlClassForColumn($objColumn);
$objReflection = new ReflectionClass ($strControlType);
$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaCreate');

if ($blnHasMethod) {
	echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
} else {

?>
/**
		 * Create and setup QListBox <?= $strControlId ?>

		 * @param string $strControlId optional ControlId to use
		 * @param QQCondition $objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return QListBox
		 */
		public function <?= $strControlId ?>_Create($strControlId = null, QQCondition $objCondition = null, $objOptionalClauses = null) {
			$this-><?= $strControlId ?> = new QListBox($this->objParentObject, $strControlId);
			$this-><?= $strControlId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlLabelNameFromColumn($objColumn) ?>');
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strControlId ?>->Required = true;
			if (!$this->blnEditMode)
				$this-><?= $strControlId ?>->AddItem(QApplication::Translate('- Select One -'), null);
<?php } ?><?php if (!$objColumn->NotNull) { ?>
			$this-><?= $strControlId ?>->AddItem(QApplication::Translate('- Select One -'), null);
<?php } ?>

			$a = $this-><?= $strControlId ?>_GetItems($objCondition, $objOptionalClauses);
			$this-><?= $strControlId ?>->AddItems($a);

			// Return the QListBox
			return $this-><?= $strControlId ?>;
		}
		
		/**
		 *	Create item list for use by <?= $strControlId ?>
		 */
		 public function <?= $strControlId ?>_GetItems($objCondition, $objOptionalClauses) {
			$a = array();
			if (is_null($objCondition)) $objCondition = QQ::All();
			$<?= $objColumn->Reference->VariableName ?>Cursor = <?= $objColumn->Reference->VariableType ?>::QueryCursor($objCondition, $objOptionalClauses);

			// Iterate through the Cursor
			while ($<?= $objColumn->Reference->VariableName ?> = <?= $objColumn->Reference->VariableType ?>::InstantiateCursor($<?= $objColumn->Reference->VariableName ?>Cursor)) {
				$objListItem = new QListItem($<?= $objColumn->Reference->VariableName ?>->__toString(), $<?= $objColumn->Reference->VariableName ?>-><?= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName ?>);
				if (($this-><?= $strObjectName ?>-><?= $objColumn->Reference->PropertyName ?>) && ($this-><?= $strObjectName ?>-><?= $objColumn->Reference->PropertyName ?>-><?= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName ?> == $<?= $objColumn->Reference->VariableName ?>-><?= $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName ?>))
					$objListItem->Selected = true;
				$a[] = $objListItem;
			}
		 	return $a;
		 }
		
		/**
		 * Create and setup QAutocomplete <?= $strControlId ?>
		 
		 * This is an alternate to the list box control above.
		 * @param string $strControlId optional ControlId to use
		 * Once created, you should either pass (<?= $strControlId ?>_GetItems()), or call ->SetDataBinder()
		 * @return QAutocomplete
		 */
		public function <?= $strControlId ?>AutoComplete_Create($strControlId = null, $mustMatch = true, $aryItems = null) {
			$this-><?= $strControlId ?> = new QAutocomplete($this->objParentObject, $strControlId);
			$this-><?= $strControlId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlLabelNameFromColumn($objColumn) ?>');
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strControlId ?>->Required = true;
<?php } ?>

			$this-><?= $strControlId ?>->MustMatch = $mustMatch;
			$this-><?= $strControlId ?>->AutoFocus = $mustMatch;

			if ($aryItems) {
				$this-><?= $strControlId ?>->Source = $aryItems;
			}
			// Return the QAutocomplete
			return $this-><?= $strControlId ?>;
		}

		/**
		 * Create and setup QLabel <?= $strLabelId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?= $strLabelId ?>_Create($strControlId = null) {
			$this-><?= $strLabelId ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?= $strLabelId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlLabelNameFromColumn($objColumn) ?>');
			$this-><?= $strLabelId ?>->Text = ($this-><?= $strObjectName ?>-><?= $objColumn->Reference->PropertyName ?>) ? $this-><?= $strObjectName ?>-><?= $objColumn->Reference->PropertyName ?>->__toString() : null;
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strLabelId ?>->Required = true;
<?php } ?>
			return $this-><?= $strLabelId ?>;
		}
<?php } ?>