<?php	class QTabsGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct($strControlClassName = 'QTabsGen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Collapsible', 'When set to true, the active panel can be closed.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Event', 'The type of event that the tabs should react to in order to activatethe tab. To activate on hover, use \"mouseover\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'HeightStyle', 'Controls the height of the tabs widget and each panel. Possiblevalues: 	* \"auto\": All panels will be set to the height of the tallest panel.	* \"fill\": Expand to the available height based on the tabs parentheight.	* \"content\": Each panel will be only as tall as its content.', QType::String),
			));
		}
	}


