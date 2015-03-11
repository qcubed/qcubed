<?php

	class QAutocomplete_CodeGenerator extends QTextBox_CodeGenerator {
		public function __construct($strControlClassName = 'QAutocomplete') {
			parent::__construct($strControlClassName);
		}

		/**
		 * @param string $strPropName
		 * @return string
		 */
		public function VarName($strPropName) {
			return 'lst' . $strPropName;
		}

		/**
		 * Generate code that will be inserted into the ModelConnector to connect a database object with this control.
		 * This is called during the codegen process. This is very similar to the QListControl code, but there are
		 * some differences. In particular, this control does not support ManyToMany references.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @throws Exception
		 * @return string
		 */
		public function ConnectorCreate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			if ($objColumn instanceof QManyToManyReference) {
				throw new Exception ("Autocomplete does not support many-to-many references.");
			}

			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strControlVarName = $objCodeGen->ModelConnectorVariableName($objColumn);
			$strLabelName = addslashes(QCodeGen::ModelConnectorControlName($objColumn));
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);

			// Read the control type in case we are generating code for a similar class
			$strControlType = $objCodeGen->GetControlCodeGenerator($objColumn)->GetControlClass();

			// Create a control designed just for selecting from a type table
			if ($objColumn instanceof QColumn && $objColumn->Reference->IsType) {
				$strRet = <<<TMPL
		/**
		 * Create and setup {$strControlType} {$strControlVarName}
		 * @param string \$strControlId optional ControlId to use
		 * @return {$strControlType}
		 */

		public function {$strControlVarName}_Create(\$strControlId = null) {

TMPL;
			} else {    // Create a control that presents a list taken from the database

				$strRet = <<<TMPL
		/**
		 * Create and setup {$strControlType} {$strControlVarName}
		 * @param null|string \$strControlId optional ControlId to use
		 * @param null|QQCondition \$objConditions override the default condition of QQ::All() to the query, itself
		 * @param null|QQClause[] \$objClauses additional QQClause object or array of QQClause objects for the query
		 * @return {$strControlType}
		 */

		public function {$strControlVarName}_Create(\$strControlId = null, QQCondition \$objCondition = null, \$objClauses = null) {
			\$this->obj{$strPropName}Condition = \$objCondition;
			\$this->obj{$strPropName}Clauses = \$objClauses;

TMPL;
			}

			// Allow the codegen process to either create custom ids based on the field/table names, or to be
			// Specified by the developer.
			$strControlIdOverride = $objCodeGen->GenerateControlId($objTable, $objColumn);

			if ($strControlIdOverride) {
				$strRet .= <<<TMPL
			if (!\$strControlId) {
				\$strControlId = '$strControlIdOverride';
			}

TMPL;
			}

			$strRet .= <<<TMPL
			\$this->{$strControlVarName} = new {$strControlType}(\$this->objParentObject, \$strControlId);
			\$this->{$strControlVarName}->Name = QApplication::Translate('{$strLabelName}');

TMPL;
			if ($objColumn instanceof QColumn && $objColumn->NotNull) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->Required = true;

TMPL;
			}

			if ($strMethod = QCodeGen::$PreferredRenderMethod) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->PreferredRenderMethod = '$strMethod';

TMPL;
			}
			$strRet .= $this->ConnectorCreateOptions($objCodeGen, $objTable, $objColumn, $strControlVarName);
			$strRet .= $this->ConnectorRefresh($objCodeGen, $objTable, $objColumn, true);

			$strRet .= <<<TMPL
			return \$this->{$strControlVarName};
		}

