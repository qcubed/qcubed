<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends QForm {
		protected $imgMyRolloverImage;

		protected function Form_Create() {
			$this->imgMyRolloverImage = new QImageRollover($this);
			$this->imgMyRolloverImage->ImageStandard = "../images/emoticons/1.png";
			$this->imgMyRolloverImage->ImageHover = "../images/emoticons/2.png";
		}
	}

	ExampleForm::Run('ExampleForm');
?>
