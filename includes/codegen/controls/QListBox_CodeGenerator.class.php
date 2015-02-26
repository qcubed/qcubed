<?php

	class QListBox_CodeGenerator extends QListControl_CodeGenerator {
		public function __construct() {
			parent::__construct('QListBox');
		}

		/**
		 * Reads the options from the special data file, and possibly the column
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param string $strControlVarName
		 * @return string
		 */
		public function ConnectorCreateOptions(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $strControlVarName) {
			$strRet = parent::ConnectorCreateOptions($objCodeGen, $objTable, $objColumn, $strControlVarName);

			if ($objColumn instanceof QManyToManyReference) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->SelectionMode = QSelectionMode::Multiple;

TMPL;
			}
			return $strRet;
		}

		/**
		 * Returns an description of the options available to modify by the designer for the code generator.
		 *
		 * @return QModelConnectorParam[]
		 */
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Rows', 'Height of field for multirow field', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'SelectionMode', 'Single or multiple selections', QModelConnectorParam::SelectionList,
					array(null => 'Default',
						'QSelectionMode::Single' => 'Single',
						'QSelectionMode::Multiple' => 'Multiple'
					))
			));
		}

	}