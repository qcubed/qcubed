<?php	class QDatepickerGen_CodeGenerator extends QControl_CodeGenerator	{
		public function __construct() {
			parent::__construct('QDatepickerGen');
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'AltFormat', 'The dateFormat to be used for the altField option. This allows onedate format to be shown to the user for selection purposes, while adifferent format is actually sent behind the scenes. For a full listof the possible formats see the formatDate function', QType::String),
				new QModelConnectorParam (get_called_class(), 'AppendText', 'The text to display after each date field, e.g., to show the requiredformat.', QType::String),
				new QModelConnectorParam (get_called_class(), 'AutoSize', 'Set to true to automatically resize the input field to accommodatedates in the current dateFormat.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'OnBeforeShow', 'Default:nullA function that takes an input field and currentdatepicker instance and returns an options object to update thedatepicker with. It is called just before the datepicker is displayed.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'OnBeforeShowDay', 'Default:nullA function that takes a date as a parameter and mustreturn an array with: 	* [0]: true/false indicating whether or not this date is selectable	* [1]: a CSS class name to add to the dates cell or \"\" for thedefault presentation	* [2]: an optional popup tooltip for this date The function is called for each day in the datepicker before it isdisplayed.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'ButtonImage', 'A URL of an image to use to display the datepicker when the showOnoption is set to \"button\" or \"both\". If set, the buttonText optionbecomes the alt value and is not directly displayed.', QType::String),
				new QModelConnectorParam (get_called_class(), 'ButtonImageOnly', 'Whether the button image should be rendered by itself instead ofinside a button element. This option is only relevant if thebuttonImage option has also been set.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ButtonText', 'The text to display on the trigger button. Use in conjunction with theshowOn option set to \"button\" or \"both\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'OnCalculateWeek', 'Default:jQuery.datepicker.iso8601WeekA function to calculate the weekof the year for a given date. The default implementation uses the ISO8601 definition: weeks start on a Monday; the first week of the yearcontains the first Thursday of the year.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'ChangeMonth', 'Whether the month should be rendered as a dropdown instead of text.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ChangeYear', 'Whether the year should be rendered as a dropdown instead of text. Usethe yearRange option to control which years are made available forselection.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'CloseText', 'The text to display for the close link. Use the showButtonPanel optionto display this button.', QType::String),
				new QModelConnectorParam (get_called_class(), 'ConstrainInput', 'When true, entry in the input field is constrained to those charactersallowed by the current dateFormat option.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'CurrentText', 'The text to display for the current day link. Use the showButtonPaneloption to display this button.', QType::String),
				new QModelConnectorParam (get_called_class(), 'JqDateFormat', 'The format for parsed and displayed dates. For a full list of thepossible formats see the formatDate function.', QType::String),
				new QModelConnectorParam (get_called_class(), 'DayNames', 'The list of long day names, starting from Sunday, for use as requestedvia the dateFormat option.', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'DayNamesMin', 'The list of minimised day names, starting from Sunday, for use ascolumn headers within the datepicker.', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'DayNamesShort', 'The list of abbreviated day names, starting from Sunday, for use asrequested via the dateFormat option.', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'Duration', 'Control the speed at which the datepicker appears, it may be a time inmilliseconds or a string representing one of the three predefinedspeeds (\"slow\", \"normal\", \"fast\").', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'FirstDay', 'Set the first day of the week: Sunday is 0, Monday is 1, etc.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'GotoCurrent', 'When true, the current day link moves to the currently selected dateinstead of today.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'HideIfNoPrevNext', 'Normally the previous and next links are disabled when not applicable(see the minDate and maxDate options). You can hide them altogether bysetting this attribute to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'IsRTL', 'Whether the current language is drawn from right to left.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'MonthNames', 'The list of full month names, for use as requested via the dateFormatoption.', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'MonthNamesShort', 'The list of abbreviated month names, as used in the month header oneach datepicker and as requested via the dateFormat option.', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'NavigationAsDateFormat', 'Whether the prevText and nextText options should be parsed as dates bythe formatDate function, allowing them to display the target monthnames for example.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'NextText', 'The text to display for the next month link. With the standardThemeRoller styling, this value is replaced by an icon.', QType::String),
				new QModelConnectorParam (get_called_class(), 'OnChangeMonthYear', 'Default:nullCalled when the datepicker moves to a new month and/oryear. The function receives the selected year, month (1-12), and thedatepicker instance as parameters. this refers to the associated inputfield.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'OnClose', 'Default:nullCalled when the datepicker is closed, whether or not adate is selected. The function receives the selected date as text (\"\"if none) and the datepicker instance as parameters. this refers to theassociated input field.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'OnSelect', 'Default:nullCalled when the datepicker is selected. The functionreceives the selected date as text and the datepicker instance asparameters. this refers to the associated input field.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'PrevText', 'The text to display for the previous month link. With the standardThemeRoller styling, this value is replaced by an icon.', QType::String),
				new QModelConnectorParam (get_called_class(), 'SelectOtherMonths', 'Whether days in other months shown before or after the current monthare selectable. This only applies if the showOtherMonths option is setto true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ShowAnim', 'The name of the animation used to show and hide the datepicker. Use\"show\" (the default), \"slideDown\", \"fadeIn\", any of the jQuery UIeffects. Set to an empty string to disable animation.', QType::String),
				new QModelConnectorParam (get_called_class(), 'ShowButtonPanel', 'Whether to display a button pane underneath the calendar. The buttonpane contains two buttons, a Today button that links to the currentday, and a Done button that closes the datepicker. The buttons textcan be customized using the currentText and closeText optionsrespectively.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ShowCurrentAtPos', 'When displaying multiple months via the numberOfMonths option, theshowCurrentAtPos option defines which position to display the currentmonth in.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'ShowMonthAfterYear', 'Whether to show the month after the year in the header.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ShowOn', 'When the datepicker should appear. The datepicker can appear when thefield receives focus (\"focus\"), when a button is clicked (\"button\"),or when either event occurs (\"both\").', QType::String),
				new QModelConnectorParam (get_called_class(), 'ShowOtherMonths', 'Whether to display dates in other months (non-selectable) at the startor end of the current month. To make these days selectable use theselectOtherMonths option.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ShowWeek', 'When true, a column is added to show the week of the year. ThecalculateWeek option determines how the week of the year iscalculated. You may also want to change the firstDay option.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'StepMonths', 'Set how many months to move when clicking the previous/next links.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'WeekHeader', 'The text to display for the week of the year column heading. Use theshowWeek option to display this column.', QType::String),
				new QModelConnectorParam (get_called_class(), 'YearRange', 'The range of years displayed in the year drop-down: either relative totodays year (\"-nn:+nn\"), relative to the currently selected year(\"c-nn:c+nn\"), absolute (\"nnnn:nnnn\"), or combinations of theseformats (\"nnnn:-nn\"). Note that this option only affects what appearsin the drop-down, to restrict which dates may be selected use theminDate and/or maxDate options.', QType::String),
				new QModelConnectorParam (get_called_class(), 'YearSuffix', 'Additional text to display after the year in the month headers.', QType::String),
			));
		}
	}


