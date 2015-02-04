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
	 * @property string  $SelectionMode {@link QSelectionMode} specifies if this is a "Single" or "Multiple" select control.
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
		 * Parses the data received back from the client/browser
		 */
		public function ParsePostData() {
			if (array_key_exists($this->strControlId, $_POST)) {
				if (is_array($_POST[$this->strControlId])) {
					// Multi-Select, so find them all.
					$this->SetSelectedItemsById($_POST[$this->strControlId], false);
				} else {
					// Single-select
					$this->SetSelectedItemsById(array($_POST[$this->strControlId]), false);
				}
			} else {
				// Multiselect forms with nothing passed via $_POST means that everything was DE selected
				if ($this->SelectionMode == QSelectionMode::Multiple) {
					$this->UnselectAllItems(false);
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
		protected function GetItemHtml($objItem) {
			// The Default Item Style
			if ($this->objItemStyle) {
				$objStyler = clone ($this->objItemStyle);
			} else {
				$objStyler = new QListItemStyle();
			}

			// Apply any Style Override (if applicable)
			if ($objStyle = $objItem->ItemStyle) {
				$objStyler->Override($objStyle);
			}

			$objStyler->SetHtmlAttribute('value', ($objItem->Empty) ? '' : $objItem->ControlId);
			if ($objItem->Selected) {
				$objStyler->SetHtmlAttribute('selected', 'selected');
			}

			$strHtml = QHtml::RenderTag('option', $objStyler->RenderHtmlAttributes(), QApplication::HtmlEntities($objItem->Name), false, true) . _nl();

			return $strHtml;
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

			if ($this->SelectionMode == QSelectionMode::Multiple) {
				$attrOverride['name'] = $this->strControlId . "[]";
			} else {
				$attrOverride['name'] = $this->strControlId;
			}

			$strToReturn = $this->RenderTag('select', $attrOverride, null, $this->RenderInnerHtml());

			// If MultiSelect and if NOT required, add a "Reset" button to deselect everything
			if (($this->SelectionMode == QSelectionMode::Multiple) && (!$this->blnRequired) && ($this->blnEnabled) && ($this->blnVisible)) {
				$strToReturn .= $this->GetResetButtonHtml();
			}
			return $strToReturn;
		}

		/**
		 * Return the inner html for the select box.
		 * @return string
		 */
		protected function RenderInnerHtml() {
			$strHtml = '';
			$intItemCount = $this->GetItemCount();
			if (!$intItemCount) return '';
			$groups = array();

			for ($intIndex = 0; $intIndex < $intItemCount; $intIndex++) {
				$objItem = $this->GetItem ($intIndex);
				// Figure Out Groups (if applicable)
				if ($strGroup = $objItem->ItemGroup) {
					$groups[$strGroup][] = $objItem;
				} else {
					$groups[''][] = $objItem;
				}
			}

			foreach ($groups as $strGroup=>$items) {
				if (!$strGroup) {
					foreach ($items as $objItem) {
						$strHtml .= $this->GetItemHtml($objItem);
					}
				}
				else {
					$strGroupHtml = '';
					foreach ($items as $objItem) {
						$strGroupHtml .= $this->GetItemHtml($objItem);
					}
					$strHtml .= QHtml::RenderTag('optgroup', ['label' => QApplication::HtmlEntities($strGroup)], $strGroupHtml);
				}
			}
			return $strHtml;
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
						$this->ValidationError = sprintf($this->strLabelForRequired, $this->strName);
					else
						$this->ValidationError = $this->strLabelForRequiredUnnamed;
					return false;
				}

				if (($this->SelectedIndex == 0) && (strlen($this->SelectedValue) == 0)) {
					if ($this->strName)
						$this->ValidationError = sprintf($this->strLabelForRequired, $this->strName);
					else
						$this->ValidationError = $this->strLabelForRequiredUnnamed;
					return false;
				}
			}

			$this->ValidationError = null;
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