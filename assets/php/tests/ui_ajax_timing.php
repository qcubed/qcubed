<?php
    require_once('../qcubed.inc.php');

/**
 * Class AjaxTimingForm
 *
 * Tests the timing of ajax events and our ability to record a change to a control. There is a bit of a race condition
 * that we need to get under control. For example, if the user clicks a checkbox that also has a "click" handler, and
 * inside that handler, tests the value of the checkbox, the user should see the new value and not the one before the
 * click.
 */
    
	class AjaxTimingForm extends QForm {
		protected $txt1;
		protected $lblTxt1Change;
		protected $lblTxt1KeyUp;

		protected $chk;
		protected $lblCheck;

		protected function Form_Create() {
			$this->txt1 = new QTextBox($this, 'txtbox');
			$this->txt1->Name = "TextBox Test";
			$this->txt1->Text = "Change me";
			$this->txt1->AddAction(new QChangeEvent(), new QAjaxAction('txtChange'));
			$this->txt1->AddAction(new QKeyUpEvent(), new QAjaxAction('txtKeyUp'));

			$this->lblTxt1Change = new QLabel($this);
			$this->lblTxt1Change->Name = "Value after Change";

			$this->lblTxt1KeyUp = new QLabel($this);
			$this->lblTxt1KeyUp->Name = "Value after Key Up";

			$this->chk = new QCheckBox($this, 'chkbox');
			$this->chk->Name = "Checkbox Text";
			$this->chk->AddAction(new QClickEvent(), new QAjaxAction('chkChange'));
			$this->lblCheck = new QLabel($this);
			$this->lblCheck->Name = "Value after Click";
		}

		protected function txtChange($strFormId, $strControlId, $strParameter) {
			$this->lblTxt1Change->Text = $this->txt1->Text;
		}

		protected function txtKeyUp($strFormId, $strControlId, $strParameter) {
			$this->lblTxt1KeyUp->Text = $this->txt1->Text;
		}

		protected function chkChange($strFormId, $strControlId, $strParameter) {
			$this->lblCheck->Text = $this->chk->Checked;
		}

	}
AjaxTimingForm::Run('AjaxTimingForm');
