<?php
	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the droppable. Can be set when
	 * 		initialising (first creating) the droppable.
	 * @property mixed $Accept All draggables that match the selector will be accepted. If a function is
	 * 		specified, the function will be called for each draggable on the page
	 * 		(passed as the first argument to the function), to provide a custom filter.
	 * 		The function should return true if the draggable should be accepted.
	 * @property string $ActiveClass If specified, the class will be added to the droppable while an acceptable
	 * 		draggable is being dragged.
	 * @property boolean $AddClasses If set to false, will prevent the ui-droppable class from being added. This
	 * 		may be desired as a performance optimization when calling .droppable() init
	 * 		on many hundreds of elements.
	 * @property boolean $Greedy If true, will prevent event propagation on nested droppables.
	 * @property string $HoverClass If specified, the class will be added to the droppable while an acceptable
	 * 		draggable is being hovered.
	 * @property string $Scope Used to group sets of draggable and droppable items, in addition to
	 * 		droppable's accept option. A draggable with the same scope value as a
	 * 		droppable will be accepted.
	 * @property string $Tolerance Specifies which mode to use for testing whether a draggable is 'over' a
	 * 		droppable. Possible values: 'fit', 'intersect', 'pointer', 'touch'.
	 * 
	 * 
	 * fit:
	 * 		draggable overlaps the droppable entirely
	 * intersect: draggable overlaps the
	 * 		droppable at least 50%
	 * pointer: mouse pointer overlaps the droppable
	 * touch:
	 * 		draggable overlaps the droppable any amount
	 * @property QJsClosure $OnActivate This event is triggered any time an accepted draggable starts dragging.
	 * 		This can be useful if you want to make the droppable 'light up' when it can
	 * 		be dropped on.
	 * @property QJsClosure $OnDeactivate This event is triggered any time an accepted draggable stops dragging.
	 * @property QJsClosure $OnOver This event is triggered as an accepted draggable is dragged 'over' (within
	 * 		the tolerance of) this droppable.
	 * @property QJsClosure $OnOut This event is triggered when an accepted draggable is dragged out (within
	 * 		the tolerance of) this droppable.
	 * @property QJsClosure $OnDrop This event is triggered when an accepted draggable is dropped 'over'
	 * 		(within the tolerance of) this droppable. In the callback, $(this)
	 * 		represents the droppable the draggable is dropped on.
	 * ui.draggable
	 * 		represents the draggable.
	 */

	class QDroppableBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var mixed */
		protected $mixAccept = null;
		/** @var string */
		protected $strActiveClass = null;
		/** @var boolean */
		protected $blnAddClasses = null;
		/** @var boolean */
		protected $blnGreedy = null;
		/** @var string */
		protected $strHoverClass = null;
		/** @var string */
		protected $strScope = null;
		/** @var string */
		protected $strTolerance = null;
		/** @var QJsClosure */
		protected $mixOnActivate = null;
		/** @var QJsClosure */
		protected $mixOnDeactivate = null;
		/** @var QJsClosure */
		protected $mixOnOver = null;
		/** @var QJsClosure */
		protected $mixOnOut = null;
		/** @var QJsClosure */
		protected $mixOnDrop = null;

		protected function makeJsProperty($strProp, $strKey, $strQuote = "'") {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJson($objValue, $strQuote) . ', ';
		}

		protected function makeJqOptions() {
			$strJson = '{';
			$strJson .= $this->makeJsProperty('Disabled', 'disabled');
			$strJson .= $this->makeJsProperty('Accept', 'accept');
			$strJson .= $this->makeJsProperty('ActiveClass', 'activeClass');
			$strJson .= $this->makeJsProperty('AddClasses', 'addClasses');
			$strJson .= $this->makeJsProperty('Greedy', 'greedy');
			$strJson .= $this->makeJsProperty('HoverClass', 'hoverClass');
			$strJson .= $this->makeJsProperty('Scope', 'scope');
			$strJson .= $this->makeJsProperty('Tolerance', 'tolerance');
			$strJson .= $this->makeJsProperty('OnActivate', 'activate');
			$strJson .= $this->makeJsProperty('OnDeactivate', 'deactivate');
			$strJson .= $this->makeJsProperty('OnOver', 'over');
			$strJson .= $this->makeJsProperty('OnOut', 'out');
			$strJson .= $this->makeJsProperty('OnDrop', 'drop');
			return $strJson.'}';
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'droppable';
		}

		public function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();

			$strJs = sprintf('jQuery("#%s").%s(%s)', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
			QApplication::ExecuteJavaScript($strJs);
			return $strToReturn;
		}

		/**
		 * Remove the droppable functionality completely. This will return the element
		 * back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").droppable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the droppable.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").droppable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the droppable.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").droppable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any droppable option. If no value is specified, will act as a
		 * getter.
		 * @param $optionName
		 * @param $value
		 */
		public function Option($optionName, $value = null) {
			$args = array();
			$args[] = "option";
			$args[] = $optionName;
			if ($value !== null) {
				$args[] = $value;
			}

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").droppable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple droppable options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s").droppable(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'Accept': return $this->mixAccept;
				case 'ActiveClass': return $this->strActiveClass;
				case 'AddClasses': return $this->blnAddClasses;
				case 'Greedy': return $this->blnGreedy;
				case 'HoverClass': return $this->strHoverClass;
				case 'Scope': return $this->strScope;
				case 'Tolerance': return $this->strTolerance;
				case 'OnActivate': return $this->mixOnActivate;
				case 'OnDeactivate': return $this->mixOnDeactivate;
				case 'OnOver': return $this->mixOnOver;
				case 'OnOut': return $this->mixOnOut;
				case 'OnDrop': return $this->mixOnDrop;
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
			$this->blnModified = true;

			switch ($strName) {
				case 'Disabled':
					try {
						$this->blnDisabled = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Accept':
					$this->mixAccept = $mixValue;
					break;

				case 'ActiveClass':
					try {
						$this->strActiveClass = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AddClasses':
					try {
						$this->blnAddClasses = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Greedy':
					try {
						$this->blnGreedy = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'HoverClass':
					try {
						$this->strHoverClass = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Scope':
					try {
						$this->strScope = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tolerance':
					try {
						$this->strTolerance = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnActivate':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnActivate = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnDeactivate':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnDeactivate = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnOver':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnOver = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnOut':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnOut = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnDrop':
					try {
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnDrop = QType::Cast($mixValue, 'QJsClosure');
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
