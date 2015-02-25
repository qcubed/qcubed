<?php

	class QCheckBox_CodeGenerator extends QControl_CodeGenerator {
		public function __construct() {
			parent::__construct('QCheckBox');
		}

		/**
		 * @param string $strPropName
		 * @return string
		 */
		public function VarName($strPropName) {
			return 'chk' . $strPropName;
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

			// Read the control type in case we are generating code for a subclass
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
			\$this->{$strControlVarName}->Checked = \$this->{$strObjectName}->{$objColumn->PropertyName};

TMPL;

			if ($strMethod = QCodeGen::$PreferredRenderMethod) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->PreferredRenderMethod = '$strMethod';

TMPL;
			}
			$strRet .= $this->ConnectorCreateOptions($objCodeGen, $objTable, $objColumn, $strControlVarName);

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
		 * @param QTable $objTable
		 * @param QColumn $objColumn
		 * @param bool $blnInit
		 * @return string
		 */
		public function ConnectorRefresh(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = $this->VarName($strPropName);

			$strRet = "\t\t\tif (\$this->{$strControlVarName}) \$this->{$strControlVarName}->Checked = \$this->{$strObjectName}->{$strPropName};";
			return $strRet . "\n";
		}

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference $objColumn
		 * @return string
		 */
		public function ConnectorUpdate(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = $this->VarName($strPropName);
			$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->PropertyName} = \$this->{$strControlVarName}->Checked;

TMPL;
			return $strRet;
		}
	}