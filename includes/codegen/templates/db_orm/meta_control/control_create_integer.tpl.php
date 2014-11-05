		/**
		 * Create and setup QIntegerTextBox <?= $strControlId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QIntegerTextBox
		 */
		public function <?= $strControlId ?>_Create($strControlId = null) {
			$this-><?= $strControlId ?> = new QIntegerTextBox($this->objParentObject, $strControlId);
			$this-><?= $strControlId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlLabelNameFromColumn($objColumn) ?>');
			$this-><?= $strControlId ?>->Text = $this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>;
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strControlId ?>->Required = true;
<?php } ?>
			return $this-><?= $strControlId ?>;
		}

		/**
		 * Create and setup QLabel <?= $strLabelId ?>

		 * @param string $strControlId optional ControlId to use
		 * @param string $strFormat optional sprintf format to use
		 * @return QLabel
		 */
		public function <?= $strLabelId ?>_Create($strControlId = null, $strFormat = null) {
			$this-><?= $strLabelId ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?= $strLabelId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlLabelNameFromColumn($objColumn) ?>');
			$this-><?= $strLabelId ?>->Text = $this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>;
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strLabelId ?>->Required = true;
<?php } ?>
			$this-><?= $strLabelId ?>->Format = $strFormat;
			return $this-><?= $strLabelId ?>;
		}