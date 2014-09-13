<?php
	/**
	 * Autocomplete Base File
	 * 
	 * The QAutocompleteBase class defined here provides an interface between the generated
	 * QAutocompleteGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QAutocomplete.class.php file instead.
	 *
	 */


	/**
	 * @deprecated since Qcubed 2.1.1. Please use QListItem
	 * List items that can be sent to an autocomplete in non-ajax mode. Put them in an array and send to ->Source.
	 */
	class QAutocompleteListItem extends QListItem {
		/**
		 * @deprecated since Qcubed 2.1.1. Please use QListItem
		 * @param $strName
		 * @param $strValue
		 * @param bool $blnSelected
		 * @param null $strItemGroup
		 * @param null $strOverrideParameters
		 */
		public function __construct($strName, $strValue, $blnSelected = false, $strItemGroup = null, $strOverrideParameters = null) {
			parent::__construct($strName, $strValue, $blnSelected, $strItemGroup, $strOverrideParameters);
			trigger_error("QAutocompleteListItem has been deprecated. Please use QListItem", E_USER_NOTICE);
		}

		/**
		 * @deprecated since Qcubed 2.1.1. Please use QListItem
		 * @return string
		 */
		public function toJsObject() {
			trigger_error("QAutocompleteListItem has been deprecated. Please use QListItem", E_USER_NOTICE);
			return JavaScriptHelper::toJsObject(array("value" => $this->Name, "id" => $this->Value));
		}
	}

	/**
	 * Special event to handle source ajax callbacks
	 */
	class QAutocomplete_SourceEvent extends QEvent {
		/** Event Name */
		const EventName = 'QAutocomplete_Source';
	}


	/**
	 * Implements the JQuery UI Autocomplete widget
	 * 
	 * The Autocomplete is JQuery UIs version of a field with an attached drop down menu. As you type in
	 * the field, the menu appears, and the items in the menu are filtered by what the user types. This class allows
	 * you to use an array of QListItems, or an array of database objects as the source. You can also pass this array
	 * statically in the Source parameter at creation time, or dynamically via Ajax by using SetDataBinder, and then
	 * in your data binder function, setting the DataSource parameter.
	 * 
	 * @property string $SelectedId the id of the selected item. When QAutocompleteListItem objects are used for the DataSource, this corresponds to the Value of the item
	 * @property boolean $MustMatch if true, non matching values are not accepted by the input
	 * @property string $MultipleValueDelimiter if set, the Autocomplete will keep appending the new selections to the previous term, delimited by this string.
	 *    This is useful when making QAutocomplete handle multiple values (see http://jqueryui.com/demos/autocomplete/#multiple ).
	 * @property boolean $DisplayHtml if set, the Autocomplete will treat the 'label' portion of each data item as Html.
	 * @property-write array $Source an array of strings, QListItem's, or data objects. To be used at creation time. {@inheritdoc }
	 * @property-write array $DataSource an array of strings, QListItem's, or data objects
	 * @link http://jqueryui.com/autocomplete/
	 * @access private
	 * @package Controls\Base
	 */
	class QAutocompleteBase extends QAutocompleteGen
	{
		const RESPONSE_ATTR = '__qac_response';

		/** @var string */
		protected $strSelectedId = null;
		/** @var boolean */
		protected $blnUseAjax = false;

		/* Moved to QAutoComplete2 plugin */
		//protected $blnMustMatch = false;
		//protected $strMultipleValueDelimiter = null;
		//protected $blnDisplayHtml = false;

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct ($objParentObject, $strControlId);
			$this->AddJavascriptFile('qcubed.autocomplete.js');
		}

		/**
		 * When this filter is passed to QAutocomplete::UseFilter, only the items in the source list that contain the typed term will be shown in the drop-down
		 * This is the default filter used by the jQuery autocomplete. Useful when resetting from a previousely set filter.
		 * @see QAutocomplete::UseFilter
		 */
		const FILTER_CONTAINS ='function(array, term) { var matcher = new RegExp($.ui.autocomplete.escapeRegex(term), "i"); return $.grep(array, function(value) { return matcher.test(value.label || value.value || value); }); }';
		/**
		 * When this filter is passed to QAutocomplete::UseFilter, only the items in the source list that start with the typed term will be shown in the drop-down
		 * @see QAutocomplete::UseFilter
		 */
		const FILTER_STARTS_WITH ='function(array, term) { var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(term), "i"); return $.grep(array, function(value) { return matcher.test(value.label || value.value || value); }); }';

		/**
		 * Set a filter to use when using a simple array as a source (in non-ajax mode). Note that ALL non-ajax autocompletes on the page
		 * will use the new filter.
		 *
		 * @static
		 * @throws QCallerException
		 * @param string|QJsClosure $filter represents a closure that will be used as the global filter function for jQuery autocomplete.
		 * The closure should take two arguments - array and term. array is the list of all available choices, term is what the user typed in the input box.
		 * It should return an array of suggestions to show in the drop-down.
		 * <b>Example:</b> <code>QAutocomplete::UseFilter(QAutocomplete::FILTER_STARTS_WITH)</code>
		 * @return void
		 *
		 * @see QAutocomplete::FILTER_CONTAINS
		 * @see QAutocomplete::FILTER_STARTS_WITH
		 */
		static public function UseFilter($filter) {
			if ($filter instanceof QJsClosure) {
				$filter = $filter->toJsObject();
			} else if (!is_string($filter)) {
				throw new QCallerException("filter must be either a string or an instance of QJsClosure");
			}
			$strJS = '(function($, undefined) { $.ui.autocomplete.filter = ' . $filter . '} (jQuery))';
			QApplication::ExecuteJavaScript($strJS);
		}


		/**
		 * Set the data binder for ajax filtering
		 * 
		 * Call this at creation time to set the data binder of the item list you will display. The data binder 
		 * will be an AjaxAction function, and so will receive the following parameters:
		 * - FormId
		 * - ControlId
		 * - Parameter
		 * The Parameter in particular will be the term that you should use for filtering. There are situations
		 * where the term will not be the same as the contents of the field.
		 *
		 * @param string         $strMethodName    Name of the method which has to be bound
		 * @param QForm|QControl $objParentControl The parent control on which the action is to be bound
		 * @param bool           $blnReturnTermAsParameter Return the terms as a parameter to the handler
		 */
		public function SetDataBinder($strMethodName, $objParentControl = null, $blnReturnTermAsParameter = false) {
			$strBody = '';

			$strJsReturnParam = $this->JsReturnParam();

			
			if ($objParentControl) {
				$objAction = new QAjaxControlAction($objParentControl, $strMethodName, 'default', null, $strJsReturnParam);
			} else {
				$objAction = new QAjaxAction($strMethodName, 'default', null, $strJsReturnParam);
			}
			
			// use the ajax action to generate an ajax script for us, but 
			// since this is an option of the control, we can't actually 'bind' it, so we instead use an
			// empty action to tie the action to the data binder method name
			$objEvent = new QAutocomplete_SourceEvent();
			$objAction->Event = $objEvent;
			$strBody = 'this.response = response;';	// response is a javascript closure, and we have to save it to use it later.
			$strBody .= $objAction->RenderScript($this);
			$this->mixSource = new QJsClosure($strBody, array('request', 'response'));
					
			$this->RemoveAllActions(QAutocomplete_SourceEvent::EventName);
			$objAction = new QNoScriptAjaxAction($objAction);
			parent::AddAction($objEvent, $objAction);
			
			$this->blnUseAjax = true;
			$this->blnModified = true;
		}

		/**
		 * Return the javascript for the return parameter used by the data binder above.
		 * @return string
		 */
		protected function JsReturnParam() {
			return 'request.term';
		}

		// These functions are used to keep track of the selected value, and to implement
		// optional autocomplete functionality.
		/**
		 * Gets the Javascript part of the control which is sent to the client side upon the completion of Render
		 * @return string The JS string
		 */
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			$strJS .= sprintf (';qAutocomplete("%s")', $this->getJqControlId());

			return $strJS;
		}
		
		
		// Response to an ajax request for data
		protected function prepareAjaxList($dataSource) {
			$list = $dataSource ? JavaScriptHelper::toJsObject($dataSource) : "[]";
			$strJS = sprintf('$j("#%s").data("ui-autocomplete").response(%s);', $this->ControlId, $list);
			QApplication::ExecuteJavaScript($strJS, true);
		}

		/**
		 *
		 */
		public function SetEmpty() {
			$this->Text = '';
			$this->SelectedId = null;
		}

		/**
		 * PHP __set Magic method
		 * @param string $strName Property Name
		 * @param string $mixValue Property Value
		 *
		 * @throws Exception|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'DataSource':
					// Assign data to a DataSource from within the data binder function only.
					// Data should be array items that at a minimum contain a 'value' and an 'id'
					// They can also contain a 'label', which will be displayed in the popup menu only
					if ($this->blnUseAjax) {
						$this->prepareAjaxList($mixValue);
					} else {
						$this->Source = $mixValue;
					}
					break;
					
				case "SelectedValue":	// mirror list control
				case 'SelectedId':
					// Set this at creation time to initialize the selected id. 
					// This is also set by the javascript above to keep track of subsequent selections made by the user.
					try {
						if ($mixValue == 'null') {
							$this->strSelectedId = null;
						} else {
							$this->strSelectedId = QType::Cast($mixValue, QType::String);
						}
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				case 'Source':
					try {
						if (is_array ($mixValue) && count($mixValue) > 0 && $mixValue[0] instanceof QListItem) {
							// figure out what item is selected
							foreach ($mixValue as $objItem) {
								if ($objItem->Selected) {
									$this->strSelectedId = $objItem->Value;
									$this->Text = $objItem->Name;
								}
							}
						}
						parent::__set($strName, $mixValue);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
			
		}

		/**
		 * PHP __get magic method implementation
		 * @param string $strName Name of the property
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "SelectedValue":	// mirror list control
				case 'SelectedId': return $this->strSelectedId;

				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}


		/**** Codegen Helpers, used during the Codegen process only. ****/

		public static function Codegen_VarName($strPropName) {
			return 'lst' . $strPropName;
		}

		public static function Codegen_MetaVariableDeclaration (QCodeGen $objCodeGen, QColumn $objColumn) {
			$strClassName = $objCodeGen->FormControlClassForColumn($objColumn);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);

			$strRet = <<<TMPL
		/**
		 * @var {$strClassName} {$strControlVarName}
		 * @access protected
		 */
		protected \${$strControlVarName};

		/**
		* @var obj{$strPropName}Condition
		* @access protected
		*/
		protected \$obj{$strPropName}Condition;

		/**
		* @var obj{$strPropName}Clauses
		* @access protected
		*/
		protected \$obj{$strPropName}Clauses;

