<?php require('../../includes/prepend.inc.php');

class PersistentExampleForm extends QForm {
	// We will persist this control in the $_SESSION
	protected $ddnProjectPicker;

	// for the purpose of simplicity for this example,
	// we'll create a public QLabel to pass in the status 
	// information between the Persistent Control $ddnProjectPicker
	// and this parent QForm. 
	public $lblStatus; 

	protected function Form_Create() {
		$this->lblStatus = new QLabel($this);		
		// Initialize the text of the label; if the query
		// gets executed, this will be overwritten
		$this->lblStatus->Text = "The query to populate the dropdown was NOT executed";

		$this->ddnProjectPicker = ProjectPickerListBox::CreatePersistent(
			'ProjectPickerListBox', // name of the control class
			$this, // parent - the current QForm
			'ddnProjects' // id on the form - just your usual ControlID
		);
	}
}

/**
 * This class encapsulates the logic of populating a list box
 * with a set of projects.
 */
class ProjectPickerListBox extends QListBox {            
	
	/**
	 * This constructor will only be executed once - afterwards,
	 * the state of the control will be stored into the $_SESSION
	 * and, on future loads, populated from the session state.
	 */
	public function __construct($objParentObject, $strControlId) {
		parent::__construct($objParentObject, $strControlId);
				
		$projects = Project::QueryArray(
			QQ::All(),            
			QQ::OrderBy(QQN::Project()->Name)                
		);
				
		foreach ($projects as $project) {
			$this->AddItem($project->Name, $project->Id);
		}
		
		// Reset the status of the parent form's label to indicate
		// that the query was actually run
		$objParentObject->lblStatus->Text = "The query was executed";        
	}
}

PersistentExampleForm::Run('PersistentExampleForm');

?>