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
			$strJqOptions = '{';
<% foreach ($objJqDoc->options as $option) { %>
			$strJqOptions .= $this->makeJsProperty('<%= $option->propName %>', '<%= $option->name %>');
<% } %>
			return $strJqOptions.'}';
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return '<%= $objJqDoc->strJqSetupFunc %>';
		}

		public function GetControlHtml() {
			$strToReturn = parent::GetControlHtml();

			$strJs = sprintf('jQuery("#%s").%s(%s)', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
			QApplication::ExecuteJavaScript($strJs);
			return $strToReturn;
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

		public function AddAction($objEvent, $objAction) {
			$strEventClass = get_class($objEvent);
			if (array_key_exists($strEventClass, self::$custom_events)) {
				$objAction->Event = $objEvent;
				$strEventName = self::$custom_events[$strEventClass];
				$this->$strEventName = new QJsClosure($objAction->RenderScript($this));
				if ($objAction instanceof QAjaxAction) {
					$objAction = new QNoScriptAjaxAction($objAction);
					parent::AddAction($objEvent, $objAction);
				} else if (!($objAction instanceof QJavaScriptAction)) {
					throw new Exception('handling of "' . get_class($objAction) . '" actions with "' . $strEventClass . '" events not yet implemented');
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
