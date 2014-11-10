<?php $controlType = $this->MetaControlControlClass ($objColumn);?>
		/**
		 * Create and setup <?= $controlType ?> <?= $strControlId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return <?= $controlType ?>
		 */
		public function <?= $strControlId ?>_Create($strControlId = null) {
			$this-><?= $strControlId ?> = new <?= $controlType?>($this->objParentObject, $strControlId);
			$this-><?= $strControlId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlControlName($objColumn) ?>');
			$this-><?= $strControlId ?>->Checked = $this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>;
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
			$this-><?= $strLabelId ?>->Text = ($this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>) ? QApplication::Translate('Yes') : QApplication::Translate('No');
			return $this-><?= $strLabelId ?>;
		}