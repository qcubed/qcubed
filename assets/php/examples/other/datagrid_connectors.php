<?php
require_once('../qcubed.inc.php');

// Define the Qform with all our Qcontrols
class ExamplesForm extends QForm {

	// Local declarations of the DataGrid
	protected $dtgProjects;
	protected $pxyExample;
	protected $objAdditionalConditions;
	protected $objAdditionalClauses;

	protected function Form_Create() {
		// Define the DataGrid -- note that the DataGrid Connector is a DataGrid, itself --
		// so let's just use it as a datagrid
		$this->dtgProjects = new ProjectList($this);
		$this->dtgProjects->SetDataBinder('DefaultDataBinder', $this);

		// Only show projects whose status is "open"
		$this->objAdditionalConditions = QQ::Equal(QQN::Project()->ProjectStatusTypeId, ProjectStatusType::Open);

		//expand on the ManagerPerson's login, since we're displaying it
		$this->objAdditionalClauses = array(
			QQ::Expand(QQN::Project()->ManagerPerson),
			QQ::Expand(QQN::Project()->ManagerPerson->Login)
		);

		// DataBinding is already configured -- so we do not need to worry about it
		// But remember that dtgProjects is just a regular datagrid, as well
		// So we can configure as we see fit, e.g. adding pagination or styling
		$this->dtgProjects->Paginator = new QPaginator($this->dtgProjects);
		$this->dtgProjects->ItemsPerPage = 6;
		$this->dtgProjects->AlternateRowCssClass = 'alternate';

		// All we need to do is to utilize the ProjectDataGrid built-in functionality
		// to create, define and setup the various columns that WE choose, in the order
		// that WE want.
		$this->dtgProjects->CreateNodeColumn('Name', QQN::Project()->Name);
		$this->dtgProjects->CreateNodeColumn('StartDate', QQN::Project()->StartDate);
		$this->dtgProjects->CreateNodeColumn("EndDate", QQN::Project()->EndDate);

		// We can easily add columns from linked/related tables.  However, to do this
		// you MUST use a QQuery node descriptor.  No string-based properties allowed.
		// Bonus: the DataGrid Connector will even automatically add sorting for columns in related tables.
		$colUsername = $this->dtgProjects->CreateNodeColumn('Username', QQN::Project()->ManagerPerson->Login->Username);

		// And remember, since it's a regular datagrid with regular columns,
		// we can stylize as we see fit
		$colUsername->CellParamsCallback = [$this, 'GetUserNameCellParams'];
		$colUsername->Name = 'Manager\'s Username';

		$colStatus = $this->dtgProjects->CreateNodeColumn('ProjectStatusType', QQN::Project()->ProjectStatusType);
		$colStatus->HtmlEntities = false;
		$colStatus->Format = '<strong>%s</strong>';

		$this->pxyExample = new QControlProxy($this);
		$this->pxyExample->AddAction(new QClickEvent(), new QAjaxAction('pxyExample_Click'));
	}

	public function GetUserNameCellParams(Project $item){
		return [
			'style' => 'background-color: #cef;'
		];
	}

	public function DefaultDataBinder() {
		$this->dtgProjects->BindData($this->objAdditionalConditions, $this->objAdditionalClauses);
	}


	// Instead of actually redirecting you to an example edit project page, we'll
	// use a DisplayAlert() call as a stub function.  Hopefully, you get the idea. =)
	protected function pxyExample_Click($strFormId, $strControlId, $strParameter) {
		QApplication::DisplayAlert('Pretending to edit Project #' . $strParameter);
	}
}

// Run the Form we have defined
ExamplesForm::Run('ExamplesForm');
?>
