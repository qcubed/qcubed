<?php	class QDialogGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct() {
			parent::__construct('QDialogGen');
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'AutoOpen', 'If set to true, the dialog will automatically open uponinitialization. If false, the dialog will stay hidden until the open()method is called.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'CloseOnEscape', 'Specifies whether the dialog should close when it has focus and theuser presses the escape (ESC) key.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'CloseText', 'Specifies the text for the close button. Note that the close text isvisibly hidden when using a standard theme.', QType::String),
				new QModelConnectorParam (get_called_class(), 'DialogClass', 'The specified class name(s) will be added to the dialog, foradditional theming.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Draggable', 'If set to true, the dialog will be draggable by the title bar.Requires the jQuery UI Draggable widget to be included.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'MaxHeight', 'The maximum height to which the dialog can be resized, in pixels.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MaxWidth', 'The maximum width to which the dialog can be resized, in pixels.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MinHeight', 'The minimum height to which the dialog can be resized, in pixels.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MinWidth', 'The minimum width to which the dialog can be resized, in pixels.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Modal', 'If set to true, the dialog will have modal behavior; other items onthe page will be disabled, i.e., cannot be interacted with. Modaldialogs create an overlay below the dialog but above other pageelements.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Resizable', 'If set to true, the dialog will be resizable. Requires the jQuery UIResizable widget to be included.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Title', 'Specifies the title of the dialog. If the value is null, the titleattribute on the dialog source element will be used.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Width', 'The width of the dialog, in pixels.', QType::Integer),
			));
		}
	}


