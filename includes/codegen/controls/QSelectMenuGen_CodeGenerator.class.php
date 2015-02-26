<?php	class QSelectMenuGen_CodeGenerator extends QListBox_CodeGenerator	{
		public function __construct($strControlClassName = 'QSelectMenuGen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the selectmenu if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Width', 'The width of the menu, in pixels. When the value is null, the width ofthe native select is used.', QType::Integer),
			));
		}
	}


