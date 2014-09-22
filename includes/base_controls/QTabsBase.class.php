<?php
	/**
	 * QTabs Base File
	 * 
	 * The QTabsBase class defined here provides an interface between the generated
	 * QTabsGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QTabs.class.php file instead.
	 *
	 */

	class QTabs_BeforeActivateEventExt extends QJqUiEvent {
		const EventName = 'tabsbeforeactivate';

	}

    /**
     * Impelements JQuery Ui Tabs
     * 
     * Tabs are similar to an Accordion, but tabs along the top are used to switch between panels. The top
     * level html items in the panel will become the items that are switched.
	 *
	 * Specify the names of the tabs either in the TabHeadersArray, or assign a Name attribute to the top
	 * level child controls and those names will be used as the tab names.
     * 
	 * @property-write array $Headers
	 *
	 * @link http://jqueryui.com/tabs/
	 * @package Controls\Base
	 */
	class QTabsBase extends QTabsGen
	{
		protected $objTabHeadersArray = array();
		protected $blnAutoRenderChildren = true;
		protected $intSelected = 0;

		/**
		 * Return the javascript associated with the control.
		 * @return string
		 */
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			$strJS .= sprintf('; $j("#%s").on("tabsselect", function(event, ui) {$j("#%s").val(ui.index);})',
					$this->ControlId,
					$this->getSelectedInputId());
			return $strJS;
		}

		/**
		 * Return the id of the selection
		 * @return string
		 */
		protected function getSelectedInputId() {
			return $this->ControlId.'_selected';
		}

		/**
		 * Records the current selection based on the selected item the user clicked.
		 */
		public function ParsePostData() {
			$strSelectedInputId = $this->getSelectedInputId();
			if (array_key_exists($strSelectedInputId, $_POST)) {
				$this->intSelected = $_POST[$strSelectedInputId];
			}
		}

		/**
		 * Renders child controls as divs so that they become tabs.
		 * @param bool $blnDisplayOutput
		 * @return null|string
		 */
		protected function RenderChildren($blnDisplayOutput = true) {
			$strToReturn = $this->GetTabHeaderHtml();

			foreach ($this->GetChildControls() as $objControl) {
				if (!$objControl->Rendered) {
					$renderMethod = $objControl->strPreferredRenderMethod;
					$strToReturn .= '<div>';
					$strToReturn .= $objControl->$renderMethod($blnDisplayOutput);
					$strToReturn .= '</div>';
				}
			}

			if ($blnDisplayOutput) {
				print($strToReturn);
				return null;
			} else
				return $strToReturn;
		}

		/**
		 * Returns the HTML for the tab header. This includes the names and the control logic to record what the
		 * user clicked.
		 *
		 * @return string
		 */
		protected function GetTabHeaderHtml() {
			$strResult = sprintf('<input id="%s" type="hidden" value="%d"/>', $this->getSelectedInputId(), $this->intSelected);
			$strResult .= '<ul>';
			$childControls = $this->GetChildControls();
			for ($i = 0, $cnt = count($childControls); $i < $cnt; ++$i) {
				$strControlId = $childControls[$i]->ControlId;
				$strResult .= '<li><a href="#'.$strControlId.'">';
				if (array_key_exists($key = $strControlId, $this->objTabHeadersArray) ||
						array_key_exists($key = $i, $this->objTabHeadersArray)) {
					$objHeader = $this->objTabHeadersArray[$key];
					if ($objHeader instanceof QControl) {
						$strResult .= $objHeader->GetControlHtml();
					} else {
						$strResult .= (string)$objHeader;
					}
				}
				elseif ($strName = $childControls[$i]->Name) {
					$strResult .= $strName;
				}
				else {
					$strResult .= 'Tab '. ($i+1);
				}
				$strResult .= '</a></li>';
			}

			$strResult .= '</ul>';
			return $strResult;
		}

		/**
		 * Set the tab header for a tab
		 * 
		 * Give it a control and a name to set the header
		 * 
		 * TBD: impelment ajax fetch of tab content
		 * 
		 * @param integer|QControl|string $mixHeaderIndicator either the 0-based index of the header, or the section control or that control's id
		 * @param string|QControl $mixHeader string or control to render as the tab header
		 * @return void
		 */
		public function SetHeader($mixHeaderIndicator, $mixHeader) {
			$key = ($mixHeaderIndicator instanceof QControl) ? $mixHeaderIndicator->ControlId : $mixHeaderIndicator;
			$this->objTabHeadersArray[$key] = $mixHeader;
		}

		/**
		 * Generated method overrides the built-in QControl method, causing it to not redraw completely. We restore
		 * its functionality here.
		 */
		public function Refresh() {
			$this->CallJqUiMethod(false, "refresh");
			QControl::Refresh();
		}


		/**
		 * Overrides default so that if a tab does not pass validation, it will be visible.
		 * @return bool
		 */
		public function ValidateControlAndChildren() {
			// Initially Assume Validation is True
			$blnToReturn = true;

			// Check the Control Itself
			if (!$this->Validate()) {
				$blnToReturn = false;
			}

			// Recursive call on Child Controls
			$intControlNum = 0;

			foreach ($this->GetChildControls() as $objChildControl) {
				// Only Enabled and Visible and Rendered controls should be validated
				if (($objChildControl->Visible) && ($objChildControl->Enabled) && ($objChildControl->RenderMethod) && ($objChildControl->OnPage)) {
					if (!$objChildControl->ValidateControlAndChildren()) {
						$this->CallJqUiMethod(false, "option", 'active', $intControlNum);
						$blnToReturn = false;
					}
				}
				$intControlNum++;
			}

			return $blnToReturn;
		}

		/**
		 * Given a tab name or index, returns its index. If invalid, return false;
		 * @param string|integer $mixTab
		 * @return bool|int
		 */
		protected function FindTabIndex ($mixTab) {
			$count = 0;

			if ($this->objTabHeadersArray) {
				$count = count($this->objTabHeadersArray);
			}
			else {
				$childControls = $this->GetChildControls();
				$count = count ($childControls);
			}
			if (is_numeric($mixTab)) {
				if ($mixTab < $count) {
					return $mixTab;
				}
			}

			if ($this->objTabHeadersArray) {
				for ($i = 0, $cnt = $count; $i < $cnt; ++$i) {
					if ($this->objTabHeadersArray[$i] == $mixTab) {
						return $i;
					}
				}
			}
			else {
				for ($i = 0, $cnt = $count; $i < $cnt; ++$i) {
					if ($mixTab == $childControls[$i]->Name) {
						return $i;
					}
				}

			}
			return false;
		}

		/**
		 * Activate the tab with the given name or number.
		 *
		 * @param string|integer $mixTab
		 */
		public function ActivateTab ($mixTab) {
			if (false !== ($i = $this->FindTabIndex($mixTab))) {
				parent::Option2('active', $i);
			}
		}

		/**
		 * Enable or disable a tab, or all tabs.
		 *
		 * @param null|string|integer $mixTab  If null, enables or disables all tabs. Otherwise, the name or index of a tab.
		 * @param bool $blnEnable True to enable tabs. False to disable.
		 */
		public function EnableTab ($mixTab = null, $blnEnable = true) {

			if (is_null($mixTab)) {
				if ($blnEnable) {
					parent::Enable();
				} else {
					parent::Disable();
				}
				return;
			}
			if (false !== ($i = $this->FindTabIndex($mixTab))) {
				if ($blnEnable) {
					parent::Enable1($i);
				} else {
					parent::Disable1($i);
				}

			}
		}


		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'Headers':
					try {
						$this->objTabHeadersArray = QType::Cast($mixValue, QType::ArrayType);
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
	}
?>