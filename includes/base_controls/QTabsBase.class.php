<?php
	/**
	 * QTabs Base File
	 * 
	 * The QTabsBase class defined here provides an interface between the generated
	 * QTabsGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QTabs.class.php file instead.
	 *
	 */

    /**
     * Implements JQuery Ui Tabs
     * 
     * Tabs are similar to an Accordion, but tabs along the top are used to switch between panels. The top
     * level html items in the panel will become the items that are switched.
	 *
	 * Specify the names of the tabs either in the TabHeadersArray, or assign a Name attribute to the top
	 * level child controls and those names will be used as the tab names.
     *
	 * @property-write array $Headers	Array of names for the tabs. You can also specify by assigning the Name attribute of each pane.
	 * @property-read array $SelectedId	Control Id of the selected pane. Use ->Active to get the zero-based index of the selected pane.
	 *
	 * @link http://jqueryui.com/tabs/
	 * @package Controls\Base
	 */
	class QTabsBase extends QTabsGen
	{
		/** @var array Names of tabs. Can also specify with Name attribute of child controls. */
		protected $objTabHeadersArray = array();
		/** @var bool Automatically render the children by default, since these are the tabs. */
		protected $blnAutoRenderChildren = true;
		/** @var string ControlId of currently selected child item. Use ->Active to get the index of the current selection. */
		protected $strSelectedId = null;

		/**
		 * Return the javascript associated with the control.
		 * @return string
		 */
		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			QApplication::ExecuteJsFunction('qcubed.tabs', $this->GetJqControlId(), QJsPriority::High);

			return $strJS;
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
					$strToReturn .= QHtml::RenderTag('div', null, $objControl->$renderMethod($blnDisplayOutput));
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
			$strHtml = '';
			$childControls = $this->GetChildControls();
			for ($i = 0, $cnt = count($childControls); $i < $cnt; ++$i) {
				$strControlId = $childControls[$i]->ControlId;
				if (array_key_exists($key = $strControlId, $this->objTabHeadersArray) ||
						array_key_exists($key = $i, $this->objTabHeadersArray)) {
					$objHeader = $this->objTabHeadersArray[$key];
					if ($objHeader instanceof QControl) {
						$strText = $objHeader->GetControlHtml();
					} else {
						$strText = (string)$objHeader;
					}
				}
				elseif ($strName = $childControls[$i]->Name) {
					$strText = $strName;
				}
				else {
					$strText = 'Tab '. ($i+1);
				}
				$strAnchor = QHtml::RenderTag('a', ['href'=>'#' . $strControlId], $strText, false, true);
				$strHtml .= QHtml::RenderTag ('li', null, $strAnchor);
			}
			return QHtml::RenderTag('ul', null, $strHtml);
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
			parent::Refresh();
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
						$this->ActivateTab($intControlNum);
						$blnToReturn = false;
					}
				}
				$intControlNum++;
			}

			return $blnToReturn;
		}

		/**
		 * Given a tab name, index or control ID, returns its index. If invalid, returns false;
		 * @param string|integer $mixTab
		 * @return bool|int
		 */
		protected function FindTabIndex ($mixTab) {
			if ($mixTab === null) return false;

			if ($this->objTabHeadersArray) {
				$count = count($this->objTabHeadersArray);
			}
			else {
				$childControls = $this->GetChildControls();
				$count = count ($childControls);
			}

			if (is_numeric($mixTab)) {
				if ($mixTab < $count) {
					return $mixTab; // assume numbers less than the index are index numbers
				}
			}

			// If there is a headers array, check for a name in there
			if ($this->objTabHeadersArray) {
				for ($i = 0, $cnt = $count; $i < $cnt; ++$i) {
					if ($this->objTabHeadersArray[$i] == $mixTab) {
						return $i;
					}
				}
			}

			for ($i = 0, $cnt = $count; $i < $cnt; ++$i) {
				$objControl = $childControls[$i];
				if ($mixTab == $objControl->Name) {
					return $i;
				}
				elseif ($mixTab == $objControl->ControlId) {
					return $i;
				}
			}
			return false;
		}

		/**
		 * Activate the tab with the given name, number or controlId.
		 *
		 * @param string|integer $mixTab The tab name, tab index number or control ID
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

		/**
		 * Overriding to keep info in sync.
		 * @param QControl $objControl
		 */
		public function AddChildControl(QControl $objControl) {
			parent::AddChildControl($objControl);
			if (count ($this->objChildControlArray) == 1) {
				$this->strSelectedId = $objControl->strControlId;	// default to first item added being selected
				$this->mixActive = 0;
			}
		}

		/**
		 * Returns the state data to restore later.
		 * @return mixed
		 */
		protected function GetState() {
			return ['active'=>$this->Active, 'selectedId'=>$this->strSelectedId];
		}

		/**
		 * Restore the state of the control.
		 * @param mixed $state
		 */
		protected function PutState($state) {
			if (isset($state['active'])) {
				$this->Active = $state['active'];
				$this->strSelectedId = $state['selectedId'];
			}
		}


		public function __get($strName) {
			switch ($strName) {
				case "SelectedId": return $this->strSelectedId;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
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

				case '_active': // private method to synchronize with jQuery UI
					$this->mixActive = $mixValue[0];
					$this->strSelectedId = $mixValue[1];
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