TMPL;

			if ($objColumn instanceof QColumn && $objColumn->Reference->IsType) {
				if ($objColumn instanceof QColumn) {
					$strVarType = $objColumn->Reference->VariableType;
				} else {
					$strVarType = $objColumn->ObjectDescription;
				}
				$strRet .= <<<TMPL

		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		public function {$strControlVarName}_GetItems() {
			return {$strVarType}::\$NameArray;
		}


TMPL;
			} else {
				if ($objColumn instanceof QColumn) {
					$strRefVarType = $objColumn->Reference->VariableType;
					$strRefVarName = $objColumn->Reference->VariableName;
					$strRefTable = $objColumn->Reference->Table;
				} elseif ($objColumn instanceof QReverseReference) {
					$strRefVarType = $objColumn->VariableType;
					$strRefVarName = $objColumn->VariableName;
					$strRefTable = $objColumn->Table;
				} else {
					throw new Exception ("Unprepared to handle this column type.");
				}

				$strRet .= <<<TMPL

		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		 public function {$strControlVarName}_GetItems() {
			\$a = array();
			\$objCondition = \$this->obj{$strPropName}Condition;
			if (is_null(\$objCondition)) \$objCondition = QQ::All();
			\${$strRefVarName}Cursor = {$strRefVarType}::QueryCursor(\$objCondition, \$this->obj{$strPropName}Clauses);

			// Iterate through the Cursor
			while (\${$strRefVarName} = {$strRefVarType}::InstantiateCursor(\${$strRefVarName}Cursor)) {
				\$objListItem = new QListItem(\${$strRefVarName}->__toString(), \${$strRefVarName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName});
				if ((\$this->{$strObjectName}->{$strPropName}) && (\$this->{$strObjectName}->{$strPropName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName} == \${$strRefVarName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName}))
					\$objListItem->Selected = true;
				\$a[] = \$objListItem;
			}
			return \$a;
		 }


TMPL;
			}
			return $strRet;
		}

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QColumn|QReverseReference| QManyToManyReference $objColumn
		 * @return string
		 */
		public function ConnectorVariableDeclaration(QCodeGenBase $objCodeGen, $objColumn) {
			$strClassName = $objCodeGen->GetControlCodeGenerator($objColumn)->GetControlClass();
			$strPropName = $objCodeGen->ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);

			$strRet = <<<TMPL
		/**
		 * @var {$strClassName} {$strControlVarName}
		 * @access protected
		 */
		protected \${$strControlVarName};

		/**
		* @var obj{$strPropName}Condition
		* @access protected
		*/
		protected \$obj{$strPropName}Condition;

		/**
		* @var obj{$strPropName}Clauses
		* @access protected
		*/
		protected \$obj{$strPropName}Clauses;

TMPL;

			return $strRet;
		}

		/**
		 * Returns code to refresh the control from the saved object.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @param bool $blnInit
		 * @return string
		 */
		public function ConnectorRefresh(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strPrimaryKey = $objCodeGen->GetTable($objColumn->Reference->Table)->PrimaryKeyColumnArray[0]->PropertyName;
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strRet = '';

			if (!$blnInit) {
				$t = "\t";    // inserts an extra tab below
				$strRet = <<<TMPL
			if (\$this->{$strControlVarName}) {

TMPL;
			} else {
				$t = '';
			}

			$options = $objColumn->Options;
			if (!$options || !isset ($options['NoAutoLoad'])) {
				$strRet .= <<<TMPL
$t			\$this->{$strControlVarName}->Source = \$this->{$strControlVarName}_GetItems();

TMPL;
			}
			$strRet .= <<<TMPL
$t			if (\$this->{$strObjectName}->{$strPropName}) {
$t				\$this->{$strControlVarName}->Text = \$this->{$strObjectName}->{$strPropName}->__toString();
$t				\$this->{$strControlVarName}->SelectedValue = \$this->{$strObjectName}->{$strPropName}->{$strPrimaryKey};
$t			}
$t			else {
$t				\$this->{$strControlVarName}->Text = '';
$t				\$this->{$strControlVarName}->SelectedValue = null;
$t			}

TMPL;

			if (!$blnInit) {
				$strRet .= <<<TMPL
			}

TMPL;
			}
			return $strRet;
		}

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference $objColumn
		 * @return string
		 */
		public function ConnectorUpdate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);

			$strRet = '';
			if ($objColumn instanceof QColumn) {

				$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->PropertyName} = \$this->{$strControlVarName}->SelectedValue;

TMPL;
			} elseif ($objColumn instanceof QReverseReference) {
				$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->ObjectPropertyName} = {$objColumn->VariableType}::Load(\$this->{$strControlVarName}->SelectedValue);

TMPL;
			}
			return $strRet;
		}
	}