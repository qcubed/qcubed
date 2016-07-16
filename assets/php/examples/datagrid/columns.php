<?php
require_once('../qcubed.inc.php');

class ComplexColumn extends QHtmlTableIndexedColumn {
	public function RenderHeaderCell() {
		if ($this->objParentTable->CurrentHeaderRowIndex == 0 &&
				$this->Index > 1) {
		return null; // don't draw, first col is a span
	} else {
			return parent::RenderHeaderCell ();
		}
	}

	public function FetchHeaderCellValue() {
		if ($this->objParentTable->CurrentHeaderRowIndex == 0 &&
				$this->Index == 1) {
			return 'Year';
		}
		return parent::FetchHeaderCellValue();
	}


	public function GetHeaderCellParams() {
		$a = parent::GetHeaderCellParams();
		if ($this->Index == 0) {
			//make background white
			$a['style'] = 'background-color: white';
		}
		if ($this->ParentTable->CurrentHeaderRowIndex == 0) {
			if ($this->Index == 1) {
				$a['colspan'] = 3;
			}
		}
		return $a;
	}
}


class ExampleForm extends QForm {

	/** @var QHtmlTable */
	protected $tblPersons;

	/** @var QHtmlTable */
	protected $tblReport;

	/** @var QHtmlTable */
	protected $tblComplex;

	protected function Form_Create() {
		// Define the DataGrid
		$this->tblPersons = new QHtmlTable($this);
		$this->tblPersons->CssClass = 'simple_table';
		$this->tblPersons->RowCssClass = 'odd_row';
		$this->tblPersons->AlternateRowCssClass = 'even_row';
		$this->tblPersons->HeaderRowCssClass = 'header_row';

		// Define Columns
		// This demonstrates how to first create a column, and then add it to the table
		$objColumn = new QHtmlTableCallableColumn('Full Name', [$this, 'getFullName']);
		$this->tblPersons->AddColumn($objColumn);

		// The second column demonstrates using a property name for fetching the data
		// This also demonstrates how to create a column and add it to the table all at once, using the CreatePropertyColumn shortcut
		$this->tblPersons->CreatePropertyColumn('First Name', 'FirstName');

		// The second column demonstrates using a node column for fetching the data
		$this->tblPersons->CreateNodeColumn('Last Name', QQN::Person()->LastName);

		// Specify the local Method which will actually bind the data source to the datagrid.
		// In order to not over-bloat the form state, the datagrid will use the data source only when rendering itself,
		// and then it will proceed to remove the data source from memory.  Because of this, you will need to define
		// a "data binding" method which will set the datagrid's data source.  You specify the name of the method
		// here.  The framework will be responsible for calling your data binding method whenever the datagrid wants
		// to render itself.
		$this->tblPersons->SetDataBinder('tblPersons_Bind');

		$this->tblReport = new QHtmlTable($this);
		$this->tblReport->CssClass = 'simple_table';
		$this->tblReport->RowCssClass = 'odd_row';
		$this->tblReport->AlternateRowCssClass = 'even_row';
		$this->tblReport->HeaderRowCssClass = 'header_row';

		// "named" index columns
		$this->tblReport->CreateIndexedColumn("Year", 0);
		$this->tblReport->CreateIndexedColumn("Model", 1);
		// "unnamed" index columns
		$this->tblReport->CreateIndexedColumn();
		$this->tblReport->CreateIndexedColumn();
		// index columns for associative arrays
		$this->tblReport->CreateIndexedColumn("Count", "#count");

		$this->tblReport->SetDataBinder('tblReport_Bind');

		$this->tblComplex = new QHtmlTable($this);
		$this->tblComplex->CssClass = 'simple_table';
		$this->tblComplex->RowCssClass = 'odd_row';
		$this->tblComplex->AlternateRowCssClass = 'even_row';
		$this->tblComplex->HeaderRowCssClass = 'header_row';

		// "named" index columns
		$col = $this->tblComplex->AddColumn (new ComplexColumn("", "Name"));
		$col->RenderAsHeader = true;
		$this->tblComplex->AddColumn (new ComplexColumn("2000", 1));
		$this->tblComplex->AddColumn (new ComplexColumn("2001", 2));
		$this->tblComplex->AddColumn (new ComplexColumn("2002", 3));
		$this->tblComplex->HeaderRowCount = 2;

		$this->tblComplex->SetDataBinder('tblComplex_Bind');
	}

	protected function tblPersons_Bind() {
		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->tblPersons->DataSource = Person::LoadAll();
	}

	public function getFullName($item) {
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
	protected function tblComplex_Bind() {
		$a[] = array ('Name' => 'Income', 1=>1000, 2=>2000, 3=>1500);
		$a[] = array ('Name' => 'Expense', 1=>500, 2=>700, 3=>2100);
		$a[] = array ('Name' => 'Net', 1=>1000-500, 2=>2000-700, 3=>1500-2100);

		$this->tblComplex->DataSource = $a;
	}

}

ExampleForm::Run('ExampleForm');
?>