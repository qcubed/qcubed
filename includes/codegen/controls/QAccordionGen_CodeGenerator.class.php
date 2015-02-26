<?php	class QAccordionGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct($strControlClassName = 'QAccordionGen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Collapsible', 'Whether all the sections can be closed at once. Allows collapsing theactive section.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the accordion if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Event', 'The event that accordion headers will react to in order to activatethe associated panel. Multiple events can be specified, separated by aspace.', QType::String),
				new QModelConnectorParam (get_called_class(), 'HeightStyle', 'Controls the height of the accordion and each panel. Possible values: 	* \"auto\": All panels will be set to the height of the tallest panel.	* \"fill\": Expand to the available height based on the accordionsparent height.	* \"content\": Each panel will be only as tall as its content.', QType::String),
			));
		}
	}


