<?php	class QSelectableGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct($strControlClassName = 'QSelectableGen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'AutoRefresh', 'This determines whether to refresh (recalculate) the position and sizeof each selectee at the beginning of each select operation. If youhave many items, you may want to set this to false and call therefresh() method manually.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Delay', 'Time in milliseconds to define when the selecting should start. Thishelps prevent unwanted selections when clicking on an element.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the selectable if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Distance', 'Tolerance, in pixels, for when selecting should start. If specified,selecting will not start until the mouse has been dragged beyond thespecified distance.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Tolerance', 'Specifies which mode to use for testing whether the lasso shouldselect an item. Possible values: 	* \"fit\": Lasso overlaps the item entirely.	* \"touch\": Lasso overlaps the item by any amount.', QType::String),
			));
		}
	}


