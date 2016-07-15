<?php

	class QSliderBase_CodeGenerator extends QControl_CodeGenerator {
		public function __construct($strControlClassName = 'QSlider') {
			parent::__construct($strControlClassName);
		}

		/**
		 * @param string $strPropName
		 * @return string
		 */
		public function VarName($strPropName) {
			return 'sld' . $strPropName;
		}

		/**
		 * Generate code that will be inserted into the ModelConnector to connect a database object with this control.
		 * This is called during the codegen process. This is very similar to the QListControl code, but there are
		 * some differences. In particular, this control does not support ManyToMany references.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QSqlTable $objTable
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public function ConnectorCreate(QCodeGenBase $objCodeGen, QSqlTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strClassName = $objTable->ClassName;
			$strControlVarName = $objCodeGen->ModelConnectorVariableName($objColumn);
			$strLabelName = addslashes(QCodeGen::ModelConnectorControlName($objColumn));

			// Read the control type in case we are generating code for a subclass of QTextBox
			$strControlType = $objCodeGen->GetControlCodeGenerator($objColumn)->GetControlClass();

			$strRet = <<<TMPL
		/**
		 * Create and setup a $strControlType $strControlVarName
		 * @param string \$strControlId optional ControlId to use
		 * @return $strControlType
		 */
		public function {$strControlVarName}_Create(\$strControlId = null) {

TMPL;
			$strControlIdOverride = $objCodeGen->GenerateControlId($objTable, $objColumn);

			if ($strControlIdOverride) {
				$strRet .= <<<TMPL
			if (!\$strControlId) {
				\$strControlId = '$strControlIdOverride';
			}

TMPL;
			}
			$strRet .= <<<TMPL
			\$this->{$strControlVarName} = new $strControlType(\$this->objParentObject, \$strControlId);
			\$this->{$strControlVarName}->Name = QApplication::Translate('$strLabelName');

TMPL;

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

			return $strRet;
		}

		/**
		 * Returns code to refresh the control from the saved object.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QSqlTable $objTable
		 * @param QSqlColumn $objColumn
		 * @param bool $blnInit
		 * @return string
		 */
		public function ConnectorRefresh(QCodeGenBase $objCodeGen, QSqlTable $objTable, $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = $this->VarName($strPropName);

			if ($blnInit) {
				$strRet = "\t\t\t\$this->{$strControlVarName}->Value = \$this->{$strObjectName}->{$strPropName};";
			} else {
				$strRet = "\t\t\tif (\$this->{$strControlVarName}) \$this->{$strControlVarName}->Value = \$this->{$strObjectName}->{$strPropName};";
			}
			return $strRet . "\n";
		}

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QSqlTable $objTable
		 * @param QSqlColumn|QReverseReference $objColumn
		 * @return string
		 */
		public function ConnectorUpdate(QCodeGenBase $objCodeGen, QSqlTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = $this->VarName($strPropName);
			$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$strPropName} = \$this->{$strControlVarName}->Value;

TMPL;
			return $strRet;
		}
	}