<?php
	require('../../../../includes/configuration/prepend.inc.php');

	// Define the Qform with all our Qcontrols
	class ExamplesForm extends QForm {
		// Local declarations of our Qcontrols
		protected $lblMessage;
		protected $btnButtonA;
		protected $btnButtonB;

		// Initialize our Controls during the Form Creation process
		protected function Form_Create() {
			// Define the Label
			$this->lblMessage = new QLabel($this);
			$this->lblMessage->Text = 'Open the Firebug console, and then click one of the buttons above';

			// Define the Button
			$this->btnButtonA = new QButton($this);
			$this->btnButtonA->Text = 'Button A';			
			$this->btnButtonA->AddAction(new QClickEvent(), new QAjaxAction('btnButtonA_Click'));

			$this->btnButtonB = new QButton($this);
			$this->btnButtonB->Text = 'Button B (local database required)';
			$this->btnButtonB->AddAction(new QClickEvent(), new QAjaxAction('btnButtonB_Click'));
		}

		protected function btnButtonA_Click($strFormId, $strControlId, $strParameter) {			
			QFirebug::log('This is a sample log message');
			QFirebug::warn('This is a sample warning');
			
			// Some other types of alerts - note that the error() method
			// actually throws a Firebug exception!
//			QFirebug::error('This is a sample error');
//			QFirebug::info("This is a sample informational message");

			$this->lblMessage->Text = 'Button A was clicked!';            
		}

		protected function btnButtonB_Click($strFormId, $strControlId, $strParameter) {			        
			QApplication::$Database[1]->EnableProfiling();
			$HowManyProjects = Project::CountByProjectStatusTypeId(ProjectStatusType::Open);
			QFirebug::OutputDatabaseProfile();
			
			$this->lblMessage->Text = 'Button B was clicked!';
		}
	}

	// Run the Form we have defined
	ExamplesForm::Run('ExamplesForm');
?>
