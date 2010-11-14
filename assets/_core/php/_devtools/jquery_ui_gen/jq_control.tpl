<?php
	/* Custom event classes for this control */
<% foreach ($objJqDoc->options as $option) { %>
	<% if ($option instanceof Event) { %>
	/**
	 * <%= str_replace("\n", "\n\t * ", wordwrap(trim($option->description), 75, "\n\t\t")) %>
	 */
	class <%= $option->eventClassName %> extends QEvent {
		const EventName = '<%= $option->eventName %>';
	}

	<% } %>
<% } %>

	/**
<% foreach ($objJqDoc->options as $option) { %>
	 * @property <%= $option->phpType %> $<%= $option->propName %> <%= str_replace("\n", "\n\t * ", wordwrap(trim($option->description), 75, "\n\t\t")) %>
<% } %>
	 */

	class <%= $objJqDoc->strQcClass %>Base extends <%= $objJqDoc->strQcBaseClass %>	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
<% foreach ($objJqDoc->options as $option) { %>
		/** @var <%= $option->phpType %> */
	<% if (!$option->defaultValue) { %>
		protected $<%= $option->varName %>;
	<% } %>
	<% if ($option->defaultValue) { %>
		protected $<%= $option->varName %> = null;
	<% } %>
<% } %>

		/** @var array $custom_events Event Class Name => Event Property Name */
		protected static $custom_events = array(
<% foreach ($objJqDoc->options as $option) { %>
	<% if ($option instanceof Event) { %>
			'<%= $option->eventClassName %>' => '<%= $option->propName %>',
	<% } %>
<% } %>
		);
		
		protected function makeJsProperty($strProp, $strKey) {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJsObject($objValue) . ', ';
		}

		protected function makeJqOptions() {
<% if (method_exists($objJqDoc->strQcBaseClass, 'makeJqOptions')) { %>
			$strJqOptions = parent::makeJqOptions();
			if ($strJqOptions) $strJqOptions .= ', ';
<% } %>
<% if (!method_exists($objJqDoc->strQcBaseClass, 'makeJqOptions')) { %>
			$strJqOptions = '';
<% } %>
<% foreach ($objJqDoc->options as $option) { %>
			$strJqOptions .= $this->makeJsProperty('<%= $option->propName %>', '<%= $option->name %>');
<% } %>
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return '<%= $objJqDoc->strJqSetupFunc %>';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			return  $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}

<% foreach ($objJqDoc->methods as $method) { %>
		/**
		 * <%= str_replace("\n", "\n\t\t * ", wordwrap(trim($method->description))) %>
<% foreach ($method->requiredArgs as $reqArg) { %>
    <% if ($reqArg{0} != '"') { %>
		 * @param <%= $reqArg %>
    <% } %>
<% } %>
<% foreach ($method->optionalArgs as $optArg) { %>
		 * @param <%= $optArg %>
<% } %>
		 */
		public function <%= $method->phpSignature %> {
			$args = array();
<% foreach ($method->requiredArgs as $reqArg) { %>
			$args[] = <%= $reqArg %>;
<% } %>
<% foreach ($method->optionalArgs as $optArg) { %>
			if (<%= $optArg %> !== null) {
				$args[] = <%= $optArg %>;
			}
<% } %>

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s")<%= $method->call %>(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

<% } %>
		/**
		 * returns the property name corresponding to the given custom event
		 * @param QEvent $objEvent the custom event
		 * @return the property name corresponding to $objEvent
		 */
		protected function getCustomEventPropertyName(QEvent $objEvent) {
			$strEventClass = get_class($objEvent);
			if (array_key_exists($strEventClass, <%= $objJqDoc->strQcClass %>::$custom_events))
				return <%= $objJqDoc->strQcClass %>::$custom_events[$strEventClass];
<% if (method_exists($objJqDoc->strQcBaseClass, 'getCustomEventPropertyName')) { %>
			return parent::getCustomEventPropertyName($objEvent);
<% } %>
<% if (!method_exists($objJqDoc->strQcBaseClass, 'getCustomEventPropertyName')) { %>
			return null;
<% } %>
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
<% foreach ($objJqDoc->options as $option) { %>
				case '<%= $option->propName %>': return $this-><%= $option->varName %>;
<% } %>
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
<% foreach ($objJqDoc->options as $option) { %>
				case '<%= $option->propName %>':
	<% if (!$option->phpQType) { %>
					$this-><%= $option->varName %> = $mixValue;
					break;
	<% } %>
	<% if ($option->phpQType) { %>
					try {
	    <% if ($option instanceof Event) { %>
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
    	<% } %>
						$this-><%= $option->varName %> = QType::Cast($mixValue, <%= $option->phpQType %>);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
	<% } %>

<% } %>
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
