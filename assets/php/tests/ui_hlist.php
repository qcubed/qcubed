<?php
    require_once('../qcubed.inc.php');
    
	class SelectForm extends QForm {
		protected $list1;

		protected $btnServer;
		protected $btnAjax;

		protected $a;


		protected function Form_Create() {
			$this->a = [new QHListItem ('A', 1),
				new QHListItem ('B', 2),
				new QHListItem ('C', 3),
				new QHListItem ('D', 4)
			];

			$this->list1 = new QHListControl($this);
			$this->list1->Name = 'List';


			$this->list1->AddItems($this->a);
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

			$this->a[0]->AddItems(['aa'=>0, 'ab'=>2, 'ac'=>3]);
			$this->a[1]->AddItems(['ba'=>0, 'bb'=>1]);

			$this->list1->RemoveAllItems();
			$this->list1->AddListItems($this->a);
		}
		
	}
	SelectForm::Run('SelectForm');
?>