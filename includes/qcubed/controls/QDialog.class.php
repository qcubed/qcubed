<?php
	class QDialog extends QDialogBase
	{
		protected function getJqControlId() {
			return $this->ControlId ."_ctl";
		}
	}
?>