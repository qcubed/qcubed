<?php
	/**
	 * This file contains the QRadioButton class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render an HTML Radio button.
	 *
	 * Based on a QCheckbox, which is very similar to a checkbox.
	 *
	 * @package Controls
	 *
	 * @property string $Text is used to display text that is displayed next to the radio. The text is rendered as an html "Label For" the radio
	 * @property string $TextAlign specifies if "Text" should be displayed to the left or to the right of the radio.
	 * @property string $GroupName assigns the radio button into a radio button group (optional) so that no more than one radio in that group may be selected at a time.
	 * @property boolean $HtmlEntities
	 * @property boolean $Checked specifices whether or not the radio is selected
	 */
	class QRadioButton extends QCheckBox {
		/**
		 * Group to which this radio button belongs
		 * Groups determine the 'radio' behavior wherein you can select only one option out of all buttons in that group
		 * @var null|string Name of the group
		 */
		protected $strGroupName = null;

		/**
		 * Parse the data posted
		 */
		public function ParsePostData() {
			$val = $this->objForm->CheckableControlValue($this->strControlId);
			$val = QType::Cast($val, QType::Boolean);
			$this->blnChecked = !empty($val);
		}

		/**
		 * Returns the HTML code for the control which can be sent to the client.
		 *
		 * Note, previous version wrapped this in a div and made the control a block level control unnecessarily. To
		 * achieve a block control, set blnUseWrapper and blnIsBlockElement.
		 *
		 * @return string THe HTML for the control
		 */
		protected function GetControlHtml() {
			if ($this->strGroupName)
				$strGroupName = $this->strGroupName;
			else
				$strGroupName = $this->strControlId;

			$attrOverride = array('type'=>'radio', 'name'=>$strGroupName, 'value'=>$this->strControlId);
			return $this->RenderButton($attrOverride);
		}

		/**
		 * Returns the current state of the control to be able to restore it later.
		 * @return mixed
		 */
		public function GetState(){
			return array('Checked'=>$this->Checked);
		}

		/**
		 * Restore the state of the control.
		 * @param mixed $state Previously saved state as returned by GetState above.
		 */
		public function PutState($state) {
			if (isset($state['Checked'])) {
				$this->SelectedValues = $state['Checked'];
			}
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
				case "GroupName": return $this->strGroupName;

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
		 *
		 * @param string $strName  Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @return mixed
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "GroupName":
					try {
						$strGroupName = QType::Cast($mixValue, QType::String);
						if ($this->strGroupName != $strGroupName) {
							$this->strGroupName = $strGroupName;
							$this->blnModified = true;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Checked":
					try {
						$val = QType::Cast($mixValue, QType::Boolean);
						if ($val != $this->blnChecked) {
							$this->blnChecked = $val;
							if ($this->GroupName && $val == true) {
								QApplication::ExecuteJsFunction('qcubed.setRadioInGroup', $this->strControlId);
							} else {
								$this->AddAttributeScript('prop', 'checked', $val); // just set the one radio
							}
						}
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