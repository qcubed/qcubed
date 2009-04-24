<?php

abstract class QPromptDialog extends QDialogBox {
	// internal state; do not modify. Use the provided Set* methods to change 
	// the presentation
	protected $strIntroLabel = "Enter a value:";
	protected $strFirstActionLabel = "Save";

	protected $firstActionCallback;
	protected $secondActionCallback;
	
	// Sadly, the controls cannot be declared protected - otherwise .tpl doesn't work.
	public $lblPromptLabel;
	public $lblFirstAction;
	
	public $proxyFirstAction;
	public $proxySecondAction;
	public $lblBottom;
	
	public function __construct($objParentObject, $formFirstActionCallback, $strControlId = null) {
		parent::__construct($objParentObject, $strControlId);
		
		$this->AutoRenderChildren = false;
		$this->MatteClickable = false;

		$this->firstActionCallback = $formFirstActionCallback;

		// By default, this dialog box should be hidden
		$this->Display = false;

		$this->proxyFirstAction = new QControlProxy($objParentObject);
		$this->proxyFirstAction->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'first_action_click'));

		$this->proxySecondAction = new QControlProxy($objParentObject);
		$this->proxySecondAction->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'second_action_click'));

		$this->lblPromptLabel = new QLabel($this);
		$this->lblPromptLabel->HtmlEntities = false;
		
		$this->lblFirstAction = new QLabel($this);
		$this->lblFirstAction->Display = false;

		// The "bottom" label contains all the actionable controls (Save / Cancel "buttons" -
		// hyperlinks with control proxies hooked up)
		$this->lblBottom = new QLabel($this);
		$this->lblBottom->HtmlEntities = false;
		$this->lblBottom->TagName = "center";
		
		// Some visual defaults, feel free to override
		$this->Width = '210px';
		$this->Padding = '15px';
	}
	
	public function SetIntroLabel($strText) {
		$this->strIntroLabel = $strText . "<br>";
	}
	
	public function SetFirstActionLabel($strText) {
		$this->strFirstActionLabel = $strText;
	}
	
	public function SetSecondActionCallback($strFunctionName) {
		$this->secondActionCallback = $strFunctionName;
	}

	public function ShowDialogBox() {
		$this->lblPromptLabel->Text = $this->strIntroLabel;
		$this->lblFirstAction->Text = $this->strFirstActionLabel;
		
		$this->lblBottom->Text =
				"<b><a href='#' onclick=\"" .
						"qc.pA('" . $this->Form->FormId . "', '" . $this->proxyFirstAction->ControlId . "', 'QClickEvent', '', '');
						return false;\">" .
								$this->lblFirstAction->Text .
				"</a></b>" .

				"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .

				"<a href='#' onclick=\"" .
						"qc.getWrapper('" . $this->ControlId . "').hideDialogBox();" .
						"qc.pA('" . $this->Form->FormId . "', '" . $this->proxySecondAction->ControlId . "', 'QClickEvent', '', '');
						return false\">" .
								"Cancel" .
				"</a>";
						
		parent::ShowDialogBox();
	}

	public abstract function first_action_click();

	public function second_action_click() {
		// The hosting form has the optional ability to 
		// to privode a callback for the cancel click. 		
		$this->HideDialogBox();
		
		if ($this->secondActionCallback && strlen($this->secondActionCallback) > 0) {
			$this->Form->{$this->secondActionCallback}();
		}
	}
}
?>