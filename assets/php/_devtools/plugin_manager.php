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
			//$this->btnNewPlugin_Create();
			//$this->dlgUpload_Create();
		}
				
		private function btnNewPlugin_Create() {
			$this->btnNewPlugin = new QButton($this);
			$this->btnNewPlugin->Text = "Install a New Plugin";
			$this->btnNewPlugin->AddAction(new QClickEvent(), new QAjaxAction('btnNewPlugin_Click'));
		}
		/*
		public function btnNewPlugin_Click() {
			$this->dlgUpload->ShowDialogBox();
		}*/
		
		private function dtgPlugins_Create() {
			$this->dtgPlugins = new QDataGrid($this, 'dtgPlugins');
			$this->dtgPlugins->SetDataBinder('dtgPlugins_Bind');

			$this->dtgPlugins->CssClass = 'datagrid';

			$this->dtgPlugins->CreateIndexedColumn('Name', 'Name');
			$this->dtgPlugins->CreateIndexedColumn('Description', 'Description');
			$col = $this->dtgPlugins->CreateCallableColumn('Examples', [$this, 'RenderExampleLink']);
			$col->HtmlEntities = false;
		}
		
		public function dtgPlugins_Bind() {
			if (!is_dir(__PLUGINS__)) {
				return;
			}
			$pluginDirArray = scandir ( __PLUGINS__);
			$itemArray = array();
			
			foreach ($pluginDirArray as $dirItem) {
				$strComposerFilePath = __PLUGINS__ . '/' . $dirItem . '/' .'composer.json';
				if (file_exists($strComposerFilePath)) {
					$composerDetails = json_decode(file_get_contents($strComposerFilePath ), true);

					$arrayItem['Name'] = $dirItem;
					$arrayItem['Description'] = '';
					if (!empty($composerDetails['description'])) {
						$arrayItem['Description'] = $composerDetails['description'];
					} 
					$arrayItem['Examples'] = null;
					if (!empty($composerDetails['extra']['examples'])) { // embed example page name into composer file for convenience
						foreach ($composerDetails['extra']['examples'] as $strExample) {
							$strExamplePath = __PLUGINS__ . '/' . $dirItem . '/examples/' . $strExample;
							if (file_exists ($strExamplePath)) {
								$arrayItem['Examples'][] = $strExample;
							}
						}
					} 
					$itemArray[] = $arrayItem;
				}
			}
			$this->dtgPlugins->DataSource = $itemArray;
		}
		/*
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
		}*/
		
		public function RenderExampleLink($item) {
			if ($item['Examples']) {
				$strRet = '';
				foreach ($item['Examples'] as $strItem) {
					$strRet .= '<a href="' . __PLUGIN_ASSETS__ . '/' . $item['Name'] . '/examples/' . $strItem .
					'">' . QApplication::HtmlEntities($strItem) . '</a><br />';
				}
				return $strRet;
			}
			return null;
		}
		/*
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
		*/
	}	

	PluginManagerForm::Run('PluginManagerForm');
?>