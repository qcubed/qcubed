		/**
		 * Create and setup QCheckBox <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QCheckBox
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QCheckBox($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>');
			$this-><?php echo $strControlId  ?>->Checked = $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>;
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>');
			$this-><?php echo $strLabelId  ?>->Text = ($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>) ? QApplication::Translate('Yes') : QApplication::Translate('No');
			return $this-><?php echo $strLabelId  ?>;
		}