<?php
require_once('../qcubed.inc.php');
QApplication::$EncodingType = 'ISO-8859-1';

/**
 * Class MyControl
 * A text box to test the setAdditionalPostVar function's abilities, including ability to pass a null value.
 */
class MyControl extends QControl {
	public $txt;
	public $nullVal;

	public function GetControlHtml() {
		return $this->RenderTag('input');
	}
	public function ParsePostData() {
		if (isset($_POST[$this->ControlId . '_extra'])) {
			$this->txt = $_POST[$this->ControlId . '_extra']['txt'];
			$this->nullVal = $_POST[$this->ControlId . '_extra']['nullVal'];
		}
	}

	public function Validate() {
		return true;
	}

	public function GetEndScript()
	{
		$strId = $this->ControlId;

		$strJs = parent::GetEndScript();
		$strJs .= ';';
		$strJs .= "\$j('#{$strId}').change(function(event) {
			qcubed.setAdditionalPostVar('{$strId}_extra', {txt: \$j(this).val(), 'nullVal': null});
			qcubed.recordControlModification('{$strId}', 'Name', \$j(this).val());
			})";
		return $strJs;
	}
}

class ParamsForm extends QForm {
	protected $txtText;
	protected $txt2;
	protected $pnlTest;
	protected $lstCheckables;

	protected $btnSubmit;
	protected $btnAjax;

	protected function Form_Create() {
		$this->txtText = new MyControl($this);
		$this->txtText->Name = "Special Vals";

		$this->txt2 = new QTextBox($this);
		$this->txt2->Name = "Regular Val";

		$this->pnlTest = new QPanel($this);
		//$this->pnlTest->HtmlEntities = true;
		$this->pnlTest->Name = 'Result';

		$this->lstCheckables = new QCheckBoxList($this);
		$this->lstCheckables->AddItem('é - accented', 'é');
		$this->lstCheckables->AddItem('ü - umlat', 'ü');
		$this->lstCheckables->AddItem('î - circuflexed', 'î');
		$this->lstCheckables->AddItem('ß - Eszett', 'ß');

		$strId = $this->txtText->ControlId;
		$strJs = "{txt: \$j('#{$strId}').val(), nullVal:null}";

		$this->btnSubmit = new QButton($this);
		$this->btnSubmit->Text = "Server Submit";
		$this->btnSubmit->AddAction(new QClickEvent(), new QServerAction('submit_click', null, $strJs));

		$this->btnAjax = new QButton($this);
		$this->btnAjax->Text = "Ajax Submit";
		$this->btnAjax->AddAction(new QClickEvent(), new QAjaxAction('submit_click', null, null, $strJs));
	}

	protected function submit_click($strFormId, $strControlId, $mixParam) {
		// test setAdditionalPostParam
		$strResult = $this->txtText->txt;
		$strResult .= ($this->txtText->nullVal === null ? ' and is null' : ' and is not null');

		// test parameters
		$strResult .= "\n" . var_export($mixParam, true);

		// test checkables
		$checkables = $this->lstCheckables->SelectedValues;
		$strResult .= "\n" . var_export($checkables, true);
		$checkables = $this->lstCheckables->SelectedNames;
		$strResult .= "\n" . var_export($checkables, true);
		$strResult .= "\n" . 'Ordinals: ' . ord($this->txtText->Name) . ',' . ord($strResult);
		$strResult .= "\n" . 'Regular: ' . $this->txt2->Text;
		
		$this->pnlTest->Text = $strResult;
	}
}
ParamsForm::Run('ParamsForm');
