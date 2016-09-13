<?php
	/**
	 * This file contains the QControlLabel class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render a 'label' HTML element
	 *
	 * @package                             Controls
	 * @property string      $Text         Text to be shown for the label
	 * @property string      $ForControlId The control ID 'for' which the label is being created
	 * @property-write mixed $ForControl   QControl instance which can be supplied to an instance of QControlLabel
	 *                                      to set it as the target control for which the label will be created
	 */
	class QControlLabel extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		/** @var null|string The text for the label (which is going to be visible on screen) */
		protected $strText = null;

		// BEHAVIOR
		/** @var string The control ID of the control for which this label will be created */
		protected $strForControlId;

		//////////
		// Methods
		//////////
		public function ParsePostData() {
		}

		/**
		 * Return the HTML for the control to be rendered
		 *
		 * @return string The HTML for the control
		 */
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle) {
				$strStyle = sprintf('style="%s"', $strStyle);
			}

			$strToReturn = sprintf('<label id="%s" for="%s" %s %s>%s</label>',
				$this->strControlId,
				$this->strForControlId,
				$this->RenderHtmlAttributes(),
				$strStyle,
				$this->strText);

			return $strToReturn;
		}

		/**
		 * Validates the control. For now, it only returns true
		 *
		 * @return bool
		 */
		public function Validate() {
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method to get the value of properties in the class
		 *
		 * @param string $strName Name of the property whose value we have to get
		 *
		 * @return mixed|null|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text":
					return $this->strText;
				case "ForControlId":
					return $this->strForControlId;

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
		 * PHP magic methof to set value of properties in the class
		 *
		 * @param string $strName  Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @return mixed|void
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "Text":
					try {
						$this->strText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ForControlId":
					try {
						$this->strForControlId = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "ForControl":
					try {
						$objControl = QType::Cast($mixValue, 'QControl');
						$this->strForControlId = QType::Cast($objControl->ControlId, QType::String);
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