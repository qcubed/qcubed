<?php
//$strControlType = $objCodeGen->MetaControlControlClass($objManyToManyReference->Column);
//$objReflection = new ReflectionClass ($strControlType);
//$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaCreate');

//if ($blnHasMethod) {
//	echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
//} else {
?>

/**
		 * Create and setup QCheckBoxList <?= $strControlId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QCheckBoxList
		 */
		public function <?= $strControlId ?>_Create($strControlId = null) {
			$this-><?= $strControlId ?> = new QCheckBoxList($this->objParentObject, $strControlId);
			$this-><?= $strControlId ?>->Name = QApplication::Translate('<?= QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural) ?>');
			foreach (<?= $objManyToManyReference->VariableType ?>::$NameArray as $intId => $strValue)
				$this-><?= $strControlId ?>->AddItem(new QListItem($strValue, $intId));
			$this-><?= $strControlId ?>->SelectedValues = array_keys($this-><?= $strObjectName?>->Get<?= $objManyToManyReference->ObjectDescription?>Array());

			return $this-><?= $strControlId ?>;
		}

		/**
		 * Create and setup QLabel <?= $strLabelId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?= $strLabelId ?>_Create($strControlId = null) {
			$this-><?= $strLabelId ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?= $strLabelId ?>->Name = QApplication::Translate('<?= QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural) ?>');
			
			$aSelection = $this-><?= $strObjectName?>->Get<?= $objManyToManyReference->ObjectDescription?>Array();
			$this-><?= $strLabelId ?>->Text = implode($this->str<?= $objManyToManyReference->ObjectDescription; ?>Glue, $aSelection);
			return $this-><?= $strLabelId ?>;
		}
<?php
// }
?>