<?php

	class QNumericTextBox_CodeGenerator extends QTextBox_CodeGenerator {
		public function __construct($strControlClassName = 'QNumericTextBox') {
			parent::__construct($strControlClassName);
		}

		/**
		 * Returns an description of the options available to modify by the designer for the code generator.
		 *
		 * @return array
		 */
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Maximum', 'Meximum value allowed', QType::String),// float or integer
				new QModelConnectorParam (get_called_class(), 'Minimum', 'Meximum value allowed', QType::String),
				new QModelConnectorParam (get_called_class(), 'Step', 'If value must be aligned on a step, the step amount', QType::String),
				new QModelConnectorParam (get_called_class(), 'LabelForLess', 'If value is too small, override the default error message', QType::String),
				new QModelConnectorParam (get_called_class(), 'LabelForGreater', 'If value is too big, override the default error message', QType::String),
				new QModelConnectorParam (get_called_class(), 'LabelForNotStepAligned', 'If value is not step aligned, override the default error message', QType::String)
			));
		}

	}