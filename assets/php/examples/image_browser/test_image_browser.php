<?php
// Include prepend.inc to load Qcubed
	require_once('../qcubed.inc.php');

	class TestImageBrowser extends QForm {
		/**
		 * @var QImageBrowser
		 */
		protected $imbBrowser;
		
		protected function Form_Create() {
			$this->imbBrowser = new QImageBrowser($this);
			$this->imbBrowser->Template = 'image_browser.tpl.php';
			// $this->imbBrowser->AutoRenderChildren = true;
			// force main image size
			$this->imbBrowser->MainImage->Width = 150;
			$this->imbBrowser->MainImage->Height = 150;
			$this->imbBrowser->MainImage->AddAction(new QClickEvent(), new QAjaxControlAction($this->imbBrowser, 'btnNext_Click'));
			$this->imbBrowser->LoadImagesFromDirectory("../images/emoticons", '/png/i');
		}
	}

	TestImageBrowser::Run('TestImageBrowser', 'test_image_browser.tpl.php');
?>
