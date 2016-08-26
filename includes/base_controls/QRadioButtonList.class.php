<?php
	/**
	 * This file contains the QRadioButtonList class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render a List of HTML Radio Buttons (inhereting from ListControl).
	 * By definition, radio button lists are single-select ListControls.
	 *
	 * So assuming you have a list of 10 items, and you have RepeatColumn set to 3:
	 *
	 *	RepeatDirection::Horizontal would render as:
	 *	1	2	3
	 *	4	5	6
	 *	7	8	9
	 *	10
	 *
	 *	RepeatDirection::Vertical would render as:
	 *	1	5	8
	 *	2	6	9
	 *	3	7	10
	 *	4
	 *
	 * @package Controls
	 *
	 * @property string $TextAlign specifies if each ListItem's Name should be displayed to the left or to the right of the radio button.
	 * @property boolean $HtmlEntities
	 * @property integer $CellPadding specified the HTML Table's CellPadding
	 * @property integer $CellSpacing specified the HTML Table's CellSpacing
	 * @property integer $RepeatColumns specifies how many columns should be rendered in the HTML Table
	 * @property string $RepeatDirection specifies which direction should the list go first: horizontal or vertical
	 * @property integer $ButtonMode specifies how to render buttons
	 */
	class QRadioButtonList extends QListControl {
		const ButtonModeNone = 0;
		const ButtonModeJq = 1;
		const ButtonModeSet = 2;
		const ButtonModeList = 3;	// just a vanilla list of radio buttons with no row or column styling

		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strTextAlign = QTextAlign::Right;

		/** @var  string The class to use when wrapping a button-label group */
		protected $strButtonGroupClass;

		// BEHAVIOR
		protected $blnHtmlEntities = true;

		// LAYOUT
		protected $intCellPadding = -1;
		protected $intCellSpacing = -1;
		protected $intRepeatColumns = 1;
		protected $strRepeatDirection = QRepeatDirection::Vertical;
		protected $objItemStyle = null;
		protected $intButtonMode;
		protected $strMaxHeight; // will create a scroll pane if height is exceeded

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->objItemStyle = new QListItemStyle();
		}

		//////////
		// Methods
		//////////
		public function ParsePostData() {
			$val = $this->objForm->CheckableControlValue($this->strControlId);
			if ($val === null) {
				$this->UnselectAllItems(false);
			} else {
				$this->SetSelectedItemsByIndex(array($val), false);
			}
		}

		public function GetEndScript() {
			$ctrlId = $this->ControlId;
			if ($this->intButtonMode == self::ButtonModeSet) {
				QApplication::ExecuteControlCommand($ctrlId, 'buttonset', QJsPriority::High);
			} elseif ($this->intButtonMode == self::ButtonModeJq) {
				QApplication::ExecuteSelectorFunction(["input:radio", "#" . $ctrlId], 'button', QJsPriority::High);
			}
			$strScript = parent::GetEndScript();
			return $strScript;
		}

		protected function GetItemHtml($objItem, $intIndex, $strTabIndex, $blnWrapLabel) {
			$objLabelStyles = new QTagStyler();
			if ($this->objItemStyle) {
				$objLabelStyles->Override($this->objItemStyle); // default style
			}
			if ($objItemStyle = $objItem->ItemStyle) {
				$objLabelStyles->Override($objItemStyle); // per item styling
			}

			$objStyles = new QTagStyler();
			$objStyles->SetHtmlAttribute('type', 'radio');
			$objStyles->SetHtmlAttribute('value', $intIndex);
			$objStyles->SetHtmlAttribute('name', $this->strControlId);
			$strIndexedId = $this->strControlId . '_' . $intIndex;
			$objStyles->SetHtmlAttribute('id', $strIndexedId);

			if ($strTabIndex) {
				$objStyles->TabIndex = $strTabIndex;	// Use parent control tabIndex, which will cause the browser to take them in order of drawing
			}
			if (!$this->Enabled) {
				$objStyles->Enabled = false;
			}

			$strLabelText = $this->GetLabelText($objItem);

			if ($objItem->Selected) {
				$objStyles->SetHtmlAttribute('checked', 'checked');
			}

			$objStyles->SetHtmlAttribute("autocomplete", "off"); // recommended bugfix for firefox in certain situations

			if (!$blnWrapLabel) {
				$objLabelStyles->SetHtmlAttribute('for', $strIndexedId);
			}

			$this->OverrideItemAttributes($objItem, $objStyles, $objLabelStyles);

			$strHtml = QHtml::RenderLabeledInput(
				$strLabelText,
				$this->strTextAlign == QTextAlign::Left,
				$objStyles->RenderHtmlAttributes(),
				$objLabelStyles->RenderHtmlAttributes(),
				$blnWrapLabel);

			return $strHtml;
		}

		/**
		 * Provides a way for subclasses to override the attributes on specific items just before they are drawn.
		 *
		 * @param $objItem
		 * @param $objItemAttributes
		 * @param $objLabelAttributes
		 */
		protected function OverrideItemAttributes ($objItem, QTagStyler $objItemAttributes, QTagStyler $objLabelAttributes) {}

		/**
		 * Return the escaped text of the label.
		 *
		 * @param $objItem
		 * @return string
		 */
		protected function GetLabelText ($objItem) {
			$strLabelText = $objItem->Label;
			if (empty($strLabelText)) {
				$strLabelText = $objItem->Name;
			}
			if ($this->blnHtmlEntities) {
				$strLabelText = QApplication::HtmlEntities($strLabelText);
			}
			return $strLabelText;
		}

		protected function GetControlHtml() {
			$intItemCount = $this->GetItemCount();
			if (!$intItemCount) return '';

			if ($this->intButtonMode == self::ButtonModeSet || $this->intButtonMode == self::ButtonModeList) {
				return $this->RenderButtonSet();
			}
			elseif ($this->intRepeatColumns == 1) {
				$strToReturn = $this->RenderButtonColumn();
			}
			else {
				$strToReturn = $this->RenderButtonTable();
			}

			if ($this->strMaxHeight) {
				$objStyler = new QTagStyler();
				$objStyler->SetCssStyle('max-height', $this->strMaxHeight, true);
				$objStyler->SetCssStyle('overflow-y', 'scroll');

				$strToReturn = QHtml::RenderTag('div', $objStyler->RenderHtmlAttributes(), $strToReturn);
			}
			return $strToReturn;

		}

		/**
		 * Renders the button group as a table, paying attention to the number of columns wanted.
		 * @return string
		 */
		public function RenderButtonTable() {
			// TODO: Do this without using a table, since this is really not a correct use of html
			$strToReturn = '';
			if ($this->ItemCount > 0) {
				// Figure out the number of ROWS for this table
				$intRowCount = floor($this->ItemCount / $this->intRepeatColumns);
				$intWidowCount = ($this->ItemCount % $this->intRepeatColumns);
				if ($intWidowCount > 0)
					$intRowCount++;

				// Iterate through Table Rows
				for ($intRowIndex = 0; $intRowIndex < $intRowCount; $intRowIndex++) {
					// Figure out the number of COLUMNS for this particular ROW
					if (($intRowIndex == $intRowCount - 1) && ($intWidowCount > 0))
						// on the last row for a table with widowed-columns, ColCount is the number of widows
						$intColCount = $intWidowCount;
					else
						// otherwise, ColCount is simply intRepeatColumns
						$intColCount = $this->intRepeatColumns;

					// Iterate through Table Columns
					$strRowHtml = '';
					for ($intColIndex = 0; $intColIndex < $intColCount; $intColIndex++) {
						if ($this->strRepeatDirection == QRepeatDirection::Horizontal)
							$intIndex = $intColIndex + $this->intRepeatColumns * $intRowIndex;
						else
							$intIndex = (floor($this->ItemCount / $this->intRepeatColumns) * $intColIndex)
								+ min(($this->ItemCount % $this->intRepeatColumns), $intColIndex)
								+ $intRowIndex;

						$strItemHtml = $this->GetItemHtml($this->GetItem($intIndex), $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel);
						$strCellHtml = QHtml::RenderTag ('td', null, $strItemHtml);
						$strRowHtml .= $strCellHtml;
					}

					$strRowHtml = QHtml::RenderTag('tr', null, $strRowHtml);
					$strToReturn .= $strRowHtml;
				}
			}

			return $this->RenderTag('table',
				null,
				null,
				$strToReturn);
		}

		/**
		 * Renders the checkbox list as a buttonset, rendering just as a list of checkboxes and allowing css or javascript
		 * to format the rest.
		 * @return string
		 */
		public function RenderButtonSet() {
			$count = $this->ItemCount;
			$strToReturn = '';
			for ($intIndex = 0; $intIndex < $count; $intIndex++) {
				$strToReturn .= $this->GetItemHtml($this->GetItem($intIndex), $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel) . "\n";
			}
			return $this->RenderTag('div',
				null,
				null,
				$strToReturn);
		}

		/**
		 * Render as a single column. This implementation simply wraps the rows in divs.
		 * @return string
		 */
		public function RenderButtonColumn() {
			$count = $this->ItemCount;
			$strToReturn = '';
			$groupAttributes = null;
			if ($this->strButtonGroupClass) {
				$groupAttributes = ["class"=>$this->strButtonGroupClass];
			}
			for ($intIndex = 0; $intIndex < $count; $intIndex++) {
				$strHtml = $this->GetItemHtml($this->GetItem($intIndex), $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel);
				$strToReturn .= QHtml::RenderTag('div', $groupAttributes, $strHtml);
			}
			return $this->RenderTag('div',
				null,
				null,
				$strToReturn);
		}

		public function Validate() {
			if ($this->blnRequired) {
				if ($this->SelectedIndex == -1) {
					$this->ValidationError = sprintf(QApplication::Translate('%s is required'), $this->strName);
					return false;
				}
			}

			return true;
		}

		/**
		 * Override of superclass that will update the selection using javascript so that the whole control does
		 * not need to be redrawn.
		 */
		protected function RefreshSelection() {
			$index = $this->SelectedIndex;
			QApplication::ExecuteSelectorFunction(['input', '#' . $this->ControlId], 'val', [$index]);
			if ($this->intButtonMode == self::ButtonModeSet ||
					$this->intButtonMode == self::ButtonModeJq) {
				QApplication::ExecuteSelectorFunction(['input', '#' . $this->ControlId], 'button', "refresh");
			}
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "TextAlign": return $this->strTextAlign;

				// BEHAVIOR
				case "HtmlEntities": return $this->blnHtmlEntities;

				// LAYOUT
				case "CellPadding": return $this->intCellPadding;
				case "CellSpacing": return $this->intCellSpacing;
				case "RepeatColumns": return $this->intRepeatColumns;
				case "RepeatDirection": return $this->strRepeatDirection;
				case "ItemStyle": return $this->objItemStyle;
				case "ButtonMode": return $this->intButtonMode;
				
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
			switch ($strName) {
				// APPEARANCE
				case "TextAlign":
					try {
						if ($this->strTextAlign !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->blnModified = true;
							$this->strTextAlign = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HtmlEntities":
					try {
						if ($this->blnHtmlEntities !== ($mixValue = QType::Cast($mixValue, QType::Boolean))) {
							$this->blnModified = true;
							$this->blnHtmlEntities = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// LAYOUT
				case "CellPadding":
					try {
						$this->intCellPadding = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CellSpacing":
					try {
						$this->intCellSpacing = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "RepeatColumns":
					try {
						$this->intRepeatColumns = QType::Cast($mixValue, QType::Integer);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					if ($this->intRepeatColumns < 1)
						throw new QCallerException("RepeatColumns must be greater than 0");
					break;
				case "RepeatDirection":
					try {
						$this->strRepeatDirection = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemStyle":
					try {
						$this->objItemStyle = QType::Cast($mixValue, "QListItemStyle");
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case "ButtonMode":
					try {
						$this->intButtonMode = QType::Cast($mixValue, QType::Integer);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

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
				new QModelConnectorParam (get_called_class(), 'TextAlign', '', QModelConnectorParam::SelectionList,
					array (null=>'Default',
						'QTextAlign::Left'=>'Left',
						'QTextAlign::Right'=>'Right'
					)),
				new QModelConnectorParam (get_called_class(), 'HtmlEntities', 'Set to false to have the browser interpret the labels as HTML', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'RepeatColumns', 'The number of columns of checkboxes to display', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'RepeatDirection', 'Whether to repeat horizontally or vertically', QModelConnectorParam::SelectionList,
					array (null=>'Default',
						'QRepeatDirection::Horizontal'=>'Horizontal',
						'QRepeatDirection::Vertical'=>'Vertical'
					)),
				new QModelConnectorParam (get_called_class(), 'ButtonMode', 'How to display the buttons', QModelConnectorParam::SelectionList,
					array (null=>'Default',
						'QRadioButtonList::ButtonModeJq'=>'JQuery UI Buttons',
						'QRadioButtonList::ButtonModeSet'=>'JQuery UI Buttonset'
					)),
				new QModelConnectorParam (get_called_class(), 'MaxHeight', 'If set, will wrap it in a scrollable pane with the given max height', QType::Integer)
			));
		}

	}