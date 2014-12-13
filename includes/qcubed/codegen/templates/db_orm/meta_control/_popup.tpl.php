<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<?php echo __META_CONTROLS_GEN__ ?>" TargetFileName="<?php echo $objTable->ClassName ?>PopupGen.class.php"/>
<?php print("<?php\n"); ?>
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>UpdatePanel.class.php');
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>ViewPanel.class.php');
	require_once(__META_CONTROLS__ . '/<?php echo $objTable->ClassName ?>DataTable.class.php');

	/**
	 * @property-write QCallback $ViewPanelPostCreateCallback
	 * @property-write QCallback $EditPanelPostCreateCallback
	 * @property-write QCallback $CreatePanelPostCreateCallback
	 * @property-write QCallback $SearchControlPostCreateCallback
	 * @property-write string $EditTemplate
	 * @property-write string $ViewTemplate
	 * @property-write string $CreateTemplate
	 * @property-read QDialog $PopupDialog
	 */
	class <?php echo $objTable->ClassName ?>PopupGen extends QPanel {
		/** @var QDialog */
		protected $dlgPopup;
		/** @var QCallback */
		protected $objSearchCallback;
		/** @var QCallback */
		protected $objViewPanelPostCreateCallback;
		/** @var QCallback */
		protected $objEditPanelPostCreateCallback;
		/** @var QCallback */
		protected $objCreatePanelPostCreateCallback;
		/** @var QCallback */
		protected $objSearchControlPostCreateCallback;
		/** @var <?php echo $objTable->ClassName ?>DataTable */
		protected $objSearchControl;
		protected $strEditTemplate;
		protected $strViewTemplate;
		protected $strCreateTemplate;

		public function __construct($objParentObject, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$this->AutoRenderChildren = true;
			$this->UseWrapper = false;

			$this->dlgPopup = new QDialog($this);
			$this->dlgPopup->Height = 'auto';
			$this->dlgPopup->Width = 'auto';
			$this->dlgPopup->AutoOpen = false;
			$this->dlgPopup->AutoRenderChildren = true;
			$this->dlgPopup->Modal = true;
		}

		/**
		 * @param $obj<?php echo $objTable->ClassName ?>Ref
		 * @param string|null $strDialogTitle
		 * @return <?php echo $objTable->ClassName ?>ViewPanel
		 */
		public function View($obj<?php echo $objTable->ClassName ?>Ref, $strDialogTitle = null) {
			$this->dlgPopup->RemoveChildControls(true);
			$objViewPanel = new <?php echo $objTable->ClassName ?>ViewPanel($this->dlgPopup, $obj<?php echo $objTable->ClassName ?>Ref, true);
			if ($this->strViewTemplate) {
				$objViewPanel->Template = $this->strViewTemplate;
			}
			if (is_null($strDialogTitle)) {
				$strDialogTitle = QApplication::Translate('<?php echo $objTable->ClassName ?>');
			}
			$this->dlgPopup->Title = $strDialogTitle;
			$this->dlgPopup->ShowDialogBox();
			$this->dlgPopup->MoveToTop();
			if ($this->objViewPanelPostCreateCallback)
				$this->objViewPanelPostCreateCallback->Call($objViewPanel);
			return $objViewPanel;
		}

		/**
		 * @param mixed $obj<?php echo $objTable->ClassName ?>Ref
		 * @param QCallback|null $objSaveCallback
		 * @param QCallback|null $objDeleteCallback
		 * @param string|null $strDialogTitle
		 * @return <?php echo $objTable->ClassName ?>UpdatePanel
		 */
		public function Edit($obj<?php echo $objTable->ClassName ?>Ref, QCallback $objSaveCallback = null, QCallback $objDeleteCallback = null, $strDialogTitle = null) {
			$this->dlgPopup->RemoveChildControls(true);
			$objEditPanel = new <?php echo $objTable->ClassName ?>UpdatePanel($this->dlgPopup, $obj<?php echo $objTable->ClassName ?>Ref, false);
			if ($this->strEditTemplate) {
				$objEditPanel->Template = $this->strEditTemplate;
			}
			$objEditPanel->SaveCallback = new DialogClosingCallback($this->dlgPopup, $objSaveCallback);
			$objEditPanel->CancelCallback = new DialogClosingCallback($this->dlgPopup);
			$objEditPanel->DeleteCallback = new DialogClosingCallback($this->dlgPopup, $objDeleteCallback);
			if (is_null($strDialogTitle)) {
				$strDialogTitle = QApplication::Translate('Edit <?php echo $objTable->ClassName ?>');
			}
			$this->dlgPopup->Title = $strDialogTitle;
			$this->dlgPopup->ShowDialogBox();
			$this->dlgPopup->MoveToTop();
			if ($this->objEditPanelPostCreateCallback)
				$this->objEditPanelPostCreateCallback->Call($objEditPanel);
			return $objEditPanel;
		}

		/**
		 * @param QCallback|null $objSaveCallback
		 * @param string|null $strDialogTitle
		 * @return <?php echo $objTable->ClassName ?>UpdatePanel
		 */
		public function Create(QCallback $objSaveCallback = null, $strDialogTitle = null) {
			$this->dlgPopup->RemoveChildControls(true);
			$objCreatePanel = new <?php echo $objTable->ClassName ?>UpdatePanel($this->dlgPopup, null, false);
			if ($this->strCreateTemplate) {
				$objCreatePanel->Template = $this->strCreateTemplate;
			}
			$objCreatePanel->SaveCallback = new DialogClosingCallback($this->dlgPopup, $objSaveCallback);
			$objCreatePanel->CancelCallback = new DialogClosingCallback($this->dlgPopup);
			if (is_null($strDialogTitle)) {
				$strDialogTitle = QApplication::Translate('Create <?php echo $objTable->ClassName ?>');
			}
			$this->dlgPopup->Title = $strDialogTitle;
			$this->dlgPopup->ShowDialogBox();
			$this->dlgPopup->MoveToTop();
			if ($this->objCreatePanelPostCreateCallback)
				$this->objCreatePanelPostCreateCallback->Call($objCreatePanel);
			return $objCreatePanel;
		}

		/**
		 * @param QCallback|null $objCallback
		 * @param string|null $strDialogTitle
		 * @return <?php echo $objTable->ClassName ?>DataTable
		 */
		public function Select(QCallback $objCallback = null, $strDialogTitle = null) {
			$this->dlgPopup->RemoveChildControls(true);
			$this->objSearchCallback = $objCallback;
			$this->objSearchControl = new <?php echo $objTable->ClassName ?>DataTable($this->dlgPopup);
			$this->objSearchControl->AddAction(new QDataTable_RowClickEvent(), new QAjaxControlAction($this, "searchRow_Click"));
			if (is_null($strDialogTitle)) {
				$strDialogTitle = QApplication::Translate('Select <?php echo $objTable->ClassName ?>');
			}
			$this->dlgPopup->Title = $strDialogTitle;
			$this->dlgPopup->ShowDialogBox();
			$this->dlgPopup->MoveToTop();
			if ($this->objSearchControlPostCreateCallback)
				$this->objSearchControlPostCreateCallback->Call($this->objSearchControl);
			return $this->objSearchControl;
		}

		public function searchRow_Click($strFormId, $strControlId, $strParameter) {
			$this->CloseEditPopup(false);
			$obj<?php echo $objTable->ClassName ?> = $this->objSearchControl->LoadObjectFromRowData($strParameter);
			if ($this->objSearchCallback) {
				$this->objSearchCallback->Call($obj<?php echo $objTable->ClassName ?>);
			}
		}

		public function CloseEditPopup($blnChangesMade, $blnDeleted = false) {
			$this->dlgPopup->RemoveChildControls(true);
			$this->dlgPopup->HideDialogBox();
		}

		public function __get($strName) {
			switch ($strName) {
				case "PopupDialog": return $this->dlgPopup;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "ViewPanelPostCreateCallback":
					try {
						$this->objViewPanelPostCreateCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "EditPanelPostCreateCallback":
					try {
						$this->objEditPanelPostCreateCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CreatePanelPostCreateCallback":
					try {
						$this->objCreatePanelPostCreateCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "SearchControlPostCreateCallback":
					try {
						$this->objSearchControlPostCreateCallback = QType::Cast($mixValue, 'QCallback');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "EditTemplate":
					try {
						$this->strEditTemplate = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ViewTemplate":
					try {
						$this->strViewTemplate = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CreateTemplate":
					try {
						$this->strCreateTemplate = QType::Cast($mixValue, QType::String);
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
	}
