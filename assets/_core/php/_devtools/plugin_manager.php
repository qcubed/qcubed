<?php

	require('../../../../includes/configuration/prepend.inc.php');

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
			$this->dtgPlugins = new QDataGrid($this);
			$this->dtgPlugins->SetDataBinder('dtgPlugins_Bind');

			$this->dtgPlugins->CssClass = 'datagrid';
			$this->dtgPlugins->AlternateRowStyle->CssClass = 'alternate';
			
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Title',
                    '<a href="plugin_edit.php?strType=installed&strName=<?= $_ITEM->strName ?>"><?= $_ITEM->strName ?></a>', 'HtmlEntities=false'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Files',
                    '<?= count($_ITEM->objAllFilesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Includes',
                    '<?= count($_ITEM->objIncludesArray) ?>'));
			$this->dtgPlugins->AddColumn(new QDataGridColumn('Examples',
                    '<?= count($_ITEM->objExamplesArray) ?>'));


			$this->dtgPlugins->AddColumn(new QDataGridColumn('Description',
                    '<?= $_ITEM->strDescription ?>'));			
		}
		
		public function dtgPlugins_Bind() {			
			$this->dtgPlugins->DataSource = QPluginConfigParser::parseInstalledPlugins();
		}
		
        private function dlgUpload_Create() {
            $this->dlgUpload = new QFileAssetDialog($this, 'dlgUpload_done');
            $this->dlgUpload->lblMessage->Text = "Please upload a plugin .zip file";
            $this->dlgUpload->btnUpload->Text = "Upload";
            $this->dlgUpload->btnCancel->Text = "Cancel";
            $this->dlgUpload->SetCustomStyle("background-color", "rgb(238, 255, 221)");
            $this->dlgUpload->SetCustomStyle("padding", "10px");
		}
		
        public function dlgUpload_done($strFormId, $strControlId, $strParameter) {
            $this->dlgUpload->HideDialogBox();
            
            $pluginFolder = QPluginInstaller::processUploadedPluginArchive($this->dlgUpload->flcFileAsset);
			
			if ($pluginFolder == null) {
				QApplication::DisplayAlert(QPluginInstaller::getLastError());
				return;
			}
			
			QApplication::Redirect('plugin_edit.php?strType=new&strName=' . $pluginFolder);
        }		
	}	

	PluginManagerForm::Run('PluginManagerForm');
?>