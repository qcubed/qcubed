<?php
/**
 * This is a base class to support classes that are derived from QHtmlTable. The methods here support the use
 * of QHtmlTable derived classes as a list connector, something that displays a list of records from a database,
 * and optionally allows the user to do CRUD operations on individual records.
 */

abstract class QHtmlTable_CodeGenerator extends QControl_CodeGenerator implements QDataList_CodeGenerator_Interface {

	/**
	 * dtg stands for "DataGrid", a QCubed historical name for tables displaying data. Override if you want something else.
	 * @param string $strPropName
	 * @return string
	 */
	public function VarName($strPropName) {
		return 'dtg' . $strPropName;
	}

	
	/****
	 * CONNECTOR GEN
	 * The following functions generate the ListGen code that will go into the generated/connector_base directory
	 *******/

	/**
	 * Generate the text to insert into the "ConnectorGen" class comments. This would typically be "property" PHPDoc
	 * declarations for __get and __set properties declared in the class.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	public function DataListConnectorComments(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strCode = <<<TMPL
 * @property QQCondition 	\$Condition Any condition to use during binding
 * @property QQClauses 		\$Clauses Any clauses to use during binding

TMPL;
		return $strCode;
	}


	/**
	 * The main entry point for generating all the "ConnectorGen" code that defines the generated list connector
	 * in the generated/connector_base directory.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 */
	public function DataListConnector(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strCode = $this->DataListMembers($objCodeGen, $objTable);
		$strCode .= $this->DataListConstructor($objCodeGen, $objTable);
		$strCode .= $this->DataListCreatePaginator($objCodeGen, $objTable);
		$strCode .= $this->DataListCreateColumns($objCodeGen, $objTable);
		$strCode .= $this->DataListDataBinder($objCodeGen, $objTable);
		$strCode .= $this->DataListGet($objCodeGen, $objTable);
		$strCode .= $this->DataListSet($objCodeGen, $objTable);

		return $strCode;
	}

	/**
	 * Generate the member variables for the "ConnectorGen" class.
	 * 
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListMembers(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strCode = <<<TMPL
	/**
	 * @var null|QQCondition	Condition to use to filter the list.
	 * @access protected
	 */
	protected \$objCondition;

	/**
	 * @var null|QQClause[]		Clauses to attach to the query.
	 * @access protected
	 */
	protected \$objClauses;


TMPL;
		$strCode .= $this->DataListColumnDeclarations($objCodeGen, $objTable);
		return $strCode;
	}

	/**
	 * Generate member variables for the columns that will be created later. This implementation makes the columns
	 * public so that classes can easily manipulate the columns further after construction.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 * @throws Exception
	 */
	protected function DataListColumnDeclarations(QCodeGenBase $objCodeGen, QSqlTable $objTable) {

		$strCode = <<<TMPL
	// Publicly accessible columns that allow parent controls to directly manipulate them after creation.

TMPL;
		foreach ($objTable->ColumnArray as $objColumn) {
			if (isset($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == QFormGen::None)) continue;
			if (isset($objColumn->Options['NoColumn']) && $objColumn->Options['NoColumn']) continue;
			$strColVarName = 'col' . $objCodeGen->ModelConnectorPropertyName($objColumn);
			$strCode .= <<<TMPL
	/** @var QHtmlTableNodeColumn */
	public \${$strColVarName};

TMPL;
		}

		foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
			$strColVarName = 'col' . $objReverseReference->ObjectDescription;

			if ($objReverseReference->Unique) {
				$strCode .= <<<TMPL
	/** @var QHtmlTableNodeColumn {$strColVarName} */
	public \${$strColVarName};

TMPL;
			}
		}
		$strCode .= "\n";
		return $strCode;
	}

	/**
	 * Generate a constructor for a subclass of itself.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 */
	protected function DataListConstructor(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strClassName = $this->GetControlClass();

		$strCode = <<<TMPL

	/**
	 * {$strClassName} constructor. The default creates a paginator, sets a default data binder, and sets the grid up
	 * watch the data. Columns are set up by the parent control. Feel free to override the constructor to do things differently.
	 *
	 * @param QControl|QForm \$objParent
	 * @param null|string \$strControlId
	 */
	public function __construct(\$objParent, \$strControlId = false) {
		parent::__construct(\$objParent, \$strControlId);
		\$this->CreatePaginator();
		\$this->SetDataBinder('BindData', \$this);
		\$this->Watch(QQN::{$objTable->ClassName}());
	}


TMPL;
		return $strCode;
	}

	/**
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	public function DataListCreatePaginator(QCodeGenBase $objCodeGen, QSqlTable $objTable)
	{
		$strCode = <<<TMPL
	/**
	 * Creates the paginator. Override to add an additional paginator, or to remove it.
	 */
	protected function CreatePaginator() {
		\$this->Paginator = new QPaginator(\$this);
		\$this->ItemsPerPage = __FORM_LIST_ITEMS_PER_PAGE__;
	}

