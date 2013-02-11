<?php
require_once('../qcubed.inc.php');

QForm::$FormStateHandler = 'QSessionFormStateHandler';

class ExampleForm extends QForm {

	protected $tnvExample;
	protected $tnvExampleDynamic;
	protected $pnlCode;

	// Define all the QControl objects for our Tree Navigation
	protected function Form_Create() {
		$this->tnvExample = new QTreeNav($this);
		$this->tnvExample->CssClass = 'treenav';
		$this->tnvExample->AddAction(new QChangeEvent(), new QAjaxAction('tnvExample_Change'));

		$this->tnvExampleDynamic = new QTreeNav($this);
		$this->tnvExampleDynamic->CssClass = 'treenav';
		$this->tnvExampleDynamic->AddAction(new QChangeEvent(), new QAjaxAction('tnvExample_Change'));
		$this->tnvExampleDynamic->SetLoader('tnvExampleDynamic_LoadItem', $this);

		$this->pnlCode = new QPanel($this);
		$this->pnlCode->CssClass = 'codeDisplay';

		$this->objDefaultWaitIcon = new QWaitIcon($this);

		// Create a treenav of the file/folder directory for qqcubed includes
		$this->tnvExample_AddItems(dirname(__INCLUDES__ . '.'));
		$this->tnvExampleDynamic_AddItems(dirname(__INCLUDES__ . '.'));
	}

	protected function tnvExample_AddItems($strDirectory, $objParentItem = null) {
		$objDirectory = opendir($strDirectory);
		if (!$objParentItem){
			$objParentItem = $this->tnvExample;
		}
		while ($strFilename = readdir($objDirectory)) {
			if (($strFilename) && ($strFilename != '.') && ($strFilename != '..') && ($strFilename != 'configuration.inc.php') && ($strFilename != 'configuration_pro.inc.php') && ($strFilename != 'CVS')) {
				// Create the new TreeNavItem
				$tniFile = new QTreeNavItem($strFilename, $strDirectory . '/' . $strFilename, false, $objParentItem);

				// Recurse down the tree if we're at a directory
				if (is_dir($strDirectory . '/' . $strFilename)) {
					// We're currently looking at a directory -- make recursive call to go down the tree
					$this->tnvExample_AddItems($strDirectory . '/' . $strFilename, $tniFile);
				}
			}
		}

		closedir($objDirectory);
	}

	protected function tnvExampleDynamic_AddItems($strDirectory, $objParentItem = null) {
		if (!$objParentItem){
			$objParentItem = $this->tnvExampleDynamic;
		}
		$this->AddFilesToTreeNav($strDirectory, $objParentItem);
	}

	public function tnvExampleDynamic_LoadItem($objItem) {
		$this->AddFilesToTreeNav($objItem->Value, $objItem);
	}

	protected function AddFilesToTreeNav($strDirectory, $objParent) {
		$objDirectory = opendir($strDirectory);

		while ($strFilename = readdir($objDirectory)) {
			if (($strFilename) && ($strFilename != '.') && ($strFilename != '..') && ($strFilename != 'configuration.inc.php') && ($strFilename != 'configuration_pro.inc.php') && ($strFilename != 'CVS')) {
				// Create the new TreeNavItem
				$tniFile = new QTreeNavItem($strFilename, $strDirectory . '/' . $strFilename, false, $objParent);

				//cause the load function to be called when a subdirectory's expanded
				if (is_dir($strDirectory . '/' . $strFilename)) {
					//NOTE: To hide the expand icon on empty directories, also check the filecount of this dir
					//before setting HasChildren
					$tniFile->HasChildren = true;
				}
			}
		}

		closedir($objDirectory);
	}

	protected function tnvExample_Change($strFormId, $strControlId, $strParameter) {
		$tree = $this->GetControl($strControlId);
		$objItem = $tree->SelectedItem;
		if (is_dir($tree->SelectedValue)){
			$this->pnlCode->Text = 'Current directory is <strong>' . $tree->SelectedItem->Name . '</strong>.  ' .
					'Please select a file on the left';
		} else {
			$strCode = highlight_file($tree->SelectedValue, true);
			$this->pnlCode->Text = $strCode;
		}
	}
}

// And now run our defined form
ExampleForm::Run('ExampleForm');
?>