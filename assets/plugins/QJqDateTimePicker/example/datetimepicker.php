<?php
	require('../../../../includes/configuration/prepend.inc.php');

	class ExampleForm extends QForm {
		protected $dtp1;
		protected $dtp2;

		protected function Form_Create() {
			$this->dtp1 = new QJqDateTimePicker($this);

			$this->dtp2 = new QJqDateTimePicker($this);
			$this->dtp2->MinDate = new QDateTime('2009-01-01');
			$this->dtp2->MaxDate = new QDateTime('2012-01-01');
			$this->dtp2->StepMinute = 10;
			$this->dtp2->DateFormat = "YYYY-MM-DD";
			$this->dtp2->TimeFormat = "hh.mm";
		}
	}

	ExampleForm::Run('ExampleForm');
?>
