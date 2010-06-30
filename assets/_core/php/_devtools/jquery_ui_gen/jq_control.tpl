<?php
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

		protected function makeJsProperty($strProp, $strKey, $strQuote = "'") {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJson($objValue, $strQuote) . ', ';
		}

		protected function makeJqOptions() {
			$strJson = '{';
<% foreach ($objJqDoc->options as $option) { %>
			$strJson .= $this->makeJsProperty('<%= $option->propName %>', '<%= $option->name %>');
<% } %>
			return $strJson.'}';
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

			$strArgs = JavaScriptHelper::toJson($args);
			$strJs = sprintf('jQuery("#%s")<%= $method->call %>(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

<% } %>

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
						if ($mixValue instanceof QAjaxAction) {
						    /** @var QAjaxAction $mixValue */
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
