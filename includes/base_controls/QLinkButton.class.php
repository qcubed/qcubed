<?php
	/**
	 * This file contains the QLinkButton class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render an HTML link <a href>, but will act like a Button or ImageButton.
	 * (it is a subclass of actioncontrol)
	 * Therefore, you cannot define a "URL/HREF" destination for this LinkButton.  It simply links
	 * to "#".  And then if a ClientAction is defined, it will execute that when clicked.  If a ServerAction
	 * is defined, it will execute PostBack and execute that when clicked.
	 *
	 * @package Controls
	 *
	 * @property string $Text is the text of the Link
	 * @property string $HtmlEntities
	 */
	class QLinkButton extends QActionControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		/** @var string|null The text on the button */
		protected $strText = null;
		/** @var bool Should htmlentities be used on this control? */
		protected $blnHtmlEntities = true;

		//////////
		// Methods
		//////////
		/**
		 * Function to return the formatted HTML for the control
		 * @return string The control's HTML
		 */
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			$strToReturn = sprintf('<a href="#" id="%s" %s%s>%s</a>',
				$this->strControlId,
				$this->GetAttributes(),
				$strStyle,
				($this->blnHtmlEntities) ? 
					QApplication::HtmlEntities($this->strText) :
					$this->strText);

			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * The PHP __get magic method
		 * @param string $strName Name of the property
		 *
		 * @return array|bool|int|mixed|null|QControl|QForm|string
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "HtmlEntities": return $this->blnHtmlEntities;
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
		 * The PHP __set megic method implementation
		 * @param string $strName Name of the property
		 * @param string $mixValue Value of the property
		 *
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