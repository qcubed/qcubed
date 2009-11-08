<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends QForm {
		// Declare the DataGrid
		protected $dtgPersons;

		protected function Form_Create() {
			// Define the DataGrid
			$this->dtgPersons = new QDataGrid($this);			
			$this->dtgPersons->ShowFilter = true;
			
			// To create pagination, we will create a new paginator, and specify the datagrid
			// as the paginator's parent.  (We do this because the datagrid is the control
			// who is responsible for rendering the paginator, as opposed to the form.)
			$objPaginator = new QPaginator($this->dtgPersons);
			$this->dtgPersons->Paginator = $objPaginator;
			
			// Now, with a paginator defined, we can set up some additional properties on
			// the datagrid.  For purposes of this example, let's make the datagrid show
			// only 5 items per page.
			$this->dtgPersons->ItemsPerPage = 20;

			// Define Columns
			$idCol = new QDataGridColumn('Person ID', '<?= $_ITEM->Id ?>',
				'Width=100',
				array(
					  'OrderByClause' => QQ::OrderBy(QQN::Person()->Id),
					  'ReverseOrderByClause' => QQ::OrderBy(QQN::Person()->Id, false)));
			$idCol->Filter = QQ::Equal(QQN::Person()->Id, null);
			$idCol->FilterType = QFilterType::TextFilter;
			$idCol->FilterBoxSize = 3; //note that due to the CSS applied to the examples, this doesn't do anything
			$this->dtgPersons->AddColumn($idCol);
			
			$fNameCol = new QDataGridColumn('First Name', '<?= $_ITEM->FirstName ?>',
				'Width=200',
				array(
					'OrderByClause' => QQ::OrderBy(QQN::Person()->FirstName),
					'ReverseOrderByClause' => QQ::OrderBy(QQN::Person()->FirstName, false)));
			$fNameCol->Filter = QQ::Like(QQN::Person()->FirstName, null);
			$fNameCol->FilterPrefix = $fNameCol->FilterPostfix = '%';
			$fNameCol->FilterType = QFilterType::TextFilter;
			$this->dtgPersons->AddColumn($fNameCol);
			
			$lNameCol = new QDataGridColumn('Last Name', '<?= $_ITEM->LastName ?>',
				'Width=200',
				array(
					  'OrderByClause' => QQ::OrderBy(QQN::Person()->LastName),
					  'ReverseOrderByClause' => QQ::OrderBy(QQN::Person()->LastName, false)));
			QQN::Person()->LastName->SetFilteredDataGridColumnFilter($lNameCol);
			$this->dtgPersons->AddColumn($lNameCol);
		
			// Let's default the sorting to the last name column (column #2)
			$this->dtgPersons->SortColumnIndex = 2;

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

			// Because browsers will apply different styles/colors for LINKs
			// We must explicitly define the ForeColor for the HeaderLink.
			// The header row turns into links when the column can be sorted.
			$objStyle = $this->dtgPersons->HeaderLinkStyle;
			$objStyle->ForeColor = 'white';
		}

		protected function dtgPersons_Bind() {
			// We must first let the datagrid know how many total items there are
			$this->dtgPersons->TotalItemCount = Person::QueryCount($this->dtgPersons->Conditions);

			// Next, we must be sure to load the data source, passing in the datagrid's
			// limit info into our loadall method.
			$this->dtgPersons->DataSource = Person::QueryArray($this->dtgPersons->Conditions,
			QQ::Clause(
				$this->dtgPersons->OrderByClause,
				$this->dtgPersons->LimitClause
			));
		}
	}

	ExampleForm::Run('ExampleForm');
?>
