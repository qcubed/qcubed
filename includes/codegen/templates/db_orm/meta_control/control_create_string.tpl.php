<?php
	$strControlType = $objCodeGen->MetaControlControlClass($objColumn);
	$objReflection = new ReflectionClass ($strControlType);
	$blnHasMethod = $objReflection->hasMethod ('Codegen_MetaCreate');

	if ($blnHasMethod) {
		echo $strControlType::Codegen_MetaCreate($objCodeGen, $objTable, $objColumn);
	} else {

?>

		/**
		 * Create and setup a <?= $strControlType?> <?= $strControlId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return <?= $strControlType?>
		 */
		public function <?= $strControlId ?>_Create($strControlId = null) {
			$this-><?= $strControlId ?> = new <?= $strControlType?>($this->objParentObject, $strControlId);
			$this-><?= $strControlId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlControlName($objColumn) ?>');
			$this-><?= $strControlId ?>->Text = $this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>;
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strControlId ?>->Required = true;
<?php } ?>
<?php if ($objColumn->DbType == QDatabaseFieldType::Blob) { ?>
			$this-><?= $strControlId ?>->TextMode = QTextMode::MultiLine;
<?php } ?>
<?php if (($objColumn->VariableType == QType::String) && (is_numeric($objColumn->Length))) { ?>
			$this-><?= $strControlId ?>->MaxLength = <?= $strClassName ?>::<?= $objColumn->PropertyName ?>MaxLength;
<?php } ?>
			return $this-><?= $strControlId ?>;
		}

		/**
		 * Create and setup QLabel <?= $strLabelId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?= $strLabelId ?>_Create($strControlId = null) {
			$this-><?= $strLabelId ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?= $strLabelId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlControlName($objColumn) ?>');
			$this-><?= $strLabelId ?>->Text = $this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>;
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strLabelId ?>->Required = true;
<?php } ?>
			return $this-><?= $strLabelId ?>;
		}

<?php } ?>