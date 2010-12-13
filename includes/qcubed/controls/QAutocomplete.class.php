<?php
	class QAutocomplete_SourceEvent extends QEvent {
		const EventName = 'QAutocomplete_Source';
	}

	class QAutocompleteListItem extends QListItem {
		public function toJsObject() {
			return JavaScriptHelper::toJsObject(array("label" => $this->Name, "value" => $this->Value));
		}
	}

	/**
	 * @property-write array $DataSource an array of strings or QAutocompleteListItem's
	 */
	class QAutocomplete extends QAutocompleteBase
	{
		const RESPONSE_ATTR = '__qac_response';
		protected $blnUseAjax = false;

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
		 * Set a filter to use when using a simple array as a source (in non-ajax mode).
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

		protected function getCustomEventPropertyName(QEvent $objEvent) {
			if ($objEvent instanceof QAutocomplete_SourceEvent)
				return 'Source';
			return parent::getCustomEventPropertyName($objEvent);
		}

		protected function createEventWrapper(QEvent $objEvent, QAction $objAction) {
			if ($objEvent instanceof QAutocomplete_SourceEvent) {
				$objAction->Event = $objEvent;
				$strBody = JavaScriptHelper::customDataInsertion($this, self::RESPONSE_ATTR, "response");
				$strBody .= $objAction->RenderScript($this);
				return new QJsClosure($strBody, array('request', 'response'));
			}
			return parent::createEventWrapper($objEvent, $objAction);
		}

		protected function prepareAjaxList($dataSource) {
			$strJS = JavaScriptHelper::customDataRetrieval($this, self::RESPONSE_ATTR, "response");
			$list = $dataSource ? JavaScriptHelper::toJsObject($dataSource) : "[]";
			$strJS .= 'response(' . $list .');';
			QApplication::ExecuteJavaScript($strJS, true);
		}

		public function SetDataBinder($strMethodName, $objParentControl = null) {
			if ($objParentControl) {
				$this->Source = new QAjaxControlAction($objParentControl, $strMethodName);
			} else {
				$this->Source = new QAjaxAction($strMethodName);
			}
		}

		public function __set($strName, $mixValue) {
			if ($strName === 'Source') {
				if ($mixValue instanceof QAjaxAction) {
					$this->blnUseAjax = true;
					$this->RemoveAllActions(QAutocomplete_SourceEvent::EventName);
					$this->AddAction(new QAutocomplete_SourceEvent(), $mixValue);
				} else {
					$this->blnUseAjax = false;
					parent::__set($strName, $mixValue);
				}
				$this->blnModified = true;
				return;
			}
			if ($strName === 'DataSource') {
				if ($this->blnUseAjax) {
					$this->prepareAjaxList($mixValue);
				}
				return;
			}
			parent::__set($strName, $mixValue);
		}
	}
?>