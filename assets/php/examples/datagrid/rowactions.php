<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {

	// Declare the DataGrid
	protected $dtgPersons;

	protected function Form_Create() {
		// Define the DataGrid
		$this->dtgPersons = new QDataGrid($this, 'dtgPersons');

		// Style this with a QCubed built-in style that will highlight the row hovered over.
		$this->dtgPersons->AddCssClass('clickable-rows');

		// Define Columns
		$this->dtgPersons->CreateNodeColumn('First Name', QQN::Person()->FirstName);
		$this->dtgPersons->CreateNodeColumn('Last Name', QQN::Person()->LastName);
		
		// Specify the Datagrid's Data Binder method
		$this->dtgPersons->SetDataBinder('dtgPersons_Bind');

		// Attach a callback to the table that will create an attribute in the row's tr tag that will be the id of data row in the database
		$this->dtgPersons->RowParamsCallback = [$this, 'dtgPersons_GetRowParams'];

		// Add an action that will detect a click on the row, and return the html data value that was created by RowParamsCallback
		$this->dtgPersons->AddAction(new QCellClickEvent(0, null, QCellClickEvent::RowDataValue('value')), new QAjaxAction('dtgPersonsRow_Click'));
	}

	// DisplayFullName will be called by the DataGrid on each row, whenever it tries to render
	// the Full Name column.  Note that we take in the $objPerson as a Person parameter.  Also
	// note that DisplayFullName is a PUBLIC function -- because it will be called by the QDataGrid class.
	public function DisplayFullName(Person $objPerson) {
		$strToReturn = sprintf('%s, %s', $objPerson->LastName, $objPerson->FirstName);
		return $strToReturn;
	}

	protected function dtgPersons_Bind() {
		// We must be sure to load the data source
		$this->dtgPersons->DataSource = Person::LoadAll();
	}

	public function dtgPersons_GetRowParams($objRowObject, $intRowIndex) {
		$strKey = $objRowObject->PrimaryKey();
		$params['data-value'] = $strKey;
		return $params;
	}


	public function dtgPersonsRow_Click($strFormId, $strControlId, $strParameter) {
		$intPersonId = intval($strParameter);

		$objPerson = Person::Load($intPersonId);

		QApplication::DisplayAlert("You clicked on a person with ID #" . $intPersonId .
				": " . $objPerson->FirstName . " " . $objPerson->LastName);
	}
}

ExampleForm::Run('ExampleForm');
?>