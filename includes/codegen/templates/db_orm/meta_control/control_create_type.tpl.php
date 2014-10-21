		/**
		 * Create and setup QListBox <?= $strControlId ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QListBox
		 */
		public function <?= $strControlId ?>_Create($strControlId = null) {
			$this-><?= $strControlId ?> = new QListBox($this->objParentObject, $strControlId);
			$this-><?= $strControlId ?>->Name = QApplication::Translate('<?= QCodeGen::MetaControlLabelNameFromColumn($objColumn) ?>');
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strControlId ?>->Required = true;
<?php } ?><?php if (!$objColumn->NotNull) { ?>
			$this-><?= $strControlId ?>->AddItem(QApplication::Translate('- Select One -'), null);
<?php } ?>
			foreach (<?= $objColumn->Reference->VariableType ?>::$NameArray as $intId => $strValue)
				$this-><?= $strControlId ?>->AddItem(new QListItem($strValue, $intId, $this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?> == $intId));
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
			$this-><?= $strLabelId ?>->Text = ($this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>) ? <?= $objColumn->Reference->VariableType ?>::$NameArray[$this-><?= $strObjectName ?>-><?= $objColumn->PropertyName ?>] : null;
<?php if ($objColumn->NotNull) { ?>
			$this-><?= $strLabelId ?>->Required = true;
<?php } ?>
			return $this-><?= $strLabelId ?>;
		}