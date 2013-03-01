<?php
	// Load QCubed
	require('../qcubed.inc.php');

	/**
	 * The Page Builder is used to create QForms from draggable/droppable conrols.
	 */
	class PageBuilder extends QForm {
		/** @var QDialog The floating toolbox for the controls. */
		protected $dlgToolbox;
		/** @var QPanel The space where controls can be dropped. */
		protected $pnlApp;
		/** @var QDialog The modal used for editing a control. */
		protected $dlgEdit;
		/** @var array Controls not wanted in the toolbox. */
		protected $arrDisallowedControls = array('QForm', 'QControl', 'QSampleControl');
		/** @var array Properties not wanted in the edit panel. */
		protected $arrDisallowedProps = array('FontStrikeout','HtmlBefore', 'HtmlAfter', 'Form', 'Rendered', 'Rendering','OnPage','Modified','WrapperModified','IsBlockElement', 'BackColor','ForeColor','FontSize','FontUnderline','FontItalic','FontBold', 'HtmlEntities','ActionsMustTerminate','BorderColor','BorderStyle','BorderWidth','Display','DisplayStyle','FontNames','FontOverline', 'Opacity', 'Visible', 'Overflow', 'Position', 'Draggable', 'Resizable', 'Droppable','ParentControl', 'ChildControlArray', 'Top', 'Left', 'Width','Height', 'Cursor', 'Disabled', 'Enabled', 'RenderMethod', 'JavaScripts','StyleSheets','FormAttributes', 'UseWrapper');
		/** @var QPanel The scrollable list of controls. */
		protected $pnlTools;

		protected function Form_Create() {
			$this->pnlApp = new QPanel($this, "Application");
			$this->pnlApp->Droppable = true;
			$this->pnlApp->AutoRenderChildren = true;
			$this->pnlApp->AddAction(new QDroppable_DropEvent(), new QAjaxAction("pnlApp_Drop"));
			// A hint that we will remove when a control is dropped.
			$this->pnlApp->Text = "<h2 class='drop-hint'>Drop Controls Here</h2>";

			$this->dlgToolbox = new QDialog($this, "Toolbox");
			$this->dlgToolbox->AutoRenderChildren = true;
			$this->dlgToolbox->Title = "Controls";
			$this->dlgToolbox->Width = "auto";
			$this->dlgToolbox->Height = 500;
			$this->dlgToolbox->HasCloseButton = false;
			$this->dlgToolbox->CloseOnEscape = false;
			$this->dlgToolbox->Resizable = false;

			$this->pnlTools = new QPanel($this->dlgToolbox, "ToolboxTools");
			$this->pnlTools->AutoRenderChildren = true;
			/* This routine grabs all the controls out of the default directory,
			 * it should probably have a directory of its own to pull from.
			 */
			$filepath = __DOCROOT__ . __SUBDIRECTORY__."/includes/qcubed/controls";
			$objDirectory = opendir($filepath);
			$strClassNameArray = array();
			while ($strFile = readdir($objDirectory)) {
				$intPosition = strpos($strFile, '.class.php');
				if ($intPosition) {
					$strClassName = substr($strFile, 0, $intPosition);
					if (!in_array($strClassName, $this->arrDisallowedControls)){
						$strClassNameArray[$strClassName] = $strClassName;
					}
				}
			}
			// It's easier to manage when sorted alphabetically
			asort($strClassNameArray);

			foreach($strClassNameArray as $strClassName){
				$toolTextbox = new QPanel($this->pnlTools);
				$toolTextbox->AddCssClass('control-tool');
				$toolTextbox->Text = $strClassName;
				$toolTextbox->Moveable = true;
				$toolTextbox->DragObj->Revert = QDraggable::RevertOn;
				$toolTextbox->ToolTip = "Drag/Drop me to create a new ". $strClassName;
			}

			$this->dlgEdit = new QDialog($this);
			$this->dlgEdit->AutoRenderChildren = true;
			$this->dlgEdit->AutoOpen = false;
			$this->dlgEdit->Width = "auto";
			$this->dlgEdit->Modal = true;
		}

		/**
		 * This is the event for when a component gets dropped.
		 */
		public function pnlApp_Drop($strFormId, $strControlId, $strActionParameter){
			$objDropped = $this->GetControl($this->pnlApp->DropObj->DroppedId);
			$strControl = $objDropped->Text;
			$objNew = new $strControl($this->pnlApp);
			$objNew->Name = $objDropped->Text;
			$objNew->PreferedRenderMethod = "RenderWithName";
			$objNew->AddAction(new QClickEvent(), new QAjaxAction('edit_Click'));
			$this->populateEdit($objNew);
			// Remove the hint
			$this->pnlApp->Text = '';
		}

		/**
		 * This is the event for when a component gets saved.
		 */
		public function btnSaveControl_Click($strFormId, $strControlId, $strActionParameter){
			$objToModify = $this->GetControl($strActionParameter);

			foreach($this->dlgEdit->GetChildControls() as $control){
				if (is_a($control, 'QTextBox') && strlen(trim($control->Text)) > 0){
					$property = $control->Name;
					$objToModify->$property = $control->Text;
				}
				if (is_a($control, 'QCheckBox')){
					$property = substr($control->Name, 0, strlen($control->Name)-1);
					$objToModify->$property = $control->Checked;
				}
			}

			$this->pnlApp->Refresh();
			$this->dlgEdit->Close();
		}

		/**
		 * This is the event for when a component gets clicked to edit.
		 */
		public function edit_Click($strFormId, $strControlId, $strActionParameter){
			$objToModify = $this->GetControl($strControlId);
			$this->populateEdit($objToModify);
		}

		/**
		 * This takes a parameter of the control to modify.
		 * Populates the edit dialog.
		 */
		protected function populateEdit($objToModify){
			$this->dlgEdit->RemoveChildControls(true);
			$this->dlgEdit->Title = "Editing ". get_class($objToModify);

			$myDump = new ReflectionClass($objToModify);
			foreach($myDump->getProperties() as $dump){
				$friendlyName = substr($dump->name, 3);
				if (!in_array($friendlyName, $this->arrDisallowedProps)){
					$type = substr($dump->name, 0,3);
					switch ($type){
						case "str":
							$control = new QTextBox($this->dlgEdit);
							$control->Name = $friendlyName;
							break;
						case "bln":
							$control = new QCheckBox($this->dlgEdit);
							$control->Name = $friendlyName.'?';
							$control->Checked = $objToModify->$friendlyName;
							$control->HtmlEntities = false;
							break;
						default:
							break;
					}

					if (isset($control)){
						$control->PreferedRenderMethod = "RenderWithName";
					}
				}
			}
			$btnSaveControl = new QButton($this->dlgEdit);
			$btnSaveControl->ActionParameter = $objToModify->ControlId;
			$btnSaveControl->AddAction(new QClickEvent(), new QAjaxAction('btnSaveControl_Click'));
			$btnSaveControl->Text = "Save";
			$this->dlgEdit->Open();
		}
	}

	PageBuilder::Run('PageBuilder');
?>