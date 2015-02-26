<?php	class QProgressbarGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct($strControlClassName = 'QProgressbarGen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the progressbar if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Max', 'The maximum value of the progressbar.', QType::Integer),
			));
		}
	}


