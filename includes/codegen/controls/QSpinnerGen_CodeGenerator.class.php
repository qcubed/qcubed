<?php	class QSpinnerGen_CodeGenerator extends QTextBox_CodeGenerator	{
		public function __construct() {
			parent::__construct('QSpinnerGen');
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Culture', 'Sets the culture to use for parsing and formatting the value. If null,the currently set culture in Globalize is used, see Globalize docs foravailable cultures. Only relevant if the numberFormat option is set.Requires Globalize to be included.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the spinner if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'NumberFormat', 'Format of numbers passed to Globalize, if available. Most common are\"n\" for a decimal number and \"C\" for a currency value. Also see theculture option.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Page', 'The number of steps to take when paging via the pageUp/pageDownmethods.', QType::Integer),
			));
		}
	}


