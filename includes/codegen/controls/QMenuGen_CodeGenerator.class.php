<?php	class QMenuGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct($strControlClassName = 'QMenuGen') {
			parent::__construct($strControlClassName);
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the menu if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Items', 'Selector for the elements that serve as the menu items. Note: Theitems option should not be changed after initialization. (versionadded: 1.11.0)', QType::String),
				new QModelConnectorParam (get_called_class(), 'Menus', 'Selector for the elements that serve as the menu container, includingsub-menus. Note: The menus option should not be changed afterinitialization. Existing submenus will not be updated.', QType::String),
				new QModelConnectorParam (get_called_class(), 'Role', 'Customize the ARIA roles used for the menu and menu items. The defaultuses \"menuitem\" for items. Setting the role option to \"listbox\" willuse \"option\" for items. If set to null, no roles will be set, which isuseful if the menu is being controlled by another element that ismaintaining focus. Note: The role option should not be changed afterinitialization. Existing (sub)menus and menu items will not beupdated.', QType::String),
			));
		}
	}


