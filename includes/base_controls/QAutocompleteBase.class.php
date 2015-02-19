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
		const JsReturnParam = 'ui'; // ends up being the request.term value
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
		/** @var string */
		protected $strSelectedId = null;
		/** @var boolean */
		protected $blnUseAjax = false;

		/* Moved to QAutoComplete2 plugin */
		//protected $blnMustMatch = false;
		//protected $strMultipleValueDelimiter = null;
		//protected $blnDisplayHtml = false;

		/**
		 * When this filter is passed to QAutocomplete::UseFilter, only the items in the source list that contain the typed term will be shown in the drop-down
		 * This is the default filter used by the jQuery autocomplete. Useful when resetting from a previousely set filter.
		 * @see QAutocomplete::UseFilter
		 */
		const FILTER_CONTAINS ='
		function(array, term) {
			var matcher = new RegExp($j.ui.autocomplete.escapeRegex(term), "i");
			return $j.grep(array, function(value) {
				return matcher.test(value.label || value.value || value);
			});
		}';
		/**
		 * When this filter is passed to QAutocomplete::UseFilter, only the items in the source list that start with the typed term will be shown in the drop-down
		 * @see QAutocomplete::UseFilter
		 */
		const FILTER_STARTS_WITH ='
		function(array, term) {
			var matcher = new RegExp("^" + $j.ui.autocomplete.escapeRegex(term), "i");
			return $j.grep(array, function(value) {
				return matcher.test(value.label || value.value || value);
			});
		}';

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
			if (is_string($filter)) {
				$filter = new QJsNoQuoteString($filter);
			} else if (!$filter instanceof QJsClosure) {
				throw new QCallerException("filter must be either a string or an instance of QJsClosure");
			}
			QApplication::ExecuteJsFunction('qcubed.acUseFilter', $filter);
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
		 */
		public function SetDataBinder($strMethodName, $objParentControl = null) {
			if ($objParentControl) {
				$objAction = new QAjaxControlAction($objParentControl, $strMethodName, 'default', null, 'ui');
			} else {
				$objAction = new QAjaxAction($strMethodName, 'default', null, 'ui');
			}
			$this->AddAction(new QAutocomplete_SourceEvent(), $objAction);

			$this->mixSource = new QJsNoQuoteString('qcubed.acSourceFunction');

			$this->blnUseAjax = true;
			$this->blnModified = true;
		}

		// These functions are used to keep track of the selected value, and to implement
		// optional autocomplete functionality.
		/**
		 * Gets the Javascript part of the control which is sent to the client side upon the completion of Render
		 * @return string The JS string
		 */
		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			QApplication::ExecuteJsFunction('qc.autocomplete', $this->getJqControlId(), QJsPriority::High);
			return $strJS;
		}
		
		
		// Response to an ajax request for data
		protected function prepareAjaxList($dataSource) {
			if (!$dataSource) {
				$dataSource = array();
			}
			QApplication::ExecuteJsFunction('qc.acSetData', $this->getJqControlId(), $dataSource, QJsPriority::High);
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
				case "Value":
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
				case "Value": // most common situation
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

		/**
		 * @param QCodeGen $objCodeGen
		 * @param QColumn|QReverseReference| QManyToManyReference $objColumn
		 * @return string
		 */
		public static function Codegen_MetaVariableDeclaration (QCodeGen $objCodeGen, $objColumn) {
			$strClassName = $objCodeGen->MetaControlControlClass($objColumn);
			$strPropName = $objCodeGen->MetaControlPropertyName($objColumn);
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
		 * This is called during the codegen process. This is very similar to the QListControl code, but there are
		 * some differences. In particular, this control does not support ManyToMany references.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @return string
		 * @throws Exception
		 */
		public static function Codegen_MetaCreate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {

			if ($objColumn instanceof QManyToManyReference) {
				throw new Exception ("Autocomplete does not support many-to-many references.");
			}

			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strControlVarName = $objCodeGen->MetaControlVariableName($objColumn);
			$strLabelName = addslashes(QCodeGen::MetaControlControlName($objColumn));
			$strPropName = QCodeGen::MetaControlPropertyName ($objColumn);

			// Read the control type in case we are generating code for a similar class
			$strControlType = $objCodeGen->MetaControlControlClass($objColumn);

			// Create a control designed just for selecting from a type table
			if ($objColumn instanceof QColumn && $objColumn->Reference->IsType) {
				$strRet=<<<TMPL
		/**
		 * Create and setup {$strControlType} {$strControlVarName}
		 * @param string \$strControlId optional ControlId to use
		 * @return {$strControlType}
		 */

		public function {$strControlVarName}_Create(\$strControlId = null) {

TMPL;
			} else {	// Create a control that presents a list taken from the database

					$strRet = <<<TMPL
		/**
		 * Create and setup {$strControlType} {$strControlVarName}
		 * @param string \$strControlId optional ControlId to use
		 * @param QQCondition \$objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] \$objClauses additional QQClause object or array of QQClause objects for the query
		 * @return QListBox
		 */

		public function {$strControlVarName}_Create(\$strControlId = null, QQCondition \$objCondition = null, \$objClauses = null) {
			\$this->obj{$strPropName}Condition = \$objCondition;
			\$this->obj{$strPropName}Clauses = \$objClauses;

TMPL;
			}

			// Allow the codegen process to either create custom ids based on the field/table names, or to be
			// Specified by the developer.
			$strControlIdOverride = $objCodeGen->GenerateControlId($objTable, $objColumn);

			if ($strControlIdOverride) {
				$strRet .= <<<TMPL
			if (!\$strControlId) {
				\$strControlId = '$strControlIdOverride';
			}

TMPL;
			}

			$strRet .= <<<TMPL
			\$this->{$strControlVarName} = new {$strControlType}(\$this->objParentObject, \$strControlId);
			\$this->{$strControlVarName}->Name = QApplication::Translate('{$strLabelName}');

TMPL;
			if ($objColumn instanceof QColumn && $objColumn->NotNull) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->Required = true;

TMPL;
			}

			if ($strMethod = QCodeGen::$PreferredRenderMethod) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->PreferredRenderMethod = '$strMethod';

TMPL;
			}
			$strRet .= static::Codegen_MetaCreateOptions ($objCodeGen, $objTable, $objColumn, $strControlVarName);
			$strRet .= static::Codegen_MetaRefresh ($objCodeGen, $objTable, $objColumn, true);

			$strRet .= <<<TMPL
			return \$this->{$strControlVarName};
		}

TMPL;

			if ($objColumn instanceof QColumn && $objColumn->Reference->IsType) {
				if ($objColumn instanceof QColumn) {
					$strVarType = $objColumn->Reference->VariableType;
				} else {
					$strVarType = $objColumn->ObjectDescription;
				}
				$strRet .= <<<TMPL

		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		public function {$strControlVarName}_GetItems() {
			return {$strVarType}::\$NameArray;
		}


TMPL;
			}
			else {
				if ($objColumn instanceof QColumn) {
					$strRefVarType = $objColumn->Reference->VariableType;
					$strRefVarName = $objColumn->Reference->VariableName;
					$strRefPropName = $objColumn->Reference->PropertyName;
					$strRefTable = $objColumn->Reference->Table;
				}
				elseif ($objColumn instanceof QReverseReference) {
					$strRefVarType = $objColumn->VariableType;
					$strRefVarName = $objColumn->VariableName;
					$strRefPropName = $objColumn->PropertyName;
					$strRefTable = $objColumn->Table;
				}

				$strRet .= <<<TMPL

		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		 public function {$strControlVarName}_GetItems() {
			\$a = array();
			\$objCondition = \$this->obj{$strPropName}Condition;
			if (is_null(\$objCondition)) \$objCondition = QQ::All();
			\${$strRefVarName}Cursor = {$strRefVarType}::QueryCursor(\$objCondition, \$this->obj{$strPropName}Clauses);

			// Iterate through the Cursor
			while (\${$strRefVarName} = {$strRefVarType}::InstantiateCursor(\${$strRefVarName}Cursor)) {
				\$objListItem = new QListItem(\${$strRefVarName}->__toString(), \${$strRefVarName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName});
				if ((\$this->{$strObjectName}->{$strPropName}) && (\$this->{$strObjectName}->{$strPropName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName} == \${$strRefVarName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName}))
					\$objListItem->Selected = true;
				\$a[] = \$objListItem;
			}
			return \$a;
		 }


TMPL;
			}
			return $strRet;

		}

		/**
		 * Returns code to refresh the control from the saved object.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @param bool $blnInit
		 * @return string
		 */
		public static function Codegen_MetaRefresh(QCodeGen $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strPrimaryKey = $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName;
			$strPropName = QCodeGen::MetaControlPropertyName($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strRet = '';

			if (!$blnInit) {
				$t = "\t";	// inserts an extra tab below
				$strRet = <<<TMPL
			if (\$this->{$strControlVarName}) {

TMPL;
			} else {
				$t = '';
			}

			$options = $objColumn->Options;
			if (!$options || !isset ($options['NoAutoLoad'])) {
				$strRet .= <<<TMPL
$t			\$this->{$strControlVarName}->Source = \$this->{$strControlVarName}_GetItems();

TMPL;
			}
			$strRet .= <<<TMPL
$t			if (\$this->{$strObjectName}->{$strPropName}) {
$t				\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName}->__toString();
$t				\$this->{$strControlVarName}->SelectedValue = \$this->{$strObjectName}->{$strPropName}->{$strPrimaryKey};
$t			}
$t			else {
$t				\$this->{$strControlVarName}->Text = '';
$t				\$this->{$strControlVarName}->SelectedValue = null;
$t			}

TMPL;

			if (!$blnInit) {
				$strRet .= <<<TMPL
			}

TMPL;
			}
			return $strRet;

		}

		/**
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference $objColumn
		 * @return string
		 */
		public static function Codegen_MetaUpdate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::MetaControlPropertyName($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);

			$strRet = '';
			if ($objColumn instanceof QColumn) {

				$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->PropertyName} = \$this->{$strControlVarName}->SelectedValue;

TMPL;
			}
			elseif ($objColumn instanceof QReverseReference) {
				$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->ObjectPropertyName} = {$objColumn->VariableType}::Load(\$this->{$strControlVarName}->SelectedValue);

TMPL;
			}
			return $strRet;
		}
	}
?>