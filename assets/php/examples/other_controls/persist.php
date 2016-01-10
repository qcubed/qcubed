<?php require_once('../qcubed.inc.php');

class PersistentExampleForm extends QForm {
	// We will persist this control in the $_SESSION
	protected $ddnProjectPicker1;
	protected $ddnProjectPicker2;
	protected $fld1;
	protected $fld2;

	protected $btnReload;

	protected function Form_Create() {

		$this->ddnProjectPicker1 = new ProjectPickerListBox ($this);
		$this->ddnProjectPicker2 = new ProjectPickerListBox ($this);
		$this->ddnProjectPicker2->SaveState = true;

		$this->fld1 = new QTextBox($this);
		$this->fld1->Text = 'Change Me';
		$this->fld2 = new QTextBox($this);
		$this->fld2->Text = 'Change Me';
		$this->fld2->SaveState = true;

		$this->btnReload = new QButton($this);
		$this->btnReload->Text = 'Reload the Page';
		// Any action will trigger the communication from the client to the server to record changes on the client side.
		$this->btnReload->AddAction (new QClickEvent(), new QAjaxAction('btnReload_Click'));
	}

	protected function btnReload_Click() {
		QApplication::Redirect('persist.php');
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
	public function __construct($objParentObject) {
		parent::__construct($objParentObject);

		$projects = Project::QueryArray(
			QQ::All(),
			QQ::OrderBy(QQN::Project()->Name)
		);

		foreach ($projects as $project) {
			$this->AddItem($project->Name, $project->Id);
		}
	}
}

PersistentExampleForm::Run('PersistentExampleForm');

?>
