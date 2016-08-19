<?php
	/**
	 * This file contains the QCheckBox class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render an HTML Checkbox.
	 *
	 * Labels are a little tricky with checkboxes. There are two built-in ways to make labels:
	 * 1) Assign a Name property, and render using something like RenderWithName
	 * 2) Assign a Text property, in which case the checkbox will be wrapped with a label and the text you assign.
	 *
	 * @package Controls
	 *
	 * @property string $Text is used to display text that is displayed next to the checkbox.  The text is rendered as an html "Label For" the checkbox.
	 * @property string $TextAlign specifies if "Text" should be displayed to the left or to the right of the checkbox.
	 * @property boolean $Checked specifices whether or not hte checkbox is checked
	 * @property boolean $HtmlEntities specifies whether the checkbox text will have to be run through htmlentities or not.
	 */
	class QCheckBox extends QControl {
		/** @var string Tag for rendering the control */
		protected $strTag = 'input';
		protected $blnIsVoidElement = true;

		// APPEARANCE
		/** @var string Text opposite to the checkbox */
		protected $strText = null;
		/** @var QTextAlign|string the alignment of the string */
		protected $strTextAlign = QTextAlign::Right;
		
		// BEHAVIOR
		/** @var bool Should the htmlentities function be run on the control's text (strText)? */
		protected $blnHtmlEntities = true;

		// MISC
		/** @var bool Determines whether the checkbox is checked? */
		protected $blnChecked = false;

		/**
		 * @var  QTagStyler for labels of checkboxes. If side-by-side labeling, the styles will be applied to a
		 * span that wraps both the checkbox and the label.
		 */
		protected $objLabelStyle;


		//////////
		// Methods
		//////////

		/**
		 * Parses the Post Data submitted for the control and sets the values
		 * according to the data submitted
		 */
		public function ParsePostData() {
			$val = $this->objForm->CheckableControlValue($this->strControlId);
			if ($val !== null) {
				$this->blnChecked = QType::Cast($val, QType::Boolean);
			}
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
			$attrOverride = array('type'=>'checkbox', 'name'=>$this->strControlId, 'value'=>'true');
			return $this->RenderButton($attrOverride);
		}

		/**
		 * Render the button code. Broken out to allow QRadioButton to use it too.
		 *
		 * @param $attrOverride
		 * @return string
		 */
		protected function RenderButton ($attrOverride) {
			if ($this->blnChecked) {
				$attrOverride['checked']='checked';
			}

			if (strlen($this->strText)) {
				$strText = ($this->blnHtmlEntities) ? QApplication::HtmlEntities($this->strText) : $this->strText;
				if (!$this->blnWrapLabel) {
					$strLabelAttributes = ' for="' . $this->strControlId .'"';
				} else {
					$strLabelAttributes = $this->RenderLabelAttributes();
				}
				$strCheckHtml = QHtml::RenderLabeledInput(
					$strText,
					$this->strTextAlign == QTextAlign::Left,
					$this->RenderHtmlAttributes($attrOverride),
					$strLabelAttributes,
					$this->blnWrapLabel
				);
				if (!$this->blnWrapLabel) {
					// Additionally wrap in a span so we can associate the label with the checkbox visually and apply the styles
					$strCheckHtml = QHtml::RenderTag('span',  $this->RenderLabelAttributes(), $strCheckHtml);
				}
			}
			else {
				$strCheckHtml = $this->RenderTag('input', $attrOverride, null, null, true);
			}
			return $strCheckHtml;
		}

		/**
		 * Return a styler to style the label that surrounds the control if the control has text.
		 * @return QTagStyler
		 */
		public function getCheckLabelStyler() {
			if (!$this->objLabelStyle) {
				$this->objLabelStyle = new QTagStyler();
			}
			return $this->objLabelStyle;
		}

		/**
		 * There is a little bit of a conundrum here. If there is text assigned to the checkbox, we wrap
		 * the checkbox in a label. However, in this situation, its unclear what to do with the class and style
		 * attributes that are for the checkbox. We are going to let the developer use the label styler to make
		 * it clear what their intentions are.
		 * @return string
		 */
		protected function RenderLabelAttributes() {
			$objStyler = new QTagStyler();
			$attributes = $this->GetHtmlAttributes(null, null, ['title']); // copy tooltip to wrapping label
			$objStyler->SetAttributes($attributes);
			$objStyler->Override($this->getCheckLabelStyler());

			if (!$this->Enabled) {
				$objStyler->AddCssClass('disabled');	// add the disabled class to the label for styling
			}
			if (!$this->Display) {
				$objStyler->Display = false;
			}
			return $objStyler->RenderHtmlAttributes();
		}

		/**
		 * Checks whether the post data submitted for the control is valid or not
		 * Right now it tests whether or not the control was marked as required and then tests whether it
		 * was checked or not
		 * @return bool
		 */
		public function Validate() {
			if ($this->blnRequired) {
				if (!$this->blnChecked) {
					if ($this->strName)
						$this->ValidationError = QApplication::Translate($this->strName) . ' ' . QApplication::Translate('is required');
					else
						$this->ValidationError = QApplication::Translate('Required');
					return false;
				}
			}
			return true;
		}

		/**
		 * Returns the current state of the control to be able to restore it later.
		 */
		public function GetState(){
			return array('checked'=>$this->Checked);
		}

		/**
		 * Restore the  state of the control.
		 *
		 * @param mixed $state
		 */
		public function PutState($state) {
			if (isset($state['checked'])) {
				$this->Checked = $state['checked'];
			}
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Name of the property
		 *
		 * @return mixed
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "TextAlign": return $this->strTextAlign;

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
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed
		 * @throws QInvalidCastException|QCallerException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				// APPEARANCE

				case "Text":
					try {
						$val = QType::Cast($mixValue, QType::String);
						if ($val !== $this->strText) {
							$this->strText = $val;
							$this->blnModified = true;
						}
						return $this->strText;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "TextAlign":
					try {
						$val = QType::Cast($mixValue, QType::String);
						if ($val !== $this->strTextAlign) {
							$this->strTextAlign = $val;
							$this->blnModified = true;
						}
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

				// MISC
				case "Checked":
					try {
						$val = QType::Cast($mixValue, QType::Boolean);
						if ($val != $this->blnChecked) {
							$this->blnChecked = $val;
							$this->AddAttributeScript('prop', 'checked', $val);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// Copy certain attributes to the label styler when assigned since its part of the control.
				case 'CssClass':
					try {
						parent::__set($strName, $mixValue);
						$this->getCheckLabelStyler()->CssClass = $mixValue; // assign to both checkbox and label so they can be styled together using css
						$this->blnModified = true;
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

		/**
		 * Returns an description of the options available to modify by the designer for the code generator.
		 *
		 * @return QModelConnectorParam[]
		 */
		public static function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Text', 'Label on checkbox', QType::String),
				new QModelConnectorParam (get_called_class(), 'TextAlign', 'Left or right alignment of label', QModelConnectorParam::SelectionList,
					array ('QTextAlign::Right'=>'QTextAlign::Right',
						'QTextAlign::Left'=>'QTextAlign::Left'
					)),
				new QModelConnectorParam (get_called_class(), 'HtmlEntities', 'Whether to apply HTML entities on the label', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'CssClass', 'The css class(es) to apply to the checkbox and label together', QType::String)
			));
		}


	}