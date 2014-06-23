<?php
	 /**
	 * This file contains the QNumericTextBox class
	 *
	 * @package Controls
	 */


	/**
	 * A subclass of TextBox with its validate method overridden -- Validate will also ensure
	 * that the Text is a valid integer/float and (if applicable) is in the range of Minimum <= x <= Maximum.
	 * This class is abstract. QIntegerTextBox and QFloatTextBox are derived from it.
	 *
	 * @package                 Controls
	 * @property mixed  $Maximum         (optional) is the maximum value the integer/float can be
	 * @property mixed  $Minimum         (optional) is the minimum value the integer/float can be
	 * @property mixed  $Step            (optional) is the step interval for allowed values ( beginning from $Minimum if set)
	 * @property string $LabelForGreater Text to show when the input is greater than the allowed value
	 * @property string $LabelForLess    Text to show when the input is lesser than the minimum allowed value
	 * @property string $LabelForNotStepAligned
	 *                          set this property to show an error message if the entered value is not step-aligned
	 *                          if not set the value is changed to the next step-aligned value (no error)
	 */
	abstract class QNumericTextBox extends QTextBox {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		//DATA TYPE
		/** @var string Data type of the input (float|integer) */
		protected $strDataType = null;
		// MISC
		/** @var mixed Maximum allowed Value */
		protected $mixMaximum = null;
		/** @var mixed Minimum allowed value */
		protected $mixMinimum = null;
		/** @var mixed Float or Integer value, the multiple of which the input must be */
		protected $mixStep = null;

		/** @var string Text to show when the input value is less than minimum allowed value */
		protected $strLabelForLess;
		/** @var string Text to show when the input value is greater than the maximum allowed value */
		protected $strLabelForGreater;
		/** @var string Text to show when the input value is not step aligned */
		protected $strLabelForNotStepAligned = null;

		//////////
		// Methods
		//////////
		/**
		 * Constructor for the control
		 *
		 * @param QControl|QForm $objParentObject
		 * @param null|string    $strControlId
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strLabelForInvalid = QApplication::Translate('Invalid Number');
			$this->strLabelForLess = QApplication::Translate('Value must be less than %s');
			$this->strLabelForGreater = QApplication::Translate('Value must be greater than %s');
		}

		/**
		 * @return bool whether or not the input passed all the values
		 */
		public function Validate() {
			if (parent::Validate()) {
				if ($this->strText != "") {
					try {
						$this->strText = QType::Cast($this->strText, $this->strDataType);
					} catch (QInvalidCastException $objExc) {
						$this->strValidationError = $this->strLabelForInvalid;
						$this->MarkAsModified();
						return false;
					}

					if (!is_numeric($this->strText)) {
						$this->strValidationError = $this->strLabelForInvalid;
						$this->MarkAsModified();
						return false;
					}

					if (!is_null($this->mixStep)) {
						$newVal = QType::Cast(round(($this->strText - $this->mixMinimum) / $this->mixStep) * $this->mixStep + $this->mixMinimum, $this->strDataType);

						if ($newVal != $this->strText) {
							if ($this->strLabelForNotStepAligned) {
								$this->strValidationError = sprintf($this->strLabelForNotStepAligned, $this->mixStep);
								$this->MarkAsModified();
								return false;
							}
							$this->strText = $newVal;
							$this->MarkAsModified();
						}
					}

					if ((!is_null($this->mixMinimum)) && ($this->strText < $this->mixMinimum)) {
						$this->strValidationError = sprintf($this->strLabelForGreater, $this->mixMinimum);
						$this->MarkAsModified();
						return false;
					}

					if ((!is_null($this->mixMaximum)) && ($this->strText > $this->mixMaximum)) {
						$this->strValidationError = sprintf($this->strLabelForLess, $this->mixMaximum);
						$this->MarkAsModified();
						return false;
					}
				}
			} else
				return false;

			$this->strValidationError = "";
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Name of the property
		 *
		 * @return array|bool|int|mixed|null|QControl|QForm|string
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// MISC
				case "Maximum":
					return $this->mixMaximum;
				case "Minimum":
					return $this->mixMinimum;
				case 'Step':
					return $this->mixStep;
				case 'LabelForGreater':
					return $this->strLabelForGreater;
				case 'LabelForLess':
					return $this->strLabelForLess;
				case 'LabelForNotStepAligned':
					return $this->strLabelForNotStepAligned;

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
		 * @param string $strName Property Name
		 * @param string $mixValue Property value
		 *
		 * @throws Exception|QCallerException
		 * @throws Exception|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// MISC
				case "Maximum":
					try {
						$this->mixMaximum = QType::Cast($mixValue, $this->strDataType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Minimum":
					try {
						$this->mixMinimum = QType::Cast($mixValue, $this->strDataType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Step":
					try {
						$this->mixStep = QType::Cast($mixValue, $this->strDataType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LabelForGreater':
					try {
						$this->strLabelForGreater = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LabelForLess':
					try {
						$this->strLabelForLess = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LabelForNotStepAligned':
					try {
						$this->strLabelForNotStepAligned = QType::Cast($mixValue, QType::String);
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
	}

?>