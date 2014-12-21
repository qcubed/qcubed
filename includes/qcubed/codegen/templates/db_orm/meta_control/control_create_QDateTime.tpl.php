		/**
		 * Create and setup QDateTimePicker <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
<?php
switch ($objColumn->DbType) {
	case QDatabaseFieldType::DateTime:
?>
		 * @return QJqDateTimePicker
<?php
		break;
	case QDatabaseFieldType::Time:
?>
		 * @return QDateTimePicker
<?php
		break;
	default:
?>
		 * @return QDatePickerBox
<?php
		break;
}
?>
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null) {
<?php
	switch ($objColumn->DbType) {
		case QDatabaseFieldType::DateTime:
?>
			$this-><?php echo $strControlId  ?> = new QJqDateTimePicker($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->CssClass = 'textbox ui-corner-all';
			$this-><?php echo $strControlId  ?>->DateFormat = "MMM DD, YYYY";
			$this-><?php echo $strControlId  ?>->TimeFormat = "hhhh:mm";
<?php
			break;
		case QDatabaseFieldType::Time:
?>
			$this-><?php echo $strControlId  ?> = new QDateTimePicker($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->DateTimePickerType = QDateTimePickerType::Time;
			$this-><?php echo $strControlId  ?>->TimeFormat = "hhhh:mm";
<?php
			break;
		default:
?>
			$this-><?php echo $strControlId  ?> = new QDatePickerBox($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->CssClass = 'textbox ui-corner-all';
<?php
			break;
	}
?>;
			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>');
			$this-><?php echo $strControlId  ?>->DateTime = $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>;
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
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objColumn->PropertyName)  ?>');
			if (!is_null($strDateTimeFormat))
				$this->str<?php echo $objColumn->PropertyName  ?>DateTimeFormat = $strDateTimeFormat;
			$this-><?php echo $strLabelId  ?>->Text = $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?> ? $this-><?php echo $strObjectName  ?>-><?php echo $objColumn->PropertyName  ?>->qFormat($this->str<?php echo $objColumn->PropertyName  ?>DateTimeFormat) : null;
<?php if ($objColumn->NotNull) { ?>
			$this-><?php echo $strLabelId  ?>->Required = true;
<?php } ?>
			return $this-><?php echo $strLabelId  ?>;
		}

<?php
	switch ($objColumn->DbType) {
		case QDatabaseFieldType::DateTime:
?>
		protected $str<?php echo $objColumn->PropertyName  ?>DateTimeFormat = "MMM DD, YYYY hhhh:mm";
<?php
			break;
		case QDatabaseFieldType::Time:
?>
		protected $str<?php echo $objColumn->PropertyName  ?>DateTimeFormat = "hhhh:mm";
<?php
			break;
		default:
?>
		protected $str<?php echo $objColumn->PropertyName  ?>DateTimeFormat = "MMM DD, YYYY";
<?php
			break;
	}
?>
