<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends QForm {
		// Declare the panel
		// Notice how we don't declare the textboxes that we will display
		// We do this to demonstrate that the panel can render its own set of dynamically created controls
		// through using AutoRenderChildren
		protected $pnlPanel;

		protected $pnlFieldset;

		// For this example, show how the panel can display this strMessage
		public $strMessage = 'Hello, world!';

		protected function Form_Create() {
			// Define the Panel
			$this->pnlPanel = new QPanel($this);
			$this->pnlPanel->Width = 300;
			$this->pnlPanel->BackColor = '#dddddd';
			$this->pnlPanel->Padding = '10px 0px';
			$this->pnlPanel->HorizontalAlign = QHorizontalAlign::Center;

			// Define a Template to make it Pretty
			$this->pnlPanel->Text = 'Text Here Goes First';
			$this->pnlPanel->Template = 'pnl_panel.tpl.php';


			// Let's have the pnlPanel auto render any and all child controls
			$this->pnlPanel->AutoRenderChildren = true;

			// Define a bunch of textboxes, and put it into the panel
			for ($intIndex = 1; $intIndex <= 10; $intIndex++) {
				// The parent must be the panel, because the panel is going to be responsible
				// for rendering it.
				$txtTextbox = new QTextBox($this->pnlPanel);
				$txtTextbox->Text = sprintf('Textbox #%s', $intIndex);
				$txtTextbox->Width = 250;
			}

			$this->pnlFieldset = new QFieldset ($this);
			$this->pnlFieldset->Legend = 'Fieldset Example';
			$this->pnlFieldset->AutoRenderChildren = true;
			$this->pnlFieldset->Width = 300;
			$this->pnlFieldset->Padding = '10px 10px';

			// Define a bunch of checkboxes, and put it into the fieldset. Fieldsets can encapsulate any form element.
			for ($intIndex = 1; $intIndex <= 5; $intIndex++) {
				// The parent must be the fields, because the fieldset is going to be responsible
				// for rendering it.
				$chkCheckbox = new QCheckBox($this->pnlFieldset);
				$chkCheckbox->Text = sprintf('Checkbox #%s', $intIndex);
				$chkCheckbox->Width = 250;
			}


		}
	}

	ExampleForm::Run('ExampleForm');
?>
