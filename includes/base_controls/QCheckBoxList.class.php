<?php
	/**
	 * This file contains the QCheckBoxList class.
	 *
	 * @package Controls
	 */

	/**
	 * This class will render a List of HTML Checkboxes (inhereting from ListControl).
	 * By definition, checkbox lists are multiple-select ListControls.
	 *
	 * So assuming you have a list of 10 items, and you have RepeatColumn set to 3:
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
	 * @property string $Text is used to display text that is displayed next to the checkbox.  The text is rendered as an html "Label For" the checkbox.
	 * @property integer $CellPadding specified the HTML Table's CellPadding
	 * @property integer $CellSpacing specified the HTML Table's CellSpacing
	 * @property integer $RepeatColumns specifies how many columns should be rendered in the HTML Table
	 * @property string $RepeatDirection pecifies which direction should the list go first...
	 * @property boolean $HtmlEntities
	 */
	class QCheckBoxList extends QListControl {
		
		const ButtonModeNone = 0;	// Uses the RepeatColumns and RepeateDirection settings to make a structure
		const ButtonModeJq = 1;		// a list of individual jquery ui buttons
		const ButtonModeSet = 2;	// a jqueryui button set
		const ButtonModeList = 3;	// just a vanilla list of checkboxes with no row or column styling

		///////////////////////////
		// Private Member Variables
		///////////////////////////
		
		// APPEARANCE
		protected $strTextAlign = QTextAlign::Right;
		
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
		}

		//////////
		// Methods
		//////////
		/**
		 * Parses the post data. Many different scenarios are covered. See below.
		 */
		public function ParsePostData() {
			$val = $this->objForm->CheckableControlValue($this->strControlId);
			if (empty($val)) {
				$this->UnselectAllItems(false);
			}
			else {
				$this->SetSelectedItemsByIndex($val, false);
			}
		}

		/**
		 * Return the javascript associated with the control.
		 *
		 * @return string
		 */
		public function GetEndScript() {
			$ctrlId = $this->ControlId;
			if ($this->intButtonMode == self::ButtonModeSet) {
				QApplication::ExecuteControlCommand($ctrlId, 'buttonset', QJsPriority::High);
			} elseif ($this->intButtonMode == self::ButtonModeJq) {
				QApplication::ExecuteSelectorFunction(["input:checkbox", "#" . $ctrlId], 'button', QJsPriority::High);
			}
			$strScript = parent::GetEndScript();
			return $strScript;
		}

		/**
		 * Return the HTML for the given item.
		 *
		 * @param QListItem $objItem
		 * @param integer $intIndex
		 * @param string $strTabIndex
		 * @param boolean $blnWrapLabel
		 * @return string
		 */
		protected function GetItemHtml(QListItem $objItem, $intIndex, $strTabIndex, $blnWrapLabel) {
			$objLabelStyles = new QTagStyler();
			if ($this->objItemStyle) {
				$objLabelStyles->Override($this->objItemStyle); // default style
			}
			if ($objItemStyle = $objItem->ItemStyle) {
				$objLabelStyles->Override($objItemStyle); // per item styling
			}

			$objStyles = new QTagStyler();
			$objStyles->SetHtmlAttribute('type', 'checkbox');
			$objStyles->SetHtmlAttribute('name', $this->strControlId . '[]');
			$objStyles->SetHtmlAttribute('value', $intIndex);

			$strIndexedId = $objItem->Id;
			$objStyles->SetHtmlAttribute('id', $strIndexedId);
			if ($strTabIndex) {
				$objStyles->TabIndex = $strTabIndex;
			}
			if (!$this->Enabled) {
				$objStyles->Enabled = false;
			}

			$strLabelText = $objItem->Label;
			if (empty($strLabelText)) {
				$strLabelText = $objItem->Name;
			}
			if ($this->blnHtmlEntities) {
				$strLabelText = QApplication::HtmlEntities($strLabelText);
			}

			if ($objItem->Selected) {
				$objStyles->SetHtmlAttribute('checked', 'checked');
			}

			if (!$blnWrapLabel) {
				$objLabelStyles->SetHtmlAttribute('for', $strIndexedId);
			}

			$objStyles->AddCssClass('qc-tableCell');
			$objLabelStyles->AddCssClass('qc-tableCell');

			$strHtml = QHtml::RenderLabeledInput(
				$strLabelText,
				$this->strTextAlign == QTextAlign::Left,
				$objStyles->RenderHtmlAttributes(),
				$objLabelStyles->RenderHtmlAttributes(),
				$blnWrapLabel);

			return $strHtml;
		}

		/**
		 * Return the html to draw the base control.
		 * @return string
		 */
		protected function GetControlHtml() {
			/* Deprecated. Use Margin and Padding on the ItemStyle attribute.
			if ($this->intCellPadding >= 0)
				$strCellPadding = sprintf('cellpadding="%s" ', $this->intCellPadding);
			else
				$strCellPadding = "";

			if ($this->intCellSpacing >= 0)
				$strCellSpacing = sprintf('cellspacing="%s" ', $this->intCellSpacing);
			else
				$strCellSpacing = "";
			*/

			if ($this->intButtonMode == self::ButtonModeSet || $this->intButtonMode == self::ButtonModeList) {
				return $this->RenderButtonSet();
			}
			else {
				$strToReturn = $this->RenderButtonTable();
			}

			return $strToReturn;

		}

		/**
		 * Renders the button group as a table, paying attention to the number of columns wanted.
		 * @return string
		 */
		public function RenderButtonTable() {
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

						$strItemHtml = $this->GetItemHtml($this->objListItemArray[$intIndex], $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel);
						$strRowHtml .= $strItemHtml;
					}

					$strRowHtml = QHtml::RenderTag('div', ['class'=>'qc-tableRow'], $strRowHtml);
					$strToReturn .= $strRowHtml;
				}

				if ($this->strMaxHeight) {
					// wrap table in a scrolling div that will end up being the actual object
					//$objStyler = new QTagStyler();
					$this->SetCssStyle('max-height', $this->strMaxHeight, true);
					$this->SetCssStyle('overflow-y', 'scroll');

					$strToReturn = QHtml::RenderTag('div', ['class'=>'qc-table'], $strToReturn);
				} else {
					$this->AddCssClass('qc-table'); // format as a table
				}
			}

			return $this->RenderTag ('div', ['id'=>$this->strControlId], null, $strToReturn);
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
				$strToReturn .= $this->GetItemHtml($this->objListItemArray[$intIndex], $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel) . "\n";
			}
			$strToReturn = $this->RenderTag('div', ['id'=>$this->strControlId], null, $strToReturn);
			return $strToReturn;
		}

		/**
		 * Render as a single column. This implementation simply wraps the columns in divs.
		 * @return string
		 */
		public function RenderButtonColumn() {
			$count = $this->ItemCount;
			$strToReturn = '';
			for ($intIndex = 0; $intIndex < $count; $intIndex++) {
				$strHtml = $this->GetItemHtml($this->objListItemArray[$intIndex], $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel);
				$strToReturn .= QHtml::RenderTag('div', null, $strHtml);
			}
			$strToReturn = $this->RenderTag('div', ['id'=>$this->strControlId], null, $strToReturn);
			return $strToReturn;
		}


		/**
		 * Validate the control.
		 * @return bool
		 */
		public function Validate() {
			if ($this->blnRequired) {
				if ($this->SelectedIndex == -1) {
					$this->ValidationError = QApplication::Translate($this->strName) . ' ' . QApplication::Translate('is required');
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
			$indexes = $this->SelectedIndexes;
			QApplication::ExecuteSelectorFunction(['input', '#' . $this->ControlId], 'val', $indexes);
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
				case "MaxHeight": return $this->strMaxHeight;
				
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
						if ($this->intCellPadding !== ($mixValue = QType::Cast($mixValue, QType::Integer))) {
							$this->blnModified = true;
							$this->intCellPadding = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CellSpacing":
					try {
						if ($this->intCellSpacing !== ($mixValue = QType::Cast($mixValue, QType::Integer))) {
							$this->blnModified = true;
							$this->intCellSpacing = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "RepeatColumns":
					try {
						if ($this->intRepeatColumns !== ($mixValue = QType::Cast($mixValue, QType::Integer))) {
							$this->blnModified = true;
							$this->intRepeatColumns = $mixValue;
						}
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					if ($this->intRepeatColumns < 1)
						throw new QCallerException("RepeatColumns must be greater than 0");
					break;
				case "RepeatDirection":
					try {
						if ($this->strRepeatDirection !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->blnModified = true;
							$this->strRepeatDirection = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemStyle":
					try {
						$this->blnModified = true;
						$this->objItemStyle = QType::Cast($mixValue, "QTagStyler");
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case "ButtonMode":
					try {
						if ($this->intButtonMode !== ($mixValue = QType::Cast($mixValue, QType::Integer))) {
							$this->blnModified = true;
							$this->intButtonMode = $mixValue;
						}
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				case "MaxHeight":
					try {
						if (empty ($mixValue)) {
							$this->strMaxHeight = null;
						}
						else {
							$this->strMaxHeight = QType::Cast($mixValue, QType::String);
						}
						$this->blnModified = true;
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
		 * Returns a description of the options available to modify by the designer for the code generator.
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
						'QCheckBoxList::ButtonModeJq'=>'JQuery UI Buttons',
						'QCheckBoxList::ButtonModeSet'=>'JQuery UI Buttonset'
					)),
				new QModelConnectorParam (get_called_class(), 'MaxHeight', 'If set, will wrap it in a scrollable pane with the given max height', QType::Integer)
			));
		}

	}