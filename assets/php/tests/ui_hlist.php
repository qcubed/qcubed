<?php
    require_once('../qcubed.inc.php');
    
	class SelectForm extends QForm {
		protected $list1;

		protected $btnServer;
		protected $btnAjax;

		protected function Form_Create() {
			$this->list1 = new QHtmlList($this);
			$this->list1->Name = 'List';

			$a = [new QListItem ('A', 1),
				new QListItem ('B', 2),
				new QListItem ('C', 3),
				new QListItem ('D', 4)
			];

			$this->list1->AddItems($a);
			$this->list1->SetDataBinder([$this, 'DataBind']);

			$this->btnServer = new QButton ($this);
			$this->btnServer->Text = 'Server Submit';
			$this->btnServer->AddAction(new QClickEvent(), new QServerAction('submit_click'));

			$this->btnAjax = new QButton ($this);
			$this->btnAjax->Text = 'Ajax Submit';
			$this->btnAjax->AddAction(new QClickEvent(), new QAjaxAction('submit_click'));
		}

		protected function submit_click($strFormId, $strControlId, $strParameter) {
		}

		public function DataBind() {
			$a = [new QListItem ('A', 1),
				new QListItem ('B', 2),
				new QListItem ('C', 3),
				new QListItem ('D', 4)
			];

			$a[0]->AddItems(['aa'=>0, 'ab'=>2, 'ac'=>3]);
			$a[1]->AddItems(['ba'=>0, 'bb'=>1]);

			$this->list1->RemoveAllItems();
			$this->list1->AddItems($a);
		}
		
	}
	SelectForm::Run('SelectForm');
?>