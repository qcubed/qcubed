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
			$strClassNameArray[] = $strClassName;
			require_once(__META_CONTROLS__ . '/' . $strClassName . 'ListDetailView.class.php');
		}
	}
	sort($strClassNameArray);

	class Dashboard extends QForm {
		/** @var QTabs */
		protected $tabs;

		protected function Form_Create() {
			$this->tabs = new QTabs($this);
			$headers = array();

			// Use the strClassNameArray as magically determined above to aggregate the listbox of classes
			// Obviously, this should be modified if you want to make a custom dashboard
			global $strClassNameArray;
			foreach ($strClassNameArray as $strClassName) {
				$strListDetailViewClassName = $strClassName . 'ListDetailView';
				new $strListDetailViewClassName($this->tabs);
				$headers[] = _tr($strClassName);
			}
			$this->tabs->Headers = $headers;
			$this->objDefaultWaitIcon = new QWaitIcon($this);
		}

		public function Form_Validate() {
			foreach ($objErrorControls = $this->GetErrorControls() as $objErrorControl) {
				$objErrorControl->Blink('#ff6666', '#ffff66');
			}
			// Because we performed no custom validation, let's always return true
			return true;
		}
	}

	Dashboard::Run('Dashboard');
?>
