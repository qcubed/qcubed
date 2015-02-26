<?php	class QResizableGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct() {
			parent::__construct('QResizableGen');
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Animate', 'Animates to the final size after resizing.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'AnimateEasing', 'Which easing to apply when using the animate option.', QType::String),
				new QModelConnectorParam (get_called_class(), 'AutoHide', 'Whether the handles should hide when the user is not hovering over theelement.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Delay', 'Tolerance, in milliseconds, for when resizing should start. Ifspecified, resizing will not start until after mouse is moved beyondduration. This can help prevent unintended resizing when clicking onan element.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the resizable if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Distance', 'Tolerance, in pixels, for when resizing should start. If specified,resizing will not start until after mouse is moved beyond distance.This can help prevent unintended resizing when clicking on an element.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Ghost', 'If set to true, a semi-transparent helper element is shown forresizing.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Grid', 'Snaps the resizing element to a grid, every x and y pixels. Arrayvalues: [ x, y ].', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'Helper', 'A class name that will be added to a proxy element to outline theresize during the drag of the resize handle. Once the resize iscomplete, the original element is sized.', QType::String),
				new QModelConnectorParam (get_called_class(), 'MaxHeight', 'The maximum height the resizable should be allowed to resize to.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MaxWidth', 'The maximum width the resizable should be allowed to resize to.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MinHeight', 'The minimum height the resizable should be allowed to resize to.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MinWidth', 'The minimum width the resizable should be allowed to resize to.', QType::Integer),
			));
		}
	}


