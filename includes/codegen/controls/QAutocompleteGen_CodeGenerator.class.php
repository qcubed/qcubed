<?php	class QAutocompleteGen_CodeGenerator extends QTextBox_CodeGenerator	{
		public function __construct() {
			parent::__construct('QAutocompleteGen');
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'AutoFocus', 'If set to true the first item will automatically be focused when themenu is shown.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Delay', 'The delay in milliseconds between when a keystroke occurs and when asearch is performed. A zero-delay makes sense for local data (moreresponsive), but can produce a lot of load for remote data, whilebeing less responsive.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the autocomplete if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'MinLength', 'The minimum number of characters a user must type before a search isperformed. Zero is useful for local data with just a few items, but ahigher value should be used when a single character search could matcha few thousand items.', QType::Integer),
			));
		}
	}


