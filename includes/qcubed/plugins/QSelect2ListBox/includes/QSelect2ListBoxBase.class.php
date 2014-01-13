<?php

	class QSelect2ListBoxBase extends QSelect2ListBoxGen
	{
		public function  __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->AddJavascriptFile("../../plugins/QSelect2ListBox/select2-release-3.2/select2.min.js");
			$this->AddCssFile("../../plugins/QSelect2ListBox/select2-release-3.2/select2.css");
		}

		protected function GetResetButtonHtml() {
			return '';
		}
	}
?>
