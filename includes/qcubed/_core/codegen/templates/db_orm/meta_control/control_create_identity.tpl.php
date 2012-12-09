		/**
		 * Create and setup QLabel <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			if ($this->blnEditMode)
				$this-><?php echo $strControlId  ?>->Text = $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>;
			else
				$this-><?php echo $strControlId  ?>->Text = 'N/A';
			return $this-><?php echo $strControlId  ?>;
		}