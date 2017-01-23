<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends QForm
	{
		/** @var  QButton */
		protected $btnRegular;
		/** @var  QButton */
		protected $btnBlocking;
		protected $intRegularCount = 0;
		protected $intBlockingCount = 0;


		protected $lblRegular;
		protected $lblBlocking;

		protected function Form_Create()
		{
			$this->btnRegular = new QButton($this);
			$this->btnRegular->Text = "Regular Button";
			$this->btnRegular->AddAction (new QClickEvent(), new QAjaxAction('btnRegular_Click'));
			$this->btnBlocking = new QButton($this);
			$this->btnBlocking->Text = "Blocking Button";
			$this->btnBlocking->AddAction (new QClickEvent(0, null, null, true), new QAjaxAction('btnBlocking_Click'));

			// Define a Message label
			$this->lblRegular = new QLabel($this);
			$this->lblRegular->Text = '0';
			$this->lblBlocking = new QLabel($this);
			$this->lblBlocking->Text = '0';
		}

		protected function btnRegular_Click() {
			$this->intRegularCount += 1;
			$this->lblRegular->Text = $this->intRegularCount;
			$this->btnRegular->Enabled = false;
		}

		protected function btnBlocking_Click() {
			$this->intBlockingCount += 1;
			$this->lblBlocking->Text = $this->intBlockingCount;
			$this->btnBlocking->Enabled = false;
		}
	}

ExampleForm::Run('ExampleForm');

