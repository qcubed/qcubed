<?php
	/**
	 * This file contains the QFormBase class.
	 *
	 * @package Controls
	 * @filesource
	 */

	/**
	 * @package Controls
	 * @property-read string     $FormId              Form ID of the QForm
	 * @property-read string     $CallType            Type of call (useful when the QForm submits due to user action)
	 * @property-read QWaitIcon  $DefaultWaitIcon     Default Ajax wait icon control
	 * @property-read integer    $FormStatus          Status of form (pre-render stage, rendering stage of already rendered stage)
	 * @property string          $HtmlIncludeFilePath (Alternate) path to the template file to be used
	 * @property string          $CssClass            Form CSS class.
	 */
	abstract class QFormBase extends QBaseClass {
		///////////////////////////
		// Static Members
		///////////////////////////
		/** @var bool True when css scripts get rendered on page. Lets user call RenderStyles in header. */
		protected static $blnStylesRendered = false;

		///////////////////////////
		// Protected Member Variables
		///////////////////////////
		/** @var string Form ID (usually passed as the first argument to the 'Run' method call) */
		protected $strFormId;
		/** @var integer representational integer value of what state the form currently is in */
		protected $intFormStatus;
		/** @var QControl[] Array of QControls with this form as the parent */
		protected $objControlArray;
		/**
		 * @var QControlGrouping List of Groupings in the form (for old drag and drop)
		 * Use of this is deprecated in favor of jQueryUI drag and drop, but code remains in case we need it again.
		 * @deprecated
		 */
		protected $objGroupingArray;
		/** @var bool Has the body tag already been rendered? */
		protected $blnRenderedBodyTag = false;
		protected $blnRenderedCheckableControlArray = array();
		/** @var string The type of call made to the QForm (Ajax, Server or Fresh GET request) */
		protected $strCallType;
		/** @var null|QWaitIcon Default wait icon for the page/QForm  */
		protected $objDefaultWaitIcon = null;

		protected $strFormAttributeArray = array();

		/** @var array List of included JavaScript files for this QForm */
		protected $strIncludedJavaScriptFileArray = array();
		/** @var array List of ignored JavaScript files for this QForm */
		protected $strIgnoreJavaScriptFileArray = array();

		/** @var array List of included CSS files for this QForm */
		protected $strIncludedStyleSheetFileArray = array();
		/** @var array List of ignored CSS files for this QForm */
		protected $strIgnoreStyleSheetFileArray = array();

		protected $strPreviousRequestMode = false;
		/**
		 * @var string The QForm's template file path.
		 * When this value is not supplied, the 'Run' function will try to find and use the
		 * .tpl.php file with the same filename as the QForm in the same same directory as the QForm file.
		 */
		protected $strHtmlIncludeFilePath;
		/** @var string CSS class to be set for the 'form' tag when QCubed Renders the QForm */
		protected $strCssClass;

		protected $strCustomAttributeArray = null;

		protected $strWatcherTime;

		///////////////////////////
		// Form Status Constants
		///////////////////////////
		/** Form has not started rendering */
		const FormStatusUnrendered = 1;
		/** Form has started rendering but has not finished */
		const FormStatusRenderBegun = 2;
		/** Form rendering has already been started and finished */
		const FormStatusRenderEnded = 3;

		///////////////////////////
		// Form Preferences
		///////////////////////////
		/**
		 * @var null|string The key to encrypt the formstate
		 *              when saving and retrieving from the chosen FormState handler
		 */
		public static $EncryptionKey = null;
		/**
		 * @var string Chosen FormStateHandler
		 *              default is QFormStateHandler as shown here,
		 *              however it is read from the configuration.inc.php (in the QForm class)
		 *              In case something goes wrong with QForm, the default FormStateHandler here will
		 *              try to take care of the situation.
		 */
		public static $FormStateHandler = 'QFormStateHandler';

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method for getting property values of object
		 * @param string $strName Name of the propery
		 *
		 * @return int|mixed|null|string
		 * @throws QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "FormId": return $this->strFormId;
				case "CallType": return $this->strCallType;
				case "DefaultWaitIcon": return $this->objDefaultWaitIcon;
				case "FormStatus": return $this->intFormStatus;
				case "HtmlIncludeFilePath": return $this->strHtmlIncludeFilePath;
				case "CssClass": return $this->strCssClass;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		/**
		 * PHP magic function to set the value of properties of class object
		 * @param string $strName Name of the property
		 * @param string $mixValue Value of the property
		 *
		 * @return mixed|string
		 * @throws QCallerException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "HtmlIncludeFilePath":
					// Passed-in value is null -- use the "default" path name of file".tpl.php"
					if (!$mixValue) {
						$strPath = realpath(substr(QApplication::$ScriptFilename, 0, strrpos(QApplication::$ScriptFilename, '.php')) . '.tpl.php');
						if ($strPath === false) {
							// Look again based on the object name
							$strPath = realpath(get_class($this) . '.tpl.php');
						}
					}

					// Use passed-in value
					else
						$strPath = realpath($mixValue);

					// Verify File Exists, and if not, throw exception
					if (is_file($strPath)) {
						$this->strHtmlIncludeFilePath = $strPath;
						return $strPath;
					} else
						throw new QCallerException('Accompanying HTML Include File does not exist: "' . $mixValue . '"');
					break;

				case "CssClass":
					try {
						return ($this->strCssClass = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}


		/////////////////////////
		// Helpers for ControlId Generation
		/////////////////////////

		/**
		 * Generates Control ID used to keep track of those QControls whose ID was not explicitly set.
		 * It uses the counter variable to maintain uniqueness for Control IDs during the life of the page
		 * Life of the page is untill the time when the formstate expired and is removed by the
		 * garbage collection of the formstate handler
		 * @return string the Ajax Action ID
		 */
		public function GenerateControlId() {
//			$strToReturn = sprintf('control%s', $this->intNextControlId);
			$strToReturn = sprintf('c%s', $this->intNextControlId);
			$this->intNextControlId++;
			return $strToReturn;
		}
		/**
		 * @var int Counter variable to contain the numerical part of the Control ID value.
		 *      it is automatically incremented everytime the GenerateControlId() runs
		 */
		protected $intNextControlId = 1;

		/////////////////////////
		// Helpers for AjaxActionId Generation
		/////////////////////////
		/**
		 * Generates Ajax Action ID used to keep track of Ajax Actions
		 * It uses the counter variable to maintain uniqueness for Ajax Action IDs during the life of the page
		 * Life of the page is untill the time when the formstate expired and is removed by the
		 * garbage collection of the formstate handler
		 * @return string the Ajax Action ID
		 */
		public function GenerateAjaxActionId() {
			$strToReturn = sprintf('a%s', $this->intNextAjaxActionId);
			$this->intNextAjaxActionId++;
			return $strToReturn;
		}

		/**
		 * @var int Counter variable to contain the numerical part of the AJAX ID value.
		 *      it is automatically incremented everytime the GenerateAjaxActionId() runs
		 */
		protected $intNextAjaxActionId = 1;

		/////////////////////////
		// Event Handlers
		/////////////////////////
		/**
		 * Custom Form Run code.
		 * To contain code which should be run 'AFTER' QCubed's QForm run has been completed
		 * but 'BEFORE' the custom event handlers are called
		 * (In case it is to be used, it should be overriden by a child class)
		 */
		protected function Form_Run() {}

		/**
		 * To contain the code which should be executed after the Form Run and
		 * before the custom handlers are called (In case it is to be used, it should be overridden by a child class)
		 * In this situation, we are about to process an event, or the user has reloaded the page. Do whatever you
		 * need to do before any event processing.
		 */
		protected function Form_Load() {}

		/**
		 * To contain the code to initialize the QForm on the first call.
		 * Once the QForm is created, the state is saved and is reused by the Run method.
		 * In short - this function will run only once (the first time the QForm is to be created)
		 * (In case it is to be used, it should be overriden by a child class)
		 */
		protected function Form_Create() {}

		/**
		 * To contain the code to be executed after Form_Run, Form_Create, Form_Load has been called
		 * and the custom defined event handlers have been executed but actual rendering process has not begun.
		 * This is a good place to put data into a session variable that you need to send to
		 * other forms.
		 */
		protected function Form_PreRender() {}

		/**
		 * Override this method to set data in your form controls. Appropriate things to do would be to:
		 * - Respond to options sent by _GET or _POST variables.
		 * - Load data into the control from the database
		 * - Initialize controls whose data depends on the state or data in other controls.
		 *
		 * When this is called, the controls will have been created by Form_Create, and will have already read their saved state.
		 *
		 */
		protected function Form_Initialize() {}

		/**
		 * The Form_Validate method.
		 *
		 * Before we get here, all the controls will first be validated. Override this method to do
		 * additional form level validation, and any form level actions needed as part of the validation process,
		 * like displaying an error message.
		 *
		 * This is the last thing called in the validation process, and will always be called if
		 * validation is requested, even if prior controls caused a validation error. Return false to prevent
		 * validation and cancel the current action.
		 *
		 * $blnValid will contain the result of control validation. If it is false, you know that validation will
		 * fail, regardless of what you return from the function.
		 *
		 * @return bool 	Return false to prevent validation.
		 */
		protected function Form_Validate() {return true;}

		/**
		 * If you want to respond in some way to an invalid form that you have not already been able to handle,
		 * override this function. For example, you could display a message that an error occurred with some of the
		 * controls.
		 */
		protected function Form_Invalid() {}

		/**
		 * This function is meant to be overriden by child class and is called when the Form exits
		 * (After the form render is complete and just before the Run function completes execution)
		 */
		protected function Form_Exit() {}


		/**
		 * VarExport the Controls or var_export the current QForm
		 * (well, be ready for huge amount of text)
		 * @param bool $blnReturn
		 *
		 * @return mixed
		 */
		public function VarExport($blnReturn = true) {
			if ($this->objControlArray) foreach ($this->objControlArray as $objControl)
				$objControl->VarExport(false);
			if ($blnReturn) {
				return var_export($this, true);
			}
			else {
				return null;
			}
		}

		/**
		 * Returns whether or not the checkable control with the given controlId has been rendered or not.
		 * @param string $strControlId
		 *
		 * @return bool
		 */
		public function IsCheckableControlRendered($strControlId) {
			return array_key_exists($strControlId, $this->blnRenderedCheckableControlArray);
		}

		/**
		 * Helper function for below GetModifiedControls
		 * @param QControl $objControl
		 * @return boolean
		 */
		protected static function IsControlModified ($objControl) {
			return $objControl->IsModified();
		}
		/**
		 * Return only the controls that have been modified
		 */
		public function GetModifiedControls() {
			$ret = array_filter ($this->objControlArray, 'QForm::IsControlModified');
			return $ret;
		}

		/**
		 * This method initializes the actual layout of the form
		 * It runs in all cases including initial form (the time when Form_Create is run) as well as on
		 * trigger actions (QServerAction, QAjaxAction, QServerControlAction and QAjaxControlAction)
		 *
		 * It is responsible for implementing the logic and sequence in which page wide checks are done
		 * such as running Form_Validate and Control validations for every control of the page and their
		 * child controls. Checking for an existing FormState and loading them before trigerring any action
		 * is also a responsibility of this method.
		 * @param string $strFormId The Form ID of the calling QForm
		 * @param null $strAlternateHtmlFile location of the alternate HTML template file
		 *
		 * @throws QCallerException
		 * @throws QInvalidFormStateException
		 * @throws Exception
		 */
		public static function Run($strFormId, $strAlternateHtmlFile = null) {
			// See if we can get a Form Class out of PostData
			$objClass = null;
			if (array_key_exists('Qform__FormId', $_POST) && ($_POST['Qform__FormId'] == $strFormId) && array_key_exists('Qform__FormState', $_POST)) {
				$strPostDataState = $_POST['Qform__FormState'];

				if ($strPostDataState)
					// We might have a valid form state -- let's see by unserializing this object
					$objClass = QForm::Unserialize($strPostDataState);

				// If there is no QForm Class, then we have an Invalid Form State
				if (!$objClass) {
					self::InvalidFormState();
				}
			}

			if ($objClass) {
				// Globalize
				global $_FORM;
				$_FORM = $objClass;

				$objClass->strCallType = $_POST['Qform__FormCallType'];
				$objClass->intFormStatus = QFormBase::FormStatusUnrendered;

				if ($objClass->strCallType == QCallType::Ajax)
					QApplication::$RequestMode = QRequestMode::Ajax;
				else if($objClass->strCallType == QCallType::Server && array_key_exists('Qform__FormParameterType', $_POST)) {
					$param = array();
					parse_str(urldecode($_POST['Qform__FormParameter']), $param);
					$_POST['Qform__FormParameter'] = $param['Qform__FormParameter'];
				}

				// Iterate through all the control modifications
				$strModificationArray = explode("\n", trim($_POST['Qform__FormUpdates']));
				if ($strModificationArray) foreach ($strModificationArray as $strModification) {
					$strModification = trim($strModification);

					if ($strModification) {
						$intPosition = strpos($strModification, ' ');
						$strControlId = substr($strModification, 0, $intPosition);
						$strModification = substr($strModification, $intPosition + 1);

						$intPosition = strpos($strModification, ' ');
						if ($intPosition !== false) {
							$strProperty = substr($strModification, 0, $intPosition);
							$strValue = substr($strModification, $intPosition + 1);
						} else {
							$strProperty = $strModification;
							$strValue = null;
						}

						switch ($strProperty) {
							case 'Parent':
								if ($strValue) {
									if ($strValue == $objClass->FormId) {
										$objClass->objControlArray[$strControlId]->SetParentControl(null);
									} else {
										$objClass->objControlArray[$strControlId]->SetParentControl($objClass->objControlArray[$strValue]);
									}
								} else {
									// Remove all parents
									$objClass->objControlArray[$strControlId]->SetParentControl(null);
									$objClass->objControlArray[$strControlId]->SetForm(null);
									$objClass->objControlArray[$strControlId] = null;
									unset($objClass->objControlArray[$strControlId]);
								}
								break;
							default:
								if (array_key_exists($strControlId, $objClass->objControlArray))
									$objClass->objControlArray[$strControlId]->__set($strProperty, $strValue);
								break;
						}
					}
				}

				// Clear the RenderedCheckableControlArray
				if (!empty($_POST['Qform__FormCheckableControls'])) {
					$objClass->blnRenderedCheckableControlArray = array();
					$strCheckableControlList = trim($_POST['Qform__FormCheckableControls']);
					$strCheckableControlArray = explode(' ', $strCheckableControlList);
					foreach ($strCheckableControlArray as $strCheckableControl) {
						$objClass->blnRenderedCheckableControlArray[trim($strCheckableControl)] = true;
					}
				}

				// Iterate through all the controls

				// This is original code. In an effort to minimize changes, we aren't going to touch the server calls for now
				if ($objClass->strCallType != QCallType::Ajax) {
					foreach ($objClass->objControlArray as $objControl) {
						// If they were rendered last time and are visible (and if ServerAction, enabled), then Parse its post data
						if (($objControl->Visible) &&
							($objControl->Enabled) &&
							($objControl->RenderMethod)) {
							// Call each control's ParsePostData()
							$objControl->ParsePostData();
						}

						// Reset the modified/rendered flags and the validation
						// in ALL controls
						$objControl->ResetFlags();
					}
				}
				else {
					// Ajax post. Only send data to controls specified in the post to save time.
					foreach ($_POST as $key=>$val) {
						$strControlId = $key;
						if (($intOffset = strpos ($strControlId, '_')) !== false) {	// the first break is the control id
							$strControlId = substr ($strControlId, 0, $intOffset);
						}
						$previouslyFoundArray = array();
						if (($objControl = $objClass->GetControl($strControlId)) &&
								!isset($previouslyFoundArray[$strControlId])) {
							if (($objControl->Visible) &&
								($objControl->RenderMethod)) {
								// Call each control's ParsePostData()
								$objControl->ParsePostData();
								$objControl->ResetFlags();  // this should NOT be needed, but just in case
							}

							$previouslyFoundArray[$strControlId] = true;
						}
					}
				}

				// Only if our action is validating, we are going to reset the validation state of all the controls
				if (isset($_POST['Qform__FormControl']) && isset($objClass->objControlArray[$_POST['Qform__FormControl']])) {
					$objControl = $objClass->objControlArray[$_POST['Qform__FormControl']];
					if ($objControl->CausesValidation) {
						foreach ($objClass->objControlArray as $objControl) {
							$objControl->ValidationReset();
						}
					}
				}

				// Trigger Run Event (if applicable)
				$objClass->Form_Run();

				// Trigger Load Event (if applicable)
				$objClass->Form_Load();

				// Trigger a triggered control's Server- or Ajax- action (e.g. PHP method) here (if applicable)
				$objClass->TriggerActions();

				// Once all the controls have been set up, remember them.
				$objClass->SaveControlState();
			} else {
				// We have no form state -- Create Brand New One
				$objClass = self::CreateForm($strFormId);

				// Globalize
				global $_FORM;
				$_FORM = $objClass;

				// Setup HTML Include File Path, based on passed-in strAlternateHtmlFile (if any)
				try {
					$objClass->HtmlIncludeFilePath = $strAlternateHtmlFile;
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

				// By default, this form is being created NOT via a PostBack
				// So there is no CallType
				$objClass->strCallType = QCallType::None;

				$objClass->strFormId = $strFormId;
				$objClass->intFormStatus = QFormBase::FormStatusUnrendered;
				$objClass->objControlArray = array();
				$objClass->objGroupingArray = array();

				// Trigger Run Event (if applicable)
				$objClass->Form_Run();

				// Trigger Create Event (if applicable)
				$objClass->Form_Create();

				$objClass->Form_Initialize();

				if (defined ('__DESIGN_MODE__')) {
					$dlg = new QModelConnectorEditDlg ($objClass, 'qconnectoreditdlg');
					$objControls = $objClass->GetAllControls();
					foreach ($objControls as $objControl) {
						if ($objControl->LinkedNode) {
							$objControl->AddAction (new QContextMenuEvent(), new QAjaxAction ('ctlDesigner_Click'));
							$objControl->AddAction (new QContextMenuEvent(), new QStopPropagationAction());
							$objControl->AddAction (new QContextMenuEvent(), new QTerminateAction());
						}
					}
				}

			}

			// Trigger PreRender Event (if applicable)
			$objClass->Form_PreRender();

			// Render the Page
			switch ($objClass->strCallType) {
				case QCallType::Ajax:
					// Must use AJAX-based renderer
					$objClass->RenderAjax();
					break;

				case QCallType::Server:
				case QCallType::None:
				case '':
					// Server/Postback or New Page
					// Make sure all controls are marked as not being on the page yet
					foreach ($objClass->objControlArray as $objControl)
						$objControl->ResetOnPageStatus();

					// Use Standard Rendering
					$objClass->Render();
					break;

				default:
					throw new Exception('Unknown Form CallType: ' . $objClass->strCallType);
			}

			// Ensure that RenderEnd() was called during the Render process
			switch ($objClass->intFormStatus) {
				case QFormBase::FormStatusUnrendered:
					throw new QCallerException('$this->RenderBegin() is never called in the HTML Include file');
				case QFormBase::FormStatusRenderBegun:
					throw new QCallerException('$this->RenderEnd() is never called in the HTML Include file');
				case QFormBase::FormStatusRenderEnded:
					break;
				default:
					throw new QCallerException('FormStatus is in an unknown status');
			}

			// Tigger Exit Event (if applicable)
			$objClass->Form_Exit();
		}

		private function ctlDesigner_Click ($strFormId, $strControlId, $mixParam) {
			$objControl = $this->GetControl($strControlId);
			$dlg = $this->GetControl ('qconnectoreditdlg');
			$dlg->EditControl ($objControl);
		}

		/**
		 * An invalid form state was found. 
		 * We were handed a formstate, but the formstate could not be interpreted. This could be for
		 * a variety of reasons, and is dependent on the formstate handler. Most likely, the user hit
		 * the back button past the back button limit of what we remember, or the user lost the session.
		 * Or, you simply have not set up the form state handler correctly.
		 * In the past, we threw an exception, but that was not a very user friendly response. 
		 * The response below resubmits the url without a formstate so that a new one will be created. 
		 * Override if you want a different response.
		 */
		public static function InvalidFormState() {
			ob_clean();
			if (isset($_POST['Qform__FormCallType']) &&  $_POST['Qform__FormCallType'] == QCallType::Ajax) {
				// AJAX-based Response
				header('Content-Type: text/json'); // not application/json, as IE reportedly blows up on that, but jQuery knows what to do.

				$strJSON = JavaScriptHelper::toJsObject(['loc' => 'reload']);

				// Output it and update render state
				if (QApplication::$EncodingType && QApplication::$EncodingType != 'UTF-8') {
					$strJSON = mb_convert_encoding($strJSON, 'UTF-8', QApplication::$EncodingType); // json must be UTF-8 encoded
				}
				print ($strJSON);
			} else {
				header('Location: '. QApplication::$RequestUri);
			}

			// End the Response Script
			exit();	
		}

		/**
		 * Calls a data binder associated with the form. Does this so data binder can be protected. Mostly for legacy code.
		 * @param callable $callable
		 * @param  QControl $objPaginatedControl
		 * @throws QDataBindException
		 */
		public function CallDataBinder($callable, $objPaginatedControl) {
			try {
				call_user_func($callable, $objPaginatedControl);
			} catch (QCallerException $objExc) {
				throw new QDataBindException($objExc);
			}
		}

		/**
		 * Renders the AjaxHelper for the QForm
		 * @param QControlBase $objControl
		 *
		 * @return string The Ajax helper string (should be JS commands)
		 */
		protected function RenderAjaxHelper($objControl) {
			$controls = [];

			if ($objControl) {
				$controls = array_merge($controls, $objControl->RenderAjax());	// will return an array of controls to be merged with current controls
				foreach ($objControl->GetChildControls() as $objChildControl) {
					$controls = array_merge($controls, $this->RenderAjaxHelper($objChildControl));
				}
			}

			return $controls;
		}

		/**
		 * Renders the actual ajax return value as a json object. Since json must be UTF-8 encoded, will convert to
		 * UTF-8 if needed. Response is parsed in the "success" function in qcubed.js, and handled there.
		 */
		protected function RenderAjax() {
			$aResponse = array();

			if (QApplication::$JavascriptExclusiveCommand) {
				/**
				 * Processing of the actions has resulted in a very high priority exclusive response. This would typically
				 * happen when a javascript widget is requesting data from us. We want to respond as quickly as possible,
				 * and also prevent possibly redrawing the widget while its already in the middle of its own drawing.
				 * We short-circuit the drawing process here.
				 */

				$aResponse = QApplication::GetJavascriptCommandArray();
				$strFormState = QForm::Serialize($this);
				$aResponse[QAjaxResponse::Controls][] = [QAjaxResponse::Id=>"Qform__FormState", QAjaxResponse::Value=>$strFormState];	// bring it back next time
				ob_clean();
				QApplication::SendAjaxResponse($aResponse);
				exit();
			}

			// Update the Status
			$this->intFormStatus = QFormBase::FormStatusRenderBegun;

			// Broadcast the watcher change to other windows listening
			if (QWatcher::FormWatcherChanged($this->strWatcherTime)) {
				$aResponse[QAjaxResponse::Watcher] = true;
			}

			// Recursively render changed controls, starting with all top-level controls
			$controls = array();
			foreach ($this->GetAllControls() as $objControl) {
				if (!$objControl->ParentControl) {
					$controls = array_merge($controls, $this->RenderAjaxHelper($objControl));
				}
			}
			$aResponse[QAjaxResponse::Controls] = $controls;

			// Go through all controls and gather up any JS or CSS to run or Form Attributes to modify
			foreach ($this->GetAllControls() as $objControl) {
				// Include any javascript files that were added by the control
				// Note: current implementation does not handle removal of javascript files
				if ($strScriptArray = $this->ProcessJavaScriptList($objControl->JavaScripts)) {
					QApplication::AddJavaScriptFiles($strScriptArray);
				}

				// Include any new stylesheets
				if ($strScriptArray = $this->ProcessStyleSheetList($objControl->StyleSheets)) {
					QApplication::AddStyleSheets(array_keys($strScriptArray));
				}

				// Form Attributes?
				if ($objControl->FormAttributes) {
					QApplication::ExecuteControlCommand($this->strFormId, 'attr', $objControl->FormAttributes);
					foreach ($objControl->FormAttributes as $strKey=>$strValue) {
						if (!array_key_exists($strKey, $this->strFormAttributeArray)) {
							$this->strFormAttributeArray[$strKey] = $strValue;
						} else if ($this->strFormAttributeArray[$strKey] != $strValue) {
							$this->strFormAttributeArray[$strKey] = $strValue;
						}
					}
				}
			}

			$strControlIdToRegister = array();
			foreach ($this->GetAllControls() as $objControl) {
				$strScript = '';
				if ($objControl->Rendered) { // whole control was rendered during this event
					$strScript = trim ($objControl->GetEndScript());
					$strControlIdToRegister[] = $objControl->ControlId;
				} else {
                    $objControl->RenderAttributeScripts(); // render one-time attribute commands only
                }
				if ($strScript) {
					QApplication::ExecuteJavaScript($strScript, QJsPriority::High);	// put these last in the high priority queue, just before getting the commands below
				}
				$objControl->ResetFlags();
            }

			if ($strControlIdToRegister) {
				$aResponse[QAjaxResponse::RegC] = $strControlIdToRegister;
			}


			foreach ($this->objGroupingArray as $objGrouping) {
				$strRender = $objGrouping->Render();
				if (trim($strRender))
					QApplication::ExecuteJavaScript($strRender, QJsPriority::High);
			}


			$aResponse = array_merge($aResponse, QApplication::GetJavascriptCommandArray());

			// Add in the form state
			$strFormState = QForm::Serialize($this);
			$aResponse[QAjaxResponse::Controls][] = [QAjaxResponse::Id=>"Qform__FormState", QAjaxResponse::Value=>$strFormState];

			$strContents = trim(ob_get_contents());

			if (strtolower(substr($strContents, 0, 5)) == 'debug') {
				// TODO: Output debugging information.
			} else {
				ob_clean();

				QApplication::SendAjaxResponse($aResponse);
			}

			// Update Render State
			$this->intFormStatus = QFormBase::FormStatusRenderEnded;
			exit;
		}

		/**
		 * Saves the formstate using the 'Save' method of FormStateHandler set in configuration.inc.php
		 * @param QForm $objForm
		 *
		 * @return string the Serialized QForm
		 */
		public static function Serialize(QForm $objForm) {
			// Get and then Update PreviousRequestMode
			$strPreviousRequestMode = $objForm->strPreviousRequestMode;
			$objForm->strPreviousRequestMode = QApplication::$RequestMode;

			// Figure Out if we need to store state for back-button purposes
			$blnBackButtonFlag = true;
			if ($strPreviousRequestMode == QRequestMode::Ajax)
				$blnBackButtonFlag = false;

			// Create a Clone of the Form to Serialize
			$objForm = clone($objForm);

			// Cleanup internal links between controls and the form
			if ($objForm->objControlArray) foreach ($objForm->objControlArray as $objControl) {
				$objControl->Sleep();
			}

			// Use PHP "serialize" to serialize the form
			$strSerializedForm = serialize($objForm);

			// Setup and Call the FormStateHandler to retrieve the PostDataState to return
			$strFormStateHandler = QForm::$FormStateHandler;
			$strPostDataState = $strFormStateHandler::Save ($strSerializedForm, $blnBackButtonFlag);

			// Return the PostDataState
			return $strPostDataState;
		}

		/**
		 * Unserializes (extracts) the FormState using the 'Load' method of FormStateHandler set in configuration.inc.php
		 * @param string $strPostDataState The string identifying the FormState to the loaded for Unserialization
		 *
		 * @internal param string $strSerializedForm
		 * @return QForm the Form object
		 */
		public static function Unserialize($strPostDataState) {
			// Setup and Call the FormStateHandler to retrieve the Serialized Form
			$strFormStateHandler = QForm::$FormStateHandler;
			$strSerializedForm = $strFormStateHandler::Load ($strPostDataState);

			if ($strSerializedForm) {
				// Unserialize and Cast the Form
				// For the QSessionFormStateHandler the __PHP_Incomplete_Class occurs sometimes
				// for the result of the unserialize call.
				$objForm = unserialize($strSerializedForm);
				$objForm = QType::Cast($objForm, 'QForm');

				// Reset the links from Control->Form
				if ($objForm->objControlArray) foreach ($objForm->objControlArray as $objControl) {
					// If you are having trouble with a __PHP_Incomplete_Class here, it means you are not including the definitions
					// of your own controls in the form.
					$objControl->Wakeup($objForm);
				}

				// Return the Form
				return $objForm;
			} else
				return null;
		}

		/**
		 * Create a new form with the given type.
		 * @param string $strFormId  This is here mainly for backward compatibility, if subclasses use it.
		 * @return QForm
		 */
		private static function CreateForm ($strFormId) {
			$strClass = get_called_class();
			return new $strClass();
		}

		/**
		 * Add a QControl to the current QForm.
		 * @param QControl|QControlBase $objControl
		 *
		 * @throws QCallerException
		 */
		public function AddControl(QControl $objControl) {
			$strControlId = $objControl->ControlId;
			$objControl->MarkAsModified(); // make sure new controls get drawn
			if (array_key_exists($strControlId, $this->objControlArray))
				throw new QCallerException(sprintf('A control already exists in the form with the ID: %s', $strControlId));
			if (array_key_exists($strControlId, $this->objGroupingArray))
				throw new QCallerException(sprintf('A Grouping already exists in the form with the ID: %s', $strControlId));
			$this->objControlArray[$strControlId] = $objControl;
		}

		/**
		 * Returns a control from the current QForm
		 * @param string $strControlId The Control ID of the control which is needed to be fetched
		 *               from the current QForm (should be the child of the current QForm).
		 *
		 * @return null|QControl
		 */
		public function GetControl($strControlId) {
			if (isset($this->objControlArray[$strControlId])) {
				return $this->objControlArray[$strControlId];
			}
			else {
				return null;
			}
		}

		/**
		 * Removes a QControl (and its children) from the current QForm
		 * @param string $strControlId
		 */
		public function RemoveControl($strControlId) {
			if (array_key_exists($strControlId, $this->objControlArray)) {
				// Get the Control in Question
				$objControl = $this->objControlArray[$strControlId];

				// Remove all Child Controls as well
				$objControl->RemoveChildControls(true);

				// Remove this control from the parent
				if ($objControl->ParentControl)
					$objControl->ParentControl->RemoveChildControl($strControlId, false);

				// Remove this control
				unset($this->objControlArray[$strControlId]);

				// Remove this control from any groups
				foreach ($this->objGroupingArray as $strKey => $objGrouping)
					$this->objGroupingArray[$strKey]->RemoveControl($strControlId);
			}
		}

		/**
		 * Returns all controls belonging to the Form as an array.
		 * @return mixed|QControl[]
		 */
		public function GetAllControls() {
			return $this->objControlArray;
		}

		/**
		 * Tell all the controls to save their state.
		 */
		protected function SaveControlState() {
			// tell the controls to save their state
			$a = $this->GetAllControls();
			foreach ($a as $control) {
				$control->_WriteState();
			}
		}

		/**
		 * Tell all the controls to read their state.
		 */
		protected function RestoreControlState() {
			// tell the controls to restore their state
			$a = $this->GetAllControls();
			foreach ($a as $control) {
				$control->_ReadState();
			}
		}


		/**
		 * Custom Attributes are other html name-value pairs that can be rendered within the form using this method.
		 * For example, you can now render the autocomplete tag on the QForm
		 * additional javascript actions, etc.
		 * 		$this->SetCustomAttribute("autocomplete", "off");
		 * Will render:
		 *      	[form ...... autocomplete="off"] (replace sqare brackets with angle brackets)
		 * @param string $strName Name of the attribute
		 * @param string $strValue Value of the attribute
		 *
		 * @throws QCallerException
		 */
		public function SetCustomAttribute($strName, $strValue) {
			if ($strName == "method" || $strName == "action")
				throw new QCallerException(sprintf("Custom Attribute not supported through SetCustomAttribute(): %s", $strName));

			if (!is_null($strValue))
				$this->strCustomAttributeArray[$strName] = $strValue;
			else {
				$this->strCustomAttributeArray[$strName] = null;
				unset($this->strCustomAttributeArray[$strName]);
			}
		}

		/**
		 * Returns the requested custom attribute from the form.
		 * This attribute must have already been set.
		 * @param string $strName Name of the Custom Attribute
		 *
		 * @return mixed
		 * @throws QCallerException
		 */
		public function GetCustomAttribute($strName) {
			if ((is_array($this->strCustomAttributeArray)) && (array_key_exists($strName, $this->strCustomAttributeArray)))
				return $this->strCustomAttributeArray[$strName];
			else
				throw new QCallerException(sprintf("Custom Attribute does not exist in Form: %s", $strName));
		}

		public function RemoveCustomAttribute($strName) {
			if ((is_array($this->strCustomAttributeArray)) && (array_key_exists($strName, $this->strCustomAttributeArray))) {
				$this->strCustomAttributeArray[$strName] = null;
				unset($this->strCustomAttributeArray[$strName]);
			} else
				throw new QCallerException(sprintf("Custom Attribute does not exist in Form: %s", $strName));
		}


		public function AddGrouping(QControlGrouping $objGrouping) {
			$strGroupingId = $objGrouping->GroupingId;
			if (array_key_exists($strGroupingId, $this->objGroupingArray))
				throw new QCallerException(sprintf('A Grouping already exists in the form with the ID: %s', $strGroupingId));
			if (array_key_exists($strGroupingId, $this->objControlArray))
				throw new QCallerException(sprintf('A Control already exists in the form with the ID: %s', $strGroupingId));
			$this->objGroupingArray[$strGroupingId] = $objGrouping;
		}

		public function GetGrouping($strGroupingId) {
			if (array_key_exists($strGroupingId, $this->objGroupingArray))
				return $this->objGroupingArray[$strGroupingId];
			else
				return null;
		}

		public function RemoveGrouping($strGroupingId) {
			if (array_key_exists($strGroupingId, $this->objGroupingArray)) {
				// Remove this Grouping
				unset($this->objGroupingArray[$strGroupingId]);
			}
		}

		/**
		 * Retruns the Groupings
		 * @return mixed
		 */
		public function GetAllGroupings() {
			return $this->objGroupingArray;
		}

		/**
		 * Returns the child controls of the current QForm or a QControl object
		 *
		 * @param QForm|QControl|QFormBase $objParentObject The object whose child controls are to be searched for
		 *
		 * @throws QCallerException
		 * @return QControl[]
		 */
		public function GetChildControls($objParentObject) {
			$objControlArrayToReturn = array();

			if ($objParentObject instanceof QForm) {
				// They want all the ChildControls for this Form
				// Basically, return all objControlArray QControls where the Qcontrol's parent is NULL
				foreach ($this->objControlArray as $objChildControl) {
					if (!($objChildControl->ParentControl))
						array_push($objControlArrayToReturn, $objChildControl);
				}
				return $objControlArrayToReturn;

			} else if ($objParentObject instanceof QControl) {
				return $objParentObject->GetChildControls();
				// THey want all the ChildControls for a specific Control
				// Basically, return all objControlArray QControls where the Qcontrol's parent is the passed in parentobject
				/*				$strControlId = $objParentObject->ControlId;
								foreach ($this->objControlArray as $objChildControl) {
									$objParentControl = $objChildControl->ParentControl;
									if (($objParentControl) && ($objParentControl->ControlId == $strControlId)) {
										array_push($objControlArrayToReturn, $objChildControl);
									}
								}*/

			} else
				throw new QCallerException('ParentObject must be either a QForm or QControl object');
		}

		/**
		 * This function evaluates the QForm Template.
		 * It will try to open the Template file specified in the call to 'Run' method for the QForm
		 * and then execute it.
		 * @param string $strTemplate Path to the HTML template file
		 *
		 * @return string The evaluated HTML string
		 */
		public function EvaluateTemplate($strTemplate) {
			global $_ITEM;
			global $_CONTROL;
			global $_FORM;

			if ($strTemplate) {
				QApplication::$ProcessOutput = false;
				// Store the Output Buffer locally
				$strAlreadyRendered = ob_get_contents();
                if ($strAlreadyRendered) {
                    ob_clean();
                }

				// Evaluate the new template
				ob_start('__QForm_EvaluateTemplate_ObHandler');
				require($strTemplate);
				$strTemplateEvaluated = ob_get_contents();
				ob_end_clean();

				// Restore the output buffer and return evaluated template
                if ($strAlreadyRendered) {
                    print($strAlreadyRendered);
                }
				QApplication::$ProcessOutput = true;

				return $strTemplateEvaluated;
			} else
				return null;
		}

		/**
		 * Triggers an event handler method for a given control ID
		 * NOTE: Parameters must be already validated.
		 *
		 * @param string $strControlId  Control ID for which the method has to be triggered
		 * @param string $strMethodName Method name which has to be fired
		 */
		protected function TriggerMethod($strControlId, $strMethodName) {
			$strParameter = $_POST['Qform__FormParameter'];

			$intPosition = strpos($strMethodName, ':');
			if ($intPosition !== false) {
				$strControlName = substr($strMethodName, 0, $intPosition);
				$strMethodName = substr($strMethodName, $intPosition + 1);

				$objControl = $this->objControlArray[$strControlName];
				QControl::CallActionMethod ($objControl, $strMethodName, $this->strFormId, $strControlId, $strParameter);
			} else
				$this->$strMethodName($this->strFormId, $strControlId, $strParameter);
		}

		/**
		 * Calles 'Validate' method on a QControl recursively
		 * @param QControl $objControl
		 *
		 * @return bool
		 */
		protected function ValidateControlAndChildren(QControl $objControl) {
			return $objControl->ValidateControlAndChildren();
		}

		/**
		 * Runs/Triggers any and all event handling functions for the control on which an event took place
		 * Depending on the control's CausesValidation value, it also calls for validation of the control or
		 * control and children or entire QForm.
		 *
		 * @param null|string $strControlIdOverride If supplied, the control with the supplied ID is selected
		 *
		 * @throws Exception|QCallerException
		 */
		protected function TriggerActions($strControlIdOverride = null) {
			if (array_key_exists('Qform__FormControl', $_POST)) {
				if ($strControlIdOverride) {
					$strId = $strControlIdOverride;
				} else {
					$strId = $_POST['Qform__FormControl'];
				}

				// Control ID determined
				if ($strId != '') {
					$strEvent = $_POST['Qform__FormEvent'];
					$strAjaxActionId = NULL;

					// Does this Control which performed the action exist?
					if (array_key_exists($strId, $this->objControlArray)) {
						// Get the ActionControl as well as the Actions to Perform
						$objActionControl = $this->objControlArray[$strId];

						switch ($this->strCallType) {
							case QCallType::Ajax:
								// split up event class name and ajax action id: i.e.: QClickEvent#a3 => [QClickEvent, a3]
								$arrTemp = explode('#',$strEvent);
								$strEvent = $arrTemp[0];
								if(count($arrTemp) == 2)
									$strAjaxActionId = $arrTemp[1];
								$objActions = $objActionControl->GetAllActions($strEvent, 'QAjaxAction');
								break;
							case QCallType::Server:
								$objActions = $objActionControl->GetAllActions($strEvent, 'QServerAction');
								break;
							default:
								throw new Exception('Unknown Form CallType: ' . $this->strCallType);
						}

						// Validation Check
						$blnValid = true;
						$mixCausesValidation = null;

						// Figure out what the CausesValidation directive is
						// Set $mixCausesValidation to the default one (e.g. the one defined on the control)
						$mixCausesValidation = $objActionControl->CausesValidation;

						// Next, go through the linked ajax/server actions to see if a causesvalidation override is set on any of them
						if ($objActions) foreach ($objActions as $objAction) {
							if (!is_null($objAction->CausesValidationOverride)) {
								$mixCausesValidation = $objAction->CausesValidationOverride;
							}
						}

						// Now, Do Something with mixCauseValidation...

						// Starting Point is a QControl
						if ($mixCausesValidation instanceof QControl) {
							if (!$this->ValidateControlAndChildren($mixCausesValidation)) {
								$blnValid = false;
							}

							// Starting Point is an Array of QControls
						} else if (is_array($mixCausesValidation)) {
							foreach (((array) $mixCausesValidation) as $objControlToValidate) {
								if (!$this->ValidateControlAndChildren($objControlToValidate)) {
									$blnValid = false;
								}
							}

							// Validate All the Controls on the Form
						} else if ($mixCausesValidation === QCausesValidation::AllControls) {
							foreach ($this->GetChildControls($this) as $objControl) {
								// Only Enabled and Visible and Rendered controls that are children of this form should be validated
								if (($objControl->Visible) && ($objControl->Enabled) && ($objControl->RenderMethod) && ($objControl->OnPage)) {
									if (!$this->ValidateControlAndChildren($objControl)) {
										$blnValid = false;
									}
								}
							}

							// CausesValidation specifed by QCausesValidation directive
						} else if ($mixCausesValidation == QCausesValidation::SiblingsAndChildren) {
							// Get only the Siblings of the ActionControl's ParentControl
							// If not ParentControl, then the parent is the form itself
							if (!($objParentObject = $objActionControl->ParentControl)) {
								$objParentObject = $this;
							}

							// Get all the children of ParentObject
							foreach ($this->GetChildControls($objParentObject) as $objControl) {
								// Only Enabled and Visible and Rendered controls that are children of ParentObject should be validated
								if (($objControl->Visible) && ($objControl->Enabled) && ($objControl->RenderMethod) && ($objControl->OnPage)) {
									if (!$this->ValidateControlAndChildren($objControl)) {
										$blnValid = false;
									}
								}
							}

							// CausesValidation specifed by QCausesValidation directive
						} else if ($mixCausesValidation == QCausesValidation::SiblingsOnly) {
							// Get only the Siblings of the ActionControl's ParentControl
							// If not ParentControl, tyhen the parent is the form itself
							if (!($objParentObject = $objActionControl->ParentControl)) {
								$objParentObject = $this;
							}

							// Get all the children of ParentObject
							foreach ($this->GetChildControls($objParentObject) as $objControl)
								// Only Enabled and Visible and Rendered controls that are children of ParentObject should be validated
								if (($objControl->Visible) && ($objControl->Enabled) && ($objControl->RenderMethod) && ($objControl->OnPage)) {
									if (!$objControl->Validate()) {
										$objControl->MarkAsModified();
										$blnValid = false;
									}
								}

							// No Validation Requested
						} else {}


						// Run Form-Specific Validation (if any)
						if ($mixCausesValidation && !($mixCausesValidation instanceof QDialog)) {
							if (!$this->Form_Validate()) {
								$blnValid = false;
							}
						}


						// Go ahead and run the ServerActions or AjaxActions if Validation Passed and if there are Server/Ajax-Actions defined
						if ($blnValid) {
							if ($objActions) foreach ($objActions as $objAction) {
								if ($strMethodName = $objAction->MethodName) {
									if (($strAjaxActionId == NULL) 			//if this call was not an ajax call
										|| ($objAction->Id == NULL) 		// or the QAjaxAction derived action has no id set
										//(a possible way to add a callback that gets executed on every ajax call for this control)
										|| ($strAjaxActionId == $objAction->Id)) //or the ajax action id passed from client side equals the id of the current ajax action
										$this->TriggerMethod($strId, $strMethodName);
								}
							}
						}
						else {
							$this->Form_Invalid();	// notify form that something went wrong
						}
					} else {
						// Nope -- Throw an exception
						throw new Exception(sprintf('Control passed by Qform__FormControl does not exist: %s', $strId));
					}
				}
				/* else {
					// TODO: Code to automatically execute any PrimaryButton's onclick action, if applicable
					// Difficult b/c of all the QCubed hidden parameters that need to be set to get the action to work properly
					// Javascript interaction of PrimaryButton works fine in Firefox... currently doens't work in IE 6.
				}*/
			}
		}

		/**
		 * Begins rendering the page
		 */
		protected function Render() {
			if (QWatcher::FormWatcherChanged($this->strWatcherTime)) {
				QApplication::ExecuteJsFunction('qc.broadcastChange');
			}

			require($this->HtmlIncludeFilePath);
		}

		/**
		 * Render the children of this QForm
		 * @param bool $blnDisplayOutput
		 *
		 * @return null|string Null when blnDisplayOutput is true
		 */
		protected function RenderChildren($blnDisplayOutput = true) {
			$strToReturn = "";

			foreach ($this->GetChildControls($this) as $objControl)
				if (!$objControl->Rendered)
					$strToReturn .= $objControl->Render($blnDisplayOutput);

			if ($blnDisplayOutput) {
				print($strToReturn);
				return null;
			} else
				return $strToReturn;
		}

		/**
		 * This exists to prevent inadverant "New"
		 */
		protected function __construct() {}

		/**
		 * Renders the tags to include the css style sheets. Call this in your head tag if you want to
		 * put these there. Otherwise, the styles will automatically be included just after the form.
		 *
		 * @param bool $blnDisplayOutput
		 * @return null|string
		 */
		public function RenderStyles($blnDisplayOutput = true, $blnInHead = true) {
			$strToReturn = '';
			$this->strIncludedStyleSheetFileArray = array();

			// Figure out initial list of StyleSheet includes
			$strStyleSheetArray = array();

			foreach ($this->GetAllControls() as $objControl) {
				// Include any StyleSheets?  The control would have a
				// comma-delimited list of stylesheet files to include (if applicable)
				if ($strScriptArray = $this->ProcessStyleSheetList($objControl->StyleSheets)) {
					$strStyleSheetArray = array_merge($strStyleSheetArray, $strScriptArray);
				}
			}

			// In order to make ui-themes workable, move the jquery.css to the end of list.
			// It should override any rules that it can override.
			foreach ($strStyleSheetArray as $strScript) {
				if (__JQUERY_CSS__ == $strScript) {
					unset($strStyleSheetArray[$strScript]);
					$strStyleSheetArray[$strScript] = $strScript;
					break;
				}
			}

			// Include styles that need to be included
			foreach ($strStyleSheetArray as $strScript) {
				if ($blnInHead) {
					$strToReturn  .= '<link href="' . $this->GetCssFileUri($strScript) . '" rel="stylesheet" />';
				} else {
					$strToReturn  .= '<style type="text/css" media="all">@import "' . $this->GetCssFileUri($strScript) . '"</style>';
				}
				$strToReturn .= "\r\n";
			}

			self::$blnStylesRendered = true;

			// Return or Display
			if ($blnDisplayOutput) {
				if(!QApplication::$CliMode) {
					print($strToReturn);
				}
				return null;
			} else {
				if(!QApplication::$CliMode) {
					return $strToReturn;
				} else {
					return '';
				}
			}
		}

		/**
		 * Initializes the QForm rendering process
		 * @param bool $blnDisplayOutput Whether the output is to be printed (true) or simply returned (false)
		 *
		 * @return null|string
		 * @throws QCallerException
		 */
		public function RenderBegin($blnDisplayOutput = true) {
			// Ensure that RenderBegin() has not yet been called
			switch ($this->intFormStatus) {
				case QFormBase::FormStatusUnrendered:
					break;
				case QFormBase::FormStatusRenderBegun:
				case QFormBase::FormStatusRenderEnded:
					throw new QCallerException('$this->RenderBegin() has already been called');
					break;
				default:
					throw new QCallerException('FormStatus is in an unknown status');
			}

			// Update FormStatus and Clear Included JS/CSS list
			$this->intFormStatus = QFormBase::FormStatusRenderBegun;

			// Prepare for rendering

			QApplicationBase::$ProcessOutput = false;
			$strOutputtedText = trim(ob_get_contents());
			if (strpos(strtolower($strOutputtedText), '<body') === false) {
				$strToReturn = '<body>';
				$this->blnRenderedBodyTag = true;
			} else
				$strToReturn = '';
			QApplicationBase::$ProcessOutput = true;


			// Iterate through the form's ControlArray to Define FormAttributes and additional JavaScriptIncludes
			$this->strFormAttributeArray = array();
			foreach ($this->GetAllControls() as $objControl) {
				// Form Attributes?
				if ($objControl->FormAttributes) {
					$this->strFormAttributeArray = array_merge($this->strFormAttributeArray, $objControl->FormAttributes);
				}
			}

			if (is_array($this->strCustomAttributeArray))
				$this->strFormAttributeArray = array_merge($this->strFormAttributeArray, $this->strCustomAttributeArray);

			// Create $strFormAttributes
			$strFormAttributes = '';
			foreach ($this->strFormAttributeArray as $strKey=>$strValue) {
				$strFormAttributes .= sprintf(' %s="%s"', $strKey, $strValue);
			}

			if ($this->strCssClass)
				$strFormAttributes .= ' class="' . $this->strCssClass . '"';

			// Setup Rendered HTML
			$strToReturn .= sprintf('<form method="post" id="%s" action="%s"%s>', $this->strFormId, htmlentities(QApplication::$RequestUri), $strFormAttributes);
			$strToReturn .= "\r\n";
			
			if (!self::$blnStylesRendered) {
				$strToReturn .= $this->RenderStyles(false, false);
			}

			// Perhaps a strFormModifiers as an array to
			// allow controls to update other parts of the form, like enctype, onsubmit, etc.

			// Return or Display
			if ($blnDisplayOutput) {
				if(!QApplication::$CliMode) {
					print($strToReturn);
				}
				return null;
			} else {
				if(!QApplication::$CliMode) {
					return $strToReturn;
				} else {
					return '';
				}
			}
		}

		/**
		 * Internal helper function used by RenderBegin and by RenderAjax
		 * Given a comma-delimited list of javascript files, this will return an array of files that NEED to still
		 * be included because (1) it hasn't yet been included and (2) it hasn't been specified to be "ignored".
		 *
		 * This WILL update the internal $strIncludedJavaScriptFileArray array.
		 *
		 * @param string | array $strJavaScriptFileList
		 * @return string[] array of script files to include or NULL if none
		 */
		protected function ProcessJavaScriptList($strJavaScriptFileList)  {

			if (empty($strJavaScriptFileList)) return null;

			$strArrayToReturn = array();

			if (!is_array($strJavaScriptFileList)) {
				$strJavaScriptFileList = explode(',', $strJavaScriptFileList);
			}

			// Iterate through the list of JavaScriptFiles to Include...
			foreach ($strJavaScriptFileList as $strScript) {
				if ($strScript = trim($strScript)) {

					// Include it if we're NOT ignoring it and it has NOT already been included
					if ((array_search($strScript, $this->strIgnoreJavaScriptFileArray) === false) &&
						!array_key_exists($strScript, $this->strIncludedJavaScriptFileArray)) {
						$strArrayToReturn[$strScript] = $strScript;
						$this->strIncludedJavaScriptFileArray[$strScript] = true;
					}
				}
			}

			if (count($strArrayToReturn))
				return $strArrayToReturn;

			return null;
		}

		/**
		 * Primarily used by RenderBegin and by RenderAjax
		 * Given a comma-delimited list of stylesheet files, this will return an array of file that NEED to still
		 * be included because (1) it hasn't yet been included and (2) it hasn't been specified to be "ignored".
		 *
		 * This WILL update the internal $strIncludedStyleSheetFileArray array.
		 *
		 * @param string $strStyleSheetFileList
		 * @return string[] array of stylesheet files to include or NULL if none
		 */
		protected function ProcessStyleSheetList($strStyleSheetFileList) {
			$strArrayToReturn = array();

			// Is there a comma-delimited list of StyleSheet files to include?
			if ($strStyleSheetFileList = trim($strStyleSheetFileList)) {
				$strScriptArray = explode(',', $strStyleSheetFileList);

				// Iterate through the list of StyleSheetFiles to Include...
				foreach ($strScriptArray as $strScript)
					if ($strScript = trim($strScript))

						// Include it if we're NOT ignoring it and it has NOT already been included
						if ((array_search($strScript, $this->strIgnoreStyleSheetFileArray) === false) &&
							!array_key_exists($strScript, $this->strIncludedStyleSheetFileArray)) {
							$strArrayToReturn[$strScript] = $strScript;
							$this->strIncludedStyleSheetFileArray[$strScript] = true;
						}
			}

			if (count($strArrayToReturn))
				return $strArrayToReturn;

			return null;
		}

		/**
		 * Returns whether or not this Form is being run due to a PostBack event (e.g. a ServerAction or AjaxAction)
		 * @return bool
		 */
		public function IsPostBack() {
			return ($this->strCallType != QCallType::None);
		}

		/**
		 * Will return an array of Strings which will show all the error and warning messages
		 * in all the controls in the form.
		 *
		 * @param bool $blnErrorsOnly Show only the errors (otherwise, show both warnings and errors)
		 * @return string[] an array of strings representing the (multiple) errors and warnings
		 */
		public function GetErrorMessages($blnErrorsOnly = false) {
			$strToReturn = array();
			foreach ($this->GetAllControls() as $objControl) {
				if ($objControl->ValidationError)
					array_push($strToReturn, $objControl->ValidationError);
				if (!$blnErrorsOnly)
					if ($objControl->Warning)
						array_push($strToReturn, $objControl->Warning);
			}

			return $strToReturn;
		}

		/**
		 * Will return an array of QControls from the form which have either an error or warning message.
		 *
		 * @param bool $blnErrorsOnly Return controls that have just errors (otherwise, show both warnings and errors)
		 * @return QControl[] an array of controls representing the (multiple) errors and warnings
		 */
		public function GetErrorControls($blnErrorsOnly = false) {
			$objToReturn = array();
			foreach ($this->GetAllControls() as $objControl) {
				if ($objControl->ValidationError)
					array_push($objToReturn, $objControl);
				else if (!$blnErrorsOnly)
					if ($objControl->Warning)
						array_push($objToReturn, $objControl);
			}

			return $objToReturn;
		}

		/**
		 * Gets the JS file URI, given a string input
		 * @param string $strFile File name to be tested
		 *
		 * @return string the final JS file URI
		 */
		public function GetJsFileUri($strFile) {
			return QApplication::GetJsFileUri($strFile);
		}

		/**
		 * Gets the CSS file URI, given a string input
		 * @param string $strFile File name to be tested
		 *
		 * @return string the final CSS URI
		 */
		public function GetCssFileUri($strFile) {
			return QApplication::GetCssFileUri($strFile);
		}

		/**
		 * Get high level form javascript files to be included. Default here includes all
		 * javascripts needed to run qcubed.
		 * Override and add to this list and include
		 * javascript and jQuery files and libraries needed for your application.
		 * Javascript files included before __QCUBED_JS_CORE__ can refer to jQuery as $.
		 * After qcubed.js, $ becomes $j, so add other libraries that need
		 * $ in a different context after qcubed.js, and insert jQuery libraries and  plugins that
		 * refer to $ before qcubed.js file.
		 *
		 * @return array
		 */
		protected function GetFormJavaScripts() {
			return array (__JQUERY_BASE__,
				__JQUERY_EFFECTS__,
				'jquery/jquery.ajaxq-0.0.1.js',
				__QCUBED_JS_CORE__);
		}

		/**
		 * Renders the end of the form, including the closing form and body tags.
		 * Renders the html for hidden controls.
		 * @param bool $blnDisplayOutput should the output be returned or directly printed to screen.
		 *
		 * @return null|string
		 * @throws QCallerException
		 */
		public function RenderEnd($blnDisplayOutput = true) {
			// Ensure that RenderEnd() has not yet been called
			switch ($this->intFormStatus) {
				case QFormBase::FormStatusUnrendered:
					throw new QCallerException('$this->RenderBegin() was never called');
				case QFormBase::FormStatusRenderBegun:
					break;
				case QFormBase::FormStatusRenderEnded:
					throw new QCallerException('$this->RenderEnd() has already been called');
					break;
				default:
					throw new QCallerException('FormStatus is in an unknown status');
			}

			$strHtml = '';	// This will be the final output

			/**** Render any controls that get automatically rendered ****/
			foreach ($this->GetAllControls() as $objControl) {
				if ($objControl instanceof QDialog &&
					!$objControl->Rendered) {
					$strRenderMethod = $objControl->PreferredRenderMethod;
					$strHtml .= $objControl->$strRenderMethod(false) . _nl();
				}
			}

			/**** Prepare Javascripts ****/

			// Clear included javascript array since we are completely redrawing the page
			$this->strIncludedJavaScriptFileArray = array();
			$strControlIdToRegister = array();
			$strEventScripts = '';

			// Add form level javascripts and libraries
			$strJavaScriptArray = $this->ProcessJavaScriptList($this->GetFormJavaScripts());
			QApplication::AddJavaScriptFiles($strJavaScriptArray);
			$strFormJsFiles = QApplication::RenderFiles();	// Render the form-level javascript files separately

			// Go through all controls and gather up any JS or CSS to run or Form Attributes to modify
			foreach ($this->GetAllControls() as $objControl) {
				if ($objControl->Rendered || $objControl->ScriptsOnly) {
					$strControlIdToRegister[] = $objControl->ControlId;

					/* Note: GetEndScript may cause the control to register additional commands, or even add javascripts, so those should be handled after this. */
					if ($strControlScript = $objControl->GetEndScript()) {
						$strControlScript = JavaScriptHelper::TerminateScript($strControlScript);

						// Add comments for developer version of output
						if (!QApplication::$Minimize) {
							// Render a comment
							$strControlScript = _nl() .  _nl() .
								sprintf ('/*** EndScript -- Control Type: %s, Control Name: %s, Control Id: %s  ***/',
									get_class($objControl), $objControl->Name, $objControl->ControlId) .
								_nl() .
								_indent($strControlScript);
						}
						$strEventScripts .= $strControlScript;
					}
				}

				// Include the javascripts specified by each control.
				if ($strScriptArray = $this->ProcessJavaScriptList($objControl->JavaScripts)) {
					QApplication::AddJavaScriptFiles($strScriptArray);
				}

				// Include any StyleSheets?  The control would have a
				// comma-delimited list of stylesheet files to include (if applicable)
				if ($strScriptArray = $this->ProcessStyleSheetList($objControl->StyleSheets)) {
					QApplication::AddStyleSheets(array_keys($strScriptArray));
				}

				// Form Attributes?
				if ($objControl->FormAttributes) {
					QApplication::ExecuteControlCommand($this->strFormId, 'attr', $objControl->FormAttributes);
					foreach ($objControl->FormAttributes as $strKey=>$strValue) {
						if (!array_key_exists($strKey, $this->strFormAttributeArray)) {
							$this->strFormAttributeArray[$strKey] = $strValue;
						} else if ($this->strFormAttributeArray[$strKey] != $strValue) {
							$this->strFormAttributeArray[$strKey] = $strValue;
						}
					}
				}
			}

			// Add grouping commands to events (Used for deprecated drag and drop, but not removed yet)
			foreach ($this->objGroupingArray as $objGrouping) {
				$strGroupingScript = $objGrouping->Render();
				if (strlen($strGroupingScript) > 0) {
					$strGroupingScript = JavaScriptHelper::TerminateScript($strGroupingScript);
					$strEventScripts .= $strGroupingScript;
				}
			}

			/*** Build the javascript block ****/

			// Start with variable settings and initForm
			$strEndScript = sprintf('qc.initForm("%s"); ', $this->strFormId);

			// Register controls
			if ($strControlIdToRegister) {
				$strEndScript .= sprintf('qc.regCA(%s); ', JavaScriptHelper::toJsObject($strControlIdToRegister));
			}

			// Add any application level js commands.
			// This will include high and medimum level commands
			$strEndScript .= QApplication::RenderJavascript(true);

			// Add the javascript coming from controls and events just after the medium level commands
			$strEndScript .=  ';'  . $strEventScripts;

			// Add low level commands and other things that need to execute at the end
			$strEndScript .= ';' . QApplication::RenderJavascript(false);




			// Create Final EndScript Script
			$strEndScript = sprintf('<script type="text/javascript">$j(document).ready(function() { %s; });</script>', $strEndScript);


			/**** Render the HTML itself, appending the javascript we generated above ****/

			foreach ($this->GetAllControls() as $objControl) {
				if ($objControl->Rendered) {
					$strHtml .= $objControl->GetEndHtml();
				}
				$objControl->ResetFlags(); // Make sure controls are serialized in a reset state
			}

			$strHtml .= $strFormJsFiles . _nl();	// Add form level javascript files

			// put javascript environment defines up early for use by other js files.
			$strHtml .= '<script type="text/javascript">' .
				sprintf('qc.baseDir = "%s"; ', __VIRTUAL_DIRECTORY__ . __SUBDIRECTORY__) .
				sprintf('qc.jsAssets = "%s"; ', __VIRTUAL_DIRECTORY__ . __JS_ASSETS__) .
				sprintf('qc.phpAssets = "%s"; ', __VIRTUAL_DIRECTORY__ . __PHP_ASSETS__) .
				sprintf('qc.cssAssets = "%s"; ', __VIRTUAL_DIRECTORY__ . __CSS_ASSETS__) .
				sprintf('qc.imageAssets = "%s"; ', __VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__) .
				'</script>' .
				_nl();

			$strHtml .= QApplication::RenderFiles() . _nl();	// add plugin and control js files

			// Render hidden controls related to the form
			$strHtml .= sprintf('<input type="hidden" name="Qform__FormId" id="Qform__FormId" value="%s" />', $this->strFormId) . _nl();
			$strHtml .= sprintf('<input type="hidden" name="Qform__FormControl" id="Qform__FormControl" value="" />') . _nl();
			$strHtml .= sprintf('<input type="hidden" name="Qform__FormEvent" id="Qform__FormEvent" value="" />') . _nl();
			$strHtml .= sprintf('<input type="hidden" name="Qform__FormParameter" id="Qform__FormParameter" value="" />') . _nl();
			$strHtml .= sprintf('<input type="hidden" name="Qform__FormCallType" id="Qform__FormCallType" value="" />') . _nl();
			$strHtml .= sprintf('<input type="hidden" name="Qform__FormUpdates" id="Qform__FormUpdates" value="" />') . _nl();
			$strHtml .= sprintf('<input type="hidden" name="Qform__FormCheckableControls" id="Qform__FormCheckableControls" value="" />') . _nl();

			// Serialize and write out the formstate
			$strHtml .= sprintf('<input type="hidden" name="Qform__FormState" id="Qform__FormState" value="%s" />', QForm::Serialize(clone($this))) . _nl();

			// close the form tag
			$strHtml .= "</form>";

			// Add the JavaScripts rendered above
			$strHtml .= $strEndScript;

			// close the body tag
			if ($this->blnRenderedBodyTag) {
				$strHtml .= '</body>';
			}

			/**** Cleanup ****/

			// Update Form Status
			$this->intFormStatus = QFormBase::FormStatusRenderEnded;

			// Display or Return
			if ($blnDisplayOutput) {
				if(!QApplication::$CliMode) {
					print($strHtml);
				}
				return null;
			} else {
				if(!QApplication::$CliMode) {
					return $strHtml;
				} else {
					return '';
				}
			}
		}
	}

	function __QForm_EvaluateTemplate_ObHandler($strBuffer) {
		return $strBuffer;
	}
