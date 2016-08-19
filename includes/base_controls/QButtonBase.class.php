<?php
	/**
	 * This file contains the QButtonBase class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render an HTML Button.
	 *
	 * @package Controls
	 *
	 * @property string $Text is used to display the button's text
	 * @property boolean $PrimaryButton is a boolean to specify whether or not the button is 'primary' (e.g. makes this button a "Submit" form element rather than a "Button" form element)
	 * @property boolean $HtmlEntities
	 */
	abstract class QButtonBase extends QActionControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		/** @var string Text on the button  */
		protected $strText = null;
		/** @var bool Whether or not to use Htmlentities for the control */
		protected $blnHtmlEntities = true;

		// BEHAVIOR
		/** @var bool Is the button a primary button (causes form submission)? */
		protected $blnPrimaryButton = false;

		// SETTINGS
		/**
		 * @var bool Prevent any more actions from happening once action has been taken on this control
		 *  causes "event.preventDefault()" to be called on the client side
		 */
		protected $blnActionsMustTerminate = true;

		//////////
		// Methods
		//////////
		/**
		 * Return the HTML string for the control
		 * @return string The HTML string of the control
		 */
		protected function GetControlHtml() {
			if ($this->blnPrimaryButton) {
				$attrOverride['type'] = "submit";
			}
			else {
				$attrOverride['type'] = "button";
			}
			$attrOverride['name'] = $this->strControlId;
			$strInnerHtml = $this->GetInnerHtml();

			return $this->RenderTag('button', $attrOverride, null, $strInnerHtml);
		}

		/**
		 * Returns the html to appear between the button tags.
		 * @return string
		 */
		protected function GetInnerHtml() {
			return  ($this->blnHtmlEntities) ? QApplication::HtmlEntities($this->strText) : $this->strText;
		}



		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP Magic __get method implementation
		 * @param string $strName Name of the property to be fetched
		 *
		 * @return array|bool|int|mixed|null|QControl|QForm|string
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "HtmlEntities": return $this->blnHtmlEntities;

				// BEHAVIOR
				case "PrimaryButton": return $this->blnPrimaryButton;

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
		 * PHP Magic method __set implementation for this class (QButtonBase)
		 * @param string $strName Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 * @throws Exception|QInvalidCastException
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

				case "HtmlEntities":
					try {
						$this->blnHtmlEntities = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// BEHAVIOR
				case "PrimaryButton":
					try {
						$this->blnPrimaryButton = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}