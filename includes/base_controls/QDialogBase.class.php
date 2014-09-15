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
	 * The action parameter will be the id of the button that was clicked.
	 * @usage 	$dlg->AddAction(new QDialog_ButtonEvent(), new QAjaxAction($this, 'ButtonClick'));
	 */
	class QDialog_ButtonEvent extends QEvent {
		/** Event Name */
		const EventName = 'QDialog_Button';
		const JsReturnParam = 'ui'; // ends up being the button id
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
	 * Since QDialog is a descendant of QPanel, you can do anything you can to a normal QPanel,
	 * including add QControls and use a template. When you want to hide the dialog, call <code>Close()</code>
	 * 
	 * @property boolean $HasCloseButton Disables (false) or enables (true) the close X in the upper right corner of the title. Can be set when initializing the dialog.
	 * 	Can be set when initializing the dialog. Also enables or disables the ability to close the box by pressing the ESC key.
	 * @property-read integer $ClickedButton Returns the id of the button most recently clicked. (read-only)
	 * @property-write string $DialogState Set whether this dialog is in an error or highlight (info) state. Choose on of QDialog::StateNone, QDialogState::StateError, QDialogState::StateHighlight (write-only)
	 * 
	 * @link http://jqueryui.com/dialog/
	 * @package Controls\Base
	 */
	
	class QDialogBase extends QDialogGen
	{
		// enumerations

		// For DialogState option
		const StateNone = '';
		const StateError = 'ui-state-error';
		const StateHighlight = 'ui-state-highlight';

		/** @var bool default to auto open being false, since this would be a rare need, and dialogs are auto-rendered. */
		protected $blnAutoOpen = false;
        /** @var  string Id of last button clicked. */
		protected $strClickedButtonId;
        /** @var bool Should we draw a close button on the top? */
		protected $blnHasCloseButton = true;
        /** @var bool records whether dialog is open */
        protected $blnIsOpen = false;
		/** @var array whether a button causes validation */
		protected $blnValidationArray = array();

		protected $blnUseWrapper = true;
		/** @var  string state of the dialog for special display */
		protected $strDialogState;

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->blnDisplay = false;
			$this->mixCausesValidation = $this;
		}

		/**
		 * Validate the child items if the dialog is visible and the clicked button requires validation.
		 * This piece of magic makes validation specific to the dialog if an action is coming from the dialog,
		 * and prevents the controls in the dialog from being validated if the action is coming from outside
		 * the dialog.
		 *
		 * @return bool
		 */
		public function ValidateControlAndChildren() {
			if ($this->blnIsOpen &&
					!empty ($this->blnValidationArray[$this->strClickedButtonId])) {
				return parent::ValidateControlAndChildren();
			}
			return true;
		}

		/**
		 * Returns the control id for purposes of jQuery UI.
		 * @return string
		 */
		public function getJqControlId() {
			if ($this->blnUseWrapper) {
				return $this->ControlId . '_ctl';
			}
			return $this->ControlId;
		}

		/**
		 * Overrides the parent to add code to cause the default button to be fired if an enter key is pressed
		 * on a control. This purposefully does not include textarea controls, which should get the enter key to
		 * insert a newline.
		 *
		 * @return string
		 */

		public function GetControlJavaScript() {
			$strJs = parent::GetControlJavaScript();

			// Fire the default button on enter event. Works with both dialog buttons and QCubed buttons.
			// This also prevents other submit buttons outside the dialog from firing as a result of pressing enter in a control inside this dialog.
			// Since the parent control calls off on each draw, we will need to reattach events on each draw.
			$strJs .= '.on ("keydown", "input,select", function(event) {
				if (event.which == 13) {
					var b = $j(this).closest("[role=\'dialog\']").find("button[type=\'submit\']");
					if (b) {
						b[0].click();
					}
					event.preventDefault();
				}
			});
			';

			return $strJs;
		}

		/**
		 * Add additional javascript to the dialog creation to further format the dialog.
		 * This will set the class of the title bar to the strDialogState value and add an
		 * icon to implement a dialog state. Override and restyle for a different look.
		 * @return string
		 */
		protected function StylingJs() {
			$strJs = '';
			if ($this->strDialogState) {
				// Move the dialog class to the header of dialog to improve the appearance over the default.
				// Also add an appropriate icon.
				// Override this if you want your dialogs to look different.
				switch ($this->strDialogState) {
					case QDialog::StateError:
						$strIcon = 'alert';
						break;

					case QDialog::StateHighlight:
						$strIcon = 'info';
						break;
				}
				$strIconJs = sprintf('<span class="ui-icon ui-icon-%s" ></span>', $strIcon);

				$strJs .= sprintf (
					'$j("#%s").prev().addClass("%s").prepend(\'%s\');
					',
					$this->getJqControlId(), $this->strDialogState, $strIconJs);
			}
			return $strJs;
		}

		/**
		 * Implements QCubed specific dialog functions. Makes sure dialog is put at the end of the form
		 * to fix an overlay problem with jQuery UI.
		 *
		 * @return string
		 */
		protected function makeJqOptions() {
			$strOptions = parent::makeJqOptions();
            $controlId = $this->ControlId;
			$strFormId = $this->Form->FormId;

			if (!$this->blnHasCloseButton) {
				$strHideCloseButtonScript = '$j(this).prev().find(".ui-dialog-titlebar-close").hide();';
			}
			else {
				$strHideCloseButtonScript = '';
			}

            if ($strOptions) {
                $strOptions .= ', ';
            } else {
                $strOptions = '';
            }

            $strOptions .= <<<FUNC
                open: function(event, ui) {
                    qcubed.recordControlModification("$controlId", "_IsOpen", true);
                    $strHideCloseButtonScript
			    },
			    close: function(event, ui) {
			        qcubed.recordControlModification("$controlId", "_IsOpen", false);
			    },
			    appendTo: "#$strFormId"
FUNC;

			// By doing the styling at creation time, we ensure that it gets done only once.
			if ($strCreateJs = $this->StylingJs()) {
				$strOptions .= ",
				create: function(event, ui) {
				$strCreateJs
				}";
			}
			return $strOptions;
		}


		/**
		 * Adds a button to the dialog. Use this to add buttons BEFORE bringing up the dialog.
		 *
		 * @param $strButtonName
		 * @param $strButtonId	Id associated with the button for detecting clicks. Note that this is not the id on the form.
		 * 	Different dialogs can have the same button id.
		 *  To specify a control id for the button (for styling purposes for example), set the id in options.
		 * @param bool $blnCausesValidation If the button causes the dialog to be validated before the action is executed
		 * @param bool $blnIsPrimary Whether this button will be automatically clicked if user presses an enter key.
		 * @param string $strConfirmation If set, will confirm with the given string before the click is sent
		 * @param array $options	Additional attributes to add to the button. Useful things to do are:
		 *  	array('class'=>'ui-button-left') to create a button on the left side.
		 *  	array('class'=>'ui-priority-primary') to style a button as important or primary.
		 */
		public function AddButton ($strButtonName, $strButtonId = null, $blnCausesValidation = false, $blnIsPrimary = false, $strConfirmation = null, $options = null) {
			if (!$this->mixButtons) {
				$this->mixButtons = array();
			}
			$strJS = '';
			if ($strConfirmation) {
				$strJS .= sprintf ('if (confirm("%s"))', $strConfirmation);
			}

			$controlId = $this->ControlId;

			if (!$strButtonId) {
				$strButtonId = $strButtonName;
			}

			$strJS .= sprintf('
				{
					qcubed.recordControlModification("%s", "_ClickedButton", "%s");
					jQuery("#%s").trigger("QDialog_Button", $j(event.currentTarget).data("btnid"));
				}
				event.preventDefault();
				', $controlId, $strButtonId, $controlId);

			$btnOptions = array ('text'=>$strButtonName,
				'click'=>new QJsNoQuoteKey(new QJsClosure($strJS, array ('event'))),
				'data-btnid'=>$strButtonId);

			if ($options) {
				$btnOptions = array_merge($options, $btnOptions);
			}

			if ($blnIsPrimary) {
				$btnOptions['type'] = 'submit';
			}

			$this->mixButtons[] = $btnOptions;

			$this->blnValidationArray[$strButtonId] = $blnCausesValidation;

			$this->blnModified = true;
		}

		/**
		 * Remove the given button from the dialog.
		 *
		 * @param $strButtonId
		 */
		public function RemoveButton ($strButtonId) {
			if (!empty($this->mixButtons)) {
				$this->mixButtons = array_filter ($this->mixButtons, function ($a) use ($strButtonId) {return $a['id'] == $strButtonId;});
			}

			unset ($this->blnValidationArray[$strButtonId]);

			$this->blnModified = true;
		}

		/**
		 * Remove all the buttons from the dialog.
		 */
		public function RemoveAllButtons() {
			$this->mixButtons = array();
			$this->blnValidationArray = array();
			$this->blnModified = true;
		}

		/**
		 * Show or hide the given button. Changes the display attribute, so the buttons will reflow.
		 *
		 * @param $strButtonId
		 * @param $blnVisible
		 */
		public function ShowHideButton ($strButtonId, $blnVisible) {
			if ($blnVisible) {
				QApplication::ExecuteJavaScript(
					sprintf ('$j("#%s").next().find("button[data-btnid=\'%s\']").show();',
						$this->getJqControlId(), $strButtonId)
				);
			} else {
				QApplication::ExecuteJavaScript(
					sprintf ('$j("#%s").next().find("button[data-btnid=\'%s\']").hide();',
						$this->getJqControlId(), $strButtonId)
				);
			}
		}

		/**
		 * Applies CSS styles to a button that is already in the dialog.
		 *
		 * @param string $strButtonId Id of button to set the style on
		 * @param array $styles Array of key/value style specifications
		 */
		public function SetButtonStyle ($strButtonId, $styles) {
			QApplication::ExecuteJavaScript(
				sprintf ('$j("#%s").next().find("button[data-btnid=\'%s\']").css(%s)', $this->getJqControlId(), $strButtonId, JavaScriptHelper::toJsObject($styles))
			);
		}

		/**
		 * Adds a close button that just closes the dialog without firing the QDialogButton event. You can
		 * trap this by adding an action to the QDialog_CloseEvent.
		 *
		 * @param $strButtonName
		 */
		public function AddCloseButton ($strButtonName) {
			// This is an alternate button format supported by jQuery UI.
			$this->mixButtons[$strButtonName] = new QJsClosure('$j(this).dialog("close")');
		}

		/**
		 * Create a message dialog. Automatically adds an OK button that closes the dialog. To detect the close,
		 * add an action on the QDialog_CloseEvent. To change the message, use the return value and set ->Text.
		 *
		 * @param string $strMessage
		 * @param QForm | QControl $parent
		 * @param string $strControlId
		 * @return QDialog
		 */
		public static function Message($strMessage, $parent, $strControlId = null) {
			$dlg = new QDialog($parent, $strControlId);
			$dlg->Modal = true;
			$dlg->Resizable = false;
			$dlg->Text = $strMessage;
			$dlg->Height = 100; // fix problem with jquery ui dialog making space for buttons that don't exist
			return $dlg;
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

				case '_IsOpen': // Internal only, to detect when dialog has been opened or closed.
                    try {
                        $this->blnIsOpen = QType::Cast($mixValue, QType::Boolean);
                        $this->blnAutoOpen = $this->blnIsOpen;  // in case it gets redrawn
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

				case 'DialogState':
					try {
						$this->strDialogState = QType::Cast($mixValue, QType::String);
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