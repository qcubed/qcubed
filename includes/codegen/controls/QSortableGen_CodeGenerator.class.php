<?php	class QSortableGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct($strControlClassName = 'QSortableGen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Axis', 'If defined, the items can be dragged only horizontally or vertically.Possible values: \"x\", \"y\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'Cursor', 'Defines the cursor that is being shown while sorting.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Delay', 'Time in milliseconds to define when the sorting should start. Adding adelay helps preventing unwanted drags when clicking on an element.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the sortable if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Distance', 'Tolerance, in pixels, for when sorting should start. If specified,sorting will not start until after mouse is dragged beyond distance.Can be used to allow for clicks on elements within a handle.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'DropOnEmpty', 'If false, items from this sortable cant be dropped on an empty connectsortable (see the connectWith option.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ForceHelperSize', 'If true, forces the helper to have a size.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ForcePlaceholderSize', 'If true, forces the placeholder to have a size.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Grid', 'Snaps the sorting element or helper to a grid, every x and y pixels.Array values: [ x, y ].', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'Opacity', 'Defines the opacity of the helper while sorting. From 0.01 to 1.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Placeholder', 'A class name that gets applied to the otherwise white space.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Scroll', 'If set to true, the page scrolls when coming to an edge.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ScrollSensitivity', 'Defines how near the mouse must be to an edge to start scrolling.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'ScrollSpeed', 'The speed at which the window should scroll once the mouse pointergets within the scrollSensitivity distance.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Tolerance', 'Specifies which mode to use for testing whether the item being movedis hovering over another item. Possible values: 	* \"intersect\": The item overlaps the other item by at least 50%.	* \"pointer\": The mouse pointer overlaps the other item.', QType::String),
				new QModelConnectorParam (get_called_class(), 'ZIndex', 'Z-index for element/helper while being sorted.', QType::Integer),
			));
		}
	}


