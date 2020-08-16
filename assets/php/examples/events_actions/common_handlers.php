<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends QForm {
		protected $btnFocus;
		protected $btnSelect;
		protected $txtFocus;

		protected $btnBlocking;
		protected $btnRegular;
				
		protected $pnlHover;
		
		protected $btnCssAction;

		protected function Form_Create() {
			// Define the Textboxes
			$this->txtFocus = new QTextBox($this);
			$this->txtFocus->Text = 'Example Text Here';
			$this->txtFocus->OnBlur(new QAjaxAction('txtFocus_Blur'));
			
			// QFocusControlAction example
			$this->btnFocus = new QButton($this);
			$this->btnFocus->Text = 'Set Focus';
			$this->btnFocus->OnFocus(new QFocusControlAction($this->txtFocus));

			// QSelectControlAction example
			$this->btnSelect = new QButton($this);
			$this->btnSelect->Text = 'Select All in Textbox';
			$this->btnSelect->OnClick(new QSelectControlAction($this->txtFocus));

			$this->btnRegular = new QButton($this);
			$this->btnRegular->Text = "Regular Button";
			$this->btnRegular->OnClick (new QAjaxAction('btnRegular_Click'));
			
			$this->btnBlocking = new QButton($this);
			$this->btnBlocking->Text = "Blocking Button with delay";
			$this->btnBlocking->OnClick(new QAjaxAction('btnBlocking_Click'),['Delay'=>100,'Block'=>true]);

			// Define a Message label
			$this->lblRegular = new QLabel($this);
			$this->lblRegular->Text = '0';
			$this->lblBlocking = new QLabel($this);
			$this->lblBlocking->Text = '0';
		}

		protected function txtFocus_Blur() {
			QApplication::DisplayAlert('txtFocus blurred (lost focus)!');
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
?>
