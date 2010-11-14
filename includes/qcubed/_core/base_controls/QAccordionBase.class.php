<?php
	/* Custom event classes for this control */
	/**
	 * This event is triggered every time the accordion changes. If the accordion
	 * 		is animated, the event will be triggered upon completion of the animation;
	 * 		otherwise, it is triggered
	 * 		immediately.
	 * 
	 * $('.ui-accordion').bind('accordionchange', function(event,
	 * 		ui) {
	 *   ui.newHeader // jQuery object, activated header
	 *   ui.oldHeader //
	 * 		jQuery object, previous header
	 *   ui.newContent // jQuery object, activated
	 * 		content
	 *   ui.oldContent // jQuery object, previous content
	 * });</p>
	 */
	class QAccordion_ChangeEvent extends QEvent {
		const EventName = 'QAccordion_Change';
	}

	/**
	 * This event is triggered every time the accordion starts to
	 * 		change.
	 * 
	 * $('.ui-accordion').bind('accordionchangestart', function(event,
	 * 		ui) {
	 *   ui.newHeader // jQuery object, activated header
	 *   ui.oldHeader //
	 * 		jQuery object, previous header
	 *   ui.newContent // jQuery object, activated
	 * 		content
	 *   ui.oldContent // jQuery object, previous content
	 * });</p>
	 */
	class QAccordion_ChangestartEvent extends QEvent {
		const EventName = 'QAccordion_Changestart';
	}


	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the accordion. Can be set when
	 * 		initialising (first creating) the accordion.
	 * @property mixed $Active Selector for the active element. Set to false to display none at start.
	 * 		Needs collapsible: true.
	 * @property mixed $Animated Choose your favorite animation, or disable them (set to false). In addition
	 * 		to the default, 'bounceslide' and all defined easing methods are supported
	 * 		('bounceslide' requires UI Effects Core).
	 * @property boolean $AutoHeight If set, the highest content part is used as height reference for all other
	 * 		parts. Provides more consistent animations.
	 * @property boolean $ClearStyle If set, clears height and overflow styles after finishing animations. This
	 * 		enables accordions to work with dynamic content. Won't work together with
	 * 		autoHeight.
	 * @property boolean $Collapsible Whether all the sections can be closed at once. Allows collapsing the
	 * 		active section by the triggering event (click is the default).
	 * @property string $Event The event on which to trigger the accordion.
	 * @property boolean $FillSpace If set, the accordion completely fills the height of the parent element.
	 * 		Overrides autoheight.
	 * @property mixed $Header Selector for the header element.
	 * @property mixed $Icons Icons to use for headers. Icons may be specified for 'header' and
	 * 		'headerSelected', and we recommend using the icons native to the jQuery UI
	 * 		CSS Framework manipulated by jQuery UI ThemeRoller
	 * @property boolean $Navigation If set, looks for the anchor that matches location.href and activates it.
	 * 		Great for href-based state-saving. Use navigationFilter to implement your
	 * 		own matcher.
	 * @property QJsClosure $NavigationFilter Overwrite the default location.href-matching with your own matcher.
	 * @property QJsClosure $OnChange This event is triggered every time the accordion changes. If the accordion
	 * 		is animated, the event will be triggered upon completion of the animation;
	 * 		otherwise, it is triggered
	 * 		immediately.
	 * 
	 * $('.ui-accordion').bind('accordionchange', function(event,
	 * 		ui) {
	 *   ui.newHeader // jQuery object, activated header
	 *   ui.oldHeader //
	 * 		jQuery object, previous header
	 *   ui.newContent // jQuery object, activated
	 * 		content
	 *   ui.oldContent // jQuery object, previous content
	 * });</p>
	 * @property QJsClosure $OnChangestart This event is triggered every time the accordion starts to
	 * 		change.
	 * 
	 * $('.ui-accordion').bind('accordionchangestart', function(event,
	 * 		ui) {
	 *   ui.newHeader // jQuery object, activated header
	 *   ui.oldHeader //
	 * 		jQuery object, previous header
	 *   ui.newContent // jQuery object, activated
	 * 		content
	 *   ui.oldContent // jQuery object, previous content
	 * });</p>
	 */

	class QAccordionBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var mixed */
		protected $mixActive;
		/** @var mixed */
		protected $mixAnimated = null;
		/** @var boolean */
		protected $blnAutoHeight = null;
		/** @var boolean */
		protected $blnClearStyle = null;
		/** @var boolean */
		protected $blnCollapsible = null;
		/** @var string */
		protected $strEvent = null;
		/** @var boolean */
		protected $blnFillSpace = null;
		/** @var mixed */
		protected $mixHeader = null;
		/** @var mixed */
		protected $mixIcons = null;
		/** @var boolean */
		protected $blnNavigation = null;
		/** @var QJsClosure */
		protected $mixNavigationFilter;
		/** @var QJsClosure */
		protected $mixOnChange = null;
		/** @var QJsClosure */
		protected $mixOnChangestart = null;

		/** @var array $custom_events Event Class Name => Event Property Name */
		protected static $custom_events = array(
			'QAccordion_ChangeEvent' => 'OnChange',
			'QAccordion_ChangestartEvent' => 'OnChangestart',
		);
		
		protected function makeJsProperty($strProp, $strKey) {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJsObject($objValue) . ', ';
		}

		protected function makeJqOptions() {
			$strJqOptions = '';
			$strJqOptions .= $this->makeJsProperty('Disabled', 'disabled');
			$strJqOptions .= $this->makeJsProperty('Active', 'active');
			$strJqOptions .= $this->makeJsProperty('Animated', 'animated');
			$strJqOptions .= $this->makeJsProperty('AutoHeight', 'autoHeight');
			$strJqOptions .= $this->makeJsProperty('ClearStyle', 'clearStyle');
			$strJqOptions .= $this->makeJsProperty('Collapsible', 'collapsible');
			$strJqOptions .= $this->makeJsProperty('Event', 'event');
			$strJqOptions .= $this->makeJsProperty('FillSpace', 'fillSpace');
			$strJqOptions .= $this->makeJsProperty('Header', 'header');
			$strJqOptions .= $this->makeJsProperty('Icons', 'icons');
			$strJqOptions .= $this->makeJsProperty('Navigation', 'navigation');
			$strJqOptions .= $this->makeJsProperty('NavigationFilter', 'navigationFilter');
			$strJqOptions .= $this->makeJsProperty('OnChange', 'change');
			$strJqOptions .= $this->makeJsProperty('OnChangestart', 'changestart');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'accordion';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			return  $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}

		/**
		 * Remove the accordion functionality completely. This will return the element
		 * back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").accordion(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the accordion.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").accordion(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the accordion.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").accordion(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any accordion option. If no value is specified, will act as a
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

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").accordion(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple accordion options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").accordion(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Activate a content part of the Accordion programmatically. The index can be
		 * a zero-indexed number to match the position of the header to close or a
		 * Selector matching an element. Pass false to close all (only possible with
		 * collapsible:true).
		 * @param $index
		 */
		public function Activate($index) {
			$args = array();
			$args[] = "activate";
			$args[] = $index;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").accordion(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Recompute heights of the accordion contents when using the fillSpace option
		 * and the container height changed. For example, when the container is a
		 * resizable, this method should be called by its resize-event.
		 */
		public function Resize() {
			$args = array();
			$args[] = "resize";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").accordion(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * returns the property name corresponding to the given custom event
		 * @param QEvent $objEvent the custom event
		 * @return the property name corresponding to $objEvent
		 */
		protected function getCustomEventPropertyName(QEvent $objEvent) {
			$strEventClass = get_class($objEvent);
			if (array_key_exists($strEventClass, QAccordion::$custom_events))
				return QAccordion::$custom_events[$strEventClass];
			return null;
		}

		/**
		 * Wraps $objAction into an object (typically a QJsClosure) that can be assigned to the corresponding Event
		 * property (e.g. OnFocus)
		 * @param QEvent $objEvent
		 * @param QAction $objAction
		 * @return mixed the wrapped object
		 */
		protected function createEventWrapper(QEvent $objEvent, QAction $objAction) {
			$objAction->Event = $objEvent;
			return new QJsClosure($objAction->RenderScript($this));
		}

		/**
		 * If $objEvent is one of the custom events (as determined by getCustomEventPropertyName() method)
		 * the corresponding JQuery event is used and if needed a no-script action is added. Otherwise the normal
		 * QCubed AddAction is performed.
		 * @param QEvent  $objEvent
		 * @param QAction $objAction
		 */
		public function AddAction($objEvent, $objAction) {
			$strEventName = $this->getCustomEventPropertyName($objEvent);
			if ($strEventName) {
				$this->$strEventName = $this->createEventWrapper($objEvent, $objAction);
				if ($objAction instanceof QAjaxAction) {
					$objAction = new QNoScriptAjaxAction($objAction);
					parent::AddAction($objEvent, $objAction);
				} else if (!($objAction instanceof QJavaScriptAction)) {
					throw new Exception('handling of "' . get_class($objAction) . '" actions with "' . get_class($objEvent) . '" events not yet implemented');
				}
			} else {
				parent::AddAction($objEvent, $objAction);
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'Active': return $this->mixActive;
				case 'Animated': return $this->mixAnimated;
				case 'AutoHeight': return $this->blnAutoHeight;
				case 'ClearStyle': return $this->blnClearStyle;
				case 'Collapsible': return $this->blnCollapsible;
				case 'Event': return $this->strEvent;
				case 'FillSpace': return $this->blnFillSpace;
				case 'Header': return $this->mixHeader;
				case 'Icons': return $this->mixIcons;
				case 'Navigation': return $this->blnNavigation;
				case 'NavigationFilter': return $this->mixNavigationFilter;
				case 'OnChange': return $this->mixOnChange;
				case 'OnChangestart': return $this->mixOnChangestart;
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

				case 'Active':
					$this->mixActive = $mixValue;
					break;

				case 'Animated':
					$this->mixAnimated = $mixValue;
					break;

				case 'AutoHeight':
					try {
						$this->blnAutoHeight = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ClearStyle':
					try {
						$this->blnClearStyle = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Collapsible':
					try {
						$this->blnCollapsible = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Event':
					try {
						$this->strEvent = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'FillSpace':
					try {
						$this->blnFillSpace = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Header':
					$this->mixHeader = $mixValue;
					break;

				case 'Icons':
					$this->mixIcons = $mixValue;
					break;

				case 'Navigation':
					try {
						$this->blnNavigation = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NavigationFilter':
					try {
						$this->mixNavigationFilter = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnChange':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnChange = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnChangestart':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnChangestart = QType::Cast($mixValue, 'QJsClosure');
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
