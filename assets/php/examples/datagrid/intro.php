<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {

	/** @var  QHtmlTable */
	protected $dtgPersons;

	protected function Form_Create() {
		// Define the DataGrid
		$this->dtgPersons = new QHtmlTable($this);

		// Define Columns
		// This first example uses a callback to draw the column, which is the most versatile way of drawing a column.
		// In other examples, we will describe other column types that let you draw some standard column types.

		$col = $this->dtgPersons->CreateCallableColumn('First Name', [$this, 'dtgPerson_FirstName_Render']);
		$col->CellStyler->Width = 200;	// style for the 'td' tag of the column

		$col = $this->dtgPersons->CreateCallableColumn('Last Name', [$this, 'dtgPerson_LastName_Render']);
		$col->CellStyler->FontBold = true; // style for the 'td' tag of the column

		// Specify the local Method which will actually bind the data source to the datagrid.
		// In order to not over-bloat the form state, the datagrid will use the data source only when rendering itself,
		// and then it will proceed to remove the data source from memory.  Because of this, you will need to define
		// a "data binding" method which will set the datagrid's data source.  You specify the name of the method
		// here.  The framework will be responsible for calling your data binding method whenever the datagrid wants
		// to render itself.
		$this->dtgPersons->SetDataBinder('dtgPersons_Bind');

		// Update the styles of all the rows, or for just specific rows
		// (e.g. you can specify a specific style for the header row or for alternating rows)
		// Note that styles are hierarchical and inherit from each other.  For example, the default RowStyle
		// sets the FontSize as 12px, and because that attribute is not overridden in AlternateRowStyle
		// or HeaderRowStyle, both those styles will use the 12px Font Size.

		// While there are a variety of ways to style tables QCubed, the easiest and most versatile is to use css
		// classes. These are defined at the top of the intro.tpl.php file in this example.
		$this->dtgPersons->HeaderRowCssClass = 'header-row';
		$this->dtgPersons->RowCssClass = 'row';
		$this->dtgPersons->AlternateRowCssClass = 'alt-row';
	}

	protected function dtgPersons_Bind() {
		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->dtgPersons->DataSource = Person::LoadAll();
	}

	// Callbacks must be defined public, since the datagrid is calling them
	public function dtgPerson_FirstName_Render(Person $objPerson) {
		return "First Name is {$objPerson->FirstName}";
	}

	public function dtgPerson_LastName_Render(Person $objPerson) {
		return "Last Name {$objPerson->LastName}";
	}

}

ExampleForm::Run('ExampleForm');
?>