<?php	class QDroppableGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct($strControlClassName = 'QDroppableGen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'ActiveClass', 'If specified, the class will be added to the droppable while anacceptable draggable is being dragged.', QType::String),
				new QModelConnectorParam (get_called_class(), 'AddClasses', 'If set to false, will prevent the ui-droppable class from being added.This may be desired as a performance optimization when calling.droppable() init on hundreds of elements.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the droppable if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Greedy', 'By default, when an element is dropped on nested droppables, eachdroppable will receive the element. However, by setting this option totrue, any parent droppables will not receive the element. The dropevent will still bubble normally, but the event.target can be checkedto see which droppable received the draggable element.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'HoverClass', 'If specified, the class will be added to the droppable while anacceptable draggable is being hovered over the droppable.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Scope', 'Used to group sets of draggable and droppable items, in addition tothe accept option. A draggable with the same scope value as adroppable will be accepted.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Tolerance', 'Specifies which mode to use for testing whether a draggable ishovering over a droppable. Possible values: 	* \"fit\": Draggable overlaps the droppable entirely.	* \"intersect\": Draggable overlaps the droppable at least 50% in bothdirections.	* \"pointer\": Mouse pointer overlaps the droppable.	* \"touch\": Draggable overlaps the droppable any amount.', QType::String),
			));
		}
	}


