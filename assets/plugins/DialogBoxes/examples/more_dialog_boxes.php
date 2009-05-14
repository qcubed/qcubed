<?php
	require('../../../../includes/configuration/prepend.inc.php');

	// Define the Qform with all our Qcontrols
	class ExamplesForm extends QForm {
		// Local declarations of our Qcontrols
		protected $dlgConfirmationPrompt;
		protected $dlgTextPrompt;
		protected $dlgOptionPrompt;
		
		protected $bntShowDlgConfirmationPrompt;
		protected $btnShowDlgTextPrompt;
		protected $btnShowDlgOptionPrompt;
		
		protected $lblStatus;
	
		// Initialize our Controls during the Form Creation process
		protected function Form_Create() {
			$this->lblStatus = new QLabel($this);
			$this->lblStatus->Text = "Press one of the buttons above";
			
			$this->create_ConfirmationPrompt();
			$this->create_TextPrompt();
			$this->create_OptionPrompt();
		}
		
		private function create_ConfirmationPrompt() {
			// As a second parameter to the QConfirmationDialog constructor,
			// we'll pass in the name of the QForm method that we want to
			// have called when the user actually picks the "confirm" option
			// from the dialog. 
			$this->dlgConfirmationPrompt = new QConfirmationDialog($this, 'dlgConfirmation_callback_continue');
			$this->dlgConfirmationPrompt->SetIntroLabel("Hey, are you really sure?");
			
			// Optionally, we can set a callback for when the user clicks Cancel in
			// the confirmation dialog - if we don't, nothng will happen. 
			$this->dlgConfirmationPrompt->SetSecondActionCallback("dlgConfirmation_callback_cancel");
			
			// We'll use this button to trigger the confirmation dialog to pop up
			$this->bntShowDlgConfirmationPrompt = new QButton($this);
			$this->bntShowDlgConfirmationPrompt->Text = "Confirmation prompt";
			$this->bntShowDlgConfirmationPrompt->AddAction(new QClickEvent, new QAjaxAction("btnShowDlgConfirmationPrompt_click"));
		}
		
		private function create_TextPrompt() {
			// Note that dlgTextPrompt_callback will be called with a parameter -
			// that parameter is what the user inputted into the text box.
			// You can also get to this value by calling $this->dlgTextPrompt->GetValue()
			$this->dlgTextPrompt = new QTextBoxPromptDialog($this, 'dlgTextPrompt_callback');
			$this->dlgTextPrompt->SetIntroLabel("What's three plus five?");
			$this->dlgTextPrompt->SetFirstActionLabel("Check my answer");
			// Optionally, we can pre-set the value of the textbox
			$this->dlgTextPrompt->SetValue("eleven");
			
			// We'll use this button to trigger the confirmation dialog to pop up
			$this->btnShowDlgTextPrompt = new QButton($this);
			$this->btnShowDlgTextPrompt->Text = "Text box prompt";
			$this->btnShowDlgTextPrompt->AddAction(new QClickEvent, new QAjaxAction("btnShowDlgTextPrompt_click"));
		}
		
		private function create_OptionPrompt() {
			$this->dlgOptionPrompt = new QRadioButtonPromptDialog($this, 'dlgOptionPrompt_callback');
			$options = array(
				"Item A offers opportunities foo and bar" => "ValueOfItemA",
				"Item B only allows you to do X" => "ValueOfItemB",
				"Item C allows you to do X, bar, and foo" => "ValueOfItemC"
			);
			
			// Pass in the set of options as an array, and the VALUE that has to be initially selected
			// If the value parameter is omitted, the first item is selected by default. 
			$this->dlgOptionPrompt->SetOptions($options, "ValueOfItemB");
			$this->dlgOptionPrompt->SetIntroLabel("Pick an option from the list below - pick a good one!");
			$this->dlgOptionPrompt->Width = 350; // override the default width

			$this->btnShowDlgOptionPrompt = new QButton($this);
			$this->btnShowDlgOptionPrompt->Text = "Prompt to pick an option";
			$this->btnShowDlgOptionPrompt->AddAction(new QClickEvent, new QAjaxAction("btnShowDlgOptionPrompt_click"));
		}
		
		protected function btnShowDlgOptionPrompt_click($strFormId, $strControlId, $strParameter) {
			$this->dlgOptionPrompt->ShowDialogBox();
		}

		protected function btnShowDlgTextPrompt_click($strFormId, $strControlId, $strParameter) {
			$this->dlgTextPrompt->ShowDialogBox();
		}
		
		protected function btnShowDlgConfirmationPrompt_click($strFormId, $strControlId, $strParameter) {
			$this->dlgConfirmationPrompt->ShowDialogBox();
		}

		// Important: the callback function HAS to be public! It is called from another class!
		public function dlgConfirmation_callback_continue() {
			$this->lblStatus->Text = "You clicked the button and then confirmed your decision by clicking YES!";
			$this->lblStatus->Blink();
		}

		public function dlgConfirmation_callback_cancel() {
			$this->lblStatus->Text = "You clicked the button and then decided to cancel out";
			$this->lblStatus->Blink();
		}
		
		// This function will be called after the text box prompt is closed, and the parameter
		// will be set to what the user has inputted into the text box. IMPORTANT:
		// the input validation (i.e. escaping) has to be done in the callback function.
		// If you don't do this, you're vulnerable to all sorts of injection!
		public function dlgTextPrompt_callback($strInputText) {
			$this->lblStatus->Text = "3+5=" . QApplication::HtmlEntities($strInputText);
			$this->lblStatus->Blink();
		}
		
		public function dlgOptionPrompt_callback($mixSelectedOption) {
			$this->lblStatus->Text = "You picked the option with value = " . QApplication::HtmlEntities($mixSelectedOption);
			$this->lblStatus->Blink();
		}
	}

	// Run the Form we have defined
	ExamplesForm::Run('ExamplesForm');
?>