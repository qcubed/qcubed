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
	 * @property integer $RepeatColumn specifies how many columns should be rendered in the HTML Table
	 * @property string $RepeatDirection pecifies which direction should the list go first...
	 * @property boolean $HtmlEntities
	 */
	class QCheckBoxList extends QListControl {
		
		const ButtonModeNone = 0;
		const ButtonModeJq = 1;
		const ButtonModeSet = 2;
		
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
			if ($this->objForm->IsCheckableControlRendered($this->strControlId)) {
				if ((array_key_exists($this->strControlId, $_POST)) && (is_array($_POST[$this->strControlId]))) {
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if (array_key_exists($intIndex, $_POST[$this->strControlId]))
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
			$strScript = parent::GetEndScript();
			
			$ctrlId = $this->ControlId;
			if ($this->intButtonMode == self::ButtonModeSet) {
				$strScript = sprintf ('jQuery("#%s").buttonset();', $ctrlId) . "\n" . $strScript;
			} elseif ($this->intButtonMode == self::ButtonModeJq) {
				$strScript = sprintf ('jQuery("input:checkbox", "#%s").button();', $ctrlId) . "\n" . $strScript;
			}
			return $strScript;
		}

		protected function GetItemHtml($objItem, $intIndex, $strActions, $strTabIndex) {
			// The Default Item Style
			$objStyle = $this->objItemStyle;

			// Apply any Style Override (if applicable)
			if ($objItem->ItemStyle) {
				$objStyle = $objStyle->ApplyOverride($objItem->ItemStyle);
			}
			//$strIndexedId = $this->strControlId.'['.$intIndex .']'; Buggy, html spec says no [] allowed
			$strIndexedId = $this->strControlId.'_'.$intIndex;
			$strToReturn = '';
			if (!$this->blnEnabled) {
				$strToReturn .= '<span disabled="disabled">';
			}

			$strLabelText = $objItem->Label;
			if (empty($strLabelText)) {
				$strLabelText = $objItem->Name;
			}
			if ($this->blnHtmlEntities) {
				$strLabelText = QApplication::HtmlEntities($strLabelText);
			}
			$strLabel = sprintf('<label for="%s">%s</label>',
				$strIndexedId,
				$strLabelText
			);

			$strInput = sprintf('<input id="%s" name="%s" type="checkbox" %s%s%s%s%s />',
				$strIndexedId,
				$this->strControlId . '[' . $intIndex . ']',
				($this->blnEnabled) ? '' : 'disabled="disabled"',
				($objItem->Selected) ? 'checked="checked"' : '',
				$objStyle->GetAttributes(),
				$strActions,
				$strTabIndex
			);

			if ($this->strTextAlign == QTextAlign::Left) {
				$strToReturn .= $strLabel;
				$strToReturn .= $strInput;
			} else {
				$strToReturn .= $strInput;
				$strToReturn .= $strLabel;
			}

			if (!$this->blnEnabled) {
				$strToReturn .= '</span>';
			}
			$strToReturn .= '';
			return $strToReturn;
		}

		protected function GetControlHtml() {
			if ((!$this->objItemsArray) || (count($this->objItemsArray) == 0))
				return "";

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
		
			$strStyle = $this->GetStyleAttributes();
			if (strlen($strStyle) > 0)
				$strStyle = sprintf('style="%s" ', $strStyle);

			$strCustomAttributes = $this->GetCustomAttributes();

			$strActions = $this->GetActionAttributes();

			if ($this->intCellPadding >= 0)
				$strCellPadding = sprintf('cellpadding="%s" ', $this->intCellPadding);
			else
				$strCellPadding = "";

			if ($this->intCellSpacing >= 0)
				$strCellSpacing = sprintf('cellspacing="%s" ', $this->intCellSpacing);
			else
				$strCellSpacing = "";
			
			if ($this->intButtonMode == self::ButtonModeSet) {
				$strToReturn = sprintf('<div id="%s" %s%s%s%s%s>',
					$this->strControlId,
					$strAccessKey,
					$strToolTip,
					$strCssClass,
					$strStyle,
					$strCustomAttributes) . "\n";
					
				$count = $this->ItemCount;
				for ($intIndex = 0; $intIndex < $count; $intIndex++) {
					$strToReturn .= $this->GetItemHtml($this->objItemsArray[$intIndex], $intIndex, $strActions, $strTabIndex) . "\n";
				}
				$strToReturn .= '</div>';
				return $strToReturn;
			}
			// Generate Table HTML
			$strToReturn = sprintf('<table id="%s" %s%sborder="0" %s%s%s%s%s>',
				$this->strControlId,
				$strCellPadding,
				$strCellSpacing,
				$strAccessKey,
				$strToolTip,
				$strCssClass,
				$strStyle,
				$strCustomAttributes);

			if ($this->ItemCount > 0) {
				// Figure out the number of ROWS for this table
				$intRowCount = floor($this->ItemCount / $this->intRepeatColumns);
				$intWidowCount = ($this->ItemCount % $this->intRepeatColumns);
				if ($intWidowCount > 0)
					$intRowCount++;

				// Iterate through Table Rows
				for ($intRowIndex = 0; $intRowIndex < $intRowCount; $intRowIndex++) {
					$strToReturn .= '<tr>';

					// Figure out the number of COLUMNS for this particular ROW
					if (($intRowIndex == $intRowCount - 1) && ($intWidowCount > 0))
						// on the last row for a table with widowed-columns, ColCount is the number of widows
						$intColCount = $intWidowCount;
					else
						// otherwise, ColCount is simply intRepeatColumns
						$intColCount = $this->intRepeatColumns;

					// Iterate through Table Columns
					for ($intColIndex = 0; $intColIndex < $intColCount; $intColIndex++) {
						if ($this->strRepeatDirection == QRepeatDirection::Horizontal)
							$intIndex = $intColIndex + $this->intRepeatColumns * $intRowIndex;
						else
							$intIndex = (floor($this->ItemCount / $this->intRepeatColumns) * $intColIndex)
								+ min(($this->ItemCount % $this->intRepeatColumns), $intColIndex)
								+ $intRowIndex;

						$strToReturn .= '<td>';
						$strToReturn .= $this->GetItemHtml($this->objItemsArray[$intIndex], $intIndex, $strActions, $strTabIndex);
						$strToReturn .= '</td>';
                    }
					
					$strToReturn .= '</tr>';
				}
			}

			$strToReturn .= '</table>';

			// wrap table in a div so that it can scroll
			if ($this->strMaxHeight) {
				$strVal = $this->strMaxHeight;
				if (is_numeric($strVal)) {
					$strVal = $strVal . 'px';
				}
				
				$strToReturn = sprintf ('<div style="max-height:%s; overflow-y:scroll">%s</div>', $strVal, $strToReturn);
			}			

			return $strToReturn;
		}

		public function Validate() {
			if ($this->blnRequired) {
				if ($this->SelectedIndex == -1) {
					$this->strValidationError = QApplication::Translate($this->strName) . ' ' . QApplication::Translate('is required');
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

				case "MaxHeight":
					try {
						if (empty ($mixValue)) {
							$this->strMaxHeight = null;
						}
						else {
							$this->strMaxHeight = QType::Cast($mixValue, QType::String);
						}
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
	}
?>