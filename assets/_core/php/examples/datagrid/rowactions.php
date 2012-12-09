<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends QForm {
		// Declare the DataGrid
		protected $dtgPersons;

		protected function Form_Create() {
			// Define the DataGrid
			$this->dtgPersons = new QDataGrid($this, 'dtgPersons');
			$this->dtgPersons->CellPadding = 5;
			$this->dtgPersons->CellSpacing = 0;
			
			// Define Columns
			// We will use $_ITEM, $_CONTROL and $_FORM to show how you can make calls to the Person object
			// being itereated ($_ITEM), the QDataGrid itself ($_CONTROL), and the QForm itself ($_FORM).
			$this->dtgPersons->AddColumn(new QDataGridColumn('Row #', '<?= ($_CONTROL->CurrentRowIndex + 1) ?>'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('First Name', '<?= $_ITEM->FirstName ?>', 'Width=200'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('Last Name', '<?= $_ITEM->LastName ?>', 'Width=200'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('Full Name', '<?= $_FORM->DisplayFullName($_ITEM) ?>', 'Width=300'));

			// Specify the Datagrid's Data Binder method
			$this->dtgPersons->SetDataBinder('dtgPersons_Bind');

			// Make the DataGrid look nice
			$objStyle = $this->dtgPersons->RowStyle;
			$objStyle->FontSize = 12;

			$objStyle = $this->dtgPersons->AlternateRowStyle;
			$objStyle->BackColor = '#eaeaea';

			$objStyle = $this->dtgPersons->HeaderRowStyle;
			$objStyle->ForeColor = 'white';
			$objStyle->BackColor = '#000066';

			// Higlight the datagrid rows when mousing over them
			$this->dtgPersons->AddRowAction(new QMouseOverEvent(), new QCssClassAction('selectedStyle'));
			$this->dtgPersons->AddRowAction(new QMouseOutEvent(), new QCssClassAction());

			// Add a click handler for the rows. 
			// We can use $_CONTROL->CurrentRowIndex to pass the row index to dtgPersonsRow_Click()
			// or $_ITEM->Id to pass the object's id, or any other data grid variable
			$this->dtgPersons->RowActionParameterHtml = '<?= $_ITEM->Id ?>';
			$this->dtgPersons->AddRowAction(new QClickEvent(), new QAjaxAction('dtgPersonsRow_Click'));
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

		public function dtgPersonsRow_Click($strFormId, $strControlId, $strParameter) {
			$intPersonId = intval($strParameter);
			
			$objPerson = Person::Load($intPersonId);
			
			QApplication::ExecuteJavascript("alert('You clicked on a person with ID #" . $intPersonId .
				": " . $objPerson->FirstName . " " . $objPerson->LastName . "');");
		}
	}

	ExampleForm::Run('ExampleForm');
?>
