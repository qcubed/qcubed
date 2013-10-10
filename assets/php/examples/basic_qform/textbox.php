<?php
require_once('../qcubed.inc.php');

// Define the Qform with all our Qcontrols
class ExamplesForm extends QForm {

	// Local declarations of our Qcontrols
	protected $txtBasic;
	protected $txtInt;
	protected $txtFlt;
	protected $txtEmail;
	protected $btnValidate;
	
	// Initialize our Controls during the Form Creation process
	protected function Form_Create() {
		// Define our Label
		$this->txtBasic = new QTextBox($this);
		$this->txtBasic->Name = QApplication::Translate("Basic");
		
		$this->txtBasic = new QTextBox($this);
		$this->txtBasic->MaxLength = 5;
		
		$this->txtInt = new QIntegerTextBox($this);
		$this->txtInt->Maximum = 10;
		
		$this->txtFlt = new QFloatTextBox($this);
		$this->txtEmail = new QEmailTextBox($this);
		
		$this->btnValidate = new QButton ($this);
		$this->btnValidate->Text = "Filter and Validate";
		$this->btnValidate->AddAction (new QClickEvent(), new QServerAction()); // just validates
		$this->btnValidate->CausesValidation = true;
	}

}

// Run the Form we have defined
// The QForm engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>