TMPL;

			return $strRet;
		}


		/**
		 * Generate code that will be inserted into the MetaControl to connect a database object with this control.
		 * This is called during the codegen process.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @return string
		 */
		public static function Codegen_MetaCreate(QCodeGen $objCodeGen, QTable $objTable, QColumn $objColumn) {
			$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
			$strClassName = $objTable->ClassName;
			$strControlVarName = $objCodeGen->FormControlVariableNameForColumn($objColumn);
			$strLabelId = $objCodeGen->FormLabelVariableNameForColumn($objColumn);
			$strLabelName = QCodeGen::MetaControlLabelNameFromColumn($objColumn);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;

			// Read the control type in case we are generating code for a similar class
			$strControlType = $objCodeGen->FormControlClassForColumn($objColumn);

			$strRet = <<<TMPL
		/**
		 * Create and setup {$strControlType} {$strControlVarName}
		 * @param string \$strControlId optional ControlId to use
		 * @param boolean \$blnAutoLoad true if you want to use the default meta control data loader. Set to false if using your own custom loader.
		 * @param QQCondition \$objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] \$objClauses additional optional QQClause object or array of QQClause objects for the query
		 * @return {$strControlType}
		 */
		public function {$strControlVarName}_Create(\$strControlId = null, QQCondition \$objCondition = null, \$objClauses = null) {

TMPL;
			$strControlIdOverride = $objCodeGen->GenerateControlId($objTable, $objColumn);

			if ($strControlIdOverride) {
				$strRet .= <<<TMPL
			if (!\$strControlId) {
				\$strControlId = '$strControlIdOverride';
			}

TMPL;
			}
			$strRet .= <<<TMPL

			\$this->obj{$strPropName}Condition = \$objCondition;
			\$this->obj{$strPropName}Clauses = \$objClauses;
			\$this->{$strControlVarName} = new {$strControlType}(\$this->objParentObject, \$strControlId);
			\$this->{$strControlVarName}->Name = QApplication::Translate('{$strLabelName}');

TMPL;
			if ($objColumn->NotNull) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->Required = true;

TMPL;
			}

			$options = $objColumn->Options;
			if (!$options || !isset ($options['NoAutoLoad'])) {
				$strRet .= <<<TMPL
			\$this->Source = \$this->{$strControlVarName}_GetItems();

TMPL;
			}

			$strRet .= static::Codegen_MetaCreateOptions ($objColumn);

			$strRet .= <<<TMPL
			return \$this->{$strControlVarName};
		}

