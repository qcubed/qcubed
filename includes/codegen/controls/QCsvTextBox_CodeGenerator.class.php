<?php

	class QCsvTextBox_CodeGenerator extends QTextBox_CodeGenerator {
		public function __construct() {
			parent::__construct('QCsvTextBox');
		}

		/**
		 * Returns an description of the options available to modify by the designer for the code generator.
		 *
		 * @return QModelConnectorParam[]
		 */
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Delimiter', 'Default: , (comma)', QType::String),
				new QModelConnectorParam (get_called_class(), 'Enclosure', 'Default: " (double-quote)', QType::String),
				new QModelConnectorParam (get_called_class(), 'Escape', 'Default: \\ (backslash)', QType::String),
				new QModelConnectorParam (get_called_class(), 'MinItemCount', 'Minimum number of items required.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MaxItemCount', 'Maximum number of items allowed.', QType::Integer)
			));
		}

	}