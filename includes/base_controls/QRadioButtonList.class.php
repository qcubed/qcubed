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
	 */
	class QRadioButtonList extends QListControl {
		const ButtonModeNone = 0;
		const ButtonModeJq = 1;
		const ButtonModeSet = 2;
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
			$this->objItemStyle = new QListItemStyle();
		}

		//////////
		// Methods
		//////////
		public function ParsePostData() {
			if (QApplication::$RequestMode == QRequestMode::Ajax) {
				$this->SelectedIndex = $_POST[$this->strControlId];
			}
			elseif ($this->objForm->IsCheckableControlRendered($this->strControlId)) {
				if (array_key_exists($this->strControlId, $_POST)) {
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($_POST[$this->strControlId] == $intIndex)
							$this->objItemsArray[$intIndex]->Selected = true;
						else
							$this->objItemsArray[$intIndex]->Selected = false;
					}
				} else {
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) 
						$this->objItemsArray[$intIndex]->Selected = false;
				}
			}
		}

		public function GetEndScript() {
			$strScript = sprintf ('$j("#%s").on("change", "input", qc.formObjChanged);', $this->ControlId); // detect change for framework

			$ctrlId = $this->ControlId;
			if ($this->intButtonMode == self::ButtonModeSet) {
				$strScript .= sprintf ('jQuery("#%s").buttonset();', $ctrlId) . "\n";
			} elseif ($this->intButtonMode == self::ButtonModeJq) {
				$strScript .= sprintf ('jQuery("input:radio", "#%s").button();', $ctrlId) . "\n";
			}
			$strScript .= parent::GetEndScript();
			return $strScript;
		}

		protected function GetItemHtml($objItem, $intIndex, $strTabIndex, $blnWrapLabel) {
			$objLabelStyles = new QTagStyler();
			if ($this->objItemStyle) {
				$objLabelStyles->override($this->objItemStyle); // default style
			}
			if ($objItemStyle = $objItem->ItemStyle) {
				$objLabelStyles->override($objItemStyle); // per item styling
			}

			$objStyles = new QTagStyler();
			$objStyles->setHtmlAttribute('type', 'radio');
			$objStyles->setHtmlAttribute('value', $intIndex);
			$objStyles->setHtmlAttribute('name', $this->strControlId);
			$strIndexedId = $this->strControlId . '_' . $intIndex;
			$objStyles->setHtmlAttribute('id', $strIndexedId);

			if ($strTabIndex) {
				$objStyles->TabIndex = $strTabIndex;	// Use parent control tabIndex, which will cause the browser to take them in order of drawing
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
				$objStyles->setHtmlAttribute('checked', 'checked');
			}

			if ($blnWrapLabel) {
				$objLabelStyles->setHtmlAttribute('for', $strIndexedId);
			}

			$strHtml = QHtml::renderLabeledInput(
				$strLabelText,
				$this->strTextAlign == QTextAlign::Left,
				$objStyles->renderHtmlAttributes(),
				$objLabelStyles->renderHtmlAttributes(),
				$blnWrapLabel);

			return $strHtml;
		}

		protected function GetControlHtml() {
			if ((!$this->objItemsArray) || (count($this->objItemsArray) == 0))
				return "";

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
			elseif ($this->intRepeatColumns == 1) {
				$strToReturn = $this->RenderButtonColumn();
			}
			else {
				$strToReturn = $this->RenderButtonTable();
			}

			if ($this->strMaxHeight) {
				$objStyler = new QTagStyler();
				$objStyler->setCssStyle('max-height', $this->strMaxHeight, true);
				$objStyler->setCssStyle('overflow-y', 'scroll');

				$strToReturn = QHtml::renderTag('div', $objStyler->renderHtmlAttributes(), $strToReturn);
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

						$strItemHtml = $this->GetItemHtml($this->objItemsArray[$intIndex], $intIndex, $this->getHtmlAttribute('tabindex'), $this->blnWrapLabel);
						$strCellHtml = QHtml::renderTag ('td', null, $strItemHtml);
						$strRowHtml .= $strCellHtml;
					}

					$strRowHtml = QHtml::renderTag('tr', null, $strRowHtml);
					$strToReturn .= $strRowHtml;
				}
			}

			return $this->renderTag ('table', ['id'=>$this->strControlId], null, $strToReturn);
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
				$strToReturn .= $this->GetItemHtml($this->objItemsArray[$intIndex], $intIndex, $this->getHtmlAttribute('tabindex'), $this->blnWrapLabel) . "\n";
			}
			$strToReturn = $this->renderTag('div', ['id'=>$this->strControlId], null, $strToReturn);
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
				$strHtml = $this->GetItemHtml($this->objItemsArray[$intIndex], $intIndex, $this->getHtmlAttribute('tabindex'), $this->blnWrapLabel);
				$strToReturn .= QHtml::renderTag('div', null, $strHtml);
			}
			$strToReturn = $this->renderTag('div', ['id'=>$this->strControlId], null, $strToReturn);
			return $strToReturn;
		}

		public function Validate() {
			if ($this->blnRequired) {
				if ($this->SelectedIndex == -1) {
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
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "TextAlign":
					try {
						$this->strTextAlign = QType::Cast($mixValue, QType::String);
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
		 * Override to insert additional create options pertinent to the control.
		 * @param $objTable
		 * @param $objColumn
		 * @param $strControlVarName
		 * @return string|void
		 */
		public static function Codegen_MetaCreateOptions (QCodeGen $objCodeGen, QTable $objTable, $objColumn, $strControlVarName) {
			$strRet = parent::Codegen_MetaCreateOptions ($objCodeGen, $objTable, $objColumn, $strControlVarName);

			if ($objColumn instanceof QManyToManyReference) {
				$objCodeGen->ReportError($objTable->Name . ':' . $objColumn->Name . ' is not compatible with a QRadioButtonList.');
			}

			return $strRet;
		}


		/**
		 * Returns an description of the options available to modify by the designer for the code generator.
		 *
		 * @return QMetaParam[]
		 */
		public static function GetMetaParams() {
			return array_merge(parent::GetMetaParams(), array(
				new QMetaParam (get_called_class(), 'TextAlign', '', QMetaParam::SelectionList,
					array (null=>'Default',
						'QTextAlign::Left'=>'Left',
						'QTextAlign::Right'=>'Right'
					)),
				new QMetaParam (get_called_class(), 'HtmlEntities', 'Set to false to have the browser interpret the labels as HTML', QType::Boolean),
				new QMetaParam (get_called_class(), 'RepeatColumns', 'The number of columns of checkboxes to display', QType::Integer),
				new QMetaParam (get_called_class(), 'RepeatDirection', 'Whether to repeat horizontally or vertically', QMetaParam::SelectionList,
					array (null=>'Default',
						'QRepeatDirection::Horizontal'=>'Horizontal',
						'QRepeatDirection::Vertical'=>'Vertical'
					)),
				new QMetaParam (get_called_class(), 'ButtonMode', 'How to display the buttons', QMetaParam::SelectionList,
					array (null=>'Default',
						'QRadioButtonList::ButtonModeJq'=>'JQuery UI Buttons',
						'QRadioButtonList::ButtonModeSet'=>'JQuery UI Buttonset'
					)),
				new QMetaParam (get_called_class(), 'MaxHeight', 'If set, will wrap it in a scrollable pane with the given max height', QType::Integer)
			));
		}

	}
?>