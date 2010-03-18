<?php
	/**
	 * This file contains the QBlockControl class.
	 * 
	 * @package Controls
	 */

	/**
	 * This class will render HTML via a pair of <span></span> tags.  It is
	 * used to simply display any miscellaneous HTML that you would want displayed.
	 * Because of this, it can't really execute any actions.  Any Server- or Client-Actions
	 * defined will simply be ignored.
	 *
	 * @package Controls
	 *
	 * @property string $Text is the Html that you want rendered
	 * @property string $Format
	 * @property string $Template
	 * @property boolean $AutoRenderChildren
	 * @property string $TagName
	 * @property string $Padding
	 * @property boolean $HtmlEntities
	 * @property boolean $DropTarget
	 * @property string $HorizontalAlign
	 * @property string $VerticalAlign
	 * @property integer $ResizeHandleMinimum
	 * @property integer $ResizeHandleMaximum
	 * @property string $ResizeHandleDirection
	 */
	abstract class QBlockControl extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strText = null;
		protected $strFormat = null;
		protected $strTemplate = null;
		protected $blnAutoRenderChildren = false;
		protected $strPadding = null;

		protected $strTagName = null;
		protected $blnHtmlEntities = true;

		// BEHAVIOR
		protected $blnDropTarget = false;

		// LAYOUT
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		protected $strVerticalAlign = QVerticalAlign::NotSet;

		// Move Targets and Drop Zones
		protected $objMovesControlsArray = array();
		protected $objDropsControlsArray = array();
		protected $objDropsGroupingsArray = array();
		protected $objIsDropZoneFor = array();

		public function AddControlToMove($objTargetControl = null) {		
			$this->strJavaScripts = __JQUERY_EFFECTS__;
			
			if($objTargetControl && $objTargetControl->ControlId != $this->ControlId) {
				QApplication::ExecuteJavascript(sprintf('var pos_%s = $j("#%s").offset()', $objTargetControl->ControlId, $objTargetControl->ControlId));
				QApplication::ExecuteJavascript(sprintf('$j("#%s").bind("drag",  function (ev, ui) { p = $j("#%s").offset(); p.left = pos_%s.left + ui.position.left; p.top = pos_%s.top + ui.position.top; $j("#%s").offset(p); } );', $this->strControlId,	$objTargetControl->ControlId,  $objTargetControl->ControlId,  $objTargetControl->ControlId, $objTargetControl->ControlId ));
			}
			
			$this->blnMoveable = true;
			return;
		}

		public function RemoveControlToMove(QControl $objTargetControl) {
			$this->objMovesControlsArray[$objTargetControl->ControlId] = null;
			unset($this->objMovesControlsArray[$objTargetControl->ControlId]);
		}

		public function RemoveAllControlsToMove() {
			$this->objMovesControlsArray = array();
			$this->RemoveAllDropZones();
		}

		public function AddDropZone($objParentObject) {
			$this->strJavaScripts = __JQUERY_EFFECTS__;
			$this->objDropsControlsArray[$objParentObject->ControlId] = true;
			$objParentObject->DropTarget = true;
			$objParentObject->objIsDropZoneFor[$this->ControlId] = true;			
		}

		public function RemoveDropZone($objParentObject) {
			if ($objParentObject instanceof QForm) {
				$this->objDropsControlsArray[$objParentObject->FormId] = false;
			} else if ($objParentObject instanceof QBlockControl) {
				$this->objDropsControlsArray[$objParentObject->ControlId] = false;
				$objParentObject->objIsDropZoneFor[$this->ControlId] = false;
			} else
				throw new QCallerException('ParentObject must be either a QForm or QBlockControl object');
		}

		public function RemoveAllDropZones() {
			QApplication::ExecuteJavascript(sprintf('$j("#%s").draggable("option", "revert", "invalid");',$this->strControlId));
			
			foreach ($this->objDropsControlsArray as $strControlId => $blnValue) {
				if ($blnValue) {
					$objControl = $this->objForm->GetControl($strControlId);
					if ($objControl)
						$objControl->objIsDropZoneFor[$this->ControlId] = false;
				}
			}
			$this->objDropsControlsArray = array();
		}
		
		public function GetEndScript() {
			$strToReturn = parent::GetEndScript();
			
			// DROP ZONES
			foreach ($this->objDropsControlsArray as $strKey => $blnIsDropZone) {
				if ($blnIsDropZone)
					$strToReturn .= sprintf('$j("#%s").droppable(); ', $strKey);
			}

			foreach ($this->objIsDropZoneFor as $strKey => $blnIsDropZone) {
				if ($blnIsDropZone) {
					$objControl = $this->objForm->GetControl($strKey);
					if ($objControl && ($objControl->strRenderMethod)) {
						$strToReturn .= sprintf('$j("#%s").droppable("option", "accept", "#%s");', $this->strControlId, $strKey);
					}
				}
			}

			return $strToReturn;
		}

		public function GetStyleAttributes() {
			$strStyle = parent::GetStyleAttributes();
			
			if ($this->strPadding) {
				if (is_numeric($this->strPadding))
					$strStyle .= sprintf('padding:%spx;', $this->strPadding);
				else
					$strStyle .= sprintf('padding:%s;', $this->strPadding);
			}

			if (($this->strHorizontalAlign) && ($this->strHorizontalAlign != QHorizontalAlign::NotSet))
				$strStyle .= sprintf('text-align:%s;', $this->strHorizontalAlign);

			if (($this->strVerticalAlign) && ($this->strVerticalAlign != QVerticalAlign::NotSet))
				$strStyle .= sprintf('vertical-align:%s;', $this->strVerticalAlign);

			return $strStyle;
		}

		//////////
		// Methods
		//////////
		public function ParsePostData() {}
		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();

			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			if ($this->strFormat)
				$strText = sprintf($this->strFormat, $this->strText);
			else
				$strText = $this->strText;

			$strTemplateEvaluated = '';
			if ($this->strTemplate) {
				global $_CONTROL;
				$objCurrentControl = $_CONTROL;
				$_CONTROL = $this;
				$strTemplateEvaluated = $this->objForm->EvaluateTemplate($this->strTemplate);
				$_CONTROL = $objCurrentControl;
			}

			$strToReturn = sprintf('<%s id="%s" %s%s>%s%s%s</%s>',
				$this->strTagName,
				$this->strControlId,
				$this->GetAttributes(),
				$strStyle,
				($this->blnHtmlEntities) ? QApplication::HtmlEntities($strText) : $strText,
				$strTemplateEvaluated,
				($this->blnAutoRenderChildren) ? $this->RenderChildren(false) : '',
				$this->strTagName);

