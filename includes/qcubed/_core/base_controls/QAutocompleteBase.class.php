<?php
	/**
	 * The QAutocompleteBase class defined here provides an interface between the generated
	 * QAutocompleteGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QAutocomplete.class.php file instead.
	 *
	 */


	/**
	 * List items that can be sent to an autocomplete in non-ajax mode. Put them in an array and send to ->Source.
	 */
	class QAutocompleteListItem extends QListItem {
		// Note: JQuery UI does not use the terms 'value' and 'label' in the same way that QListItem uses them.
		public function toJsObject() {
			return JavaScriptHelper::toJsObject(array("value" => $this->Name, "id" => $this->Value));
		}
	}

	/**
	 * Special event to handle source ajax callbacks
	 */
	class QAutocomplete_SourceEvent extends QEvent {
		const EventName = 'QAutocomplete_Source';
	}


	/**
	 * @property-write array $DataSource an array of strings, QAutocompleteListItem's, 
	 */
	class QAutocompleteBase extends QAutocompleteGen
	{
		const RESPONSE_ATTR = '__qac_response';

		/** @var string */
		protected $strSelectedId = null;
		/** @var boolean */
		protected $blnUseAjax = false;
		/** @var boolean */
		protected $blnMustMatch = false;
		
		
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


		// Set up an Ajax data binder. 
		public function SetDataBinder($strMethodName, $objParentControl = null) {
			if ($objParentControl) {
				$objAction = new QAjaxControlAction($objParentControl, $strMethodName);
			} else {
				$objAction = new QAjaxAction($strMethodName);
			}
			
			// use the ajax action to generate an ajax script for us, but 
			// since this is an option of the control, we can't actually 'bind' it, so we instead use an
			// empty action to tie the action to the data binder method name
			$objEvent = new QAutocomplete_SourceEvent();
			$objAction->Event = $objEvent;
			$strBody = JavaScriptHelper::customDataInsertion($this, self::RESPONSE_ATTR, "response");
			$strBody .= $objAction->RenderScript($this);			
			$this->mixSource = new QJsClosure($strBody, array('request', 'response'));
					
			$this->RemoveAllActions(QAutocomplete_SourceEvent::EventName);
			$objAction = new QNoScriptAjaxAction($objAction);
			parent::AddAction($objEvent, $objAction);
			
			$this->blnUseAjax = true;
			$this->blnModified = true;
		}


		// These functions are used to keep track of the selected value, and to implement 
		// optional autocomplete functionality.
		
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			$mustMatch = ($this->blnMustMatch ? '1' : '0');
			
			$strJS .=<<<FUNC
			.bind("autocompleteselect", function (event, ui) {
			 			qcubed.recordControlModification("$this->ControlId", "SelectedId", ui.item.id);
					})						
			.bind("autocompletefocus",  function (event, ui) {
						if ( /^key/.test(event.originalEvent.originalEvent.type) ) {
			 				qcubed.recordControlModification("$this->ControlId", "SelectedId", ui.item.id);
						} 
					})
			.bind("autocompletechange", function( event, ui ) {
						var toTest = ui.item ? (ui.item.value ? ui.item.value : ui.item.label) : '';
						if ( !ui.item ||
							jQuery( this ).val() != toTest) {
								// remove invalid value, as no match 
								if ($mustMatch) {
									
									jQuery( this ).val( "" );
									jQuery( this ).data( "autocomplete" ).term = '';
								}
		 						qcubed.recordControlModification("$this->ControlId", "SelectedId", '');
						}
					})
										
FUNC;
			
			return $strJS;
		}
		
		
		// Response to an ajax request for data
		protected function prepareAjaxList($dataSource) {
			$strJS = JavaScriptHelper::customDataRetrieval($this, self::RESPONSE_ATTR, "response");
			$list = $dataSource ? JavaScriptHelper::toJsObject($dataSource) : "[]";
			$strJS .= 'response(' . $list .');';
			QApplication::ExecuteJavaScript($strJS, true);
		}


		public function __set($strName, $mixValue) {
			// Assign data to a DataSource from within the data binder function only.
			// Data should be array items that at a minimum contain a 'value' and an 'id'
			// They can also contain a 'label', which will be displayed in the popup menu only
			if ($strName === 'DataSource') {
				if ($this->blnUseAjax) {
					$this->prepareAjaxList($mixValue);
				} else {
					$this->Source = $mixValue;
				}
				return;
			}
			
			$this->blnModified = true;
			
			switch ($strName) {
				case 'SelectedId':
					$this->strSelectedId = $mixValue;
					$this->blnModified = true;
					 break;
					 
				case 'MustMatch':
					$this->blnMustMatch = $mixValue;
					$this->blnModified = true;
					break;
					
				default:
					parent::__set($strName, $mixValue);
					break;
			}
			
		}
		
		public function __get($strName) {
			switch ($strName) {
				case 'SelectedId': return $this->strSelectedId;
				case 'MustMatch': return $this->blnMustMatch;
				
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}
		
	}
?>