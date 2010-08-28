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