//			if ($this->blnDropTarget)
//				$strToReturn .= sprintf('<span id="%s_ctldzmask" style="position:absolute;"><span style="font-size: 1px">&nbsp;</span></span>', $this->strControlId);

			return $strToReturn;
		}
		public function Validate() {return true;}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "Format": return $this->strFormat;
				case "Template": return $this->strTemplate;
				case "AutoRenderChildren": return $this->blnAutoRenderChildren;
				case "TagName": return $this->strTagName;
				case "Padding": return $this->strPadding;
				case "HtmlEntities": return $this->blnHtmlEntities;

				// BEHAVIOR
				case "DropTarget": return $this->blnDropTarget;

				case "HorizontalAlign": return $this->strHorizontalAlign;
				case "VerticalAlign": return $this->strVerticalAlign;

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
				case "Text":
					try {
						$this->strText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Format":
					try {
						$this->strFormat = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Template":
					try {
						if ($mixValue) {
							if (file_exists($mixValue))
								$this->strTemplate = QType::Cast($mixValue, QType::String);
							else
								throw new QCallerException('Template file does not exist: ' . $mixValue);
						} else
							$this->strTemplate = null; 
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "AutoRenderChildren":
					try {
						$this->blnAutoRenderChildren = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "TagName":
					try {
						$this->strTagName = QType::Cast($mixValue, QType::String);
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

				case "Padding":
					try {
						$this->strPadding = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "DropTarget":
					try {
						$this->blnDropTarget = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "HorizontalAlign":
					try {
						$this->strHorizontalAlign = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "VerticalAlign":
					try {
						$this->strVerticalAlign = QType::Cast($mixValue, QType::String);
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
	}
	
	$_CONTROL = null;
?>