<?php
	require('../../../../includes/configuration/prepend.inc.php');

	class PluginEditForm extends QForm {
		protected function Form_Run() {
			// Security check for ALLOW_REMOTE_ADMIN
			// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
			QApplication::CheckRemoteAdmin();
		}

		protected function Form_Create() {

		}
	}

	PluginEditForm::Run('PluginEditForm');
?>