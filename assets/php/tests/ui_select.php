<?php
    require_once('../qcubed.inc.php');
    
	class SelectForm extends QForm {
		protected $auto1;

		protected $btnServer;
		protected $btnAjax;

		protected function Form_Create() {
			$this->auto1 = new QAutocomplete($this);
			$this->auto1->Name = 'Autocomplete';

			$a = [new QListItem ('A', 1),
				new QListItem ('B', 2),
				new QListItem ('C', 3),
				new QListItem ('D', 4)
			];

			$this->auto1->Source = $a;

			$this->btnServer = new QButton ($this);
			$this->btnServer->Text = 'Server Submit';
			$this->btnServer->AddAction(new QClickEvent(), new QServerAction('submit_click'));

			$this->btnAjax = new QButton ($this);
			$this->btnAjax->Text = 'Ajax Submit';
			$this->btnAjax->AddAction(new QClickEvent(), new QAjaxAction('submit_click'));
		}

		protected function submit_click($strFormId, $strControlId, $strParameter) {
			$this->auto1->Warning = 'Text = ' . $this->auto1->Text . ' Value = ' . $this->auto1->SelectedId;
		}
		
	}
	SelectForm::Run('SelectForm');
?>