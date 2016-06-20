<?php
/**
 * This is a base class to support classes that are derived from QSimpleTable. The methods here support the use
 * of QSimpleTable derived classes as a list connector, something that displays a list of records from a database,
 * and optionally allows the user to do CRUD operations on individual records.
 */

abstract class QSimpleTable_CodeGenerator extends QControl_CodeGenerator {

	/**
	 * dtg stands for "DataGrid", a QCubed historical name for tables displaying data. Override if you want something else.
	 * @param string $strPropName
	 * @return string
	 */
	public function VarName($strPropName) {
		return 'dtg' . $strPropName;
	}

	
	/**** CONNECTOR GEN *******/

	/**
	 * Generate the text to insert into the "ConnectorGen" class comments. This would typically be "property" PHPDoc
	 * declarations for __get and __set properties declared in the class.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 * @return string
	 */
	public function DataListConnectorGenComments(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = <<<TMPL
 * @property QQCondition 	\$Conditions Any condition to use during binding
 * @property QQClauses 		\$Clauses Any clauses to use during binding

TMPL;
		return $strCode;
	}


	/**
	 * The main entry point for generating all the "ConnectorGen" code that defines the generated list connector
	 * in the generated/connector_base directory.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 */
	public function DataListConnectorGen(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = $this->GenerateConnectorGenMembers($objCodeGen, $objTable);
		$strCode .= $this->GenerateConnectorGenConstructor($objCodeGen, $objTable);
		$strCode .= $this->GenerateConnectorGenCreatePaginator($objCodeGen, $objTable);
		$strCode .= $this->GenerateConnectorGenCreateColumns($objCodeGen, $objTable);
		$strCode .= $this->GenerateConnectorGenDataBinder($objCodeGen, $objTable);
		$strCode .= $this->GenerateConnectorGenGet($objCodeGen, $objTable);
		$strCode .= $this->GenerateConnectorGenSet($objCodeGen, $objTable);

		return $strCode;
	}

	/**
	 * Generate the member variables for the "ConnectorGen" class.
	 * 
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 * @return string
	 */
	public function GenerateConnectorGenMembers(QCodeGenBase $objCodeGen, QTable $objTable) {
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
		$strCode .= $this->GenerateColumnDeclarations($objCodeGen, $objTable);
		return $strCode;
	}

	/**
	 * Generate member variables for the columns that will be created later. This implementation makes the columns
	 * public so that classes can easily manipulate the columns further after construction.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 * @return string
	 * @throws Exception
	 */
	protected function GenerateColumnDeclarations(QCodeGenBase $objCodeGen, QTable $objTable) {

		$strCode = <<<TMPL
	// Publicly accessible columns that allow parent controls to directly manipulate them after creation.

TMPL;
		foreach ($objTable->ColumnArray as $objColumn) {
			if (isset($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == QFormGen::None)) continue;
			if (isset($objColumn->Options['NoColumn']) && $objColumn->Options['NoColumn']) continue;
			$strColVarName = 'col' . $objCodeGen->ModelConnectorPropertyName($objColumn);
			$strCode .= <<<TMPL
	/** @var QSimpleTableNodeColumn */
	public \${$strColVarName};

TMPL;
		}

		foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
			$strColVarName = 'col' . $objReverseReference->ObjectDescription;

			if ($objReverseReference->Unique) {
				$strCode .= <<<TMPL
	/** @var QSimpleTableNodeColumn {$strColVarName} */
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
	 * @param QTable $objTable
	 */
	public function GenerateConnectorGenConstructor(QCodeGenBase $objCodeGen, QTable $objTable) {
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
	 * @param QTable $objTable
	 * @return string
	 */
	public function GenerateConnectorGenCreatePaginator(QCodeGenBase $objCodeGen, QTable $objTable)
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
	 * @param QTable $objTable
	 * @return string
	 * @throws Exception
	 */
	public function GenerateConnectorGenCreateColumns(QCodeGenBase $objCodeGen, QTable $objTable)
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
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateConnectorGenDataBinder(QCodeGenBase $objCodeGen, QTable $objTable) {
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

		$strCode .= $this->GenerateConnectorGenCondition($objCodeGen, $objTable);
		$strCode .= $this->GenerateConnectorGenClauses($objCodeGen, $objTable);

		return $strCode;
	}

	/**
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateConnectorGenCondition(QCodeGenBase $objCodeGen, QTable $objTable)
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
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateConnectorGenClauses(QCodeGenBase $objCodeGen, QTable $objTable) {
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
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateConnectorGenGet(QCodeGenBase $objCodeGen, QTable $objTable) {
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
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateConnectorGenSet(QCodeGenBase $objCodeGen, QTable $objTable) {
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



	/**** Parent Gen *****/

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
	 * @param QTable $objTable
	 * @return mixed
	 */
	public function DataListCreate(QCodeGenBase $objCodeGen, QTable $objTable)
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
	 * @param QTable $objTable
	 */
	public function DataListRefresh(QCodeGenBase $objCodeGen, QTable $objTable) {
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
	 * @param QTable $objTable
	 * @return string
	 */
	public function DataListHelperMethods(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = $this->GenerateDataListCreateFunction($objCodeGen, $objTable);
		$strCode .= $this->GenerateDataListCreateColumns($objCodeGen, $objTable);
		$strCode .= $this->GenerateDataListMakeEditable($objCodeGen, $objTable);
		$strCode .= $this->GenerateDataListRowParamsCallback($objCodeGen, $objTable);

		return $strCode;
	}


	/**
	 * Generates code for the enclosing control to create this control.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateDataListCreateFunction (QCodeGenBase $objCodeGen, QTable $objTable)
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
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateDataListCreateColumns(QCodeGenBase $objCodeGen, QTable $objTable) {
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
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateDataListMakeEditable(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL

	protected function {$strVarName}_MakeEditable() {
		\$this->{$strVarName}->AddAction(new QCellClickEvent(), new QAjaxControlAction(\$this, '{$strVarName}_CellClick', null, null, '\$j(this).parent().data("value")'));
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
	 * @param QTable $objTable
	 * @return string
	 */
	protected function GenerateDataListRowParamsCallback(QCodeGenBase $objCodeGen, QTable $objTable) {
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


	/***  Parent Override class ****/

	
	/**
	 * Generates an alternative create columns function that could be used by the list panel to create the columns directly.
	 * This is designed to be added as commented out code in the list panel override class that the user can choose to use.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 * @return string
	 */
	public function GenerateCreateColumnsOverride(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL
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


TMPL;

		return $strCode;
	}

}
