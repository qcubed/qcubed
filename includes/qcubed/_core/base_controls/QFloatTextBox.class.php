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
	 * @package Controls
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
	}

?>