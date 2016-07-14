<?php

// Called by the ModelConnector Designer to create a list of controls appropriate for the given database field type

// You can put one in your __App_Includes__ directory and add to the given type to add your custom controls to the list
// The PLUGINS directories will also be searched

$controls[QControlCategoryType::Text] 			= ['QTextBox', 'QEmailTextBox', 'QUrlTextBox', 'QWriteBox'];
$controls[QControlCategoryType::Blob] 			= ['QTextBox', 'QWriteBox'];
$controls[QControlCategoryType::Char] 			= ['QTextBox'];
$controls[QControlCategoryType::Integer] 		= ['QIntegerTextBox', 'QSpinner', 'QSlider'];
$controls[QControlCategoryType::Float] 			= ['QFloatTextBox'];
$controls[QControlCategoryType::Boolean] 		= ['QCheckBox', 'QJqCheckBox', 'QRadioButton', 'QJqRadioButton'];
$controls[QControlCategoryType::DateTime] 		= ['QDatepicker', 'QDatepickerBox', 'QDateTimePicker', 'QDateTimeTextBox'];
$controls[QControlCategoryType::Date] 			= ['QDatepicker', 'QDatepickerBox', 'QDateTimePicker', 'QDateTimeTextBox'];
$controls[QControlCategoryType::Time] 			= ['QDateTimePicker', 'QDateTimeTextBox'];
$controls[QControlCategoryType::SingleSelect] 	= ['QListBox', 'QRadioButtonList', 'QAutocomplete']; // Select one item from a list of items
$controls[QControlCategoryType::MultiSelect] 	= ['QCheckBoxList', 'QListBox']; // Many-to-many. QListBox works when in Multiselect mode
$controls[QControlCategoryType::Table] 			= ['QDataGrid'];

?>