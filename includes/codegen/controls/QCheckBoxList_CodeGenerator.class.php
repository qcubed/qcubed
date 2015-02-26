<?php

	class QCheckBoxList_CodeGenerator extends QListControl_CodeGenerator {
		public function __construct() {
			parent::__construct('QCheckBoxList');
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

			if (!$objColumn instanceof QManyToManyReference) {
				$objCodeGen->ReportError($objTable->Name . ':' . $objColumn->Name . ' is not compatible with a QCheckBoxList.');
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
			$strPropName = $objColumn->ObjectDescription;
			$strPropNames = $objColumn->ObjectDescriptionPlural;
			$strControlVarName = $objCodeGen->ModelConnectorVariableName($objColumn);

			$strRet = <<<TMPL
		protected function {$strControlVarName}_Update() {
			if (\$this->{$strControlVarName}) {
				\$this->{$strObjectName}->UnassociateAll{$strPropNames}();
				\$this->{$strObjectName}->Associate{$strPropName}(\$this->{$strControlVarName}->SelectedValues);
			}
		}


TMPL;
			return $strRet;
		}

		/**
		 * Returns a description of the options available to modify by the designer for the code generator.
		 *
		 * @return QModelConnectorParam[]
		 */
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'TextAlign', '', QModelConnectorParam::SelectionList,
					array(null => 'Default',
						'QTextAlign::Left' => 'Left',
						'QTextAlign::Right' => 'Right'
					)),
				new QModelConnectorParam (get_called_class(), 'HtmlEntities', 'Set to false to have the browser interpret the labels as HTML', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'RepeatColumns', 'The number of columns of checkboxes to display', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'RepeatDirection', 'Whether to repeat horizontally or vertically', QModelConnectorParam::SelectionList,
					array(null => 'Default',
						'QRepeatDirection::Horizontal' => 'Horizontal',
						'QRepeatDirection::Vertical' => 'Vertical'
					)),
				new QModelConnectorParam (get_called_class(), 'ButtonMode', 'How to display the buttons', QModelConnectorParam::SelectionList,
					array(null => 'Default',
						'QCheckBoxList::ButtonModeJq' => 'JQuery UI Buttons',
						'QCheckBoxList::ButtonModeSet' => 'JQuery UI Buttonset'
					)),
				new QModelConnectorParam (get_called_class(), 'MaxHeight', 'If set, will wrap it in a scrollable pane with the given max height', QType::Integer)
			));
		}
	}