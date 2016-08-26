<?php
	/**
	 * This file contains the QDateTimeTextBox class.
	 *
	 * @package Controls
	 */

	/**
	 * @package Controls
	 *
	 * @property QDateTime $Maximum
	 * @property QDateTime $Minimum
	 * @property string $DateTimeFormat
	 * @property QDateTime $DateTime
	 * @property string $LabelForInvalid
	 */
	class QDateTimeTextBox extends QTextBox {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// MISC
		protected $dttMinimum = null;
		protected $dttMaximum = null;
		protected $strDateTimeFormat = "MMM D, YYYY";
		protected $dttDateTime = null;
		
		protected $strLabelForInvalid = 'For example, "Mar 20, 4:30pm" or "Mar 20"';
		protected $calLinkedControl;

		//////////
		// Methods
		//////////
		
		public function ParsePostData() {
			// Check to see if this Control's Value was passed in via the POST data
			if (array_key_exists($this->strControlId, $_POST)) {
				parent::ParsePostData();
				$this->dttDateTime = QDateTimeTextBox::ParseForDateTimeValue($this->strText);
			}
		}

		public static function ParseForDateTimeValue($strText) {
			// Trim and Clean
			$strText = strtolower(trim($strText));
			while(strpos($strText, '  ') !== false)
				$strText = str_replace('  ', ' ', $strText);
			$strText = str_replace('.', '', $strText);
			$strText = str_replace('@', ' ', $strText);

			// Are we ATTEMPTING to parse a Time value?
			if ((strpos($strText, ':') === false) &&
				(strpos($strText, 'am') === false) &&
				(strpos($strText, 'pm') === false)) {
				// There is NO TIME VALUE
				$dttToReturn = new QDateTime($strText);
				if ($dttToReturn->IsDateNull())
					return null;
				else
					return $dttToReturn;
			}

			// Add ':00' if it doesn't exist AND if 'am' or 'pm' exists
			if ((strpos($strText, 'pm') !== false) &&
				(strpos($strText, ':') === false)) {
				$strText = str_replace(' pm', ':00 pm', $strText, $intCount);
				if (!$intCount)
					$strText = str_replace('pm', ':00 pm', $strText, $intCount);
			} else if ((strpos($strText, 'am') !== false) &&
				(strpos($strText, ':') === false)) {
				$strText = str_replace(' am', ':00 am', $strText, $intCount);
				if (!$intCount)
					$strText = str_replace('am', ':00 am', $strText, $intCount);
			}

			$dttToReturn = new QDateTime($strText);
			if ($dttToReturn->IsDateNull())
				return null;
			else
				return $dttToReturn;
		}

		public function Validate() {
			if (parent::Validate()) {
				if ($this->strText != "") {
					$dttTest = QDateTimeTextBox::ParseForDateTimeValue($this->strText);

					if (!$dttTest) {
						$this->ValidationError = $this->strLabelForInvalid;
						return false;
					}

					if (!is_null($this->dttMinimum)) {
						if ($this->dttMinimum == QDateTime::Now) {
							$dttToCompare = new QDateTime(QDateTime::Now);
							$strError = QApplication::Translate('in the past');
						} else {
							$dttToCompare = $this->dttMinimum;
							$strError = QApplication::Translate('before ') . $dttToCompare->__toString();
						}

						if ($dttTest->IsEarlierThan($dttToCompare)) {
							$this->ValidationError = QApplication::Translate('Date cannot be ') . $strError;
							return false;
						}
					}
					
					if (!is_null($this->dttMaximum)) {
						if ($this->dttMaximum == QDateTime::Now) {
							$dttToCompare = new QDateTime(QDateTime::Now);
							$strError = QApplication::Translate('in the future');
						} else {
							$dttToCompare = $this->dttMaximum;
							$strError = QApplication::Translate('after ') . $dttToCompare->__toString();
						}

						if ($dttTest->IsLaterThan($dttToCompare)) {
							$this->ValidationError = QApplication::Translate('Date cannot be ') . $strError;
							return false;
						}
					}
				}
			} else
				return false;

			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "Maximum": return $this->dttMaximum;
				case "Minimum": return $this->dttMinimum;
				case 'DateTimeFormat': return $this->strDateTimeFormat;
				case 'DateTime': return $this->dttDateTime;
				case 'LabelForInvalid': return $this->strLabelForInvalid;

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
			$this->blnModified = true;

			switch ($strName) {
				// MISC
				case 'Maximum':
					try {
						if ($mixValue == QDateTime::Now)
							$this->dttMaximum = QDateTime::Now;
						else
							$this->dttMaximum = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Minimum':
					try {
						if ($mixValue == QDateTime::Now)
							$this->dttMinimum = QDateTime::Now;
						else
							$this->dttMinimum = QType::Cast($mixValue, QType::DateTime);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DateTimeFormat':
					try {
						$this->strDateTimeFormat = QType::Cast($mixValue, QType::String);
						// trigger an update to reformat the text with the new format
						$this->DateTime = $this->dttDateTime;
						return $this->strDateTimeFormat;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DateTime':
					try {
						$this->dttDateTime = QType::Cast($mixValue, QType::DateTime);
						if (!$this->dttDateTime || !$this->strDateTimeFormat) {
							parent::__set('Text', '');
						} else {
							parent::__set('Text', $this->dttDateTime->qFormat($this->strDateTimeFormat));
						}
						return $this->dttDateTime;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Text':
					$this->dttDateTime = QDateTimeTextBox::ParseForDateTimeValue($this->strText);
					return parent::__set('Text', $mixValue);

				case 'LabelForInvalid':
					try {
						return ($this->strLabelForInvalid = QType::Cast($mixValue, QType::String));
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

		/**** Codegen Helpers, used during the Codegen process only. ****/

		public static function Codegen_VarName($strPropName) {
			return 'cal' . $strPropName;
		}

	}