TMPL;
		return $strCode;
	}

	/**
	 * Creates the columns as part of the datagrid subclass.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 * @throws Exception
	 */
	public function DataListCreateColumns(QCodeGenBase $objCodeGen, QSqlTable $objTable)
	{
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL
	/**
	 * Creates the columns for the table. Override to customize, or use the ModelConnectorEditor to turn on and off 
	 * individual columns. This is a public function and called by the parent control.
	 */
	public function CreateColumns() {

TMPL;

		foreach ($objTable->ColumnArray as $objColumn) {
			if (isset($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == QFormGen::None)) continue;
			if (isset($objColumn->Options['NoColumn']) && $objColumn->Options['NoColumn']) continue;

			$strCode .= <<<TMPL
		\$this->col{$objCodeGen->ModelConnectorPropertyName($objColumn)} = \$this->CreateNodeColumn("{$objCodeGen->ModelConnectorControlName($objColumn)}", QQN::{$objTable->ClassName}()->{$objCodeGen->ModelConnectorPropertyName($objColumn)});

TMPL;

		}

		foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
			if ($objReverseReference->Unique) {
				$strCode .= <<<TMPL
		\$this->col{$objReverseReference->ObjectDescription} = \$this->CreateNodeColumn("{$objCodeGen->ModelConnectorControlName($objReverseReference)}", QQN::{$objTable->ClassName}()->{$objReverseReference->ObjectDescription});

TMPL;
			}
		}

		$strCode .= <<<TMPL
	}


TMPL;

		return $strCode;
	}


	/**
	 * Generates a data binder that can be called from the parent control, or called directly by this control.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListDataBinder(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strObjectType = $objTable->ClassName;
		$strCode = <<<TMPL
   /**
	* Called by the framework to access the data for the control and load it into the table. By default, this function will be
	* the data binder for the control, with no additional conditions or clauses. To change what data is displayed in the list,
	* you have many options:
	* - Override this method in the Connector.
	* - Set ->Condition and ->Clauses properties for semi-permanent conditions and clauses
	* - Override the GetCondition and GetClauses methods in the Connector.
	* - For situations where the data might change every time you draw, like if the data is filtered by other controls,
	*   you should call SetDataBinder after the parent creates this control, and in your custom data binder, call this function,
	*   passing in the conditions and clauses you want this data binder to use.
	*
	*	This binder will automatically add the orderby and limit clauses from the paginator, if present.
	**/
	public function BindData(\$objAdditionalCondition = null, \$objAdditionalClauses = null) {
		\$objCondition = \$this->GetCondition(\$objAdditionalCondition);
		\$objClauses = \$this->GetClauses(\$objAdditionalClauses);

		if (\$this->Paginator) {
			\$this->TotalItemCount = {$strObjectType}::QueryCount(\$objCondition, \$objClauses);
		}

		// If a column is selected to be sorted, and if that column has a OrderByClause set on it, then let's add
		// the OrderByClause to the \$objClauses array
		if (\$objClause = \$this->OrderByClause) {
			\$objClauses[] = \$objClause;
		}

		// Add the LimitClause information, as well
		if (\$objClause = \$this->LimitClause) {
			\$objClauses[] = \$objClause;
		}

		\$this->DataSource = {$strObjectType}::QueryArray(\$objCondition, \$objClauses);
	}


TMPL;

		$strCode .= $this->DataListGetCondition($objCodeGen, $objTable);
		$strCode .= $this->DataListGetClauses($objCodeGen, $objTable);

		return $strCode;
	}

	/**
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListGetCondition(QCodeGenBase $objCodeGen, QSqlTable $objTable)
	{
		$strCode = <<<TMPL
	/**
	 * Returns the condition to use when querying the data. Default is to return the condition put in the local
	 * objCondition member variable. You can also override this to return a condition. 
	 *
	 * @return QQCondition
	 */
	protected function GetCondition(\$objAdditionalCondition = null) {
		// Get passed in condition, possibly coming from subclass or enclosing control or form
		\$objCondition = \$objAdditionalCondition;
		if (!\$objCondition) {
			\$objCondition = QQ::All();
		}
		// Get condition more permanently bound
		if (\$this->objCondition) {
			\$objCondition = QQ::AndCondition(\$objCondition, \$this->objCondition);
		}

		return \$objCondition;
	}


