<?php

require_once('../qcubed.inc.php');
require('bb_parser.php');

// Define the Qform with all our Qcontrols
class ExampleForm extends QForm {

	// Local declarations of our Qcontrols
	protected $lblResultRaw;
	protected $lblResultFormatted;
	protected $txtInput;
	protected $btnButton;

	// Initialize our Controls during the Form Creation process
	protected function Form_Create() {
		// Define the text area - multi-line QTextBox
		$this->txtInput = new QTextBox($this);
		$this->txtInput->TextMode = QTextMode::MultiLine;
		$this->txtInput->Text = "Hello\n\nworld. [b]We[/b] all " .
				"love [img]http://static.php.net/www.php.net/images/logos/php-med-trans-light.gif[/img]" .
				"\n\nThis is a [url=http://www.google.com]link to Google[/url].";

		// Define the Label
		$this->lblResultRaw = new QLabel($this);
		$this->lblResultRaw->Text = 'Click the button to process the input.';

		$this->lblResultFormatted = new QLabel($this);
		$this->lblResultFormatted->HtmlEntities = false;
		$this->lblResultFormatted->Text = 'Click the button to process the input.';

		// Define the Button
		$this->btnButton = new QButton($this);
		$this->btnButton->Text = 'Click Me!';

		$this->btnButton->AddAction(new QClickEvent(), new QAjaxAction('btnButton_Click'));
	}

	// In this click handler, we will process the BBCode in the input,
	// and format it properly to turn into HTML in the lblResult.
	protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
		$strText = $this->txtInput->Text;

		// Parse the text, this is the class that knows how to act
		// on our rules
		$objParser = new BBCodeParser($strText);

		$result = $objParser->Render();
		$this->lblResultRaw->Text = $result;
		$this->lblResultFormatted->Text = $result;
	}

}

ExampleForm::Run('ExampleForm');
?>