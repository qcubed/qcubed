<?php
/**
 * Code generator for the DataGrid2 object.
 */

class QDataGrid2Base_CodeGenerator extends QControl_CodeGenerator {
	/** @var  string */
	protected $strControlClassName;

	public function __construct($strControlClassName = 'QDataGrid2') {
		$this->strControlClassName = $strControlClassName;
	}

	public function DataListVariableDeclaration(QCodeGenBase $objCodeGen, QTable $objTable)
	{
		$strCode = parent::DataListVariableDeclaration($objCodeGen, $objTable);
		$strCode .= $this->GenerateColumnDeclarations($objCodeGen, $objTable);
		return $strCode;
	}



	public function VarName($strPropName) {
		return 'dtg' . $strPropName;
	}

	/**
	 * Generate all the code to create a subclass of this class that will act as a list connector between this control
	 * and the database table given.
	 *
	 * @param QCodeGenBase $objCodeGen
	 * @param QTable $objTable
	 */
	public function DataListSubclass(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = $this->GenerateSubclassMembers($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassConstructor($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassCreatePaginator($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassCreateColumns($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassDataBinder($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassRowParams($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassGet($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassSet($objCodeGen, $objTable);

		return $strCode;
	}

	public function DataListSubclassComments(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strObjectType = $objTable->ClassName;

		$strCode = <<<TMPL
 * @property QQCondition 	\$Conditions Any condition to use during binding
 * @property QQClauses 		\$Clauses Any clauses to use during binding

TMPL;
		return $strCode;
	}


	public function GenerateSubclassMembers(QCodeGenBase $objCodeGen, QTable $objTable) {
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

	protected function GenerateColumnDeclarations(QCodeGenBase $objCodeGen, QTable $objTable) {

		$strCode = <<<TMPL
	// Publicly accessible columns to allow parent controls to directly manipulate them after creation.

TMPL;
		foreach ($objTable->ColumnArray as $objColumn) {
			if (isset($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == QFormGen::None)) continue;
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
	public function GenerateSubclassConstructor(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = <<<TMPL
	public function __construct(\$objParent, \$strControlId = false) {
		parent::__construct(\$objParent, \$strControlId);
		\$this->CreatePaginator();
		\$this->CreateColumns();
		// Set a generic data binder.
		// Set to BindData if you want to do more custom data binding
		\$this->SetDataBinder('_bindData', \$this);
	}


TMPL;
		return $strCode;
	}

	public function GenerateSubclassCreatePaginator(QCodeGenBase $objCodeGen, QTable $objTable)
	{
		$strCode = <<<TMPL
	protected function CreatePaginator() {
		\$this->Paginator = new QPaginator(\$this);
		\$this->ItemsPerPage = __FORM_DRAFTS_FORM_LIST_ITEMS_PER_PAGE__;
	}

TMPL;
		return $strCode;
	}

	public function GenerateSubclassCreateColumns(QCodeGenBase $objCodeGen, QTable $objTable)
	{
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL
	protected function CreateColumns() {

TMPL;

		foreach ($objTable->ColumnArray as $objColumn) {
			if (isset($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == QFormGen::None)) continue;

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

	public function GenerateSubclassDataBinder(QCodeGenBase $objCodeGen, QTable $objTable)
	{
		$strCode = $this->GenerateSubclassPrivateDataBinder($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassPublicDataBinder($objCodeGen, $objTable);
		return $strCode;
	}

	protected function GenerateSubclassPrivateDataBinder(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = <<<TMPL
	protected function _bindData() {
		\$this->BindData();
	}


TMPL;
		return $strCode;
	}

	protected function GenerateSubclassPublicDataBinder(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strObjectType = $objTable->ClassName;
		$strCode = <<<TMPL
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

		$strCode .= $this->GenerateSubclassGetCondition($objCodeGen, $objTable);
		$strCode .= $this->GenerateSubclassGetClauses($objCodeGen, $objTable);

		return $strCode;
	}

	protected function GenerateSubclassGetCondition(QCodeGenBase $objCodeGen, QTable $objTable)
	{
		$strCode = <<<TMPL
	protected function GetCondition(\$objAdditionalCondition) {
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

	protected function GenerateSubclassGetClauses(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = <<<TMPL
	protected function GetClauses(\$objAdditionalClauses) {
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

	protected function GenerateSubclassRowParams(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = <<<TMPL
	protected function GetRowParams (\$objRowObject, \$intCurrentRowIndex) {
		\$params = parent::GetRowParams (\$objRowObject, \$intCurrentRowIndex);
		\$strKey = \$objRowObject->PrimaryKey();
		\$params['data-value'] = \$strKey;
		return \$params;
	}


TMPL;

		return $strCode;

	}

	protected function GenerateSubclassGet(QCodeGenBase $objCodeGen, QTable $objTable) {
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

	protected function GenerateSubclassSet(QCodeGenBase $objCodeGen, QTable $objTable) {
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
					return (\$this->objCondition = QType::Cast(\$mixValue, 'QQCondition'));
				} catch (QCallerException \$objExc) {
					\$objExc->IncrementOffset();
					throw \$objExc;
				}
			case 'Clauses':
				try {
					return (\$this->objClauses = QType::Cast(\$mixValue, QType::ArrayType));
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
		$strClassName = $this->GetControlClass();

		$strCode = <<<TMPL
		\$this->{$strVarName}_Create();

TMPL;
		return $strCode;
	}


	public function DataListHelperMethods(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strCode = $this->GenerateCreateFunction($objCodeGen, $objTable);
		$strCode .= $this->GenerateAddActions($objCodeGen, $objTable);
		//$strCode .= $this->GenerateRowParamsCallback($objCodeGen, $objTable);

		return $strCode;
	}


	protected function GenerateCreateFunction (QCodeGenBase $objCodeGen, QTable $objTable)
	{
		$strPropertyName = $objCodeGen->DataListPropertyName($objTable);
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL
	protected function {$strVarName}_Create() {
		\$this->{$strVarName} = new {$strPropertyName}List(\$this);
		\$this->{$strVarName}_AddActions();
		\$this->{$strVarName}->AddCssClass('clickable-rows');

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

	protected function GenerateCreateColumns(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL
	protected function {$strVarName}_CreateColumns() {

TMPL;

		foreach ($objTable->ColumnArray as $objColumn) {
			if (isset($objColumn->Options['FormGen']) && ($objColumn->Options['FormGen'] == QFormGen::None)) continue;

			$strCode .= <<<TMPL
		\$this->col{$objCodeGen->ModelConnectorPropertyName($objColumn)} = \$this->{$strVarName}->CreateNodeColumn("{$objCodeGen->ModelConnectorControlName($objColumn)}", QQN::{$objTable->ClassName}()->{$objCodeGen->ModelConnectorPropertyName($objColumn)});

TMPL;

		}

		foreach ($objTable->ReverseReferenceArray as $objReverseReference) {
			if ($objReverseReference->Unique) {
				$strCode .= <<<TMPL
		\$this->col{$objReverseReference->ObjectDescription} = \$this->{$strVarName}->CreateNodeColumn("{$objCodeGen->ModelConnectorControlName($objReverseReference)}", QQN::{$objTable->ClassName}()->{$objReverseReference->ObjectDescription});

TMPL;
			}
		}

		$strCode .= <<<TMPL
	}
TMPL;

		return $strCode;
	}

	protected function GenerateAddActions(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL

	protected function {$strVarName}_AddActions() {
		\$this->{$strVarName}->AddAction(new QCellClickEvent(), new QAjaxControlAction(\$this, '{$strVarName}_CellClick', null, null, '\$j(this).parent().data("value")'));
	}

	protected function {$strVarName}_CellClick(\$strFormId, \$strControlId, \$strParameter) {
		if (\$strParameter) {
			\$this->EditItem(\$strParameter);
		}
	}

TMPL;

		return $strCode;
	}

	protected function GenerateRowParamsCallback(QCodeGenBase $objCodeGen, QTable $objTable) {
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

	protected function GenerateDataBinder(QCodeGenBase $objCodeGen, QTable $objTable) {
		$strObjectType = $objTable->ClassName;
		$strVarName = $objCodeGen->DataListVarName($objTable);

		$strCode = <<<TMPL

	public function {$strVarName}_BindData() {
		// See if enclosing panel has any additional conditions or clauses
		\$objCondition = \$this->GetDataListCondition();
		\$objClauses = \$this->GetDataListClauses();

		if (!\$objCondition) \$objCondition = QQ::All();

		if (\$this->{$strVarName}->Paginator) {
			\$this->{$strVarName}->TotalItemCount = {$strObjectType}::QueryCount(\$objCondition, \$objClauses);
		}

		// If a column is selected to be sorted, and if that column has a OrderByClause set on it, then let's add
		// the OrderByClause to the \$objClauses array
		if (\$objClause = \$this->{$strVarName}->OrderByClause) {
			\$objClauses[] = \$objClause;
		}

		// Add the LimitClause information, as well
		if (\$objClause = \$this->{$strVarName}->LimitClause) {
			\$objClauses[] = \$objClause;
		}

		// Set the DataSource to be a Query result from {$strObjectType}, given the clauses above
		\$this->{$strVarName}->DataSource = {$strObjectType}::QueryArray(\$objCondition, \$objClauses);
	}


TMPL;

		return $strCode;
	}



}