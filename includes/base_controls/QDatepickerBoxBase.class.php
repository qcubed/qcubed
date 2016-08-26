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
	 * A Datepicker Box is similar to a Datepicker, but its not associated with a field. It
	 * just displays a calendar for picking a date.
	 *
	 * @property string    $DateFormat             The format to use for displaying the date
	 * @property string    $DateTimeFormat         Alias for DateFormat
	 * @property QDateTime $DateTime               The date to set the field to
	 * @property mixed     $Minimum                Alias for MinDate
	 * @property mixed     $Maximum                Alias for MaxDate
	 * @property string    $Text                   Textual date to set it to
	 * @property-write string    $MinDateErrorMsg  Message to display if we are before the minimum date
	 * @property-write string    $MaxDateErrorMsg  Message to display if we are after the maximum date
	 * @link    http://jqueryui.com/datepicker/#inline
	 * @package Controls\Base
	 */
	class QDatepickerBoxBase extends QDatepickerBoxGen {
		/** @var string Format for the datetime to pick */
		protected $strDateTimeFormat = "MM/DD/YYYY"; // matches default of JQuery UI control
		/** @var QDateTime variable to store the picked value */
		protected $dttDateTime;
		/** @var  string */
		protected $strMinDateErrorMsg;
		/** @var  string */
		protected $strMaxDateErrorMsg;

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
					$this->ValidationError = QApplication::Translate("Invalid date");
					return false;
				}
				if (!is_null($this->Minimum)) {
					if ($dttDateTime->IsEarlierThan($this->Minimum)) {
						if ($this->strMinDateErrorMsg) {
							$this->ValidationError = $this->strMinDateErrorMsg;
						} else {
							$this->ValidationError = QApplication::Translate("Date is earlier than minimum allowed");
						}
						return false;
					}
				}

				if (!is_null($this->Maximum)) {
					if ($dttDateTime->IsLaterThan($this->Maximum)) {
						if ($this->strMaxDateErrorMsg) {
							$this->ValidationError = $this->strMaxDateErrorMsg;
						} else {
							$this->ValidationError = QApplication::Translate("Date is later than maximum allowed");
						}
						return false;
					}
				}
			}
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method implementation
		 *
		 * @param string $strName
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
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
		/**
		 * PHP magic method implementation
		 *
		 * @param string $strName  Property name
		 * @param string $mixValue Property value to be set
		 *
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
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

				case 'MinDateErrorMsg':
					$this->strMinDateErrorMsg = QType::Cast($mixValue, QType::String);
					break;

				case 'MaxDateErrorMsg':
					$this->strMaxDateErrorMsg = QType::Cast($mixValue, QType::String);
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

		/**
		 * @return QModelConnectorParam[]
		 */
		public static function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'DateFormat', 'How to format the date. Default: MM/DD/YY', QType::String)
			));
		}

	}