<?php
	/**
	 * This file contains the QRadioButton class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render an HTML Radio button.
	 *
	 * @package Controls
	 *
	 * @property string $Text is used to display text that is displayed next to the radio.  The text is rendered as an html "Label For" the radio
	 * @property string $TextAlign specifies if "Text" should be displayed to the left or to the right of the radio.
	 * @property string $GroupName assigns the radio button into a radio button group (optional) so that no more than one radio in that group may be selected at a time.
	 * @property boolean $HtmlEntities
	 * @property boolean $Checked specifices whether or not the radio is selected
	 */
	class QRadioButton extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		/** @var null|string the text next to the radio button  */
		protected $strText = null;
		/** @var string Text alignment  */
		protected $strTextAlign = QTextAlign::Right;

		// BEHAVIOR
		/**
		 * Group to which this radio button belongs
		 * Groups determine the 'radio' behavior wherein you can select only one option out of all buttons in that group
		 * @var null|string Name of the group
		 */
		protected $strGroupName = null;
		/** @var bool Should htmlentities be run on the contents of the control */
		protected $blnHtmlEntities = true;

		// MISC
		/** @var bool Variable to decide whether the button checked/selected */
		protected $blnChecked = false;

		//////////
		// Methods
		//////////
		/**
		 * Parse the data posted
		 */
		public function ParsePostData() {
			if (QApplication::$RequestMode == QRequestMode::Ajax) {
				$this->blnChecked = QType::Cast ($_POST[$this->strControlId], QType::Boolean);
			}
			elseif ($this->objForm->IsCheckableControlRendered($this->strControlId)) {
				if ($this->strGroupName)
					$strName = $this->strGroupName;
				else
					$strName = $this->strControlId;

				if (array_key_exists($strName, $_POST)) {
					if ($_POST[$strName] == $this->strControlId)
						$this->blnChecked = true;
					else
						$this->blnChecked = false;
				} else {
					$this->blnChecked = false;
				}
			}
		}

		/**
		 * Returns the html formatted string
		 * @return string HTML formatted string
		 */
		protected function GetControlHtml() {
			if (!$this->blnEnabled)
				$strDisabled = 'disabled="disabled" ';
			else
				$strDisabled = "";

			if ($this->intTabIndex)
				$strTabIndex = sprintf('tabindex="%s" ', $this->intTabIndex);
			else
				$strTabIndex = "";

			if ($this->strToolTip)
				$strToolTip = sprintf('title="%s" ', $this->strToolTip);
			else
				$strToolTip = "";

			if ($this->strCssClass)
				$strCssClass = sprintf('class="%s" ', $this->strCssClass);
			else
				$strCssClass = "";

			if ($this->strAccessKey)
				$strAccessKey = sprintf('accesskey="%s" ', $this->strAccessKey);
			else
				$strAccessKey = "";

			if ($this->blnChecked)
				$strChecked = 'checked="checked"';
			else
				$strChecked = "";

			if ($this->strGroupName)
				$strGroupName = $this->strGroupName;
			else
				$strGroupName = $this->strControlId;

			$strStyle = $this->GetStyleAttributes();
			if (strlen($strStyle) > 0)
				$strStyle = sprintf('style="%s" ', $strStyle);

			$strCustomAttributes = $this->GetCustomAttributes();

			if (strlen($this->strText)) {
				$this->blnIsBlockElement = true;
				if ($this->strTextAlign == QTextAlign::Left) {
					$strToReturn = sprintf('<span %s%s%s%s%s><label for="%s">%s</label><input type="radio" id="%s" name="%s" value="%s" %s%s%s%s /></span>',
						$strCssClass,
						$strToolTip,
						$strStyle,
						$strCustomAttributes,
						$strDisabled,

						$this->strControlId,
						($this->blnHtmlEntities) ? QApplication::HtmlEntities($this->strText) : $this->strText,

						$this->strControlId,
						$strGroupName,
						$this->strControlId,

						$strDisabled,
						$strChecked,
						$strAccessKey,
						$strTabIndex
					);
				} else {
					$strToReturn = sprintf('<span %s%s%s%s%s><input type="radio" id="%s" name="%s" value="%s" %s%s%s%s /><label for="%s">%s</label></span>',
						$strCssClass,
						$strToolTip,
						$strStyle,
						$strCustomAttributes,
						$strDisabled,

						$this->strControlId,
						$strGroupName,
						$this->strControlId,

						$strDisabled,
						$strChecked,
						$strAccessKey,
						$strTabIndex,

						$this->strControlId,
						($this->blnHtmlEntities) ? QApplication::HtmlEntities($this->strText) : $this->strText
					);
				}
			} else {
				$this->blnIsBlockElement = false;
				$strToReturn = sprintf('<input type="radio" id="%s" name="%s" value="%s" %s%s%s%s%s%s%s%s />',
					$this->strControlId,
					$strGroupName,
					$this->strControlId,
					$strCssClass,
					$strDisabled,
					$strChecked,
					$strAccessKey,
					$strToolTip,
					$strTabIndex,
					$strCustomAttributes,
					$strStyle);
			}

			return $strToReturn;
		}

		/**
		 * Send end script to detect the change on the control before other actions.
		 * @return string
		 */
		public function GetEndScript() {
			$str = parent::GetEndScript();
			$str = sprintf ('$j("#%s").change(qc.formObjChanged);', $this->ControlId) . $str;
			return $str;
		}


		public function Validate() {
			if ($this->blnRequired) {
				if (!$this->blnChecked) {
					$this->strValidationError = sprintf(QApplication::Translate('%s is required'), $this->strName);
					return false;
				}
			}

			$this->strValidationError = null;
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation for the QRadioButton class
		 * @param string $strName Name of the property
		 *
		 * @return array|bool|int|mixed|null|QControl|QForm|string
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "TextAlign": return $this->strTextAlign;

				// APPEARANCE
				case "GroupName": return $this->strGroupName;

				// BEHAVIOR
				case "HtmlEntities": return $this->blnHtmlEntities;

				// MISC
				case "Checked": return $this->blnChecked;

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
				case "TextAlign":
					try {
						$this->strTextAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// BEHAVIOR
				case "HtmlEntities":
					try {
						$this->blnHtmlEntities = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "GroupName":
					try {
						$this->strGroupName = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// MISC
				case "Checked":
					try {
						$this->blnChecked = QType::Cast($mixValue, QType::Boolean);
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