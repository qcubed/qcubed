<?php
	/* Custom event classes for this control */
	/**
	 * This event is triggered when clicking a tab.
	 */
	class QTabs_SelectEvent extends QEvent {
		const EventName = 'QTabs_Select';
	}

	/**
	 * This event is triggered after the content of a remote tab has been loaded.
	 */
	class QTabs_LoadEvent extends QEvent {
		const EventName = 'QTabs_Load';
	}

	/**
	 * This event is triggered when a tab is shown.
	 */
	class QTabs_ShowEvent extends QEvent {
		const EventName = 'QTabs_Show';
	}

	/**
	 * This event is triggered when a tab is added.
	 */
	class QTabs_AddEvent extends QEvent {
		const EventName = 'QTabs_Add';
	}

	/**
	 * This event is triggered when a tab is removed.
	 */
	class QTabs_RemoveEvent extends QEvent {
		const EventName = 'QTabs_Remove';
	}

	/**
	 * This event is triggered when a tab is enabled.
	 */
	class QTabs_EnableEvent extends QEvent {
		const EventName = 'QTabs_Enable';
	}

	/**
	 * This event is triggered when a tab is disabled.
	 */
	class QTabs_DisableEvent extends QEvent {
		const EventName = 'QTabs_Disable';
	}


	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the tabs. Can be set when initialising
	 * 		(first creating) the tabs.
	 * @property array $AjaxOptions Additional Ajax options to consider when loading tab content (see $.ajax).
	 * @property boolean $Cache Whether or not to cache remote tabs content, e.g. load only once or with
	 * 		every click. Cached content is being lazy loaded, e.g once and only once
	 * 		for the first click. Note that to prevent the actual Ajax requests from
	 * 		being cached by the browser you need to provide an extra cache: false flag
	 * 		to ajaxOptions.
	 * @property boolean $Collapsible Set to true to allow an already selected tab to become unselected again
	 * 		upon reselection.
	 * @property mixed $Cookie Store the latest selected tab in a cookie. The cookie is then used to
	 * 		determine the initially selected tab if the selected option is not defined.
	 * 		Requires cookie plugin. The object needs to have key/value pairs of the
	 * 		form the cookie plugin expects as options. Available options (example):
	 * 		&#123; expires: 7, path: '/', domain: 'jquery.com', secure: true &#125;.
	 * 		Since jQuery UI 1.7 it is also possible to define the cookie name being
	 * 		used via name property.
	 * @property boolean $Deselectable deprecated in jQuery UI 1.7, use collapsible.
	 * @property array $Disabled1 An array containing the position of the tabs (zero-based index) that should
	 * 		be disabled on initialization.
	 * @property string $Event The type of event to be used for selecting a tab.
	 * @property mixed $Fx Enable animations for hiding and showing tab panels. The duration option
	 * 		can be a string representing one of the three predefined speeds ("slow",
	 * 		"normal", "fast") or the duration in milliseconds to run an animation
	 * 		(default is "normal").
	 * @property string $IdPrefix If the remote tab, its anchor element that is, has no title attribute to
	 * 		generate an id from, an id/fragment identifier is created from this prefix
	 * 		and a unique id returned by $.data(el), for example "ui-tabs-54".
	 * @property string $PanelTemplate HTML template from which a new tab panel is created in case of adding a tab
	 * 		with the add method or when creating a panel for a remote tab on the fly.
	 * @property integer $Selected Zero-based index of the tab to be selected on initialization. To set all
	 * 		tabs to unselected pass -1 as value.
	 * @property string $Spinner The HTML content of this string is shown in a tab title while remote
	 * 		content is loading. Pass in empty string to deactivate that behavior. An
	 * 		span element must be present in the A tag of the title, for the spinner
	 * 		content to be visible.
	 * @property string $TabTemplate HTML template from which a new tab is created and added. The placeholders
	 * 		#&#123;href&#125; and #&#123;label&#125; are replaced with the url and tab
	 * 		label that are passed as arguments to the add method.
	 * @property QJsClosure $OnSelect This event is triggered when clicking a tab.
	 * @property QJsClosure $OnLoad This event is triggered after the content of a remote tab has been loaded.
	 * @property QJsClosure $OnShow This event is triggered when a tab is shown.
	 * @property QJsClosure $OnAdd This event is triggered when a tab is added.
	 * @property QJsClosure $OnRemove This event is triggered when a tab is removed.
	 * @property QJsClosure $OnEnable This event is triggered when a tab is enabled.
	 * @property QJsClosure $OnDisable This event is triggered when a tab is disabled.
	 */

	class QTabsBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var array */
		protected $arrAjaxOptions = null;
		/** @var boolean */
		protected $blnCache = null;
		/** @var boolean */
		protected $blnCollapsible = null;
		/** @var mixed */
		protected $mixCookie = null;
		/** @var boolean */
		protected $blnDeselectable = null;
		/** @var array */
		protected $arrDisabled1 = null;
		/** @var string */
		protected $strEvent = null;
		/** @var mixed */
		protected $mixFx = null;
		/** @var string */
		protected $strIdPrefix = null;
		/** @var string */
		protected $strPanelTemplate = null;
		/** @var integer */
		protected $intSelected;
		/** @var string */
		protected $strSpinner = null;
		/** @var string */
		protected $strTabTemplate = null;
		/** @var QJsClosure */
		protected $mixOnSelect = null;
		/** @var QJsClosure */
		protected $mixOnLoad = null;
		/** @var QJsClosure */
		protected $mixOnShow = null;
		/** @var QJsClosure */
		protected $mixOnAdd = null;
		/** @var QJsClosure */
		protected $mixOnRemove = null;
		/** @var QJsClosure */
		protected $mixOnEnable = null;
		/** @var QJsClosure */
		protected $mixOnDisable = null;

		/** @var array $custom_events Event Class Name => Event Property Name */
		protected static $custom_events = array(
			'QTabs_SelectEvent' => 'OnSelect',
			'QTabs_LoadEvent' => 'OnLoad',
			'QTabs_ShowEvent' => 'OnShow',
			'QTabs_AddEvent' => 'OnAdd',
			'QTabs_RemoveEvent' => 'OnRemove',
			'QTabs_EnableEvent' => 'OnEnable',
			'QTabs_DisableEvent' => 'OnDisable',
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
			$strJqOptions .= $this->makeJsProperty('AjaxOptions', 'ajaxOptions');
			$strJqOptions .= $this->makeJsProperty('Cache', 'cache');
			$strJqOptions .= $this->makeJsProperty('Collapsible', 'collapsible');
			$strJqOptions .= $this->makeJsProperty('Cookie', 'cookie');
			$strJqOptions .= $this->makeJsProperty('Deselectable', 'deselectable');
			$strJqOptions .= $this->makeJsProperty('Disabled1', 'disabled');
			$strJqOptions .= $this->makeJsProperty('Event', 'event');
			$strJqOptions .= $this->makeJsProperty('Fx', 'fx');
			$strJqOptions .= $this->makeJsProperty('IdPrefix', 'idPrefix');
			$strJqOptions .= $this->makeJsProperty('PanelTemplate', 'panelTemplate');
			$strJqOptions .= $this->makeJsProperty('Selected', 'selected');
			$strJqOptions .= $this->makeJsProperty('Spinner', 'spinner');
			$strJqOptions .= $this->makeJsProperty('TabTemplate', 'tabTemplate');
			$strJqOptions .= $this->makeJsProperty('OnSelect', 'select');
			$strJqOptions .= $this->makeJsProperty('OnLoad', 'load');
			$strJqOptions .= $this->makeJsProperty('OnShow', 'show');
			$strJqOptions .= $this->makeJsProperty('OnAdd', 'add');
			$strJqOptions .= $this->makeJsProperty('OnRemove', 'remove');
			$strJqOptions .= $this->makeJsProperty('OnEnable', 'enable');
			$strJqOptions .= $this->makeJsProperty('OnDisable', 'disable');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'tabs';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			return  $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}

		/**
		 * Remove the tabs functionality completely. This will return the element back
		 * to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the tabs.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the tabs.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any tabs option. If no value is specified, will act as a getter.
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
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple tabs options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Add a new tab. The second argument is either a URL consisting of a fragment
		 * identifier only to create an in-page tab or a full url (relative or
		 * absolute, no cross-domain support) to turn the new tab into an Ajax
		 * (remote) tab. The third is the zero-based position where to insert the new
		 * tab. Optional, by default a new tab is appended at the end.
		 * @param $url
		 * @param $label
		 * @param $index
		 */
		public function Add($url, $label, $index = null) {
			$args = array();
			$args[] = "add";
			$args[] = $url;
			$args[] = $label;
			if ($index !== null) {
				$args[] = $index;
			}

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Remove a tab. The second argument is the zero-based index of the tab to be
		 * removed.
		 * @param $index
		 */
		public function Remove($index) {
			$args = array();
			$args[] = "remove";
			$args[] = $index;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable a disabled tab.  To enable more than one tab at once reset the
		 * disabled property like: $('#example').tabs("option","disabled",[]);. The
		 * second argument is the zero-based index of the tab to be enabled.
		 * @param $index
		 */
		public function Enable1($index) {
			$args = array();
			$args[] = "enable";
			$args[] = $index;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable a tab. The selected tab cannot be disabled. To disable more than
		 * one tab at once use: $('#example').tabs("option","disabled", [1, 2, 3]); 
		 * The second argument is the zero-based index of the tab to be disabled.
		 * @param $index
		 */
		public function Disable1($index) {
			$args = array();
			$args[] = "disable";
			$args[] = $index;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Select a tab, as if it were clicked. The second argument is the zero-based
		 * index of the tab to be selected or the id selector of the panel the tab is
		 * associated with (the tab's href fragment identifier, e.g. hash, points to
		 * the panel's id).
		 * @param $index
		 */
		public function Select($index) {
			$args = array();
			$args[] = "select";
			$args[] = $index;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Reload the content of an Ajax tab programmatically. This method always
		 * loads the tab content from the remote location, even if cache is set to
		 * true. The second argument is the zero-based index of the tab to be
		 * reloaded.
		 * @param $index
		 */
		public function Load($index) {
			$args = array();
			$args[] = "load";
			$args[] = $index;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Change the url from which an Ajax (remote) tab will be loaded. The
		 * specified URL will be used for subsequent loads. Note that you can not only
		 * change the URL for an existing remote tab with this method, but also turn
		 * an in-page tab into a remote tab.  The second argument is the zero-based
		 * index of the tab of which its URL is to be updated.  The third is a URL the
		 * content of the tab is loaded from.
		 * @param $index
		 * @param $url
		 */
		public function Url($index, $url) {
			$args = array();
			$args[] = "url";
			$args[] = $index;
			$args[] = $url;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Retrieve the number of tabs of the first matched tab pane.
		 */
		public function Length() {
			$args = array();
			$args[] = "length";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Terminate all running tab ajax requests and animations.
		 */
		public function Abort() {
			$args = array();
			$args[] = "abort";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set up an automatic rotation through tabs of a tab pane.  The second
		 * argument is an amount of time in milliseconds until the next tab in the
		 * cycle gets activated. Use 0 or null to stop the rotation.  The third
		 * controls whether or not to continue the rotation after a tab has been
		 * selected by a user. Default: false.
		 * @param $ms
		 * @param $continuing
		 */
		public function Rotate($ms, $continuing = null) {
			$args = array();
			$args[] = "rotate";
			$args[] = $ms;
			if ($continuing !== null) {
				$args[] = $continuing;
			}

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").tabs(%s)', 
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
			if (array_key_exists($strEventClass, QTabs::$custom_events))
				return QTabs::$custom_events[$strEventClass];
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
				case 'AjaxOptions': return $this->arrAjaxOptions;
				case 'Cache': return $this->blnCache;
				case 'Collapsible': return $this->blnCollapsible;
				case 'Cookie': return $this->mixCookie;
				case 'Deselectable': return $this->blnDeselectable;
				case 'Disabled1': return $this->arrDisabled1;
				case 'Event': return $this->strEvent;
				case 'Fx': return $this->mixFx;
				case 'IdPrefix': return $this->strIdPrefix;
				case 'PanelTemplate': return $this->strPanelTemplate;
				case 'Selected': return $this->intSelected;
				case 'Spinner': return $this->strSpinner;
				case 'TabTemplate': return $this->strTabTemplate;
				case 'OnSelect': return $this->mixOnSelect;
				case 'OnLoad': return $this->mixOnLoad;
				case 'OnShow': return $this->mixOnShow;
				case 'OnAdd': return $this->mixOnAdd;
				case 'OnRemove': return $this->mixOnRemove;
				case 'OnEnable': return $this->mixOnEnable;
				case 'OnDisable': return $this->mixOnDisable;
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

				case 'AjaxOptions':
					try {
						$this->arrAjaxOptions = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Cache':
					try {
						$this->blnCache = QType::Cast($mixValue, QType::Boolean);
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

				case 'Cookie':
					$this->mixCookie = $mixValue;
					break;

				case 'Deselectable':
					try {
						$this->blnDeselectable = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Disabled1':
					try {
						$this->arrDisabled1 = QType::Cast($mixValue, QType::ArrayType);
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

				case 'Fx':
					$this->mixFx = $mixValue;
					break;

				case 'IdPrefix':
					try {
						$this->strIdPrefix = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PanelTemplate':
					try {
						$this->strPanelTemplate = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Selected':
					try {
						$this->intSelected = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Spinner':
					try {
						$this->strSpinner = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'TabTemplate':
					try {
						$this->strTabTemplate = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSelect':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSelect = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnLoad':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnLoad = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnShow':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnShow = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnAdd':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnAdd = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnRemove':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnRemove = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnEnable':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnEnable = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnDisable':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnDisable = QType::Cast($mixValue, 'QJsClosure');
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
