<?php
	/**
	 * Dialog Base Class
	 * 
	 * The QDialogBase class defined here provides an interface between the generated
	 * QDialogGen class and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QDialog.class.php file instead.
	 *
	 */
	 
	/**
	 * Special event to handle button clicks. 
	 * 
	 * Add an action to this event to get a button click.
	 */
	class QDialog_ButtonEvent extends QEvent {
		/** Event Name */
		const EventName = 'QDialog_Button';	
	}


	/**
	 * Implements a JQuery UI Dialog
	 * 
	 * A QDialog is a QPanel that pops up on the screen and implements an "in window" dialog.
	 * 
	 * There are a couple of ways to use the dialog. The simplest is as follows:
	 * 
	 * In your Form_Create():
	 * <code>
	 * $this->dlg = new QDialog($this);
	 * $this->dlg->AutoOpen = false;
	 * $this->dlg->Modal = true;
	 * $this->dlg->Text = 'Show this on the dialog.'
	 * $this->dlg->AddButton ('OK', 'ok');
	 * $this->dlg->AddAction (new QDialog_ButtonEvent(), new QHideDialog());
	 * </code>
	 * 
	 * When you want to show the dialog:
	 * <code>
	 * $this->dlg->Open();
	 * </code>
	 * 
	 * And, also remember to draw the dialog in your form template:
	 * 
	 * <code>
	 * $this->dlg->Render();
	 * </code>
	 * 
	 * 
	 * Since QDialog is a decendant of QPanel, you can do anything you can to a normal QPanel, 
	 * inluding add Qcontrols and use a template. When you want to hide the dialog, call <code>Close()</code>
	 * 
	 * @property boolean $HasCloseButton Disables (false) or enables (true) the close X in the upper right corner of the title. Can be set when initializing the dialog.
	 * @property-read integer $ClickedButton Returns the id of the button most recently clicked. (read-only)
	 * 
	 * @link http://jqueryui.com/dialog/
	 * @package Controls\Base
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


			if (!$this->blnHasCloseButton) {
				if ($strOptions) $strOptions .= ', ';
				$strOptions .= 'open: function(event, ui) { $j(this).parent().find(".ui-dialog-titlebar-close").hide(); }';
			}
			
			//move both the dialog and the matte back into the form, to ensure they continue to function
			if ($strOptions) $strOptions .= ', ';
			$strOptions .= 'create: function() { $j(this).parent().appendTo($j("form:first")); }';
			return $strOptions;
		}
	
		/**
		 * Add a button to the dialog.
		 * 
		 * Use this override to add buttons BEFORE bringing up the dialog
		 * Attach actions to the QDialog_ButtonEvent event, and then call
		 * $dlg->ClickedButton to see which button was clicked.
		 */
		
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
		
		
		/**
		 * Show the dialog.
		 * 
		 * @deprecated
		 */
		public function ShowDialogBox() {
			if (!$this->blnVisible)
				$this->Visible = true;
			if (!$this->blnDisplay)
				$this->Display = true;
			$this->Open();
			$this->blnWrapperModified = false;
		}

		/**
		 * Hide the dialog
		 * 
		 * @deprecated
		 */
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
							$this->mixHeight = 'auto';
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
