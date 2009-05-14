<?php

/**
 * A custom class that brings up an OVERLAY DIV with a prompt. The user has to
 * fill in a text box, and then press either SAVE or Cancel.
 *
 * Pressing Save calls the callback method of the creator QForm, passing it the
 * new value inputed by the user into the textbox.
 *
 * SECURITY NOTE! The caller needs to do the escaping / verification of the data
 * that they get from the user. 
 */
class QTextBoxPromptDialog extends QPromptDialog {
	// Feel free to override the properties of the textbox - for example, Width
	public $txtTextbox;
		
	// default value of the label - use SetLabel to override
	protected $strLabel = "Enter a value:";
	
	public function __construct($objParentObject, $formCallbackMethodOnContinue, $strControlId = null) {
		parent::__construct($objParentObject, $formCallbackMethodOnContinue, $strControlId);
		
		// Feel free to override this template with your own
		$this->strTemplate = __PLUGINS__ . "/DialogBoxes/QTextBoxPromptDialog.tpl.php";
				
		$this->txtTextbox = new QTextBox($this);
		
		// Ajax action doesn't work for Enter actions with Firefox - have to use ServerAction
		$this->txtTextbox->AddAction(new QEnterKeyEvent(), new QServerControlAction($this, "continue_click"));		
	}
	
	public function SetValue($strText) {
		$this->txtTextbox->Text = $strText;
	}
    
    public function GetValue($strText) {        
		return $this->txtTextbox->Text;
    }
	public function ShowDialogBox() {								
		parent::ShowDialogBox();
		
		// put the cursor into the textbox field as soon as the dialog box is shown
		QApplication::ExecuteJavaScript(sprintf("document.getElementById('%s').focus()", $this->txtTextbox->ControlId));
	}

	public function first_action_click() {
		$this->HideDialogBox();
		
		// Call the parent function's callback method, and pass it, as a
		// parameter, the new value of the textbox.
		$this->Form->{$this->firstActionCallback}($this->txtTextbox->Text);
	}
}
?>