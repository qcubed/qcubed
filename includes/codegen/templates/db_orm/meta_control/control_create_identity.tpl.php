		/**
		 * Create and setup QLabel <?= $strControlId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?= $strControlId ?>_Create($strControlId = null) {
			$this-><?= $strControlId ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?= $strControlId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlLabelNameFromColumn($objColumn) ?>');
			if ($this->blnEditMode)
				$this-><?= $strControlId ?>->Text = $this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>;
			else
				$this-><?= $strControlId ?>->Text = 'N/A';
			return $this-><?= $strControlId ?>;
		}