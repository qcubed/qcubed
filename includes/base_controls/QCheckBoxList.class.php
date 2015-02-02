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
			if (QApplication::$RequestMode == QRequestMode::Ajax) {
				// Ajax will only send information about controls that are on the screen, so we know they are rendered
				for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
					if (!empty($_POST[$this->strControlId][$intIndex]))
						$this->objItemsArray[$intIndex]->Selected = true;
					else
						$this->objItemsArray[$intIndex]->Selected = false;
				}
			}
			elseif ($this->objForm->IsCheckableControlRendered($this->strControlId)) {
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
			$strScript = sprintf ('$j("#%s").on("change", "input", qc.formObjChanged);', $this->ControlId); // detect change for framework

			$ctrlId = $this->ControlId;
			if ($this->intButtonMode == self::ButtonModeSet) {
				$strScript .= sprintf ('jQuery("#%s").buttonset();', $ctrlId) . "\n";
			} elseif ($this->intButtonMode == self::ButtonModeJq) {
				$strScript .= sprintf ('jQuery("input:checkbox", "#%s").button();', $ctrlId) . "\n";
			}
			$strScript .= parent::GetEndScript();

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
			$objStyles->SetHtmlAttribute('type', 'checkbox');
			$objStyles->SetHtmlAttribute('name', $this->strControlId . '[' . $intIndex . ']');

			$strIndexedId = $this->strControlId . '_' . $intIndex;
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

			$strHtml = QHtml::RenderLabeledInput(
				$strLabelText,
				$this->strTextAlign == QTextAlign::Left,
				$objStyles->RenderHtmlAttributes(),
				$objLabelStyles->RenderHtmlAttributes(),
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

						$strItemHtml = $this->GetItemHtml($this->objItemsArray[$intIndex], $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel);
						$strCellHtml = QHtml::RenderTag ('td', null, $strItemHtml);
						$strRowHtml .= $strCellHtml;
					}

					$strRowHtml = QHtml::RenderTag('tr', null, $strRowHtml);
					$strToReturn .= $strRowHtml;
				}
			}

			return $this->RenderTag ('table', ['id'=>$this->strControlId], null, $strToReturn);
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
				$strToReturn .= $this->GetItemHtml($this->objItemsArray[$intIndex], $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel) . "\n";
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
				$strHtml = $this->GetItemHtml($this->objItemsArray[$intIndex], $intIndex, $this->GetHtmlAttribute('tabindex'), $this->blnWrapLabel);
				$strToReturn .= QHtml::RenderTag('div', null, $strHtml);
			}
			$strToReturn = $this->RenderTag('div', ['id'=>$this->strControlId], null, $strToReturn);
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
		 * Override to insert additional create options pertinent to the control.
		 * @param $objTable
		 * @param $objColumn
		 * @param $strControlVarName
		 * @return string|void
		 */
		public static function Codegen_MetaCreateOptions (QCodeGen $objCodeGen, QTable $objTable, $objColumn, $strControlVarName) {
			$strRet = parent::Codegen_MetaCreateOptions ($objCodeGen, $objTable, $objColumn, $strControlVarName);

			if (!$objColumn instanceof QManyToManyReference) {
				$objCodeGen->ReportError($objTable->Name . ':' . $objColumn->Name . ' is not compatible with a QCheckBoxList.');
			}

			return $strRet;
		}


		/**
		 * Since this is designed to edit a many-to-many relationship, creates a separate function for updating
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public static function Codegen_MetaUpdate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->ObjectDescription;
			$strPropNames = $objColumn->ObjectDescriptionPlural;
			$strControlVarName = $objCodeGen->MetaControlVariableName($objColumn);

			$strRet = <<<TMPL
		protected function {$strControlVarName}_Update() {
			if (\$this->{$strControlVarName}) {
				\$this->{$strObjectName}->UnassociateAll{$strPropNames}();
				\$this->{$strObjectName}->Associate{$strPropName}(\$this->{$strControlVarName}->SelectedValues);
			}
		}


TMPL;
			return $strRet;
		}


		/**
		 * Returns a description of the options available to modify by the designer for the code generator.
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
						'QCheckBoxList::ButtonModeJq'=>'JQuery UI Buttons',
						'QCheckBoxList::ButtonModeSet'=>'JQuery UI Buttonset'
					)),
				new QMetaParam (get_called_class(), 'MaxHeight', 'If set, will wrap it in a scrollable pane with the given max height', QType::Integer)
			));
		}

	}
?>