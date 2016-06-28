<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {

	// Declare the DataGrid
	protected $dtgPersons;
	protected $txtFirstName;
	protected $txtLastName;
	protected $btnNew;
	protected $timer;
	/** @var  QControlProxy */
	protected $pxyDelete;

	protected function Form_Create() {
		// Define the DataGrid
		$this->dtgPersons = new QDataGrid($this);
		$this->dtgPersons->CellSpacing = 0;

		// Define Columns
		$this->dtgPersons->AddColumn(new QDataGridColumn('First Name', '<?= $_ITEM->FirstName ?>'));
		$this->dtgPersons->AddColumn(new QDataGridColumn('Last Name', '<?= $_ITEM->LastName ?>'));

		// Specify the local Method which will actually bind the data source to the datagrid.
		$this->dtgPersons->SetDataBinder('dtgPersons_Bind');

		// By default, the examples database uses the qc_watchers table to record when a something in the database has changed.
		// To configure this, including changing the table name, or even using a shared caching mechanism like
		// APC or Memcached, modify the QWatcher class in project/includes/controls
		
		// Tell the datagrid to watch the Person table.
		$this->dtgPersons->Watch(QQN::Person());

		// Create a timer to periodically check whether another user has changed the database. Depending on your
		// application, you might not need to do this, as any activity the user does to a control will also check.

		$this->timer = new QJsTimer($this, 500, true);
		$this->timer->AddAction(new QTimerExpiredEvent(), new QAjaxAction());

		// Update the styles of all the rows, or for just specific rows
		$objStyle = $this->dtgPersons->RowStyle;
		$objStyle->BackColor = '#efefff';
		$objStyle->FontSize = 12;

		$objStyle = $this->dtgPersons->AlternateRowStyle;
		$objStyle->BackColor = '#ffffff';

		$objStyle = $this->dtgPersons->HeaderRowStyle;
		$objStyle->ForeColor = '#780000';
		$objStyle->BackColor = '#ffffff';

		$this->txtFirstName = new QTextBox($this);
		$this->txtLastName = new QTextBox($this);
		$this->btnNew = new QButton($this);
		$this->btnNew->Text = 'Add';
		$this->btnNew->AddAction (new QClickEvent(), new QAjaxAction('btnNew_Click'));

		// Create a proxy control to handle clicking for a delete
		$this->pxyDelete = new QControlProxy($this);
		$this->pxyDelete->AddAction (new QClickEvent(), new QAjaxAction ('delete_Click'));
	}

	protected function dtgPersons_Bind() {
		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->dtgPersons->DataSource = Person::LoadAll();
	}
	protected function btnNew_Click($strFormId, $strControlId, $strParameter) {
		$objPerson = new Person();
		$objPerson->FirstName = $this->txtFirstName->Text;
		$objPerson->LastName = $this->txtLastName->Text;
		$objPerson->Save();
	}
}

ExampleForm::Run('ExampleForm');
?>