<?php
	require('../../../../includes/configuration/prepend.inc.php');

	class ExampleForm extends QForm {
		/** @var QSimpleTable */
		protected $tblPersons;

		/** @var QLabel */
		protected $lblSelection;

		protected function Form_Create() {
			// Define the DataGrid
			$this->tblPersons = new QDataTable($this);
			$this->tblPersons->CssClass = 'simple_table';
			$this->tblPersons->RowCssClass = 'odd_row';
			$this->tblPersons->AlternateRowCssClass = 'even_row';
			$this->tblPersons->HeaderRowCssClass = 'header_row';
			$this->tblPersons->UseAjax = true;
			$this->tblPersons->Processing = true;
			$this->tblPersons->PaginationType = 'full_numbers';

			// Define Columns

			// The first column demonstrates the use of Closures (for PHP 5.3+), or user defined function (for PHP 5.2 and below)
			$objColumn = new QSimpleTableClosureColumn('Full Name', 'ExampleForm::getFullName');
			$objColumn->OrderByClause = QQ::OrderBy(QQN::Person()->FirstName, QQN::Person()->LastName);
			$objColumn->ReverseOrderByClause = QQ::OrderBy(QQN::Person()->FirstName, 'desc', QQN::Person()->LastName, 'desc');
			$this->tblPersons->AddColumn($objColumn);

			// The second column demonstrates using a property name for fetching the data
			$objColumn = $this->tblPersons->CreatePropertyColumn('Last Name', 'LastName');
			$objColumn->OrderByClause = QQ::OrderBy(QQN::Person()->LastName);
			$objColumn->ReverseOrderByClause = QQ::OrderBy(QQN::Person()->LastName, 'desc');

			// Specify the local Method which will actually bind the data source to the datagrid.
			// In order to not over-bloat the form state, the datagrid will use the data source only when rendering itself,
			// and then it will proceed to remove the data source from memory.  Because of this, you will need to define
			// a "data binding" method which will set the datagrid's data source.  You specify the name of the method
			// here.  The framework will be responsible for calling your data binding method whenever the datagrid wants
			// to render itself.
			$this->tblPersons->SetDataBinder('tblPersons_Bind');

			// Row click handling
			$this->tblPersons->AddAction(new QDataTable_RowClickEvent(), new QAjaxAction("tableRow_Click"));

			$this->lblSelection = new QLabel($this);
		}

		protected function tblPersons_Bind() {
			// We load the data source, and set it to the datagrid's DataSource parameter
			$objCond = QQ::All();
			$filter = $this->tblPersons->Filter;
			if ($filter) {
				$objCond = QQ::OrCondition(QQ::Like(QQN::Person()->FirstName, $filter .'%'), QQ::Like(QQN::Person()->LastName, $filter .'%'));
			}
			$this->tblPersons->TotalItemCount = Person::CountAll();
			$this->tblPersons->FilteredItemCount = Person::QueryCount($objCond);
			$this->tblPersons->DataSource = Person::QueryArray($objCond, $this->tblPersons->Clauses);
		}

		public static function getFullName($item) {
			return 'Full Name is "' . $item->FirstName . ' ' . $item->LastName . '"';
		}

		public function tableRow_Click($strFormId, $strControlId, $objParameter) {
			// $objParameter is an array containing the values from the cells of the row clicked
			$this->lblSelection->Text = $objParameter[0];
		}
	}

	ExampleForm::Run('ExampleForm');
?>
