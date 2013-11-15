<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {

	/** @var QSimpleTable */
	protected $tblPersons;

	/** @var QSimpleTable */
	protected $tblReport;

	protected function Form_Create() {
		// Define the DataGrid
		$this->tblPersons = new QSimpleTable($this);
		$this->tblPersons->CssClass = 'simple_table';
		$this->tblPersons->RowCssClass = 'odd_row';
		$this->tblPersons->AlternateRowCssClass = 'even_row';
		$this->tblPersons->HeaderRowCssClass = 'header_row';

		// Define Columns
		// The first column demonstrates the use of Closures (for PHP 5.3+), or user defined function (for PHP 5.2 and below)
		if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
			$objColumn = new QSimpleTableClosureColumn('Full Name',
							function($item) {
								return 'Full Name is "' . $item->FirstName . ' ' . $item->LastName . '"';
							});
		} else {
			$objColumn = new QSimpleTableClosureColumn('Full Name', 'ExampleForm::getFullName');
		}
		$this->tblPersons->AddColumn($objColumn);

		// The second column demonstrates using a property name for fetching the data
		$this->tblPersons->CreatePropertyColumn('Last Name', 'LastName');

		// Specify the local Method which will actually bind the data source to the datagrid.
		// In order to not over-bloat the form state, the datagrid will use the data source only when rendering itself,
		// and then it will proceed to remove the data source from memory.  Because of this, you will need to define
		// a "data binding" method which will set the datagrid's data source.  You specify the name of the method
		// here.  The framework will be responsible for calling your data binding method whenever the datagrid wants
		// to render itself.
		$this->tblPersons->SetDataBinder('tblPersons_Bind');

		$this->tblReport = new QSimpleTable($this);
		$tbl = $this->tblReport;
		$tbl->CssClass = 'simple_table';
		$tbl->RowCssClass = 'odd_row';
		$tbl->AlternateRowCssClass = 'even_row';
		$tbl->HeaderRowCssClass = 'header_row';

		// "named" index columns
		$tbl->CreateIndexedColumn("Year", 0);
		$tbl->CreateIndexedColumn("Model", 1);
		// "unnamed" index columns
		$tbl->CreateIndexedColumn();
		$tbl->CreateIndexedColumn();
		// index columns for associative arrays
		$tbl->CreateIndexedColumn("Count", "#count");

		$tbl->SetDataBinder('tblReport_Bind');
	}

	protected function tblPersons_Bind() {
		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->tblPersons->DataSource = Person::LoadAll();
	}

	public static function getFullName($item) {
		return 'Full Name is "' . $item->FirstName . ' ' . $item->LastName . '"';
	}

	protected function tblReport_Bind() {
		// build the entire datasource as an array of arrays.
		$csv = '1997,Ford,E350,"ac, abs, moon",3000.00
1999,Chevy,"Venture ""Extended Edition""","",4900.00
1999,Chevy,"Venture ""Extended Edition, Very Large""","",5000.00
1996,Jeep,Grand Cherokee,"MUST SELL!';
		$data = str_getcsv($csv, "\n");
		foreach ($data as &$row) {
			$row = str_getcsv($row, ",");
			$row["#count"] = count($row);
		}
		$this->tblReport->DataSource = $data;
	}
}

ExampleForm::Run('ExampleForm');
?>