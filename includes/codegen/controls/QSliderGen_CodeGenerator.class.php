<?php	class QSliderGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct() {
			parent::__construct('QSliderGen');
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the slider if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Max', 'The maximum value of the slider.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Min', 'The minimum value of the slider.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Orientation', 'Determines whether the slider handles move horizontally (min on left,max on right) or vertically (min on bottom, max on top). Possiblevalues: \"horizontal\", \"vertical\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'Step', 'Determines the size or amount of each interval or step the slidertakes between the min and max. The full specified value range of theslider (max - min) should be evenly divisible by the step.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Value', 'Determines the value of the slider, if theres only one handle. Ifthere is more than one handle, determines the value of the firsthandle.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Values', 'This option can be used to specify multiple handles. If the rangeoption is set to true, the length of values should be 2.', QType::ArrayType),
			));
		}
	}


