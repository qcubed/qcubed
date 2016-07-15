<?php

	class QDatepickerBoxBase_CodeGenerator extends QTextBox_CodeGenerator {
		public function __construct($strControlClassName = 'QDatepickerBox') {
			parent::__construct($strControlClassName);
		}


		/**
		 * @param string $strPropName
		 * @return string
		 */
		public function VarName($strPropName) {
			return 'cal' . $strPropName;
		}

		/**
		 * Returns code to refresh the control from the saved object.
		 *
		 * @param QCodeGenBase $objCodeGen
		 * @param QSqlTable $objTable
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param bool $blnInit
		 * @return string
		 */
		public function ConnectorRefresh(QCodeGenBase $objCodeGen, QSqlTable $objTable, $objColumn, $blnInit = false) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = $this->VarName($strPropName);

			if ($blnInit) {
				$strRet = "\t\t\t\$this->{$strControlVarName}->DateTime = \$this->{$strObjectName}->{$strPropName};";
			} else {
				$strRet = "\t\t\tif (\$this->{$strControlVarName}) \$this->{$strControlVarName}->DateTime = \$this->{$strObjectName}->{$strPropName};";
			}
			return $strRet . "\n";
		}

		/**
		 * @param QCodeGenBase $objCodeGen
		 * @param QSqlTable $objTable
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public function ConnectorUpdate(QCodeGenBase $objCodeGen, QSqlTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = $objColumn->Reference ? $objColumn->Reference->PropertyName : $objColumn->PropertyName;
			$strControlVarName = $this->VarName($strPropName);
			$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->PropertyName} = \$this->{$strControlVarName}->DateTime;

TMPL;
			return $strRet;
		}
	}