<?php
	/**
	 * QAutocompleteGen File
	 * 
	 * The abstract QAutocompleteGen class defined here is
	 * code-generated and contains options, events and methods scraped from the
	 * JQuery UI documentation Web site. It is not generated by the typical
	 * codegen process, but rather is generated periodically by the core QCubed
	 * team and checked in. However, the code to generate this file is
	 * in the assets/_core/php/_devetools/jquery_ui_gen/jq_control_gen.php file
	 * and you can regenerate the files if you need to.
	 *
	 * The comments in this file are taken from the JQuery UI site, so they do
	 * not always make sense with regard to QCubed. They are simply provided
	 * as reference. Note that this is very low-level code, and does not always
	 * update QCubed state variables. See the QAutocompleteBase 
	 * file, which contains code to interface between this generated file and QCubed.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the QAutocomplete class file.
	 *
	 */

	/* Custom event classes for this control */
	
	
	/**
	 * <div>Triggered when the field is blurred, if the value has
	 * 		changed.</div><ul><li><div><strong>event</strong></div> <div>Type:
	 * 		<a>Event</a></div> <div></div></li> <li><div><strong>ui</strong></div>
	 * 		<div>Type: <a>Object</a></div> <div></div>
	 * 		<ul><li><div><strong>item</strong></div> <div>Type: <a>jQuery</a></div>
	 * 		<div>The item selected from the menu, if any. Otherwise the property is
	 * 		<code>null</code>.</div></li></ul></li></ul>
	 */
	class QAutocomplete_ChangeEvent extends QJqUiEvent {
		const EventName = 'autocompletechange';
	}
	/**
	 * <div>Triggered when the menu is hidden. Not every <code>close</code> event
	 * 		will be accompanied by a <code>change</code>
	 * 		event.</div><ul><li><div><strong>event</strong></div> <div>Type:
	 * 		<a>Event</a></div> <div></div></li> <li><div><strong>ui</strong></div>
	 * 		<div>Type: <a>Object</a></div> <div></div></li></ul>
	 */
	class QAutocomplete_CloseEvent extends QJqUiEvent {
		const EventName = 'autocompleteclose';
	}
	/**
	 * <div>Triggered when the autocomplete is
	 * 		created.</div><ul><li><div><strong>event</strong></div> <div>Type:
	 * 		<a>Event</a></div> <div></div></li> <li><div><strong>ui</strong></div>
	 * 		<div>Type: <a>Object</a></div> <div></div></li></ul>
	 */
	class QAutocomplete_CreateEvent extends QJqUiEvent {
		const EventName = 'autocompletecreate';
	}
	/**
	 * <div>Triggered when focus is moved to an item (not selecting). The default
	 * 		action is to replace the text field's value with the value of the focused
	 * 		item, though only if the event was triggered by a keyboard interaction.
	 * 						<p>Canceling this event prevents the value from being updated, but does
	 * 		not prevent the menu item from being
	 * 		focused.</p></div><ul><li><div><strong>event</strong></div> <div>Type:
	 * 		<a>Event</a></div> <div></div></li> <li><div><strong>ui</strong></div>
	 * 		<div>Type: <a>Object</a></div> <div></div>
	 * 		<ul><li><div><strong>item</strong></div> <div>Type: <a>jQuery</a></div>
	 * 		<div>The focused item.</div></li></ul></li></ul>
	 */
	class QAutocomplete_FocusEvent extends QJqUiEvent {
		const EventName = 'autocompletefocus';
	}
	/**
	 * <div>Triggered when the suggestion menu is opened or
	 * 		updated.</div><ul><li><div><strong>event</strong></div> <div>Type:
	 * 		<a>Event</a></div> <div></div></li> <li><div><strong>ui</strong></div>
	 * 		<div>Type: <a>Object</a></div> <div></div></li></ul>
	 */
	class QAutocomplete_OpenEvent extends QJqUiEvent {
		const EventName = 'autocompleteopen';
	}
	/**
	 * <div>Triggered after a search completes, before the menu is shown. Useful
	 * 		for local manipulation of suggestion data, where a custom
	 * 		<a><code>source</code></a> option callback is not required. This event is
	 * 		always triggered when a search completes, even if the menu will not be
	 * 		shown because there are no results or the Autocomplete is
	 * 		disabled.</div><ul><li><div><strong>event</strong></div> <div>Type:
	 * 		<a>Event</a></div> <div></div></li> <li><div><strong>ui</strong></div>
	 * 		<div>Type: <a>Object</a></div> <div></div>
	 * 		<ul><li><div><strong>content</strong></div> <div>Type: <a>Array</a></div>
	 * 		<div>Contains the response data and can be modified to change the results
	 * 		that will be shown. This data is already normalized, so if you modify the
	 * 		data, make sure to include both <code>value</code> and <code>label</code>
	 * 		properties for each item.</div></li></ul></li></ul>
	 */
	class QAutocomplete_ResponseEvent extends QJqUiEvent {
		const EventName = 'autocompleteresponse';
	}
	/**
	 * <div>Triggered before a search is performed, after
	 * 		<a><code>minLength</code></a> and <a><code>delay</code></a> are met. If
	 * 		canceled, then no request will be started and no items
	 * 		suggested.</div><ul><li><div><strong>event</strong></div> <div>Type:
	 * 		<a>Event</a></div> <div></div></li> <li><div><strong>ui</strong></div>
	 * 		<div>Type: <a>Object</a></div> <div></div></li></ul>
	 */
	class QAutocomplete_SearchEvent extends QJqUiEvent {
		const EventName = 'autocompletesearch';
	}
	/**
	 * <div>Triggered when an item is selected from the menu. The default action
	 * 		is to replace the text field's value with the value of the selected item.
	 * 						<p>Canceling this event prevents the value from being updated, but does
	 * 		not prevent the menu from
	 * 		closing.</p></div><ul><li><div><strong>event</strong></div> <div>Type:
	 * 		<a>Event</a></div> <div></div></li> <li><div><strong>ui</strong></div>
	 * 		<div>Type: <a>Object</a></div> <div></div>
	 * 		<ul><li><div><strong>item</strong></div> <div>Type: <a>jQuery</a></div>
	 * 		<div>The selected item.</div></li></ul></li></ul>
	 */
	class QAutocomplete_SelectEvent extends QJqUiEvent {
		const EventName = 'autocompleteselect';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QAutocompleteGen class.
	 * 
	 * This is the QAutocompleteGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QAutocompleteBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QAutocompleteBase
	 * @package Controls\Base
	 * @property mixed $AppendTo <div>Which element the menu should be appended to. Override this when the
	 * 		autocomplete is inside a <code>position: fixed</code> element. Otherwise
	 * 		the popup menu would still scroll with the page.</div>
	 * @property boolean $AutoFocus <div>If set to <code>true</code> the first item will automatically be
	 * 		focused when the menu is shown.</div>
	 * @property integer $Delay <div>The delay in milliseconds between when a keystroke occurs and when a
	 * 		search is performed. A zero-delay makes sense for local data (more
	 * 		responsive), but can produce a lot of load for remote data, while being
	 * 		less responsive.</div>
	 * @property boolean $Disabled <div>Disables the autocomplete if set to <code>true</code>.</div>
	 * @property integer $MinLength <div>The minimum number of characters a user must type before a search is
	 * 		performed. Zero is useful for local data with just a few items, but a
	 * 		higher value should be used when a single character search could match a
	 * 		few thousand items.</div>
	 * @property mixed $Position <div>Identifies the position of the suggestions menu in relation to the
	 * 		associated input element. The <code>of</code> option defaults to the input
	 * 		element, but you can specify another element to position against. You can
	 * 		refer to the <a>jQuery UI Position</a> utility for more details about the
	 * 		various options.</div>
	 * @property mixed $Source <div>Defines the data to use, must be specified. 				<p>Independent of the
	 * 		variant you use, the label is always treated as text. If you want the label
	 * 		to be treated as html you can use <a>Scott González' html extension</a>.
	 * 		The demos all focus on different variations of the <code>source</code>
	 * 		option - look for one that matches your use case, and check out the
	 * 		code.</p></div><strong>Multiple types
	 * 		supported:</strong><ul><li><strong>Array</strong>:  					An array can be
	 * 		used for local data. There are two supported formats: 					<ul><li>An array
	 * 		of strings: <code>[ "Choice1", "Choice2" ]</code></li> 						<li>An array
	 * 		of objects with <code>label</code> and <code>value</code> properties:
	 * 		<code>[ { label: "Choice1", value: "value1" }, ... ]</code></li></ul>
	 * 							The label property is displayed in the suggestion menu. The value will
	 * 		be inserted into the input element when a user selects an item. If just one
	 * 		property is specified, it will be used for both, e.g., if you provide only
	 * 		<code>value</code> properties, the value will also be used as the
	 * 		label.</li> <li><strong>String</strong>: When a string is used, the
	 * 		Autocomplete plugin expects that string to point to a URL resource that
	 * 		will return JSON data. It can be on the same host or on a different one
	 * 		(must provide JSONP). The Autocomplete plugin does not filter the results,
	 * 		instead a query string is added with a <code>term</code> field, which the
	 * 		server-side script should use for filtering the results. For example, if
	 * 		the <code>source</code> option is set to <code>"http://example.com"</code>
	 * 		and the user types <code>foo</code>, a GET request would be made to
	 * 		<code>http://example.com?term=foo</code>. The data itself can be in the
	 * 		same format as the local data described above.</li>
	 * 		<li><strong>Function</strong>:  					The third variation, a callback,
	 * 		provides the most flexibility and can be used to connect any data source to
	 * 		Autocomplete. The callback gets two arguments: 					<ul><li>A
	 * 		<code>request</code> object, with a single <code>term</code> property,
	 * 		which refers to the value currently in the text input. For example, if the
	 * 		user enters <code>"new yo"</code> in a city field, the Autocomplete term
	 * 		will equal <code>"new yo"</code>.</li> 						<li>A <code>response</code>
	 * 		callback, which expects a single argument: the data to suggest to the user.
	 * 		This data should be filtered based on the provided term, and can be in any
	 * 		of the formats described above for simple local data. It's important when
	 * 		providing a custom source callback to handle errors during the request. You
	 * 		must always call the <code>response</code> callback even if you encounter
	 * 		an error. This ensures that the widget always has the correct
	 * 		state.</li></ul> 					<p>When filtering data locally, you can make use of
	 * 		the built-in <code>$.ui.autocomplete.escapeRegex</code> function. It'll
	 * 		take a single string argument and escape all regex characters, making the
	 * 		result safe to pass to <code>new RegExp()</code>.</p></li></ul>
	 * @package Controls\Base
	 */

	class QAutocompleteGen extends QTextBox	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixAppendTo = null;
		/** @var boolean */
		protected $blnAutoFocus = null;
		/** @var integer */
		protected $intDelay = null;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var integer */
		protected $intMinLength = null;
		/** @var mixed */
		protected $mixPosition = null;
		/** @var mixed */
		protected $mixSource;
		
		protected function makeJsProperty($strProp, $strKey) {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJsObject($objValue) . ', ';
		}

		protected function makeJqOptions() {
			$strJqOptions = '';
			$strJqOptions .= $this->makeJsProperty('AppendTo', 'appendTo');
			$strJqOptions .= $this->makeJsProperty('AutoFocus', 'autoFocus');
			$strJqOptions .= $this->makeJsProperty('Delay', 'delay');
			$strJqOptions .= $this->makeJsProperty('Disabled', 'disabled');
			$strJqOptions .= $this->makeJsProperty('MinLength', 'minLength');
			$strJqOptions .= $this->makeJsProperty('Position', 'position');
			$strJqOptions .= $this->makeJsProperty('Source', 'source');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		public function getJqSetupFunction() {
			return 'autocomplete';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			$str = '';
			if ($this->getJqControlId() !== $this->ControlId) {
				// #845: if the element receiving the jQuery UI events is different than this control
				// we need to clean-up the previously attached event handlers, so that they are not duplicated 
				// during the next ajax update which replaces this control.
				$str = sprintf('jQuery("#%s").off(); ', $this->getJqControlId());
			}
			return $str . $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}
		
		/**
		 * Call a JQuery UI Method on the object. 
		 * 
		 * A helper function to call a jQuery UI Method. Takes variable number of arguments.
		 * 
		 * @param string $strMethodName the method name to call
		 * @internal param $mixed [optional] $mixParam1
		 * @internal param $mixed [optional] $mixParam2
		 */
		protected function CallJqUiMethod($strMethodName /*, ... */) {
			$args = func_get_args();

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").%s(%s)',
				$this->getJqControlId(),
				$this->getJqSetupFunction(),
				substr($strArgs, 1, strlen($strArgs)-2));	// params without brackets
			QApplication::ExecuteJavaScript($strJs);
		}


		/**
		 * <div>Closes the Autocomplete menu. Useful in combination with the
		 * <a><code>search</code></a> method, to close the open
		 * menu.</div><ul><li><div>This method does not accept any
		 * arguments.</div></li></ul>
		 */
		public function Close() {
			$this->CallJqUiMethod("close");
		}
		/**
		 * <div>Removes the autocomplete functionality completely. This will return
		 * the element back to its pre-init state.</div><ul><li><div>This method does
		 * not accept any arguments.</div></li></ul>
		 */
		public function Destroy() {
			$this->CallJqUiMethod("destroy");
		}
		/**
		 * <div>Disables the autocomplete.</div><ul><li><div>This method does not
		 * accept any arguments.</div></li></ul>
		 */
		public function Disable() {
			$this->CallJqUiMethod("disable");
		}
		/**
		 * <div>Enables the autocomplete.</div><ul><li><div>This method does not
		 * accept any arguments.</div></li></ul>
		 */
		public function Enable() {
			$this->CallJqUiMethod("enable");
		}
		/**
		 * <div>Gets the value currently associated with the specified
		 * <code>optionName</code>.</div><ul><li><div><strong>optionName</strong></div>
		 * <div>Type: <a>String</a></div> <div>The name of the option to
		 * get.</div></li></ul>
		 * @param $optionName
		 */
		public function Option($optionName) {
			$this->CallJqUiMethod("option", $optionName);
		}
		/**
		 * <div>Gets an object containing key/value pairs representing the current
		 * autocomplete options hash.</div><ul><li><div>This method does not accept
		 * any arguments.</div></li></ul>
		 */
		public function Option1() {
			$this->CallJqUiMethod("option");
		}
		/**
		 * <div>Sets the value of the autocomplete option associated with the
		 * specified
		 * <code>optionName</code>.</div><ul><li><div><strong>optionName</strong></div>
		 * <div>Type: <a>String</a></div> <div>The name of the option to
		 * set.</div></li> <li><div><strong>value</strong></div> <div>Type:
		 * <a>Object</a></div> <div>A value to set for the option.</div></li></ul>
		 * @param $optionName
		 * @param $value
		 */
		public function Option2($optionName, $value) {
			$this->CallJqUiMethod("option", $optionName, $value);
		}
		/**
		 * <div>Sets one or more options for the
		 * autocomplete.</div><ul><li><div><strong>options</strong></div> <div>Type:
		 * <a>Object</a></div> <div>A map of option-value pairs to
		 * set.</div></li></ul>
		 * @param $options
		 */
		public function Option3($options) {
			$this->CallJqUiMethod("option", $options);
		}
		/**
		 * <div>Triggers a <a><code>search</code></a> event and invokes the data
		 * source if the event is not canceled. Can be used by a selectbox-like button
		 * to open the suggestions when clicked. When invoked with no parameters, the
		 * current input's value is used. Can be called with an empty string and
		 * <code>minLength: 0</code> to display all
		 * items.</div><ul><li><div><strong>value</strong></div> <div>Type:
		 * <a>String</a></div> <div></div></li></ul>
		 * @param $value
		 */
		public function Search($value = null) {
			$this->CallJqUiMethod("search", $value);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'AppendTo': return $this->mixAppendTo;
				case 'AutoFocus': return $this->blnAutoFocus;
				case 'Delay': return $this->intDelay;
				case 'Disabled': return $this->blnDisabled;
				case 'MinLength': return $this->intMinLength;
				case 'Position': return $this->mixPosition;
				case 'Source': return $this->mixSource;
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
				case 'AppendTo':
					$this->mixAppendTo = $mixValue;
				
					if ($this->Rendered) {
						$this->CallJqUiMethod('option', 'appendTo', $mixValue);
					}
					break;

				case 'AutoFocus':
					try {
						$this->blnAutoFocus = QType::Cast($mixValue, QType::Boolean);
						if ($this->Rendered) {
							$this->CallJqUiMethod('option', 'autoFocus', $this->blnAutoFocus);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Delay':
					try {
						$this->intDelay = QType::Cast($mixValue, QType::Integer);
						if ($this->Rendered) {
							$this->CallJqUiMethod('option', 'delay', $this->intDelay);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Disabled':
					try {
						$this->blnDisabled = QType::Cast($mixValue, QType::Boolean);
						if ($this->Rendered) {
							$this->CallJqUiMethod('option', 'disabled', $this->blnDisabled);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MinLength':
					try {
						$this->intMinLength = QType::Cast($mixValue, QType::Integer);
						if ($this->Rendered) {
							$this->CallJqUiMethod('option', 'minLength', $this->intMinLength);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Position':
					$this->mixPosition = $mixValue;
				
					if ($this->Rendered) {
						$this->CallJqUiMethod('option', 'position', $mixValue);
					}
					break;

				case 'Source':
					$this->mixSource = $mixValue;
				
					if ($this->Rendered) {
						$this->CallJqUiMethod('option', 'source', $mixValue);
					}
					break;


				case 'Enabled':
					$this->Disabled = !$mixValue;	// Tie in standard QCubed functionality
					parent::__set($strName, $mixValue);
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

?>