TMPL;
			$strRet .= <<<TMPL

		/**
		 *	Create item list for use by {$strControlVarName}. This method of list generating will filter
		 *	using javascript, which works OK for a small list. If tied to a big list, use a data binder
		 *  instead to filter using ajax.
		 */
		 public function {$strControlVarName}_GetItems() {
			\$a = array();
			\$objCondition = \$this->obj{$strPropName}Condition;
			if (is_null(\$objCondition)) \$objCondition = QQ::All();
			\${$objColumn->Reference->VariableName}Cursor = {$objColumn->Reference->VariableType}::QueryCursor(\$objCondition, \$this->obj{$strPropName}Clauses);

			// Iterate through the Cursor
			while (\${$objColumn->Reference->VariableName} = {$objColumn->Reference->VariableType}::InstantiateCursor(\${$objColumn->Reference->VariableName}Cursor)) {
				\$objListItem = new QListItem(\${$objColumn->Reference->VariableName}->__toString(), \${$objColumn->Reference->VariableName}->{$objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName});
				if ((\$this->{$strObjectName}->{$objColumn->Reference->PropertyName}) && (\$this->{$strObjectName}->{$objColumn->Reference->PropertyName}->{$objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName} == \${$objColumn->Reference->VariableName}->{$objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName}))
					\$objListItem->Selected = true;
				\$a[] = \$objListItem;
			}
			return \$a;
		 }

