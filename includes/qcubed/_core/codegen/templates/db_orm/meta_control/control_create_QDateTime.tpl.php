		/**
		 * Create and setup QDateTimePicker <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @return QDateTimePicker
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
			$this-><?php echo $strControlId  ?> = new QDateTimePicker($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			$this-><?php echo $strControlId  ?>->DateTime = $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>;
			$this-><?php echo $strControlId  ?>->DateTimePickerType = QDateTimePickerType::<?php 
	switch ($objColumn->DbType) {
		case QDatabaseFieldType::DateTime:
			print 'DateTime';
			break;
		case QDatabaseFieldType::Time:
			print 'Time';
			break;
		default:
			print 'Date';
			break;
	}
?>;
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strControlId  ?>->Required = true;
<?php } ?>
			return $this-><?php echo $strControlId  ?>;
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param string $strDateTimeFormat optional DateTimeFormat to use
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null, $strDateTimeFormat = null) {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QCodeGen::MetaControlLabelNameFromColumn($objColumn)  ?>');
			$this->str<?php echo $objColumn->PropertyName  ?>DateTimeFormat = $strDateTimeFormat;
			$this-><?php echo $strLabelId  ?>->Text = sprintf($this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>) ? $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>->qFormat($this->str<?php echo $objColumn->PropertyName  ?>DateTimeFormat) : null;
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strLabelId  ?>->Required = true;
<?php } ?>
			return $this-><?php echo $strLabelId  ?>;
		}

		protected $str<?php echo $objColumn->PropertyName  ?>DateTimeFormat;
