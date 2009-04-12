<?php

class QConfirmationDialog extends QPromptDialog {
	// default value of the label - use SetLabel to override
	protected $strIntroLabel = "Are you sure?";
	
	protected $strFirstActionLabel = "Proceed";
	
	// Feel free to override this template with your own
	public $strTemplate = "QConfirmationDialog.tpl.php";

	public function __construct($objParentObject, $formFirstActionCallback, $strControlId = null) {
		parent::__construct($objParentObject, $formFirstActionCallback, $strControlId);
	}

	public function first_action_click() {
		$this->HideDialogBox();
		
		// Call the parent function's callback method
		$this->Form->{$this->firstActionCallback}();
	}
}
?>