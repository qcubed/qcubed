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
		/** @var string The text on the control */
		protected $strText = null;
	    	/** @var string The format specifier for rendering the control  */
		protected $strFormat = null;
		/** @var string Path to the HTML template (.tpl.php) file (applicable in case a template is being used for Render) */
		protected $strTemplate = null;
		/** @var bool Render the child controls of this control automatically? */
		protected $blnAutoRenderChildren = false;
		/** @var null|string|int Padding (CSS Style) value */
		protected $strPadding = null;

		/** @var string HTML tag to be used by the control (such as div or span) */
		protected $strTagName = null;
		/** @var bool Should htmlentities be used on the contents of this control? */
		protected $blnHtmlEntities = true;

		// BEHAVIOR
		/** @var bool Is it a drop target? */
		protected $blnDropTarget = false;

		// LAYOUT
		/** @var  string|QHorizontalAlign Horizontal alignment style */
		protected $strHorizontalAlign = QHorizontalAlign::NotSet;
		/** @var  string|QVerticalAlign Vertical alignment style */
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
				QApplication::ExecuteJavascript(sprintf('$j("#%s").on("drag",  function (ev, ui) { p = $j("#%s").offset(); p.left = pos_%s.left + ui.position.left; p.top = pos_%s.top + ui.position.top; $j("#%s").offset(p); } );', $this->strControlId,	$objTargetControl->ControlId,  $objTargetControl->ControlId,  $objTargetControl->ControlId, $objTargetControl->ControlId ));
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

		/**
		 * Returns the End Script of the Control which is sent to the client when the control's Render is complete
		 * @return string The JS EndScript for the control
		 */
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

		//////////
		// Methods
		//////////
		/**
		 * Public function (to be overridden in child classes) to Parse the POST data recieved by control
		 */
		public function ParsePostData() {}


		/**
		 * Returns the HTML of the QControl
		 * @return string The HTML string
		 */
		protected function GetControlHtml() {
			if ($this->strFormat) {
				$strText = sprintf($this->strFormat, $this->strText);
			}
			else {
				$strText = $this->strText;
			}

			$strText = ($this->blnHtmlEntities) ? QApplication::HtmlEntities($strText) : $strText;

			$strTemplateEvaluated = '';
			if ($this->strTemplate) {
				global $_CONTROL;
				$objCurrentControl = $_CONTROL;
				$_CONTROL = $this;
				$strTemplateEvaluated = $this->EvaluateTemplate($this->strTemplate);
				$_CONTROL = $objCurrentControl;
			}

			$strText .= $strTemplateEvaluated;

			if ($this->blnAutoRenderChildren) {
				$strText .= $this->RenderChildren(false);
			}

			$strToReturn = $this->renderTag($this->strTagName,
				null,
				null,
				$strText);

//			if ($this->blnDropTarget)
//				$strToReturn .= sprintf('<span id="%s_ctldzmask" style="position:absolute;"><span style="font-size: 1px">&nbsp;</span></span>', $this->strControlId);

			return $strToReturn;
		}

		/**
		 * Return the inner html between the tags.
		 *
		 * @return string
		 */
		protected function GetInnerHtml() {
			if ($this->strFormat) {
				$strText = sprintf($this->strFormat, $this->strText);
			}
			else {
				$strText = $this->strText;
			}

			if ($this->blnHtmlEntities) {
				$strText = QApplication::HtmlEntities($strText);
			}

			$strTemplateEvaluated = '';
			if ($this->strTemplate) {
				global $_CONTROL;
				$objCurrentControl = $_CONTROL;
				$_CONTROL = $this;
				$strTemplateEvaluated = $this->EvaluateTemplate($this->strTemplate);
				$_CONTROL = $objCurrentControl;
			}

			$strText .= $strTemplateEvaluated;

			if ($this->blnAutoRenderChildren) {
				$strText .= $this->RenderChildren(false);
			}
			return $strText;
		}

		/**
		 * Public function to be overrriden by child classes
		 *
		 * It is used to determine if the input fed into the control is valid or not.
		 * The rules are written in this function only. If the control is set for Validation,
		 * this function is automatically called on postback.
		 * @return bool Whether or not the input inside the control are valid
		 */
		public function Validate() {return true;}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Name of the property
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Text": return $this->strText;
				case "Format": return $this->strFormat;
				case "Template": return $this->strTemplate;
				case "AutoRenderChildren": return $this->blnAutoRenderChildren;
				case "TagName": return $this->strTagName;
				case "HtmlEntities": return $this->blnHtmlEntities;

				// BEHAVIOR
				case "DropTarget": return $this->blnDropTarget;

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
		 * PHP __set magic method implementation
		 * @param string $strName Property Name
		 * @param string $mixValue Property Value
		 *
		 * @return mixed
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				// APPEARANCE
				case "Text":
					try {
						if ($this->strText !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->blnModified = true;
							$this->strText = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Format":
					try {
						if ($this->strFormat !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->blnModified = true;
							$this->strFormat = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Template":
					try {
						$this->blnModified = true;
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
						if ($this->blnAutoRenderChildren !== ($mixValue = QType::Cast($mixValue, QType::Boolean))) {
							$this->blnModified = true;
							$this->blnAutoRenderChildren = $mixValue;
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "TagName":
					try {
						if ($this->strTagName !== ($mixValue = QType::Cast($mixValue, QType::String))) {
							$this->blnModified = true;
							$this->strTagName = $mixValue;
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

				case "DropTarget":
					try {
						if ($this->blnDropTarget !== ($mixValue = QType::Cast($mixValue, QType::Boolean))) {
							$this->blnModified = true;
							$this->blnDropTarget = $mixValue;
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

	}

	$_CONTROL = null;

?>