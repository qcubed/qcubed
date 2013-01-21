<?php
	require('../../../../includes/configuration/prepend.inc.php');

	class ExampleForm extends QForm {
		// Declare the DataGrid
		protected $drp1;
		protected $drp2;
		protected $drp3;

		protected function Form_Create() {
			// Define the DataGrid
			$this->drp1 = new QDateRangePicker($this);
			$this->drp1->AutoRenderChildren = true;
			$this->drp1->Input = new QTextBox($this->drp1);

			$this->drp2 = new QDateRangePicker($this);
			$this->drp2->AutoRenderChildren = true;
			$this->drp2->Input = new QTextBox($this->drp2);
			$this->drp2->AddPresetRange(QDateRangePickerPresetRange::Today());
			$this->drp2->AddPresetRange(QDateRangePickerPresetRange::Last30Days());
			$this->drp2->AddPresetRange(new QDateRangePickerPresetRange('Independence Day', '4 July', '4 July'));
			$this->drp2->AddPreset(QDateRangePickerPreset::DateRange(), 'Range');
			$this->drp2->Arrows = true;

			$this->drp3 = new QDateRangePicker($this);
			$this->drp3->Input = new QTextBox($this);
			$this->drp3->SecondInput = new QTextBox($this);
			$this->drp3->CloseOnSelect= true;
		}
	}

	ExampleForm::Run('ExampleForm');
?>
