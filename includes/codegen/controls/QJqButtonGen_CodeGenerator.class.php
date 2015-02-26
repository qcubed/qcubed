<?php	class QJqButtonGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct() {
			parent::__construct('QJqButtonGen');
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the button if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Label', 'Text to show in the button. When not specified (null), the elementsHTML content is used, or its value attribute if the element is aninput element of type submit or reset, or the HTML content of theassociated label element if the element is an input of type radio orcheckbox.', QType::String),
				new QModelConnectorParam (get_called_class(), 'JqText', 'Whether to show the label. When set to false no text will bedisplayed, but the icons option must be enabled, otherwise the textoption will be ignored.', QType::Boolean),
			));
		}
	}


