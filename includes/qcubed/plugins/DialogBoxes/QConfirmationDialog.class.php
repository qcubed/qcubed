<?php

class QConfirmationDialog extends QPromptDialog {
	// default value of the label - use SetLabel to override
	protected $strIntroLabel = "Are you sure?";
	
	protected $strFirstActionLabel = "Proceed";

	public function __construct($objParentObject, $formFirstActionCallback, $strControlId = null) {
		parent::__construct($objParentObject, $formFirstActionCallback, $strControlId);
		
		// Feel free to override this template with your own
		$this->strTemplate = __PLUGINS__ . "/DialogBoxes/QConfirmationDialog.tpl.php";
	}

	public function first_action_click() {
		$this->HideDialogBox();
		
		// Call the parent function's callback method
		$this->Form->{$this->firstActionCallback}();
	}
}
?>