<?php

	require_once('../qcubed.inc.php');

	class PluginManagerForm extends QForm {
		// Local instance of the Meta DataGrid to list Addresses
		protected $dtgPlugins;
		protected $btnNewPlugin;
		protected $dlgUpload;
		
		private $objPluginArray;

		protected function Form_Run() {
			QApplication::CheckRemoteAdmin();
		}

		protected function Form_Create() {
			$this->dtgPlugins_Create();
			$this->btnNewPlugin_Create();
			$this->dlgUpload_Create();
		}
				
		private function btnNewPlugin_Create() {
			$this->btnNewPlugin = new QButton($this);
			$this->btnNewPlugin->Text = "Install a New Plugin";
			$this->btnNewPlugin->AddAction(new QClickEvent(), new QAjaxAction('btnNewPlugin_Click'));
		}
		
		public function btnNewPlugin_Click() {
			$this->dlgUpload->ShowDialogBox();
		}
		
		private function dtgPlugins_Create() {
			$this->dtgPlugins = new QDataGrid($this, 'dtgPlugins');
			$this->dtgPlugins->SetDataBinder('dtgPlugins_Bind');

			$this->dtgPlugins->CssClass = 'datagrid';
			$this->dtgPlugins->AlternateRowStyle->CssClass = 'alternate';
			
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Title',
					'<a href="plugin_edit.php?strType=installed&strName=<?= $_ITEM->strName ?>"><?= $_ITEM->strName ?></a>', 'HtmlEntities=false'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Version',
					'<?= $_ITEM->strVersion ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Files',
					'<?= count($_ITEM->objAllFilesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Includes',
					'<?= count($_ITEM->objIncludesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Examples',
					'<?= count($_ITEM->objExamplesArray) ?>'));


			$this->dtgPlugins->AddColumn(new QDataGridColumn('Description',
				'<?= $_FORM->RenderDescription($_ITEM) ?>', 'HtmlEntities=false'));	
		}
		
		public function dtgPlugins_Bind() {			
			$this->dtgPlugins->DataSource = QPluginConfigParser::parseInstalledPlugins();
		}
		
		public function RenderDescription($objItem) {
			$exampleSnippet = "";
			
			if (sizeof($objItem->objExamplesArray) > 0) {
				$exampleSnippet .= "<br />";
				foreach ($objItem->objExamplesArray as $example) {
					$exampleSnippet .= "Example: <a href='" .
						__PLUGIN_ASSETS__ . '/' . $objItem->strName . '/' .
						$example->strFilename . "'>" . $example->strDescription . "</a><br>";
				}
			}
			
			return $objItem->strDescription . $exampleSnippet;
		}
		
		private function dlgUpload_Create() {
			$this->dlgUpload = new QFileAssetDialog($this, 'dlgUpload_done');
			$this->dlgUpload->Title = "Install a New Plugin";
			$this->dlgUpload->Width = null; // auto width
			$this->dlgUpload->lblMessage->Text = "<p>Please upload a plugin .zip file.</p>" . 
				"<p>You can get the latest plugins from the " .
				"<a target='_blank' href='" . QPluginInstaller::ONLINE_PLUGIN_REPOSITORY .
				"'>online repository</a>.</p>";
			$this->dlgUpload->btnUpload->Text = "Upload";
			$this->dlgUpload->btnCancel->Text = "Cancel";
			$this->dlgUpload->SetCustomStyle("background-color", "rgb(238, 255, 221)");
			$this->dlgUpload->SetCustomStyle("padding", "10px");
		}
		
		public function dlgUpload_done($strFormId, $strControlId, $strParameter) {
			$this->dlgUpload->HideDialogBox();

			$originalFileName = $this->dlgUpload->flcFileAsset->FileName;
			if (strtolower(substr($originalFileName, -3)) != "zip") {
				QApplication::DisplayAlert("Invalid uploaded plugin file - only ZIP allowed: " . $originalFileName);
				return;
			}
			
			$pluginFolder = QPluginInstaller::installPluginFromZip($this->dlgUpload->flcFileAsset->File);
			
			if ($pluginFolder == null) {
				QApplication::DisplayAlert(QPluginInstaller::getLastError());
				return;
			}
			
			QApplication::Redirect('plugin_edit.php?strType=new&strName=' . $pluginFolder);
		}		
	}	

	PluginManagerForm::Run('PluginManagerForm');
?>