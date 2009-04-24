<?php
	require('../../application/configuration/prepend.inc.php');

	class SampleForm extends QForm {
		protected $lblMessage;
		protected $btnButton;
		protected $txtInput; 

		protected function Form_Create() {
			$this->lblMessage = new QLabel($this);
			$this->lblMessage->HtmlEntities = false;
			$this->lblMessage->Text = 'Click the button to display the text from the textbox.';

			$this->txtInput = new QFCKeditor($this);
			$this->txtInput->Width = '700px'; 
			$this->txtInput->Height = '200px';
			$this->txtInput->Text = "Hello <b>world</b>. How <i>are</i> you?";

			$this->btnButton = new QButton($this);
			$this->btnButton->Text = 'Click Me';

			// MUST use ServerAction! See http://www.qcodo.com/forums/topic.php/576/5
			// and http://www.qcodo.com/forums/topic.php/2690
			$this->btnButton->AddAction(new QClickEvent(), new QServerAction('btnButton_Click'));
		}

		protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
			$this->lblMessage->Text = $this->txtInput->Text;
		}
	}

	SampleForm::Run('SampleForm');
?>