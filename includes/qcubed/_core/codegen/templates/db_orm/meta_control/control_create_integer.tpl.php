		/**
		 * Create and setup QIntegerTextBox <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QIntegerTextBox
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QIntegerTextBox($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>');
			$this-><?php echo $strControlId  ?>->Text = $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>;
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->Required = true;
<?php } ?>
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param string $strFormat optional sprintf format to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null, $strFormat = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>');
			$this-><?php echo $strLabelId  ?>->Text = $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>;
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strLabelId  ?>->Required = true;
<?php } ?>
			$this-><?php echo $strLabelId  ?>->Format = $strFormat;
			return $this-><?php echo $strLabelId  ?>;
		}