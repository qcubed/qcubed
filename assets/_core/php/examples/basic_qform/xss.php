<?php
require_once('../qcubed.inc.php');

// Define the Qform with all our Qcontrols
class ExamplesForm extends QForm {

	/** @var QTextbox */
	protected $txtTextbox1;

	/** @var QLabel */
	protected $lblLabel1;

	/** @var QButton */
	protected $btnButton1;

	/** @var QTextbox */
	protected $txtTextbox2;

	/** @var QLabel */
	protected $lblLabel2;

	/** @var QButton */
	protected $btnButton2;

	/** @var QTextbox */
	protected $txtTextbox3;

	/** @var QLabel */
	protected $lblLabel3;

	/** @var QButton */
	protected $btnButton3;

	/** @var QTextbox */
	protected $txtTextbox4;

	/** @var QLabel */
	protected $lblLabel4;

	/** @var QButton */
	protected $btnButton4;

	/** @var QTextbox */
	protected $txtTextbox5;

	/** @var QLabel */
	protected $lblLabel5;

	/** @var QButton */
	protected $btnButton5;

	// Initialize our Controls during the Form Creation process
	protected function Form_Create() {
		// default legacy protection, will throw an exception
		$this->txtTextbox1 = new QTextbox($this);
		$this->txtTextbox1->Text = 'Hello!';
		$this->txtTextbox1->Width = 500;

		$this->lblLabel1 = new QLabel($this);
		$this->lblLabel1->HtmlEntities = false;
		$this->lblLabel1->Text = "";

		$this->btnButton1 = new QButton($this);
		$this->btnButton1->Text = "Parse and Display";
		$this->btnButton1->AddAction(new QClickEvent(), new QAjaxAction('btnButton1_Click'));

		// htmlentities mode
		$this->txtTextbox2 = new QTextbox($this);
		$this->txtTextbox2->CrossScripting = QCrossScripting::HtmlEntities;
		$this->txtTextbox2->Text = 'Hello! <script>alert("I am an evil attacker.")</script>';
		$this->txtTextbox2->Width = 500;

		$this->lblLabel2 = new QLabel($this);
		$this->lblLabel2->Text = "";

		$this->btnButton2 = new QButton($this);
		$this->btnButton2->Text = "Parse and Display";
		$this->btnButton2->AddAction(new QClickEvent(), new QAjaxAction('btnButton2_Click'));

		// full protection with the HTMLPurifier defaults
		$this->txtTextbox3 = new QTextbox($this);
		$this->txtTextbox3->CrossScripting = QCrossScripting::HTMLPurifier;
		$this->txtTextbox3->Text = 'Hello! <script>alert("I am an evil attacker.")</script>';
		$this->txtTextbox3->Width = 500;

		$this->lblLabel3 = new QLabel($this);
		$this->lblLabel3->Text = "";

		$this->btnButton3 = new QButton($this);
		$this->btnButton3->Text = "Parse and Display";
		$this->btnButton3->AddAction(new QClickEvent(), new QAjaxAction('btnButton3_Click'));

		// full protection with an allowed list of tags
		$this->txtTextbox4 = new QTextbox($this);
		$this->txtTextbox4->CrossScripting = QCrossScripting::HTMLPurifier;
		$this->txtTextbox4->SetPurifierConfig("HTML.Allowed", "b,strong,i,em,img[src]");
		$this->txtTextbox4->Text = 'Hello! <script>alert("I am an evil attacker.")</script><b>Hello</b> <i>again</i>!';
		$this->txtTextbox4->Width = 500;

		$this->lblLabel4 = new QLabel($this);
		$this->lblLabel4->HtmlEntities = false;
		$this->lblLabel4->Text = "";

		$this->btnButton4 = new QButton($this);
		$this->btnButton4->Text = "Parse and Display";
		$this->btnButton4->AddAction(new QClickEvent(), new QAjaxAction('btnButton4_Click'));

		// the textbox won't have the XSS protection!
		$this->txtTextbox5 = new QTextbox($this);
		$this->txtTextbox5->CrossScripting = QCrossScripting::Allow;
		$this->txtTextbox5->Text = 'Hello! <script>alert("I am an evil attacker.")</script><b>Hello</b> again!';
		$this->txtTextbox5->Width = 500;

		$this->lblLabel5 = new QLabel($this);
		$this->lblLabel5->HtmlEntities = false;
		$this->lblLabel5->Text = "";

		$this->btnButton5 = new QButton($this);
		$this->btnButton5->Text = "Parse and Display";
		$this->btnButton5->AddAction(new QClickEvent(), new QAjaxAction('btnButton5_Click'));
	}

	protected function btnButton1_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel1->Text = $this->txtTextbox1->Text;
	}

	protected function btnButton2_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel2->Text = $this->txtTextbox2->Text;
	}

	protected function btnButton3_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel3->Text = $this->txtTextbox3->Text;
	}

	protected function btnButton4_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel4->Text = $this->txtTextbox4->Text;
	}

	protected function btnButton5_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel5->Text = $this->txtTextbox5->Text;
	}
}

// Run the Form we have defined
ExamplesForm::Run('ExamplesForm');
?>