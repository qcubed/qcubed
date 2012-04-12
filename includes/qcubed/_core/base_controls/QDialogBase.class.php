<?php
	/**
	 * The QDialogBase class defined here provides an interface between the generated
	 * QDialogGen class and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QDialog.class.php file instead.
	 *
	 */
	 
	 /*
	 	Notes on the dialog:
	 	
	 	This dialog will turn a panel into a dialog box. The general idea is that your
	 	panel should have all of your form objects, except for the buttons. Use the AddButton
	 	function to add buttons to the dialog. The dialog will automatically
	 	arrange the buttons and color them with the current theme. Add an action to the
	 	QDialog_ButtonEvent to respond to a button click.
	 	
	 	If you want to use your own buttons, that is fine. 
	 	Call ->Close from your button script to close down
	 	this dialog.
	 */


	/**
	 * Special event to handle button clicks. Add an action to this event to get a button click.
	 */
	class QDialog_ButtonEvent extends QEvent {
		const EventName = 'QDialog_Button';	
	}


	/**
	 * @property boolean $HasCloseButton Disables (false) or enables (true) the close X in the upper right corner of the title. Can be set when initializing the dialog.
	 * @property-read integer $ClickedButton Returns the id of the button most recently clicked. (read-only)
	 * 
	 */
	
	class QDialogBase extends QDialogGen
	{
		protected $strClickedButtonId;
		protected $blnHasCloseButton = true;
		protected $blnUseWrapper = true;	// fix for jQuery UI interaction problem with Ajax updated dialogs.

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->blnDisplay = false;
		}
		
		public function getJqControlId() {
			if ($this->blnUseWrapper) {
				return $this->ControlId . '_ctl';
			}
			return $this->ControlId;
		}

		protected function makeJqOptions() {
			$strOptions = parent::makeJqOptions();

			$strId = $this->getJqControlId();
			
			if (!$this->blnHasCloseButton) {
				$strOptions .= sprintf(', %s open: function(event, ui) { $j(this).parent().find(".ui-dialog-titlebar-close").hide(); }', "\n", $strId);
			}
			
			//move both the dialog and the matte back into the form, to ensure they continue to function
			$strOptions .= ', create: function() { $j(this).parent().appendTo($j("form:first")); }';
			return $strOptions;
		}
	
		// Use this override to add buttons BEFORE bringing up the dialog
		// Attach actions to the QDialog_ButtonEvent event, and then call
		// $dlg->ClickedButton to see which button was clicked.
		public function AddButton ($strButtonName, $strButtonId) {
			if (!$this->mixButtons) {
				$this->mixButtons = array();
			}
			$controlId = $this->ControlId;
			$strJS =<<<FUNC
			qcubed.recordControlModification("$controlId", "_ClickedButton", "$strButtonId");
			jQuery("#$controlId").trigger("QDialog_Button");
FUNC;
									
			$this->mixButtons[$strButtonName] = new QJsClosure($strJS);
									
			$this->blnModified = true;
		}
		
		
		// Deprecated. Call Open() instead.
		public function ShowDialogBox() {
			if (!$this->blnVisible)
				$this->Visible = true;
			if (!$this->blnDisplay)
				$this->Display = true;
			$this->Open();
			$this->blnWrapperModified = false;
		}

		// Deprecated. Call Close() instead.
		public function HideDialogBox() {
			$this->blnDisplay = false;
			$this->Close();
			$this->blnWrapperModified = false;
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				case '_ClickedButton': // Internal only. Do not use. Used by JS above to keep track of clicked button.
					try {
						$this->strClickedButtonId = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				// set to false to remove the close x in upper right corner and disable the
				// escape key as well
				case 'HasCloseButton':
					try {
						$this->blnHasCloseButton = QType::Cast($mixValue, QType::Boolean);
						$this->blnCloseOnEscape = $this->blnHasCloseButton;
						$this->blnModified = true;	// redraw
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
										 
				case 'Height':
					try {
						if ($mixValue == 'auto') {
							$this->intHeight = 'auto';
							if ($this->Rendered) {
								$this->CallJqUiMethod("option", $strName, $mixValue);
							}
						} else {
							parent::__set($strName, $mixValue);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Width':
					try {
						if ($mixValue == 'auto') {
							$this->intWidth = 'auto';
							if ($this->Rendered) {
								$this->CallJqUiMethod("option", $strName, $mixValue);
							}
						} else {
							parent::__set($strName, $mixValue);
						}
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				}
		}
		
		public function __get($strName) {
			switch ($strName) {
				case 'ClickedButton': return $this->strClickedButtonId;
				
				case 'HasCloseButton' : return $this->blnHasCloseButton;
				
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}
	}
?>
