<?php
	require_once('../qcubed.inc.php');

	// Define the Qform with all our Qcontrols
	class ExamplesForm extends QForm {
		// Local declarations of our QPanels to Resize
		protected $pnlLeftTop;
		protected $txtTextbox;

		// Initialize our Controls during the Form Creation process
		protected function Form_Create() {
			// Define the main panel that will resize.
			$this->pnlLeftTop = new QPanel($this);
			$this->pnlLeftTop->Width = 400;
			$this->pnlLeftTop->Height = 200;
			
			$this->pnlLeftTop->Text = 'The QCubed Development Framework is an open-source PHP 5 framework that focuses ' .
				'on freeing developers from unnecessary tedious, mundane coding.<br/><br/>The result is that developers ' .
				'can do what they do best: focus on implementing functionality and usability, improving performance and ' .
				'ensuring security.';			
			
			// Set the panel to resizable!
			$this->pnlLeftTop->Resizable = true;

			$this->txtTextbox = new QTextBox($this);
			$this->txtTextbox->TextMode = QTextMode::MultiLine;
			$this->txtTextbox->Width = 400;
			$this->txtTextbox->Height = 200;
			$this->txtTextbox->Resizable = true;
			
		}
	}

	// Run the Form we have defined
	ExamplesForm::Run('ExamplesForm');
?>
