<?php	class QDraggableGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct() {
			parent::__construct('QDraggableGen');
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'AddClasses', 'If set to false, will prevent the ui-draggable class from being added.This may be desired as a performance optimization when calling.draggable() on hundreds of elements.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Axis', 'Constrains dragging to either the horizontal (x) or vertical (y) axis.Possible values: \"x\", \"y\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'Cursor', 'The CSS cursor during the drag operation.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Delay', 'Time in milliseconds after mousedown until dragging should start. Thisoption can be used to prevent unwanted drags when clicking on anelement.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the draggable if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Distance', 'Distance in pixels after mousedown the mouse must move before draggingshould start. This option can be used to prevent unwanted drags whenclicking on an element.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Grid', 'Snaps the dragging helper to a grid, every x and y pixels. The arraymust be of the form [ x, y ].', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'Opacity', 'Opacity for the helper while being dragged.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'RefreshPositions', 'If set to true, all droppable positions are calculated on everymousemove. _Caution: This solves issues on highly dynamic pages, butdramatically decreases performance._', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'RevertDuration', 'The duration of the revert animation, in milliseconds. Ignored if therevert option is false.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Scope', 'Used to group sets of draggable and droppable items, in addition todroppables accept option. A draggable with the same scope value as adroppable will be accepted by the droppable.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Scroll', 'If set to true, container auto-scrolls while dragging.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ScrollSensitivity', 'Distance in pixels from the edge of the viewport after which theviewport should scroll. Distance is relative to pointer, not thedraggable. Ignored if the scroll option is false.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'ScrollSpeed', 'The speed at which the window should scroll once the mouse pointergets within the scrollSensitivity distance. Ignored if the scrolloption is false.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'SnapMode', 'Determines which edges of snap elements the draggable will snap to.Ignored if the snap option is false. Possible values: \"inner\",\"outer\", \"both\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'SnapTolerance', 'The distance in pixels from the snap element edges at which snappingshould occur. Ignored if the snap option is false.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'ZIndex', 'Z-index for the helper while being dragged.', QType::Integer),
			));
		}
	}


