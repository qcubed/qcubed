<?php
	require_once('../qcubed.inc.php');
	require('CalculatorWidget.class.php');

	// Define the Qform with all our Qcontrols
	class ExamplesForm extends QForm {
		// Local declarations of our Qcontrols
		protected $dlgSimpleMessage;
		protected $btnDisplaySimpleMessage;
		protected $btnDisplaySimpleMessageJsOnly;

		protected $dlgCalculatorWidget;
		protected $txtValue;
		protected $btnCalculator;

		protected $dlgYesNo;
		protected $pnlAnswer;
		protected $btnDisplayYesNo;

		protected $dlgValidation;
		protected $btnValidation;
		protected $txtFloat;

		protected $dlgErrorMessage;
		protected $btnErrorMessage;
		protected $dlgInfoMessage;
		protected $btnInfoMessage;


		// Initialize our Controls during the Form Creation process
		protected function Form_Create() {
			// Define the Simple Message Dialog Box
			$this->dlgSimpleMessage = new QDialog($this);
			$this->dlgSimpleMessage->Title = "Hello World!";
			$this->dlgSimpleMessage->Text = '<p><em>Hello, world!</em></p><p>This is a standard, no-frills dialog box.</p><p>Notice how the contents of the dialog '.
				'box can scroll, and notice how everything else in the application is grayed out.</p><p>Because we set <strong>MatteClickable</strong> to <strong>true</strong> ' .
				'(by default), you can click anywhere outside of this dialog box to "close" it.</p><p>Additional text here is just to help show the scrolling ' .
				'capability built-in to the panel/dialog box via the "Overflow" property of the control.</p>';
			$this->dlgSimpleMessage->AutoOpen = false;

			// Make sure this Dislog Box is "hidden"
			// Like any other QPanel or QControl, this can be toggled using the "Display" or the "Visible" property
			$this->dlgSimpleMessage->Display = false;

			// The First "Display Simple Message" button will utilize an AJAX call to Show the Dialog Box
			$this->btnDisplaySimpleMessage = new QButton($this);
			$this->btnDisplaySimpleMessage->Text = 'Display Simple Message QDialog';
			$this->btnDisplaySimpleMessage->AddAction(new QClickEvent(), new QAjaxAction('btnDisplaySimpleMessage_Click'));

			// The Second "Display Simple Message" button will utilize Client Side-only JavaScripts to Show the Dialog Box
			// (No postback/postajax is used)
			$this->btnDisplaySimpleMessageJsOnly = new QButton($this);
			$this->btnDisplaySimpleMessageJsOnly->Text = 'Display Simple Message QDialog (ClientSide Only)';
			$this->btnDisplaySimpleMessageJsOnly->AddAction(new QClickEvent(), new QShowDialog($this->dlgSimpleMessage));


			// Define a Yes/No modal dialog box
			$this->dlgYesNo = new QDialog($this);
			$this->dlgYesNo->Text = "Do you like QCubed?";
			$this->dlgYesNo->AddButton ('Yes');
			$this->dlgYesNo->AddButton ('No');
			$this->dlgYesNo->AddAction (new QDialog_ButtonEvent(), new QHideDialog ($this->dlgYesNo));
			$this->dlgYesNo->AddAction (new QDialog_ButtonEvent(), new QAjaxAction ('dlgYesNo_Button'));
			$this->dlgYesNo->AutoOpen = false;
			$this->dlgYesNo->Modal = true;
			$this->dlgYesNo->Resizable = false;
			$this->dlgYesNo->HasCloseButton = false;

			$this->pnlAnswer = new QPanel($this);
			$this->pnlAnswer->Text = 'Hmmm';
			
			$this->btnDisplayYesNo = new QButton($this);
			$this->btnDisplayYesNo->Text = 'Do you love me.';
			$this->btnDisplayYesNo->AddAction(new QClickEvent(), new QShowDialog($this->dlgYesNo));
			
			
			// Define the CalculatorWidget example. passing in the Method Callback for whenever the Calculator is Closed
			// This is  example uses QButton's instead of the JQuery UI buttons
			$this->dlgCalculatorWidget = new CalculatorWidget('btnCalculator_Close', $this);
			$this->dlgCalculatorWidget->Title = "Calculator Widget";
			$this->dlgCalculatorWidget->AutoOpen = false;
			$this->dlgCalculatorWidget->Resizable = false;

			// Setup the Value Textbox and Button for this example
			$this->txtValue = new QTextBox($this);

			$this->btnCalculator = new QButton($this);
			$this->btnCalculator->Text = 'Show Calculator Widget';
			$this->btnCalculator->AddAction(new QClickEvent(), new QAjaxAction('btnCalculator_Click'));

			// Validate on JQuery UI buttons
			$this->dlgValidation = new QDialog($this);
			$this->dlgValidation->AddButton ('OK', 'ok', true, true); // specify that this button causes validation and is the default button
			$this->dlgValidation->AddButton ('Cancel', 'cancel');

			// This next button demonstrates a confirmation button that is styled to the left side of the dialog box.
			// This is a QCubed addition to the jquery ui functionality
			$this->dlgValidation->AddButton ('Confirm', 'confirm', true, false, 'Are you sure?', array('class'=>'ui-button-left'));
			$this->dlgValidation->Width = 400; // Need extra room for buttons

			$this->dlgValidation->AddAction (new QDialog_ButtonEvent(), new QAjaxAction('dlgValidate_Click'));
			$this->dlgValidation->Title = 'Enter a number';

			// Set up a field to be auto rendered, so no template is needed
			$this->dlgValidation->AutoRenderChildren = true;
			$this->txtFloat = new QFloatTextBox($this->dlgValidation);
			$this->txtFloat->Placeholder = 'Float only';
			$this->txtFloat->PreferedRenderMethod = 'RenderWithError'; // Tell the panel to use this method when rendering

			$this->btnValidation = new QButton($this);
			$this->btnValidation->Text = 'Show Validation Example';
			$this->btnValidation->AddAction(new QClickEvent(), new QShowDialog($this->dlgValidation));

			// Message examples
			$this->dlgErrorMessage = QDialog::Message("Don't do that!", $this);
			$this->dlgErrorMessage->DialogState = QDialog::StateError;
			$this->dlgErrorMessage->Title = 'Alert';
			$this->btnErrorMessage = new QButton($this);
			$this->btnErrorMessage->Text = 'Show Error';
			$this->btnErrorMessage->AddAction(new QClickEvent(), new QShowDialog($this->dlgErrorMessage));

			$this->dlgInfoMessage = QDialog::Message("You did it.", $this);
			$this->dlgInfoMessage->DialogState = QDialog::StateHighlight;
			$this->dlgInfoMessage->Title = 'Status';
			$this->btnInfoMessage = new QButton($this);
			$this->btnInfoMessage->Text = 'Show Info';
			$this->btnInfoMessage->AddAction(new QClickEvent(), new QShowDialog($this->dlgInfoMessage));
		}

		protected function btnDisplaySimpleMessage_Click($strFormId, $strControlId, $strParameter) {
			// "Show" the Dialog Box using the Open() method
			$this->dlgSimpleMessage->Open();
		}
		
		protected function dlgYesNo_Button($strFormId, $strControlId, $strParameter) {
			if ($strParameter == 'Yes') {
				$this->pnlAnswer->Text = 'They love me.';
			} else {
				$this->pnlAnswer->Text = 'They love me not.';
			}
		}
		
		
		protected function btnCalculator_Click($strFormId, $strControlId, $strParameter) {
			// Setup the Calculator Widget's Value
			$this->dlgCalculatorWidget->Value = trim($this->txtValue->Text);

			// And Show it
			$this->dlgCalculatorWidget->Open();
			//$this->dlgCalculatorWidget->ShowDialogBox();
		}

		public function dlgValidate_Click($strFormId, $strControlId, $strParameter) {
			if ($strParameter == 'cancel') {
				$this->txtFloat->Text = '';
			}
			$this->dlgValidation->Close();
		}


			// Setup the "Callback" function for when the calculator closes
		// This needs to be a public method
		public function btnCalculator_Close() {
			$this->txtValue->Text = $this->dlgCalculatorWidget->Value;
		}
	}

	// Run the Form we have defined
	ExamplesForm::Run('ExamplesForm');
?>