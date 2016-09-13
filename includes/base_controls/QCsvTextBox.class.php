<?php
	/**
	 * @package Controls
	 */

	/**
	 * A subclass of TextBox that allows the user to type in a list of values to be converted into
	 * an array. Uses str_getcsv to process.
	 *
	 * @property string $Delimiter is the csv separator. Default: , (comma)
	 * @property string $Enclosure
	 * @property string $Escape
	 * @property integer $MinItemCount
	 * @property integer $MaxItemCount
	 */
	class QCsvTextBox extends QTextBox {
		/** @var string */
		protected $strDelimiter = ',';
		/** @var string  */
		protected $strEnclosure = '"';
		/** @var string  */
		protected $strEscape = '\\';
		/** @var int  */
		protected $intMinItemCount = null;
		/** @var int  */
		protected $intMaxItemCount = null;

		/**
		 * Constructor
		 *
		 * @param QControl|QForm $objParentObject Parent of this textbox
		 * @param null|string    $strControlId    Desired control ID for the textbox
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			// borrows too short and too long labels from super class
			$this->strLabelForTooShort = QApplication::Translate('Enter at least %s items.');
			$this->strLabelForTooLong = QApplication::Translate('Enter no more than %s items.');
		}

		/**
		 * Validate the control, setting validation error if there is a problem.
		 * @return bool
		 */
		public function Validate() {
			$blnRet = parent::Validate();
			if ($blnRet) {
				$a = str_getcsv($this->strText);

				if ($this->intMinItemCount !== null &&
						count ($a) < $this->intMinItemCount) {
					$this->ValidationError = sprintf($this->strLabelForTooShort, $this->intMinItemCount);
					return false;
				}

				if ($this->intMaxItemCount !== null &&
					count ($a) > $this->intMaxItemCount) {
					$this->ValidationError = sprintf($this->strLabelForTooLong, $this->intMaxItemCount);
					return false;
				}

			}

			// If we're here, then everything is a-ok.  Return true.
			return true;
		}

		/**
		 * PHP magic method
		 * @param string $strName Property name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Delimiter": return $this->strDelimiter;
				case "Enclosure": return $this->strEnclosure;
				case "Escape": return $this->strEscape;
				case "MinItemCount": return $this->intMinItemCount;
				case "MaxItemCount": return $this->intMaxItemCount;
				case 'Value':
					if (empty($this->strText)) return array();
					return str_getcsv($this->strText, $this->strDelimiter, $this->strEnclosure, $this->strEscape);

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		/**
		 * PHP __set magic method implementation
		 *
		 * @param string $strName  Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @return mixed|void
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "Delimiter":
					try {
						$this->strDelimiter = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Enclosure":
					try {
						$this->strEnclosure = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Escape":
					try {
						$this->strEscape = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MinItemCount":
					try {
						$this->intMinItemCount = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MaxItemCount":
					try {
						$this->intMaxItemCount = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Value":
					try {
						$a =  QType::Cast($mixValue, QType::ArrayType);
						$temp_memory = fopen('php://memory', 'w');
						fputcsv($temp_memory, $a, $this->strDelimiter, $this->strEnclosure);
						rewind($temp_memory);
						$this->strText = fgets($temp_memory);
						fclose($temp_memory);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}

		/**
		 * Returns an description of the options available to modify by the designer for the code generator.
		 *
		 * @return QModelConnectorParam[]
		 */
		public static function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Delimiter', 'Default: , (comma)', QType::String),
				new QModelConnectorParam (get_called_class(), 'Enclosure', 'Default: " (double-quote)', QType::String),
				new QModelConnectorParam (get_called_class(), 'Escape', 'Default: \\ (backslash)', QType::String),
				new QModelConnectorParam (get_called_class(), 'MinItemCount', 'Minimum number of items required.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MaxItemCount', 'Maximum number of items allowed.', QType::Integer)
			));
		}

	}