<?php require('../../application/configuration/prepend.inc.php');

class ExampleForm extends QForm {
	// private state
	private $mctPerson;
	
	// Local declarations of our Qcontrols
	protected $txtFirstName;
	protected $txtLastName;
	protected $lstLogin;
	
	protected $btnCompare;
	
	protected $lblComparisonResult;
	
	// Initialize our Controls during the Form Creation process
	protected function Form_Create() {
		// Let's load a sample project to edit.
		// We'll arbitrarily pick an object to edit - doesn't
		// really matter which one to illustrate the point here.
		$this->mctPerson = PersonMetaControl::Create($this, 3);
				
		// Create the textboxes
		$this->txtFirstName = $this->mctPerson->txtFirstName_Create();
		$this->txtLastName 	= $this->mctPerson->txtLastName_Create();
		
		// Status label
		$this->lblComparisonResult = new QLabel($this);
		$this->lblComparisonResult->HtmlEntities = false;
		$this->lblComparisonResult->Text = "Comparison results will show up here";

		// Define a Button and a click handler for it
		$this->btnCompare = new QButton($this);
		$this->btnCompare->Text = 'Save and Compare';
		$this->btnCompare->AddAction(new QClickEvent(), new QAjaxAction('btnCompare_Click'));
	}

	protected function btnCompare_Click($strFormId, $strControlId, $strParameter) {
		// Clone the old project before we save the new one, so
		// that we could compare the two
		$oldPerson = clone $this->mctPerson->Person;
		
		// Now, save the Project object. Note that $oldProject will not be
		// modified - we created a new instance of the Project object with it
		// using "clone".
		$this->mctPerson->SavePerson();
		
		$newPerson = $this->mctPerson->Person;
		
		// The resulting object is of type QComparisonResult
		$comparisonResult = QObjectDiff::Compare($oldPerson, $newPerson);
	
		// For this example, we'll just ask for a shorthand to-string
		// representation of the QComparison result. In production,
		// you would probably want to iterate over $comparisonResult->DifferentFields
		$this->lblComparisonResult->Text = nl2br($comparisonResult->__toString());
	}
}

// Run the Form we have defined
ExampleForm::Run('ExampleForm');

?>
