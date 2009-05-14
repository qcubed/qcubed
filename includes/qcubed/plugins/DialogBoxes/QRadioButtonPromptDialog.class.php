<?php

/**
 * A custom class that brings up an OVERLAY DIV with a a set of radio buttons.
 * The user has to pick an option, and then press either SAVE or Cancel.
 *
 * Pressing Save calls the callback method of the creator QForm, passing it the
 * new value inputed by the user into the textbox.
 *
 * SECURITY NOTE! The caller needs to do the escaping / verification of the data.
 */
class QRadioButtonPromptDialog extends QPromptDialog {
	// internal state
	public $radOptions;
	
	// default value of the label - use SetLabel to override
	protected $strLabel = "Pick an option:";
	
	public function __construct($objParentObject, $formFirstActionCallback, $strControlId = null) {
		parent::__construct($objParentObject, $formFirstActionCallback, $strControlId);
		
		// Feel free to override this template with your own
		$this->strTemplate = __PLUGINS__ . "/DialogBoxes/QRadioButtonPromptDialog.tpl.php";
		
		$this->radOptions = new QRadioButtonList($this);
	}

	/**
	 * Pass in the array of options (as label => value pairs)
	 * to display in the prompt.
	 * 
	 * If $selectedOption is not specified, it defaults to the first item
	 * in the passed array of options.
	 */
	public function SetOptions($arrOptions, $selectedOption = null) {
			if (!is_array($arrOptions) || sizeof($arrOptions) == 0) {
					throw new QCallerException("Must give a set of options as a non-blank array");
			}
			
			if (!$selectedOption) {
					list($firstKey, $firstValue) = each($arrOptions);
					$selectedOption = $firstValue;
			}

			$this->radOptions->RemoveAllItems();
			foreach ($arrOptions as $label => $value) {
					$this->radOptions->AddItem($label, $value, $value == $selectedOption);
			}
	}

	public function ShowDialogBox() {
		if ($this->radOptions->ItemCount == 0) {
			throw new QCallerException("The list of options cannot be empty for QRadioButtonPromptDialog");
		}
		parent::ShowDialogBox();
		
	}
	public function first_action_click() {
			$this->HideDialogBox();
			
			// Call the parent function's callback method, and pass it, as a
			// parameter, the new value of the textbox.
			$this->Form->{$this->firstActionCallback}($this->radOptions->SelectedValue);
	}
}
?>