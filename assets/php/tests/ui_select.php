<?php
    require_once('../qcubed.inc.php');
    
	class SelectForm extends QForm {
		protected $auto1;
		protected $auto2;

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
			$this->btnServer->CausesValidation = true;

			$this->btnAjax = new QButton ($this);
			$this->btnAjax->Text = 'Ajax Submit';
			$this->btnAjax->AddAction(new QClickEvent(), new QAjaxAction('submit_click'));
			$this->btnAjax->CausesValidation = true;

			$this->auto2 = new QAutocomplete($this);
			$this->auto2->Name = 'Autocomplete w/Ajax and Validation';
			$this->auto2->SetDataBinder('auto_Bind');
			$this->auto2->Required = true;
		}

		protected function submit_click($strFormId, $strControlId, $strParameter) {
			$this->auto1->Warning = 'Text = ' . $this->auto1->Text . ' Value = ' . $this->auto1->SelectedId;
		}

		public function auto_Bind($strFormId, $strControlId, $term) {
			$cond = QQ::OrCondition(
				QQ::Like(QQN::Person()->FirstName, '%' . $term . '%'),
				QQ::Like(QQN::Person()->LastName, '%' . $term . '%')
			);
			$a = Person::QueryArray($cond);
			$items = array();
			foreach ($a as $obj) {
				$items[] = new QListItem ($obj->FirstName . ' ' . $obj->LastName, $obj->Id);
			}
			$this->auto2->DataSource = $items;
		}
		
	}
	SelectForm::Run('SelectForm');
?>