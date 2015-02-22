<?php

// Called by the ModelConnector Designer to create a list of controls appropriate for the given database field type

// You can put one in your __App_Includes__ directory and add to the given type to add your custom controls to the list
// The PLUGINS directories will also be searched

$controls[QDatabaseFieldType::VarChar] = ['QTextBox', 'QEmailTextBox', 'QUrlTextBox', 'QWriteBox'];
$controls[QDatabaseFieldType::Blob] = ['QTextBox', 'QWriteBox'];
$controls[QDatabaseFieldType::Char] = ['QTextBox'];
$controls[QDatabaseFieldType::Integer] = ['QIntegerTextBox', 'QSpinner', 'QSlider'];
$controls[QDatabaseFieldType::Float] = ['QFloatTextBox'];
$controls[QDatabaseFieldType::Bit] = ['QCheckBox', 'QJqCheckBox', 'QRadioButton', 'QJqRadioButton'];
$controls[QDatabaseFieldType::DateTime] = ['QDatepicker', 'QDatepickerBox', 'QDateTimePicker', 'QDateTimeTextBox'];
$controls[QDatabaseFieldType::Date] = ['QDatepicker', 'QDatepickerBox', 'QDateTimePicker', 'QDateTimeTextBox'];
$controls[QDatabaseFieldType::Time] = ['QDateTimePicker', 'QDateTimeTextBox'];
$controls[QType::ArrayType] = ['QListBox', 'QRadioButtonList', 'QAutocomplete'];
$controls[QType::Association] = ['QCheckBoxList', 'QListBox']; // Many-to-many. QListBox works when in Multiselect mode

?>