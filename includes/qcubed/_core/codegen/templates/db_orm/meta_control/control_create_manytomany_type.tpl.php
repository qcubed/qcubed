		/**
		 * Create and setup QCheckBoxList <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QCheckBoxList
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QCheckBoxList($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural)  ?>');
			foreach (<?php echo $objManyToManyReference->VariableType  ?>::$NameArray as $intId => $strValue)
				$this-><?php echo $strControlId  ?>->AddItem(new QListItem($strValue, $intId));
			$this-><?php echo $strControlId  ?>->SelectedValues = array_keys($this-><?php echo $strObjectName?>->Get<?php echo $objManyToManyReference->ObjectDescription?>Array());

			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural)  ?>');
			
			$aSelection = $this-><?php echo $strObjectName?>->Get<?php echo $objManyToManyReference->ObjectDescription?>Array();
			$this-><?php echo $strLabelId  ?>->Text = implode($this->str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue, $aSelection);
			return $this-><?php echo $strLabelId  ?>;
		}