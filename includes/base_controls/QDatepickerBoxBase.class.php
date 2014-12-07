<?php

	/**
	 * DatepickerBox Base File
	 * 
	 * The QDatepickerBoxBase class defined here provides an interface between the generated
	 * QDatepickerBoxGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QDatepickerBox.class.php file instead.
	 *
	 */

    /**
     * Impelements a JQuery UI Datepicker in a box
     * 
     * A Datepicker Box is similar to a Datepicker, but its not associated with a field. It
     * just displays a calendar for picking a date.
     * 
	 * @property string $DateFormat			The format to use for displaying the date
	 * @property string $DateTimeFormat		Alias for DateFormat
	 * @property QDateTime $DateTime		The date to set the field to
	 * @property mixed $Minimum				Alias for MinDate
	 * @property mixed $Maximum				Alias for MaxDate
	 * @property string $Text				Textual date to set it to
	 *
	 * @link http://jqueryui.com/datepicker/#inline
	 * @package Controls\Base
	 */
	class QDatepickerBoxBase extends QDatepickerBoxGen {
		protected $strDateTimeFormat = "MM/DD/YY"; // matches default of JQuery UI control
		/** @var QDateTime */
		protected $dttDateTime;

		public function ParsePostData() {
			// Check to see if this Control's Value was passed in via the POST data
			if (array_key_exists($this->strControlId, $_POST)) {
				parent::ParsePostData();
				$this->dttDateTime = new QDateTime($this->strText, null, QDateTime::DateOnlyType);
				if ($this->dttDateTime->IsNull()) {
					$this->dttDateTime = null;
				}
			}
		}

		/**
		 * Validate the control.
		 * @return bool
		 */
		public function Validate() {
			if (!parent::Validate()) {
				return false;
			}

			if ($this->strText != '') {
				$dttDateTime = new QDateTime($this->strText, null, QDateTime::DateOnlyType);
				if ($dttDateTime->IsDateNull()) {
					$this->strValidationError = QApplication::Translate("Invalid date");
					return false;
				}
				if (!is_null($this->Minimum)) {
					if ($dttDateTime->IsEarlierThan($this->Minimum)) {
						$this->strValidationError = QApplication::Translate("Date is earlier than minimum allowed");
						return false;
					}
				}

				if (!is_null($this->Maximum)) {
					if ($dttDateTime->IsLaterThan($this->Maximum)) {
						$this->strValidationError = QApplication::Translate("Date is later than maximum allowed");
						return false;
					}
				}
			}

			$this->strValidationError = '';
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "Maximum":
					return $this->MaxDate;
				case "Minimum":
					return $this->MinDate;
				case 'DateTimeFormat':
				case 'DateFormat':
					return $this->strDateTimeFormat;
				case 'DateTime':
					return $this->dttDateTime ? clone($this->dttDateTime) : null;

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
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'MaxDate':
				case 'Maximum':
					try{
						if (is_string($mixValue)) {
							if (preg_match('/[+-][0-9]+[dDwWmMyY]/', $mixValue)) {
								parent::__set($strName, $mixValue);
								break;
							}
						}
						parent::__set('MaxDate', new QDateTime ($mixValue, null, QDateTime::DateOnlyType));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case 'MinDate':
				case 'Minimum':
					try {
						if (is_string($mixValue)) {
							if (preg_match('/[+-][0-9]+[dDwWmMyY]/', $mixValue)) {
								parent::__set($strName, $mixValue);
								break;
							}
						}
						parent::__set('MinDate', new QDateTime ($mixValue, null, QDateTime::DateOnlyType));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case 'DateTime':
					try {
						$this->dttDateTime = new QDateTime($mixValue, null, QDateTime::DateOnlyType);
						if ($this->dttDateTime && $this->dttDateTime->IsNull()) {
							$this->dttDateTime = null;
							$this->blnModified = true;
						}
						if (!$this->dttDateTime || !$this->strDateTimeFormat) {
							parent::__set('Text', '');
						} else {
							parent::__set('Text', $this->dttDateTime->qFormat($this->strDateTimeFormat));
						}
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case 'JqDateFormat':
					try {
						parent::__set($strName, $mixValue);
						$this->strDateTimeFormat = QCalendar::qcFrmt($this->JqDateFormat);
						// trigger an update to reformat the text with the new format
						$this->DateTime = $this->dttDateTime;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case 'DateTimeFormat':
				case 'DateFormat':
					try {
						$this->strDateTimeFormat = QType::Cast($mixValue, QType::String);
						parent::__set('JqDateFormat', QCalendar::jqFrmt($this->strDateTimeFormat));
						// trigger an update to reformat the text with the new format
						$this->DateTime = $this->dttDateTime;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case 'Text':
					parent::__set($strName, $mixValue);
					$this->dttDateTime = new QDateTime($this->strText, null, QDateTime::DateOnlyType);
					break;

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

		/**** Codegen Helpers, used during the Codegen process only. ****/

		/**
		 * Return a variable name given a property name.
		 * @param $strPropName
		 * @return string
		 */
		public static function Codegen_VarName($strPropName) {
			return 'cal' . $strPropName;
		}

		/**
		 * Return code that will update the control with data from the database.
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param bool $blnInit
		 * @return string
		 */
		public static function Codegen_MetaRefresh(QCodeGen $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);

			if ($blnInit) {
				$strRet = "\t\t\t\$this->{$strControlVarName}->DateTime = \$this->{$strObjectName}->{$strPropName};";
			} else {
				$strRet = "\t\t\tif (\$this->{$strControlVarName}) \$this->{$strControlVarName}->DateTime = \$this->{$strObjectName}->{$strPropName};";
			}
			return $strRet . "\n";
		}


		/**
		 * Return code that will update the database with info from the control.
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public static function Codegen_MetaUpdate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);
			$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->PropertyName} = \$this->{$strControlVarName}->DateTime;

TMPL;
			return $strRet;
		}

		/**
		 * @return QMetaParam[]
		 */
		public static function GetMetaParams() {
			return array_merge(parent::GetMetaParams(), array(
				new QMetaParam (get_called_class(), 'DateFormat', 'How to format the date. Default: MM/DD/YY', QType::String)
			));
		}

	}

?>
