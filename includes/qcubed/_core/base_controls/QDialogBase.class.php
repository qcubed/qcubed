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
	 * @property boolean $HasCloseButton Disables (false) or enables (true) the close X in the upper right corner of the title. 
	 * Can be set when initializing the dialog.
	 */
	
	class QDialogBase extends QDialogGen
	{
		protected $strClickedButtonId;
		protected $blnHasCloseButton = true;
		
		public function getJqControlId() {
			return $this->ControlId ."_ctl";	
		}
		
		/**
		 * Special RenderOutput to manage the wrapper. We turn off any of our own custom 
		 * wrapper attributes, because they can interfere with the wrapper JQuery UI 
		 * creates for the dialog.
		 * 
		 * RenderOutput wraps your content with valid divs and control-identifiers, echos your code
		 * to the content buffer or simply returns it. See {@link QControlBase::RenderHelper()}.
		 * 
		 * @param string $strOutput
		 * 			Your html-code which should be given out
		 * @param boolean $blnDisplayOutput
		 * 			should it be given out, or just be returned?
		 * @param boolean $blnForceAsBlockElement
		 * 			should it be given out as a block element, regardless of its configured tag?
		 * @return string
		 */
		protected function RenderOutput($strOutput, $blnDisplayOutput, $blnForceAsBlockElement = false) {
			// First, let's mark this control as being rendered and is ON the Page
			$this->blnRendering = false;
			$this->blnRendered = true;
			$this->blnOnPage = true;


			// Check for Visibility
			if (!$this->blnVisible)
				$strOutput = '';


			$strWrapperAttributes = '';
			if ($this->strWrapperCssClass)
				$strWrapperAttributes .= sprintf(' class="%s"', $this->strWrapperCssClass);

			switch ($this->objForm->CallType) {
				case QCallType::Ajax:
					$strOutput = QString::XmlEscape($strOutput);
					$strOutput = sprintf('<control id="%s">%s</control>', $this->strControlId, $strOutput);
					break;

				default:
					$strOutput = sprintf('<div id="%s_ctl" %s>%s</div>%s', $this->strControlId, $strWrapperAttributes, $strOutput, $this->GetNonWrappedHtml());
					break;
			}

			// Output or Return
			if ($blnDisplayOutput)
				print($strOutput);
			else
				return $strOutput;
		}
				
		
		protected function makeJqOptions() {
			$strOptions = parent::makeJqOptions();
		
			if (!$this->blnHasCloseButton) {
				$strOptions .= sprintf(', %s open: function(event, ui) { $j(".ui-dialog-titlebar-close", ui.dialog).hide(); }', "\n", "\n");
			}
			
			$strParentId = $this->ParentControl ? $this->ParentControl->ControlId : $this->Form->FormId;
			//move both the dialog and the matte back into the form, to ensure they continue to function
			$strOptions .= sprintf(', %s create: function() { $j(this).parent().appendTo("#%s"); $j(".ui-widget-overlay").appendTo("#%s"); }%s', "\n", $strParentId, $strParentId, "\n");
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
			qcubed.recordControlModification("$controlId", "ClickedButton", "$strButtonId");
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
			$this->blnModified = true;
			
			switch ($strName) {
				// Used by framework code above, do not call directly
				case 'ClickedButton':
					$this->strClickedButtonId = $mixValue;
					break;
					
				// set to false to remove the close x in upper right corner and disable the
				// escape key as well
				case 'HasCloseButton':
					try {
						$this->blnHasCloseButton = QType::Cast($mixValue, QType::Boolean);
						$this->blnCloseOnEscape = $this->blnHasCloseButton;
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