<?php
	/**
	 * This file contains the QFloatTextBox class.
	 *
	 * @package Controls
	 */

	/**
	 * A subclass of QNumericTextBox -- Validate will also ensure
	 * that the Text is a valid float and (if applicable) is in the range of Minimum <= x <= Maximum
	 *
	 * We do not use the sanitize capability of QTextBox here. Sanitizing the data will change the data, and
	 * if the user does not type in a valid float, we will not be able to put up a warning telling the user they made
	 * a mistake. You can easily change this behavior by setting the following:
	 * 	SanitizeFilter = FILTER_SANITIZE_NUMBER_FLOAT
	 *  SanitizeFilterOptions = FILTER_FLAG_ALLOW_FRACTION
	 *
	 * @package Controls
	 * @property int|null	 $Value			Returns the integer value of the text, sanitized.
	 *
	 */
	class QFloatTextBox extends QNumericTextBox {
		//////////
		// Methods
		//////////
		/**
		 * Constructor
		 *
		 * @param QControl|QForm $objParentObject
		 * @param null|string    $strControlId
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->strLabelForInvalid = QApplication::Translate('Invalid Float');
			$this->strDataType = QType::Float;
		}

		public function __get($strName) {
			switch ($strName) {
				case "Value":
					if ($this->strText === null || $this->strText === "") {
						return null;
					} else {
						return (float)filter_var ($this->strText, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
					}

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}