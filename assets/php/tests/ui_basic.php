<?php
    require_once('../qcubed.inc.php');
    
	class BasicForm extends QForm {
		protected $txtText;
		protected $txtText2;
		protected $lstSelect;
		protected $lstSelect2;
		protected $lstCheck;
		protected $lstRadio;
		protected $chkCheck;
		protected $rdoRadio1;
		protected $rdoRadio2;
		protected $rdoRadio3;

		protected $btnServer;
		protected $btnAjax;

		protected function Form_Create() {
			$this->txtText = new QTextBox($this);
			$this->txtText->Text = 'Default';
			$this->txtText->Name = 'TextBox';

			$this->txtText2 = new QTextBox($this);
			$this->txtText2->Text = 'Big';
			$this->txtText2->Name = 'TextBox';
			$this->txtText2->TextMode = QTextMode::MultiLine;
			$this->txtText2->Rows = 3;

			$this->chkCheck = new QCheckBox($this);
			$this->chkCheck->Name = 'CheckBox';

			$items = array (1=>'Item1', 2=>'Item2', 3=>'Item3');
			$this->lstSelect = new QListBox($this);
			$this->lstSelect->AddItems ($items);
			$this->lstSelect->Name = 'Select';

			$this->lstSelect2 = new QListBox($this);
			$this->lstSelect2->AddItems ($items);
			$this->lstSelect2->Name = 'Multiselect';
			$this->lstSelect2->SelectionMode = QSelectionMode::Multiple;

			$this->lstCheck = new QCheckBoxList($this);
			$this->lstCheck->AddItems ($items);
			$this->lstCheck->Name = 'Check List';
			$this->lstCheck->RepeatDirection = QRepeatDirection::Horizontal;
			$this->lstCheck->RepeatColumns = 4;

			$this->lstRadio = new QRadioButtonList($this);
			$this->lstRadio->AddItems ($items);
			$this->lstRadio->Name = 'Radio List';
			$this->lstRadio->RepeatDirection = QRepeatDirection::Horizontal;
			$this->lstRadio->RepeatColumns = 4;
			$this->lstRadio->SelectedIndex = 1;

			$this->rdoRadio1 = new QRadioButton($this);
			$this->rdoRadio1->Name = 'Item 1';
			$this->rdoRadio1->GroupName = 'MyGroup';
			$this->rdoRadio2 = new QRadioButton($this);
			$this->rdoRadio2->Name = 'Item 2';
			$this->rdoRadio2->GroupName = 'MyGroup';
			$this->rdoRadio3 = new QRadioButton($this);
			$this->rdoRadio3->Name = 'Item 3';
			$this->rdoRadio3->GroupName = 'MyGroup';


			$this->btnServer = new QButton ($this);
			$this->btnServer->Text = 'Server Submit';
			$this->btnServer->AddAction(new QClickEvent(), new QServerAction('submit_click'));

			$this->btnAjax = new QButton ($this);
			$this->btnAjax->Text = 'Ajax Submit';
			$this->btnAjax->AddAction(new QClickEvent(), new QAjaxAction('submit_click'));
		}

		protected function submit_click($strFormId, $strControlId, $strParameter) {
			$strTemp = $this->txtText->Text;
			$this->txtText->Text = $this->txtText2->Text;
			$this->txtText2->Text = $strTemp;

			$this->chkCheck->Warning = 'Value = ' . $this->chkCheck->Checked;
			$this->lstSelect->Warning = 'Value = ' . $this->lstSelect->SelectedValue;
			$this->lstSelect2->Warning = 'Values = ' . implode (',', $this->lstSelect2->SelectedValues);
			$this->lstCheck->Warning = 'Values = ' . implode (',', $this->lstCheck->SelectedValues);
			$this->lstRadio->Warning = 'Value = ' . $this->lstRadio->SelectedValue;
			$this->rdoRadio1->Warning = 'Value = ' . $this->rdoRadio1->Checked;
			$this->rdoRadio2->Warning = 'Value = ' . $this->rdoRadio2->Checked;
			$this->rdoRadio3->Warning = 'Value = ' . $this->rdoRadio3->Checked;
		}
		
	}
	BasicForm::Run('BasicForm');
?>