TMPL;
			return $strRet;

		}

		/**
		 * Returns code to refresh the control from the saved object.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 */
		public static function Codegen_MetaRefresh(QCodeGen $objCodeGen, QTable $objTable, QColumn $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
			$strPrimaryKey = $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName;
			$strControlVarName = $objCodeGen->FormControlVariableNameForColumn($objColumn);

			$strRet = <<<TMPL
			if (\$this->{$strControlVarName}) {

TMPL;
			$options = $objColumn->Options;
			if (!$options || !isset ($options['NoAutoLoad'])) {
				$strRet .= <<<TMPL
			\$this->Source = \$this->{$strControlVarName}_GetItems();

TMPL;
			}
			$strRet .= <<<TMPL
				if (\$this->{$strObjectName}->{$objColumn->Reference->PropertyName}) {
					\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$objColumn->Reference->PropertyName}->__toString();
					\$this->{$strControlVarName}->SelectedValue = \$this->{$strObjectName}->{$objColumn->Reference->PropertyName}->{$strPrimaryKey};
				}
				else {
					\$this->{$strControlVarName}->Text = '';
					\$this->{$strControlVarName}->SelectedValue = null;
				}
			}

TMPL;
			return $strRet;

		}

		public static function Codegen_MetaUpdate(QCodeGen $objCodeGen, QTable $objTable, QColumn $objColumn) {
			$strObjectName = $objCodeGen->VariableNameFromTable($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = static::Codegen_VarName($strPropName);
			$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->PropertyName} = \$this->{$strControlVarName}->SelectedValue;

TMPL;
			return $strRet;
		}

		/**
		 * Returns a description of the options available to modify by the designer for the code generator.
		 *
		 * @return array
		 */
		public static function GetMetaParams() {
			return array(
				new QMetaParam ('MinLength', 'Number of characters typed before lookup starts', QType::Integer),
				new QMetaParam ('AutoFocus', 'Should field auto select as typing occurs.', QType::Boolean)
			);
		}


	}
?>