TMPL;
		return $strCode;
	}

	/**
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListGetClauses(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strCode = <<<TMPL
	/**
	 * Returns the clauses to use when querying the data. Default is to return the clauses put in the local
	 * objClauses member variable. You can also override this to return clauses.
	 *
	 * @return QQClause[]
	 */
	protected function GetClauses(\$objAdditionalClauses = null) {
		\$objClauses = \$objAdditionalClauses;
		if (!\$objClauses) {
			\$objClauses = [];
		}
		if (\$this->objClauses) {
			\$objClauses = array_merge(\$objClauses, \$this->objClauses);
		}

		return \$objClauses;
	}


TMPL;
		return $strCode;
	}


	/**
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListGet(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strCode = <<<TMPL
	/**
	 * This will get the value of \$strName
	 *
	 * @param string \$strName Name of the property to get
	 * @return mixed
	 */
	public function __get(\$strName) {
		switch (\$strName) {
			case 'Condition':
				return \$this->objCondition;
			case 'Clauses':
				return \$this->objClauses;
			default:
				try {
					return parent::__get(\$strName);
				} catch (QCallerException \$objExc) {
					\$objExc->IncrementOffset();
					throw \$objExc;
				}
		}
	}


TMPL;
		return $strCode;
	}

	/**
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListSet(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strCode = <<<TMPL
	/**
	 * This will set the property \$strName to be \$mixValue
	 *
	 * @param string \$strName Name of the property to set
	 * @param string \$mixValue New value of the property
	 * @return mixed
	 */
	public function __set(\$strName, \$mixValue) {
		switch (\$strName) {
			case 'Condition':
				try {
					\$this->objCondition = QType::Cast(\$mixValue, 'QQCondition');
					\$this->MarkAsModified();
					return;
				} catch (QCallerException \$objExc) {
					\$objExc->IncrementOffset();
					throw \$objExc;
				}
			case 'Clauses':
				try {
					\$this->objClauses = QType::Cast(\$mixValue, QType::ArrayType);
					\$this->MarkAsModified();
					return;
				} catch (QCallerException \$objExc) {
					\$objExc->IncrementOffset();
					throw \$objExc;
				}
			default:
				try {
					parent::__set(\$strName, \$mixValue);
					break;
				} catch (QCallerException \$objExc) {
					\$objExc->IncrementOffset();
					throw \$objExc;
				}
		}
	}


TMPL;
		return $strCode;
	}



	/****
	 * Parent Gen
	 * The following functions generate code that is to be used by the parent object to instantiate and initialize this object.
	 *****/

	/**
	 * Return true if the data list has its own build-in filter. False will mean that a filter field will be created
	 * by default. This is still controllable by the model connector.
	 *
	 * @return bool
	 */
	public function DataListHasFilter() {
		return false;
	}

	/**
	 * Returns the code that creates the list object. This would be embedded in the pane
	 * or form that is using the list object.
	 *
	 * @param QSqlTable $objTable
	 * @return mixed
	 */
	public function DataListInstantiate(QCodeGenBase $objCodeGen, QSqlTable $objTable)
	{
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL
		\$this->{$strVarName}_Create();

TMPL;
		return $strCode;
	}

	/**
	 * Generate the code that refreshes the control after a change in the filter. The default redraws the entire control.
	 * If your control can refresh just a part of itself, insert that code here.
	 * 
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 */
	public function DataListRefresh(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);
		$strCode = <<<TMPL
		\$this->{$strVarName}->Refresh();

TMPL;
		return $strCode;

	}

	/**
	 * Generate additional methods for the enclosing control to interact with this generated control.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	public function DataListHelperMethods(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strCode = $this->DataListParentCreate($objCodeGen, $objTable);
		$strCode .= $this->DataListParentCreateColumns($objCodeGen, $objTable);
		$strCode .= $this->DataListParentMakeEditable($objCodeGen, $objTable);
		$strCode .= $this->DataListGetRowParams($objCodeGen, $objTable);

		return $strCode;
	}


	/**
	 * Generates code for the enclosing control to create this control.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListParentCreate(QCodeGenBase $objCodeGen, QSqlTable $objTable)
	{
		$strPropertyName = $objCodeGen->DataListPropertyName($objTable);
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL
   /**
	* Creates the data grid and prepares it to be row clickable. Override for additional creation operations.
	**/
	protected function {$strVarName}_Create() {
		\$this->{$strVarName} = new {$strPropertyName}List(\$this);
		\$this->{$strVarName}_CreateColumns();
		\$this->{$strVarName}_MakeEditable();
		\$this->{$strVarName}->RowParamsCallback = [\$this, "{$strVarName}_GetRowParams"];

TMPL;

		if (($o = $objTable->Options) && isset ($o['Name'])) { // Did developer default?
			$strCode .= <<<TMPL
		\$this->{$strVarName}->Name = "{$o['Name']}";

TMPL;
		}

		// Add options coming from the config file, including the LinkedNode
		$strCode .= $this->ConnectorCreateOptions($objCodeGen, $objTable, null, $strVarName);

		$strCode .= <<<TMPL
	}

