<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {
	// Declare the DataGrids
	protected $dtgProjects;
	protected $dtgCustom;

	protected function Form_Create() {
		$this->dtgProjects_Create();
		$this->dtgCustom_Create();
	}

	protected function dtgProjects_Create() {
		// Define the DataGrid
		$this->dtgProjects = new QDataGrid($this);
		$this->dtgProjects->ShowFilter = true;

		// To create pagination, we will create a new paginator, and specify the datagrid
		// as the paginator's parent.  (We do this because the datagrid is the control
		// who is responsible for rendering the paginator, as opposed to the form.)
		$objPaginator = new QPaginator($this->dtgProjects);
		$this->dtgProjects->Paginator = $objPaginator;

		// Now, with a paginator defined, we can set up some additional properties on
		// the datagrid.  For purposes of this example, let's make the datagrid show
		// only 5 items per page.
		$this->dtgProjects->ItemsPerPage = 20;

		// Define Columns

		//Project Name
		$colName = new QDataGridColumn('Project', '<?= $_ITEM->Name?>');
		$colName->OrderByClause = QQ::OrderBy(QQN::Project()->Name);
		$colName->ReverseOrderByClause = QQ::OrderBy(QQN::Project()->Name, false);
		$this->dtgProjects->AddColumn($colName);

		//Project type - filtered
		$colType = new QDataGridColumn('Type', '<?= ProjectStatusType::ToString($_ITEM->ProjectStatusTypeId) ?>');
		$colType->OrderByClause = QQ::OrderBy(QQN::Project()->ProjectStatusTypeId);
		$colType->ReverseOrderByClause = QQ::OrderBy(QQN::Project()->ProjectStatusTypeId, false);
		$colType->FilterType = QFilterType::ListFilter;
		foreach(ProjectStatusType::$NameArray as $value=>$name)
			$colType->FilterAddListItem($name, QQ::Equal(QQN::Project()->ProjectStatusTypeId,$value));
		$this->dtgProjects->AddColumn($colType);

		//Manager First Name
		$colFName = new QDataGridColumn('First Name', '<?= $_ITEM->ManagerPerson->FirstName ?>');
		$colFName->OrderByClause = QQ::OrderBy(QQN::Project()->ManagerPerson->FirstName);
		$colFName->ReverseOrderByClause = QQ::OrderBy(QQN::Project()->ManagerPerson->FirstName, false);
		$this->dtgProjects->AddColumn($colFName);

		//Manager Last Name - filtered, only show with enabled logins
		$colLName = new QDataGridColumn('Last Name', '<?= $_ITEM->ManagerPerson->LastName ?>');
		$colLName->OrderByClause = QQ::OrderBy(QQN::Project()->ManagerPerson->LastName);
		$colLName->ReverseOrderByClause = QQ::OrderBy(QQN::Project()->ManagerPerson->LastName, false);
		QQN::Project()->ManagerPerson->LastName->SetFilteredDataGridColumnFilter($colLName);
		$colLName->FilterConstant = QQ::Equal(QQN::Project()->ManagerPerson->Login->IsEnabled, true);
		$this->dtgProjects->AddColumn($colLName);

		// Specify the Datagrid's Data Binder method
		$this->dtgProjects->SetDataBinder('dtgProjects_Bind');


		/**************************/
		// Make the DataGrid look nice
		$objStyle = $this->dtgProjects->RowStyle;
		$objStyle->FontSize = 12;

		$objStyle = $this->dtgProjects->AlternateRowStyle;
		$objStyle->BackColor = '#eaeaea';

		$objStyle = $this->dtgProjects->HeaderRowStyle;
		$objStyle->ForeColor = 'white';
		$objStyle->BackColor = '#000066';

		// Because browsers will apply different styles/colors for LINKs
		// We must explicitly define the ForeColor for the HeaderLink.
		// The header row turns into links when the column can be sorted.
		$objStyle = $this->dtgProjects->HeaderLinkStyle;
		$objStyle->ForeColor = 'white';
	}

	protected function dtgProjects_Bind() {
		// We must first let the datagrid know how many total items there are
		$this->dtgProjects->TotalItemCount = Project::QueryCount($this->dtgProjects->Conditions);

		// Next, we must be sure to load the data source, passing in the datagrid's
		// limit info into our loadall method.
		$this->dtgProjects->DataSource = Project::QueryArray($this->dtgProjects->Conditions,
				QQ::Clause(
					$this->dtgProjects->OrderByClause,
					$this->dtgProjects->LimitClause
					));
	}

	protected function dtgCustom_Create() {
		$this->dtgCustom = new QDataGrid($this);
		$this->dtgCustom->ShowFilter = true;

		$this->dtgCustom->SetDataBinder('dtgCustom_Bind');

		$colName = new QDataGridColumn('Person', '<?= $_ITEM["FirstName"] . " " . $_ITEM["LastName"] ?>');
		$this->dtgCustom->AddColumn($colName);

		$colAddresses = new QDataGridColumn('# of Addresses', '<?= $_ITEM["AddressCount"] ?>');
		$colAddresses->FilterByCommand = array('column'=>'AddressCount');
		$this->dtgCustom->AddColumn($colAddresses);

		/**************************/
		// Make the DataGrid look nice
		$objStyle = $this->dtgCustom->RowStyle;
		$objStyle->FontSize = 12;

		$objStyle = $this->dtgCustom->AlternateRowStyle;
		$objStyle->BackColor = '#eaeaea';

		$objStyle = $this->dtgCustom->HeaderRowStyle;
		$objStyle->ForeColor = 'white';
		$objStyle->BackColor = '#000066';

		// Because browsers will apply different styles/colors for LINKs
		// We must explicitly define the ForeColor for the HeaderLink.
		// The header row turns into links when the column can be sorted.
		$objStyle = $this->dtgCustom->HeaderLinkStyle;
		$objStyle->ForeColor = 'white';
	}

	protected function dtgCustom_Bind()	{
		//Set up our normal query
		$sql = 'SELECT
					p.first_name as FirstName,
					p.last_name as LastName,
					count(a.id) as AddressCount
				FROM person as p
					LEFT JOIN address as a on a.person_id = p.id
				GROUP BY p.id';

		//apply any filters the user has set
		foreach($this->dtgCustom->FilterInfo as $filter) {
			if($filter['column'] == 'AddressCount') {
				$sql .= ' HAVING count(a.id) = '. $filter['value'];
			}
		}

		//and run the query, using it as the datasource for the grid
		$objDatabase = Person::GetDatabase();
		$objDbResult = $objDatabase->Query($sql);

		$array = array();
		while ($mixRow = $objDbResult->FetchArray()) {
			$array[] = $mixRow;
		}

		$this->dtgCustom->DataSource = $array;
	}
}

ExampleForm::Run('ExampleForm');
?>
