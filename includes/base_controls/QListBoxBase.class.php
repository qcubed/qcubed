<?php
	/**
	 * contains QListBoxBase class
	 * @package Controls
	 */

	/**
	 * QListBoxBase will render an HTML DropDown or MultiSelect box [SELECT] element.
	 * It extends {@link QListControl}.  By default, the number of visible rows is set to 1 and
	 * the selection mode is set to single, creating a dropdown select box.
	 *
	 * @property integer $Rows          specifies how many rows you want to have shown.
	 * @property string  $LabelForRequired
	 * @property string  $LabelForRequiredUnnamed
	 * @property string  $ItemStyle     {@link QListItemStyle}
	 * @property string  $SelectionMode {@link QSelectionMode} specifies if this is a "Single" or "Multiple" select control.
	 * @see     QListItemStyle
	 * @see     QSelectionMode
	 * @package Controls
	 */
	abstract class QListBoxBase extends QListControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		/** @var string Error to be shown if the box is empty, has a name and is marked as required */
		protected $strLabelForRequired;
		/** @var string Error to be shown If the box is empty, doesn't have a name and is marked as required */
		protected $strLabelForRequiredUnnamed;
		/** @var null|QListItemStyle The style for each element to be displayed */
		protected $objItemStyle = null;

		//////////
		// Methods
		//////////
		/**
		 * QControl-Constructor
		 * 
		 * @param QControl|QForm $objParentObject
		 * @param string $strControlId
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strLabelForRequired = QApplication::Translate('%s is required');
			$this->strLabelForRequiredUnnamed = QApplication::Translate('Required');
			$this->objItemStyle = new QListItemStyle();
		}

		/**
		 * Prases the data recieved back from the client/browser
		 */
		public function ParsePostData() {
			if (array_key_exists($this->strControlId, $_POST)) {
				if (is_array($_POST[$this->strControlId])) {
					// Multi-Select, so find them all
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if (array_search($intIndex, $_POST[$this->strControlId]) !== false)
							$this->objItemsArray[$intIndex]->Selected = true;
						else
							$this->objItemsArray[$intIndex]->Selected = false;
					}
				} else {
					// Single-select
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($_POST[$this->strControlId] == $intIndex)
							$this->objItemsArray[$intIndex]->Selected = true;
						else
							$this->objItemsArray[$intIndex]->Selected = false;
					}
				}
			} else {
				// Multiselect forms with nothing passed via $_POST means that everything was DE selected
				if ($this->strSelectionMode == QSelectionMode::Multiple) {
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						$this->objItemsArray[$intIndex]->Selected = false;
					}
				}
			}
		}

		/**
		 * Returns the HTML-Code for a single Item
		 * 
		 * @param QListItem $objItem
		 * @param integer $intIndex
		 * @return string resulting HTML
		 */
		protected function GetItemHtml($objItem, $intIndex) {
			// The Default Item Style
			$objStyle = $this->objItemStyle;

			// Apply any Style Override (if applicable)
			if ($objItem->ItemStyle) {
				$objStyle = $objStyle->ApplyOverride($objItem->ItemStyle);
			}

			$strToReturn = sprintf('<option value="%s" %s%s>%s</option>',
				($objItem->Empty) ? '' : $intIndex,
				($objItem->Selected) ? 'selected="selected"' : "",
				$objStyle->GetAttributes(),
				QApplication::HtmlEntities($objItem->Name)
			) . _nl();

			return $strToReturn;
		}

		/**
		 * Returns the html for the entire control.
		 * @return string
		 */
		protected function GetControlHtml() {
			// If no selection is specified, we select the first item, because once we draw this, that is what the browser
			// will consider selected on the screen.
			// We need to make sure that what we draw is mirrored in our current state
			if ($this->SelectionMode == QSelectionMode::Single &&
					$this->SelectedIndex == -1 &&
					$this->ItemCount > 0) {
				$this->SelectedIndex = 0;
			}

			$attrOverride = array('id'=>$this->strControlId);

			if ($this->SelectionMode == QSelectionMode::Multiple) {
				$attrOverride['name'] = $this->strControlId . "[]";
			} else {
				$attrOverride['name'] = $this->strControlId;
			}

			$strToReturn = $this->RenderTag('select', $attrOverride, null, $this->renderInnerHtml());

			// If MultiSelect and if NOT required, add a "Reset" button to deselect everything
			if (($this->SelectionMode == QSelectionMode::Multiple) && (!$this->blnRequired) && ($this->blnEnabled) && ($this->blnVisible)) {
				$strToReturn .= $this->GetResetButtonHtml();
			}
			return $strToReturn;
		}

		protected function renderInnerHtml() {
			$strToReturn = '';
			$strCurrentGroup = null;
			if (is_array($this->objItemsArray)) {
				for ($intIndex = 0; $intIndex < $this->ItemCount; $intIndex++) {
					$objItem = $this->objItemsArray[$intIndex];
					// Figure Out Groups (if applicable)
					if (!is_null($objItem->ItemGroup)) {
						// We've got grouping -- are we in a new or same group?
						if (is_null($strCurrentGroup))
							// New Group
							$strToReturn .= '<optgroup label="' . QApplication::HtmlEntities($objItem->ItemGroup) . '">';							
							
						else if ($strCurrentGroup != $objItem->ItemGroup)
							// Different Group
							$strToReturn .= '</optgroup>' . _nl() . '<optgroup label="' . QApplication::HtmlEntities($objItem->ItemGroup) . '">';

						$strCurrentGroup = $objItem->ItemGroup;
						
					// We've got no (or no more) grouping
					} else {
						if (!is_null($strCurrentGroup)) {
							// End the current group
							$strToReturn .= '</optgroup>' . _nl();
							$strCurrentGroup = null;
						}
					}
					$strToReturn .= $this->GetItemHtml($objItem, $intIndex);
				}
				
				if (!is_null($strCurrentGroup))
					$strToReturn .= '</optgroup>' . _nl();;
			}

			return $strToReturn;
		}

		/**
		 * End script to support the detection of changes before other change scripts are called.
		 * @return string
		 */
		public function GetEndScript() {
			$str = parent::GetEndScript();
			$str = sprintf ('$j("#%s").change(qc.formObjChanged);', $this->ControlId) . $str;
			return $str;
		}


		// For multiple-select based listboxes, you must define the way a "Reset" button should look
		abstract protected function GetResetButtonHtml();

		/**
		 * Determines whether the supplied input data is valid or not.
		 * @return bool
		 */
		public function Validate() {
			if ($this->blnRequired) {
				if ($this->SelectedIndex == -1) {
					if ($this->strName)
						$this->strValidationError = sprintf($this->strLabelForRequired, $this->strName);
					else
						$this->strValidationError = $this->strLabelForRequiredUnnamed;
					return false;
				}

				if (($this->SelectedIndex == 0) && (strlen($this->SelectedValue) == 0)) {
					if ($this->strName)
						$this->strValidationError = sprintf($this->strLabelForRequired, $this->strName);
					else
						$this->strValidationError = $this->strLabelForRequiredUnnamed;
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
		 * PHP magic function
		 * @param string $strName
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Rows": return $this->GetHtmlAttribute('size');
				case "LabelForRequired": return $this->strLabelForRequired;
				case "LabelForRequiredUnnamed": return $this->strLabelForRequiredUnnamed;
				case "ItemStyle": return $this->objItemStyle;
				
				// BEHAVIOR
				case "SelectionMode": return $this->HasHtmlAttribute('multiple') ? QSelectionMode::Multiple : QSelectionMode::Single;

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
		 * PHP magic method
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				// APPEARANCE
				case "Rows":
					try {
						$this->SetHtmlAttribute('size', QType::Cast($mixValue, QType::Integer));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LabelForRequired":
					try {
						$this->strLabelForRequired = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LabelForRequiredUnnamed":
					try {
						$this->strLabelForRequiredUnnamed = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// BEHAVIOR
				case "SelectionMode":
					try {
						if (QType::Cast($mixValue, QType::String) == QSelectionMode::Multiple) {
							$this->SetHtmlAttribute('multiple', 'multiple');
						} else {
							$this->RemoveHtmlAttribute('multiple');
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				
				case "ItemStyle":
					try {
						$this->blnModified = true;
						$this->objItemStyle = QType::Cast($mixValue, "QListItemStyle");
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

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

		/**** Codegen Helpers, used during the Codegen process only. ****/

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
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->SelectionMode = QSelectionMode::Multiple;

TMPL;
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
				new QMetaParam (get_called_class(), 'Rows', 'Height of field for multirow field', QType::Integer),
				new QMetaParam (get_called_class(), 'SelectionMode', 'Single or multiple selections', QMetaParam::SelectionList,
					array (null=>'Default',
						'QSelectionMode::Single'=>'Single',
						'QSelectionMode::Multiple'=>'Multiple'
					))
			));
		}

	}
?>