<?php
	require_once('../qcubed.inc.php');

	// Security check for ALLOW_REMOTE_ADMIN
	// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
	QApplication::CheckRemoteAdmin();

	// Let's "magically" determine the list of genereated Class Panel Drafts by
	// just traversing through this directory, looking for "*ListPanel.class.php" and "*EditPanel.class.php"

	// Obviously, if you are wanting to make your own dashbaord, you should change this and use more
	// hard-coded means to determine which classes' paneldrafts you want to include/use in your dashboard.
	$objDirectory = opendir(__DOCROOT__ . __PANEL_DRAFTS__);
	$strClassNameArray = array();
	while ($strFile = readdir($objDirectory)) {
		if ($intPosition = strpos($strFile, 'ListPanel.class.php')) {
			$strClassName = substr($strFile, 0, $intPosition);
			$strClassNameArray[$strClassName] = $strClassName . 'ListDetailView';
			require_once(__META_CONTROLS__ . '/' . $strClassName . 'ListDetailView.class.php');
		}
	}
	asort($strClassNameArray);

	class Dashboard extends QForm {
		/** @var QLabel */
		protected $lblTitle;
		/** @var QSelect2ListBox */
		protected $lstClassNames;
		/** @var QPanel */
		protected $pnlEdit;

		protected function Form_Create() {
			$this->lblTitle = new QLabel($this);
			$this->lblTitle->Text = 'AJAX Dashboard';

			$this->lstClassNames = new QSelect2ListBox($this);
			$this->lstClassNames->Placeholder = QApplication::Translate('Select a Class to View/Edit');
			$this->lstClassNames->AllowClear = true;
			$this->lstClassNames->Width = 350;
			$this->lstClassNames->AddAction(new QChangeEvent(), new QAjaxAction("lstClassNames_Change"));
			// Use the strClassNameArray as magically determined above to aggregate the listbox of classes
			// Obviously, this should be modified if you want to make a custom dashboard
			global $strClassNameArray;
			$this->lstClassNames->AddItem(null, null, true);
			foreach ($strClassNameArray as $strEntityClassName => $strPanelClassName) {
				$this->lstClassNames->AddItem($strEntityClassName, $strPanelClassName);
			}
			$this->objDefaultWaitIcon = new QWaitIcon($this);

			$this->pnlEdit = new QPanel($this, 'pnlEdit');
			$this->pnlEdit->AutoRenderChildren = true;
			$this->pnlEdit->Visible = false;
		}

		protected function lstClassNames_Change($strFormId, $strControlId, $strParameter) {
			// Get rid of all child controls for list and edit panels
			$this->pnlEdit->RemoveChildControls(true);

			if ($strPanelClassName = $this->lstClassNames->SelectedValue) {
				// We've selected a Class Name
				$objNewPanel = new $strPanelClassName($this->pnlEdit);
				$this->lblTitle->Text = $this->lstClassNames->SelectedName;
				$this->pnlEdit->Visible = true;
			} else {
				$this->lblTitle->Text = 'AJAX Dashboard';
				$this->pnlEdit->Visible = false;
			}
		}

	}

	Dashboard::Run('Dashboard');
?>