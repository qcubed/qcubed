<?php

// Called by the MetaControl Designer to create a list of controls appropriate for the given database field type

// You can put one in your __App_Includes__ directory and add to the given type to add your custom controls to the list
// The PLUGINS directories will also be searched

$controls[QType::String] = ['QTextBox', 'QEmailTextBox', 'QUrlTextBox', 'QWriteBox'];
$controls[QType::Integer] = ['QIntegerTextBox', 'QSpinner', 'QSlider'];
$controls[QType::Float] = ['QFloatTextBox'];
$controls[QType::Boolean] = ['QCheckBox', 'QJqCheckBox', 'QRadioButton', 'QJqRadioButton'];
$controls[QType::DateTime] = ['QDatepicker', 'QDatepickerBox', 'QDateTimePicker', 'QDateTimeTextBox'];
$controls[QType::ArrayType] = ['QListBox', 'QRadioButtonList', 'QAutocomplete'];
$controls[QType::Association] = ['QCheckBoxList', 'QListBox']; // Many-to-many. QListBox works when in Multiselect mode

?>