TMPL;
		return $strCode;
	}

	/**
	 * Generates a function to add columns to the list.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListParentCreateColumns(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL

   /**
	* Calls the list connector to add the columns. Override to customize column creation.
	**/
	protected function {$strVarName}_CreateColumns() {
		\$this->{$strVarName}->CreateColumns();
	}

TMPL;

		return $strCode;

	}

	/**
	 * Generates a typical action to respond to row clicks.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListParentMakeEditable(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL

	protected function {$strVarName}_MakeEditable() {
		\$this->{$strVarName}->AddAction(new QCellClickEvent(0, null, null, true), new QAjaxControlAction(\$this, '{$strVarName}_CellClick', null, null, QCellClickEvent::RowValue));
		\$this->{$strVarName}->AddCssClass('clickable-rows');
	}

	protected function {$strVarName}_CellClick(\$strFormId, \$strControlId, \$strParameter) {
		if (\$strParameter) {
			\$this->EditItem(\$strParameter);
		}
	}

TMPL;

		return $strCode;
	}

	/**
	 * Generates the row param callback that will enable row clicks to know what row was clicked on.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	protected function DataListGetRowParams(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL
	public function {$strVarName}_GetRowParams(\$objRowObject, \$intRowIndex) {
		\$strKey = \$objRowObject->PrimaryKey();
		\$params['data-value'] = \$strKey;
		return \$params;
	}
TMPL;

		return $strCode;

	}


	/***
	 * Parent SUBCLASS
	 * Generator code for the parent subclass. The subclass is a first-time generation only.
	 ****/

	
	/**
	 * Generates an alternate create columns function that could be used by the list panel to create the columns directly.
	 * This is designed to be added as commented out code in the list panel override class that the user can choose to use.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QSqlTable $objTable
	 * @return string
	 */
	public function DataListSubclassOverrides(QCodeGenBase $objCodeGen, QSqlTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);
		$strPropertyName = QCodeGen::DataListPropertyName($objTable);

		$strCode = <<<TMPL
/*
	 Uncomment this block to directly create the columns here, rather than creating them in the {$strPropertyName}List connector.
	 You can then modify the column creation process by editing the function below. Or, you can instead call the parent function 
	 and modify the columns after the {$strPropertyName}List creates the default columns.

	protected function {$strVarName}_CreateColumns() {

TMPL;

		foreach ($objTable->ColumnArray as $objColumn) {
			if (isset($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == QFormGen::None)) continue;
			if (isset($objColumn->Options['NoColumn']) && $objColumn->Options['NoColumn']) continue;

			$strCode .= <<<TMPL
		\$col = \$this->{$strVarName}->CreateNodeColumn("{$objCodeGen->ModelConnectorControlName($objColumn)}", QQN::{$objTable->ClassName}()->{$objCodeGen->ModelConnectorPropertyName($objColumn)});

TMPL;

		}

		foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
			if ($objReverseReference->Unique) {
				$strCode .= <<<TMPL
		\$col = \$this->{$strVarName}->CreateNodeColumn("{$objCodeGen->ModelConnectorControlName($objReverseReference)}", QQN::{$objTable->ClassName}()->{$objReverseReference->ObjectDescription});

TMPL;
			}
		}

		$strCode .= <<<TMPL
	}

*/	

TMPL;

		$strCode .= <<<TMPL
		
/*
	 Uncomment this block to use an Edit column instead of clicking on a highlighted row in order to edit an item.

		protected \$pxyEditRow;

		protected function {$strVarName}_MakeEditable () {
			\$this->>pxyEditRow = new QControlProxy(\$this);
			\$this->>pxyEditRow->AddAction(new QClickEvent(), new QAjaxControlAction(\$this, '{$strVarName}_EditClick'));
			\$this->{$strVarName}->CreateLinkColumn(QApplication::Translate('Edit'), QApplication::Translate('Edit'), \$this->>pxyEditRow, QQN::{$objTable->ClassName}()->Id, null, false, 0);
			\$this->{$strVarName}->RemoveCssClass('clickable-rows');
		}

		protected function {$strVarName}_EditClick(\$strFormId, \$strControlId, \$param) {
			\$this->EditItem(\$param);
		}
*/	

TMPL;

		return $strCode;
	}

}
