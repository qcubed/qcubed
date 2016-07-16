<?php

	class QRadioButtonListBase_CodeGenerator extends QListControl_CodeGenerator {
		public function __construct($strControlClassName = 'QRadioButtonList') {
			parent::__construct($strControlClassName);
		}

		/**
		 * Reads the options from the special data file, and possibly the column
		 * @param QCodeGenBase $objCodeGen
		 * @param QSqlTable $objTable
		 * @param QSqlColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param string $strControlVarName
		 * @return string
		 */
		public function ConnectorCreateOptions(QCodeGenBase $objCodeGen, QSqlTable $objTable, $objColumn, $strControlVarName) {
			$strRet = parent::ConnectorCreateOptions($objCodeGen, $objTable, $objColumn, $strControlVarName);

			if ($objColumn instanceof QManyToManyReference) {
				$objCodeGen->ReportError($objTable->Name . ':' . $objColumn->Name . ' is not compatible with a QRadioButtonList.');
			}

			return $strRet;
		}
	}