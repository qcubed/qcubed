<?php
	require_once('../qcubed.inc.php');

	class ExamplesForm extends QForm {
		protected $lblMessage;
		protected $btnReloadPage;
		protected $btnClearCache;

		protected function Form_Create() {
			$this->lblMessage = new QLabel($this);

			$this->btnReloadPage = new QButton($this);
			$this->btnReloadPage->Text = 'Reload this page';
			$this->btnReloadPage->AddAction(new QClickEvent(), new QJavaScriptAction('document.location.reload();'));
			
			$this->btnClearCache = new QButton($this);
			$this->btnClearCache->Text = 'Clear the cache';
			$this->btnClearCache->AddAction(new QClickEvent(), new QAjaxAction('btnClearCache_Click'));
		}
		
		protected function btnClearCache_Click($strFormId, $strControlId, $strParameter) {
			$blnSuccess = QCache::ClearNamespace('qquery/person');
			
			if ($blnSuccess) {
				$strStatus = "successful";
			} else {
				$strStatus = "NOT successful - check your cache / namespace paths";
			}

			$this->lblMessage->Text = 'Clearing the query cache for the Person table was ' . $strStatus . '. Reload the page to see the effect - query will not be executed against the database. ';
		}
	}

	// Run the Form we have defined
	ExamplesForm::Run('ExamplesForm');
?>
