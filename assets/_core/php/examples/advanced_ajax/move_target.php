<?php
	require_once('../qcubed.inc.php');

	// Define the Qform with all our Qcontrols
	class ExamplesForm extends QForm {
		// Local declarations of our Qcontrols
		protected $lblHandle;
		protected $txtTextbox;
		protected $pnlParent;

		// Initialize our Controls during the Form Creation process
		protected function Form_Create() {
			// Define the Label
			
			$this->pnlParent = new QPanel($this);
			$this->pnlParent->AddControlToMove();
			$this->pnlParent->AutoRenderChildren = true;
			
			$this->lblHandle = new QLabel($this->pnlParent);
			$this->lblHandle->Text = 'Please Enter your Name';

			// Define the Textbox, and specify positioning and location
			$this->txtTextbox = new QTextBox($this->pnlParent);
			
		}
	}

	// Run the Form we have defined
	ExamplesForm::Run('ExamplesForm');
?>
