<?php

	class QListControlBase_CodeGenerator extends QControl_CodeGenerator {
		public function __construct($strControlClassName = 'QListControl') {
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
		 * @return string
		 */
		public function ConnectorCreate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strControlVarName = $objCodeGen->ModelConnectorVariableName($objColumn);
			$strLabelName = addslashes(QCodeGen::ModelConnectorControlName($objColumn));
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);

			// Read the control type in case we are generating code for a similar class
			$strControlType = $objCodeGen->GetControlCodeGenerator($objColumn)->GetControlClass();

			// Create a control designed just for selecting from a type table
			if (($objColumn instanceof QColumn && $objColumn->Reference->IsType) ||
				($objColumn instanceof QManyToManyReference && $objColumn->IsTypeAssociation)
			) {
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
		 * @param string \$strControlId optional ControlId to use
		 * @param QQCondition \$objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] \$objClauses additional QQClause object or array of QQClause objects for the query
		 * @return QListBox
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

			if ($objColumn instanceof QColumn && $objColumn->Reference->IsType ||
				$objColumn instanceof QManyToManyReference && $objColumn->IsTypeAssociation
			) {
				if ($objColumn instanceof QColumn) {
					$strVarType = $objColumn->Reference->VariableType;
				} else {
					$strVarType = $objColumn->VariableType;
				}
				$strRefVarName = null;
				$strRet .= <<<TMPL

		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		public function {$strControlVarName}_GetItems() {
			return {$strVarType}::\$NameArray;
		}


TMPL;
			} elseif ($objColumn instanceof QManyToManyReference) {
				$strRefVarName = $objColumn->VariableName;
				$strVarType = $objColumn->VariableType;
				$strRefTable = $objColumn->AssociatedTable;
				$strRefPropName = $objColumn->OppositeObjectDescription;
				$strRefPK = $objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName;
				//$strPK = $objTable->PrimaryKeyColumnArray[0]->PropertyName;

				$strRet .= <<<TMPL
		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		public function {$strControlVarName}_GetItems() {
			\$a = array();
			\$objCondition = \$this->obj{$strPropName}Condition;
			if (is_null(\$objCondition)) \$objCondition = QQ::All();
			\$objClauses = \$this->obj{$strPropName}Clauses;

			\$objClauses[] =
				QQ::Expand(QQN::{$strVarType}()->{$strRefPropName}->{$objTable->ClassName}, QQ::Equal(QQN::{$strVarType}()->{$strRefPropName}->{$objColumn->PropertyName}, \$this->{$strObjectName}->{$strRefPK}));

			\$obj{$strVarType}Cursor = {$strVarType}::QueryCursor(\$objCondition, \$objClauses);

			// Iterate through the Cursor
			while (\${$strRefVarName} = {$strVarType}::InstantiateCursor(\$obj{$strVarType}Cursor)) {
				\$objListItem = new QListItem(\${$strRefVarName}->__toString(), \${$strRefVarName}->{$strRefPK}, \${$strRefVarName}->_{$strRefPropName} !== null);
				\$a[] = \$objListItem;
			}
			return \$a;
		}

TMPL;
			} else {
				if ($objColumn instanceof QColumn) {
					$strRefVarType = $objColumn->Reference->VariableType;
					$strRefVarName = $objColumn->Reference->VariableName;
					//$strRefPropName = $objColumn->Reference->PropertyName;
					$strRefTable = $objColumn->Reference->Table;
				} elseif ($objColumn instanceof QReverseReference) {
					$strRefVarType = $objColumn->VariableType;
					$strRefVarName = $objColumn->VariableName;
					//$strRefPropName = $objColumn->PropertyName;
					$strRefTable = $objColumn->Table;
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
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);

			$strRet = <<<TMPL
		/**
		 * @var {$strClassName} {$strControlVarName}
		 * @access protected
		 */
		protected \${$strControlVarName};

		/**
		 * @var string str{$strPropName}NullLabel
		 * @access protected
		 */
		protected \$str{$strPropName}NullLabel;


TMPL;

			if (($objColumn instanceof QColumn && !$objColumn->Reference->IsType) ||
				($objColumn instanceof QManyToManyReference && !$objColumn->IsTypeAssociation) ||
				($objColumn instanceof QReverseReference)
			) {
				$strRet .= <<<TMPL
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
			}
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
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);

			$strRet = '';

			if ($blnInit) {
				$strRet .= <<<TMPL
if (!\$this->str{$strPropName}NullLabel) {
	if (!\$this->{$strControlVarName}->Required) {
		\$this->str{$strPropName}NullLabel = '- None -';
	}
	elseif (!\$this->blnEditMode) {
		\$this->str{$strPropName}NullLabel = '- Select One -';
	}
}

TMPL;
			}
			else {
				$strRet .= "\$this->{$strControlVarName}->RemoveAllItems();\n";
			}
			$strRet .= <<<TMPL
\$this->{$strControlVarName}->AddItem(QApplication::Translate(\$this->str{$strPropName}NullLabel), null);

TMPL;

			$options = $objColumn->Options;
			if (!$options || !isset ($options['NoAutoLoad'])) {
				$strRet .=  "\$this->{$strControlVarName}->AddItems(\$this->{$strControlVarName}_GetItems());\n";
			}

			if ($objColumn instanceof QColumn) {
				$strRet .= "\$this->{$strControlVarName}->SelectedValue = \$this->{$strObjectName}->{$objColumn->PropertyName};\n";
			} elseif ($objColumn instanceof QReverseReference && $objColumn->Unique) {
				$strRet .= "if (\$this->{$strObjectName}->{$objColumn->ObjectPropertyName})\n";
				$strRet .= _indent("\$this->{$strControlVarName}->SelectedValue = \$this->{$strObjectName}->{$objColumn->ObjectPropertyName}->{$objCodeGen->GetTable($objColumn->Table)->PrimaryKeyColumnArray[0]->PropertyName};\n");
			} elseif ($objColumn instanceof QManyToManyReference) {
				if ($objColumn->IsTypeAssociation) {
					$strRet .= "\$this->{$strControlVarName}->SelectedValues = array_keys(\$this->{$strObjectName}->Get{$objColumn->ObjectDescription}Array());\n";
				} else {
					//$strRet .= $strTabs . "\$this->{$strControlVarName}->SelectedValues = \$this->{$strObjectName}->Get{$objColumn->ObjectDescription}Keys();\n";
				}
			}
			if (!$blnInit) {	// wrap it with a test as to whether the control has been created.
				$strRet = _indent($strRet);
				$strRet = <<<TMPL
if (\$this->{$strControlVarName}) {
$strRet
}

TMPL;
			}
			$strRet = _indent($strRet, 3);
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

		/**
		 * Generate helper functions for the update process.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string
		 */
		public function ConnectorUpdateMethod(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);
			$strRet = <<<TMPL
		protected function {$strControlVarName}_Update() {
			if (\$this->{$strControlVarName}) {

TMPL;

			if ($objColumn instanceof QManyToManyReference) {
				if ($objColumn->IsTypeAssociation) {
					$strRet .= <<<TMPL
				\$this->{$strObjectName}->UnassociateAll{$objColumn->ObjectDescriptionPlural}();
				\$this->{$strObjectName}->Associate{$objColumn->ObjectDescription}(\$this->{$strControlVarName}->SelectedValues);

TMPL;
				} else {
					$strRet .= <<<TMPL
				\$this->{$strObjectName}->UnassociateAll{$objColumn->ObjectDescriptionPlural}();
				foreach(\$this->{$strControlVarName}->SelectedValues as \$id) {
					\$this->{$strObjectName}->Associate{$objColumn->ObjectDescription}ByKey(\$id);
				}

TMPL;
				}
			}

			$strRet .= <<<TMPL
			}
		}

TMPL;

			return $strRet;
		}

		public function ConnectorSet(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);
			$strRet = <<<TMPL
					case '{$strPropName}NullLabel':
						return \$this->str{$strPropName}NullLabel = \$mixValue;

TMPL;
			return $strRet;
		}


		public function ConnectorGet(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
			$strControlVarName = $this->VarName($strPropName);
			$strRet = <<<TMPL
				case '{$strPropName}NullLabel':
					return \$this->str{$strPropName}NullLabel;

TMPL;
			return $strRet;
		}

	}

