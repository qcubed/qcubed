<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {

	// Declare the DataGrid
	protected $dtgPersons;

	protected function Form_Create() {
		// Define the DataGrid
		$this->dtgPersons = new QDataGrid($this);

		// Using Ajax for Pagination
		$this->dtgPersons->UseAjax = true;

		// To create pagination, we will create a new paginator, and specify the datagrid
		// as the paginator's parent.  (We do this because the datagrid is the control
		// who is responsible for rendering the paginator, as opposed to the form.)
		$objPaginator = new QPaginator($this->dtgPersons);
		$this->dtgPersons->Paginator = $objPaginator;

		// Now, with a paginator defined, we can set up some additional properties on
		// the datagrid.  For purposes of this example, let's make the datagrid show
		// only 5 items per page.
		$this->dtgPersons->ItemsPerPage = 5;

		// Define Columns
		$col = $this->dtgPersons->CreateNodeColumn('Person ID', QQN::Person()->Id);
		$col->CellStyler->Width = 100;
		$col = $this->dtgPersons->CreateNodeColumn('First Name', [QQN::Person()->FirstName, QQN::Person()->LastName]);
		$col->CellStyler->Width = 200;
		$col = $this->dtgPersons->CreateNodeColumn('Last Name', [QQN::Person()->LastName, QQN::Person()->LastName]);
		$col->CellStyler->Width = 200;

		// Let's pre-default the sorting by last name (column index #2)
		$this->dtgPersons->SortColumnIndex = 2;

		// Specify the Datagrid's Data Binder method
		$this->dtgPersons->SetDataBinder('dtgPersons_Bind');
	}

	protected function dtgPersons_Bind() {
		// We must first let the datagrid know how many total items there are
		// IMPORTANT: Do not pass a limit clause here to CountAll
		$this->dtgPersons->TotalItemCount = Person::CountAll();

		// Ask the datagrid for the sorting information for the currently active sort column
		$clauses[] = $this->dtgPersons->OrderByClause;

		// Ask the datagrid for the Limit clause that will limit what portion of the data we will get from the database
		$clauses[] = $this->dtgPersons->LimitClause;

		// Next, we must be sure to load the data source, passing in the datagrid's
		// limit info into our loadall method.
		$this->dtgPersons->DataSource = Person::LoadAll($clauses);
	}

}

ExampleForm::Run('ExampleForm');
?>