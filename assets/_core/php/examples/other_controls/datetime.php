<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends QForm {
		protected $dtxDateTimeTextBox;
		protected $btnDateTimeTextBox;

		protected $calQJQCalendar;
		protected $btnQJQCalendar;
		
		protected $dtpDatePicker;
		protected $btnDatePicker;

		protected $dtpDateTimePicker;
		protected $btnDateTimePicker;

		protected $lblResult;

		protected function Form_Create() {
			
			$this->calQJQCalendar = new QCalendar($this);
			
			$this->dtxDateTimeTextBox = new QDateTimeTextBox($this);

			// QDateTimePicker can have different "Types"
			$this->dtpDatePicker = new QDateTimePicker($this);
			$this->dtpDatePicker->DateTimePickerType = QDateTimePickerType::Date;

			$this->dtpDateTimePicker = new QDateTimePicker($this);
			$this->dtpDateTimePicker->DateTimePickerType = QDateTimePickerType::DateTime;

			// To View the "Results"
			$this->lblResult = new QLabel($this);
			$this->lblResult->Text = 'Results...';

			// Various Buttons
			$this->btnQJQCalendar = new QButton($this);
			$this->btnQJQCalendar->Text = 'Update';
			$this->btnQJQCalendar->AddAction(new QClickEvent(), new QAjaxAction('btnUpdate_Click'));
			$this->btnQJQCalendar->ActionParameter = $this->calQJQCalendar->ControlId;
			
			$this->btnDateTimeTextBox = new QButton($this);
			$this->btnDateTimeTextBox->Text = 'Update';
			$this->btnDateTimeTextBox->AddAction(new QClickEvent(), new QAjaxAction('btnUpdate_Click'));
			$this->btnDateTimeTextBox->ActionParameter = $this->dtxDateTimeTextBox->ControlId;

			$this->btnDatePicker = new QButton($this);
			$this->btnDatePicker->Text = 'Update';
			$this->btnDatePicker->AddAction(new QClickEvent(), new QAjaxAction('btnUpdate_Click'));
			$this->btnDatePicker->ActionParameter = $this->dtpDatePicker->ControlId;

			$this->btnDateTimePicker = new QButton($this);
			$this->btnDateTimePicker->Text = 'Update';
			$this->btnDateTimePicker->AddAction(new QClickEvent(), new QAjaxAction('btnUpdate_Click'));
			$this->btnDateTimePicker->ActionParameter = $this->dtpDateTimePicker->ControlId;
		}

		protected function btnUpdate_Click($strFormId, $strControlId, $strParameter) {
			$objControlToLookup = $this->GetControl($strParameter);
			$dttDateTime = $objControlToLookup->DateTime;

			// If a DateTime value is NOT selected or is INVALID, then this will be NULL
			if ($dttDateTime) {
				$this->lblResult->Text = 'QDateTime object:<br/>';
				if (!$dttDateTime->IsDateNull())
					$this->lblResult->Text .= 'Date: <strong>' . $dttDateTime->qFormat('DDD MMM D YYYY') . '</strong><br/>';
				else
					$this->lblResult->Text .= 'Date: <strong>Null</strong><br/>';
				if (!$dttDateTime->IsTimeNull())
					$this->lblResult->Text .= 'Time: <strong>' . $dttDateTime->qFormat('h:mm:ss z') . '</strong>';
				else
					$this->lblResult->Text .= 'Time: <strong>Null</strong>';
			} else {
				$this->lblResult->Text = 'QDateTime object: <strong>Null</strong>';
			}
		}
	}

	// And now run our defined form
	ExampleForm::Run('ExampleForm');
?>