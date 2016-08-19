<?php	

	/* Custom "property" event classes for this control */
	/**
	 * Type:Function( Element input, Object inst )Default:nullA function that
	 * takes an input field and current datepicker instance and returns an
	 * options object to update the datepicker with. It is called just before
	 * the datepicker is displayed.	 */
	class QDatepicker_BeforeShowEvent extends QJqUiPropertyEvent {
		const EventName = 'QDatepicker_BeforeShow';
		protected $strJqProperty = 'beforeShow';
	}

	/**
	 * Type:Function( Date date )Default:nullA function that takes a date as
	 * a parameter and must return an array with: 
	 * 
	 * 	* [0]: true/false indicating whether or not this date is selectable
	 * 	* [1]: a CSS class name to add to the dates cell or "" for the
	 * default presentation
	 * 	* [2]: an optional popup tooltip for this date
	 * 
	 *  The function is called for each day in the datepicker before it is
	 * displayed.	 */
	class QDatepicker_BeforeShowDayEvent extends QJqUiPropertyEvent {
		const EventName = 'QDatepicker_BeforeShowDay';
		protected $strJqProperty = 'beforeShowDay';
	}

	/**
	 * Type:Function()Default:jQuery.datepicker.iso8601WeekA function to
	 * calculate the week of the year for a given date. The default
	 * implementation uses the ISO 8601 definition: weeks start on a Monday;
	 * the first week of the year contains the first Thursday of the year.	 */
	class QDatepicker_CalculateWeekEvent extends QJqUiPropertyEvent {
		const EventName = 'QDatepicker_CalculateWeek';
		protected $strJqProperty = 'calculateWeek';
	}

	/**
	 * Type:Function( Integer year, Integer month, Object inst
	 * )Default:nullCalled when the datepicker moves to a new month and/or
	 * year. The function receives the selected year, month (1-12), and the
	 * datepicker instance as parameters. this refers to the associated input
	 * field.	 */
	class QDatepicker_ChangeMonthYearEvent extends QJqUiPropertyEvent {
		const EventName = 'QDatepicker_ChangeMonthYear';
		protected $strJqProperty = 'onChangeMonthYear';
	}

	/**
	 * Type:Function( String dateText, Object inst )Default:nullCalled when
	 * the datepicker is closed, whether or not a date is selected. The
	 * function receives the selected date as text ("" if none) and the
	 * datepicker instance as parameters. this refers to the associated input
	 * field.	 */
	class QDatepicker_CloseEvent extends QJqUiPropertyEvent {
		const EventName = 'QDatepicker_Close';
		protected $strJqProperty = 'onClose';
	}

	/**
	 * Type:Function( String dateText, Object inst )Default:nullCalled when
	 * the datepicker is selected. The function receives the selected date as
	 * text and the datepicker instance as parameters. this refers to the
	 * associated input field.	 */
	class QDatepicker_SelectEvent extends QJqUiPropertyEvent {
		const EventName = 'QDatepicker_Select';
		protected $strJqProperty = 'onSelect';
	}


	/**
	 * Generated QDatepickerGen class.
	 * 
	 * This is the QDatepickerGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QDatepickerBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QDatepickerBase
	 * @package Controls\Base
	 * @property mixed $AltField
	 * An input element that is to be updated with the selected date from the
	 * datepicker. Use the altFormat option to change the format of the date
	 * within this field. Leave as blank for no alternate field.
	 *
	 * @property string $AltFormat
	 * The dateFormat to be used for the altField option. This allows one
	 * date format to be shown to the user for selection purposes, while a
	 * different format is actually sent behind the scenes. For a full list
	 * of the possible formats see the formatDate function
	 *
	 * @property string $AppendText
	 * The text to display after each date field, e.g., to show the required
	 * format.
	 *
	 * @property boolean $AutoSize
	 * Set to true to automatically resize the input field to accommodate
	 * dates in the current dateFormat.
	 *
	 * @property QJsClosure $OnBeforeShow
	 * Type:Function( Element input, Object inst )Default:nullA function that
	 * takes an input field and current datepicker instance and returns an
	 * options object to update the datepicker with. It is called just before
	 * the datepicker is displayed.
	 *
	 * @property QJsClosure $OnBeforeShowDay
	 * Type:Function( Date date )Default:nullA function that takes a date as
	 * a parameter and must return an array with: 
	 * 
	 * 	* [0]: true/false indicating whether or not this date is selectable
	 * 	* [1]: a CSS class name to add to the dates cell or "" for the
	 * default presentation
	 * 	* [2]: an optional popup tooltip for this date
	 * 
	 *  The function is called for each day in the datepicker before it is
	 * displayed.
	 *
	 * @property string $ButtonImage
	 * A URL of an image to use to display the datepicker when the showOn
	 * option is set to "button" or "both". If set, the buttonText option
	 * becomes the alt value and is not directly displayed.
	 *
	 * @property boolean $ButtonImageOnly
	 * Whether the button image should be rendered by itself instead of
	 * inside a button element. This option is only relevant if the
	 * buttonImage option has also been set.
	 *
	 * @property string $ButtonText
	 * The text to display on the trigger button. Use in conjunction with the
	 * showOn option set to "button" or "both".
	 *
	 * @property QJsClosure $OnCalculateWeek
	 * Type:Function()Default:jQuery.datepicker.iso8601WeekA function to
	 * calculate the week of the year for a given date. The default
	 * implementation uses the ISO 8601 definition: weeks start on a Monday;
	 * the first week of the year contains the first Thursday of the year.
	 *
	 * @property boolean $ChangeMonth
	 * Whether the month should be rendered as a dropdown instead of text.
	 *
	 * @property boolean $ChangeYear
	 * Whether the year should be rendered as a dropdown instead of text. Use
	 * the yearRange option to control which years are made available for
	 * selection.
	 *
	 * @property string $CloseText
	 * The text to display for the close link. Use the showButtonPanel option
	 * to display this button.
	 *
	 * @property boolean $ConstrainInput
	 * When true, entry in the input field is constrained to those characters
	 * allowed by the current dateFormat option.
	 *
	 * @property string $CurrentText
	 * The text to display for the current day link. Use the showButtonPanel
	 * option to display this button.
	 *
	 * @property string $JqDateFormat
	 * The format for parsed and displayed dates. For a full list of the
	 * possible formats see the formatDate function.
	 *
	 * @property array $DayNames
	 * The list of long day names, starting from Sunday, for use as requested
	 * via the dateFormat option.
	 *
	 * @property array $DayNamesMin
	 * The list of minimised day names, starting from Sunday, for use as
	 * column headers within the datepicker.
	 *
	 * @property array $DayNamesShort
	 * The list of abbreviated day names, starting from Sunday, for use as
	 * requested via the dateFormat option.
	 *
	 * @property mixed $DefaultDate
	 * Set the date to highlight on first opening if the field is blank.
	 * Specify either an actual date via a Date object or as a string in the
	 * current dateFormat, or a number of days from today (e.g. +7) or a
	 * string of values and periods (y for years, m for months, w for weeks,
	 * d for days, e.g. +1m +7d), or null for today.Multiple types supported:
	 * 
	 * 	* Date: A date object containing the default date.
	 * 	* Number: A number of days from today. For example 2 represents two
	 * days from today and -1 represents yesterday.
	 * 	* String: A string in the format defined by the dateFormat option,
	 * or a relative date. Relative dates must contain value and period
	 * pairs; valid periods are "y" for years, "m" for months, "w" for weeks,
	 * and "d" for days. For example, "+1m +7d" represents one month and
	 * seven days from today.
	 * 

	 *
	 * @property QJsClosure $Duration
	 * Control the speed at which the datepicker appears, it may be a time in
	 * milliseconds or a string representing one of the three predefined
	 * speeds ("slow", "normal", "fast").
	 *
	 * @property integer $FirstDay
	 * Set the first day of the week: Sunday is 0, Monday is 1, etc.
	 *
	 * @property boolean $GotoCurrent
	 * When true, the current day link moves to the currently selected date
	 * instead of today.
	 *
	 * @property boolean $HideIfNoPrevNext
	 * Normally the previous and next links are disabled when not applicable
	 * (see the minDate and maxDate options). You can hide them altogether by
	 * setting this attribute to true.
	 *
	 * @property boolean $IsRTL
	 * Whether the current language is drawn from right to left.
	 *
	 * @property mixed $MaxDate
	 * The maximum selectable date. When set to null, there is no
	 * maximum.Multiple types supported:
	 * 
	 * 	* Date: A date object containing the maximum date.
	 * 	* Number: A number of days from today. For example 2 represents two
	 * days from today and -1 represents yesterday.
	 * 	* String: A string in the format defined by the dateFormat option,
	 * or a relative date. Relative dates must contain value and period
	 * pairs; valid periods are "y" for years, "m" for months, "w" for weeks,
	 * and "d" for days. For example, "+1m +7d" represents one month and
	 * seven days from today.
	 * 

	 *
	 * @property mixed $MinDate
	 * The minimum selectable date. When set to null, there is no
	 * minimum.Multiple types supported:
	 * 
	 * 	* Date: A date object containing the minimum date.
	 * 	* Number: A number of days from today. For example 2 represents two
	 * days from today and -1 represents yesterday.
	 * 	* String: A string in the format defined by the dateFormat option,
	 * or a relative date. Relative dates must contain value and period
	 * pairs; valid periods are "y" for years, "m" for months, "w" for weeks,
	 * and "d" for days. For example, "+1m +7d" represents one month and
	 * seven days from today.
	 * 

	 *
	 * @property array $MonthNames
	 * The list of full month names, for use as requested via the dateFormat
	 * option.
	 *
	 * @property array $MonthNamesShort
	 * The list of abbreviated month names, as used in the month header on
	 * each datepicker and as requested via the dateFormat option.
	 *
	 * @property boolean $NavigationAsDateFormat
	 * Whether the currentText, prevText and nextText options should be
	 * parsed as dates by the formatDate function, allowing them to display
	 * the target month names for example.
	 *
	 * @property string $NextText
	 * The text to display for the next month link. With the standard
	 * ThemeRoller styling, this value is replaced by an icon.
	 *
	 * @property mixed $NumberOfMonths
	 * The number of months to show at once.Multiple types supported:
	 * 
	 * 	* Number: The number of months to display in a single row.
	 * 	* Array: An array defining the number of rows and columns to
	 * display.
	 * 

	 *
	 * @property QJsClosure $OnChangeMonthYear
	 * Type:Function( Integer year, Integer month, Object inst
	 * )Default:nullCalled when the datepicker moves to a new month and/or
	 * year. The function receives the selected year, month (1-12), and the
	 * datepicker instance as parameters. this refers to the associated input
	 * field.
	 *
	 * @property QJsClosure $OnClose
	 * Type:Function( String dateText, Object inst )Default:nullCalled when
	 * the datepicker is closed, whether or not a date is selected. The
	 * function receives the selected date as text ("" if none) and the
	 * datepicker instance as parameters. this refers to the associated input
	 * field.
	 *
	 * @property QJsClosure $OnSelect
	 * Type:Function( String dateText, Object inst )Default:nullCalled when
	 * the datepicker is selected. The function receives the selected date as
	 * text and the datepicker instance as parameters. this refers to the
	 * associated input field.
	 *
	 * @property string $PrevText
	 * The text to display for the previous month link. With the standard
	 * ThemeRoller styling, this value is replaced by an icon.
	 *
	 * @property boolean $SelectOtherMonths
	 * Whether days in other months shown before or after the current month
	 * are selectable. This only applies if the showOtherMonths option is set
	 * to true.
	 *
	 * @property mixed $ShortYearCutoff
	 * The cutoff year for determining the century for a date (used in
	 * conjunction with dateFormat y). Any dates entered with a year value
	 * less than or equal to the cutoff year are considered to be in the
	 * current century, while those greater than it are deemed to be in the
	 * previous century.Multiple types supported:
	 * 
	 * 	* Number: A value between 0 and 99 indicating the cutoff year.
	 * 	* String: A relative number of years from the current year, e.g.,
	 * "+3" or "-5".
	 * 

	 *
	 * @property string $ShowAnim
	 * The name of the animation used to show and hide the datepicker. Use
	 * "show" (the default), "slideDown", "fadeIn", any of the jQuery UI
	 * effects. Set to an empty string to disable animation.
	 *
	 * @property boolean $ShowButtonPanel
	 * Whether to display a button pane underneath the calendar. The button
	 * pane contains two buttons, a Today button that links to the current
	 * day, and a Done button that closes the datepicker. The buttons text
	 * can be customized using the currentText and closeText options
	 * respectively.
	 *
	 * @property integer $ShowCurrentAtPos
	 * When displaying multiple months via the numberOfMonths option, the
	 * showCurrentAtPos option defines which position to display the current
	 * month in.
	 *
	 * @property boolean $ShowMonthAfterYear
	 * Whether to show the month after the year in the header.
	 *
	 * @property string $ShowOn
	 * When the datepicker should appear. The datepicker can appear when the
	 * field receives focus ("focus"), when a button is clicked ("button"),
	 * or when either event occurs ("both").
	 *
	 * @property mixed $ShowOptions
	 * If using one of the jQuery UI effects for the showAnim option, you can
	 * provide additional properties for that animation using this option.
	 *
	 * @property boolean $ShowOtherMonths
	 * Whether to display dates in other months (non-selectable) at the start
	 * or end of the current month. To make these days selectable use the
	 * selectOtherMonths option.
	 *
	 * @property boolean $ShowWeek
	 * When true, a column is added to show the week of the year. The
	 * calculateWeek option determines how the week of the year is
	 * calculated. You may also want to change the firstDay option.
	 *
	 * @property integer $StepMonths
	 * Set how many months to move when clicking the previous/next links.
	 *
	 * @property string $WeekHeader
	 * The text to display for the week of the year column heading. Use the
	 * showWeek option to display this column.
	 *
	 * @property string $YearRange
	 * The range of years displayed in the year drop-down: either relative to
	 * todays year ("-nn:+nn"), relative to the currently selected year
	 * ("c-nn:c+nn"), absolute ("nnnn:nnnn"), or combinations of these
	 * formats ("nnnn:-nn"). Note that this option only affects what appears
	 * in the drop-down, to restrict which dates may be selected use the
	 * minDate and/or maxDate options.
	 *
	 * @property string $YearSuffix
	 * Additional text to display after the year in the month headers.
	 *
	 */

	class QDatepickerGen extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixAltField = null;
		/** @var string */
		protected $strAltFormat = null;
		/** @var string */
		protected $strAppendText = null;
		/** @var boolean */
		protected $blnAutoSize = null;
		/** @var QJsClosure */
		protected $mixOnBeforeShow = null;
		/** @var QJsClosure */
		protected $mixOnBeforeShowDay = null;
		/** @var string */
		protected $strButtonImage = null;
		/** @var boolean */
		protected $blnButtonImageOnly = null;
		/** @var string */
		protected $strButtonText = null;
		/** @var QJsClosure */
		protected $mixOnCalculateWeek = null;
		/** @var boolean */
		protected $blnChangeMonth = null;
		/** @var boolean */
		protected $blnChangeYear = null;
		/** @var string */
		protected $strCloseText = null;
		/** @var boolean */
		protected $blnConstrainInput = null;
		/** @var string */
		protected $strCurrentText = null;
		/** @var string */
		protected $strJqDateFormat = null;
		/** @var array */
		protected $arrDayNames = null;
		/** @var array */
		protected $arrDayNamesMin = null;
		/** @var array */
		protected $arrDayNamesShort = null;
		/** @var mixed */
		protected $mixDefaultDate = null;
		/** @var QJsClosure */
		protected $mixDuration = null;
		/** @var integer */
		protected $intFirstDay;
		/** @var boolean */
		protected $blnGotoCurrent = null;
		/** @var boolean */
		protected $blnHideIfNoPrevNext = null;
		/** @var boolean */
		protected $blnIsRTL = null;
		/** @var mixed */
		protected $mixMaxDate = null;
		/** @var mixed */
		protected $mixMinDate = null;
		/** @var array */
		protected $arrMonthNames = null;
		/** @var array */
		protected $arrMonthNamesShort = null;
		/** @var boolean */
		protected $blnNavigationAsDateFormat = null;
		/** @var string */
		protected $strNextText = null;
		/** @var mixed */
		protected $mixNumberOfMonths = null;
		/** @var QJsClosure */
		protected $mixOnChangeMonthYear = null;
		/** @var QJsClosure */
		protected $mixOnClose = null;
		/** @var QJsClosure */
		protected $mixOnSelect = null;
		/** @var string */
		protected $strPrevText = null;
		/** @var boolean */
		protected $blnSelectOtherMonths = null;
		/** @var mixed */
		protected $mixShortYearCutoff = null;
		/** @var string */
		protected $strShowAnim = null;
		/** @var boolean */
		protected $blnShowButtonPanel = null;
		/** @var integer */
		protected $intShowCurrentAtPos;
		/** @var boolean */
		protected $blnShowMonthAfterYear = null;
		/** @var string */
		protected $strShowOn = null;
		/** @var mixed */
		protected $mixShowOptions = null;
		/** @var boolean */
		protected $blnShowOtherMonths = null;
		/** @var boolean */
		protected $blnShowWeek = null;
		/** @var integer */
		protected $intStepMonths = null;
		/** @var string */
		protected $strWeekHeader = null;
		/** @var string */
		protected $strYearRange = null;
		/** @var string */
		protected $strYearSuffix = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->AltField)) {$jqOptions['altField'] = $val;}
			if (!is_null($val = $this->AltFormat)) {$jqOptions['altFormat'] = $val;}
			if (!is_null($val = $this->AppendText)) {$jqOptions['appendText'] = $val;}
			if (!is_null($val = $this->AutoSize)) {$jqOptions['autoSize'] = $val;}
			if (!is_null($val = $this->OnBeforeShow)) {$jqOptions['beforeShow'] = $val;}
			if (!is_null($val = $this->OnBeforeShowDay)) {$jqOptions['beforeShowDay'] = $val;}
			if (!is_null($val = $this->ButtonImage)) {$jqOptions['buttonImage'] = $val;}
			if (!is_null($val = $this->ButtonImageOnly)) {$jqOptions['buttonImageOnly'] = $val;}
			if (!is_null($val = $this->ButtonText)) {$jqOptions['buttonText'] = $val;}
			if (!is_null($val = $this->OnCalculateWeek)) {$jqOptions['calculateWeek'] = $val;}
			if (!is_null($val = $this->ChangeMonth)) {$jqOptions['changeMonth'] = $val;}
			if (!is_null($val = $this->ChangeYear)) {$jqOptions['changeYear'] = $val;}
			if (!is_null($val = $this->CloseText)) {$jqOptions['closeText'] = $val;}
			if (!is_null($val = $this->ConstrainInput)) {$jqOptions['constrainInput'] = $val;}
			if (!is_null($val = $this->CurrentText)) {$jqOptions['currentText'] = $val;}
			if (!is_null($val = $this->JqDateFormat)) {$jqOptions['dateFormat'] = $val;}
			if (!is_null($val = $this->DayNames)) {$jqOptions['dayNames'] = $val;}
			if (!is_null($val = $this->DayNamesMin)) {$jqOptions['dayNamesMin'] = $val;}
			if (!is_null($val = $this->DayNamesShort)) {$jqOptions['dayNamesShort'] = $val;}
			if (!is_null($val = $this->DefaultDate)) {$jqOptions['defaultDate'] = $val;}
			if (!is_null($val = $this->Duration)) {$jqOptions['duration'] = $val;}
			if (!is_null($val = $this->FirstDay)) {$jqOptions['firstDay'] = $val;}
			if (!is_null($val = $this->GotoCurrent)) {$jqOptions['gotoCurrent'] = $val;}
			if (!is_null($val = $this->HideIfNoPrevNext)) {$jqOptions['hideIfNoPrevNext'] = $val;}
			if (!is_null($val = $this->IsRTL)) {$jqOptions['isRTL'] = $val;}
			if (!is_null($val = $this->MaxDate)) {$jqOptions['maxDate'] = $val;}
			if (!is_null($val = $this->MinDate)) {$jqOptions['minDate'] = $val;}
			if (!is_null($val = $this->MonthNames)) {$jqOptions['monthNames'] = $val;}
			if (!is_null($val = $this->MonthNamesShort)) {$jqOptions['monthNamesShort'] = $val;}
			if (!is_null($val = $this->NavigationAsDateFormat)) {$jqOptions['navigationAsDateFormat'] = $val;}
			if (!is_null($val = $this->NextText)) {$jqOptions['nextText'] = $val;}
			if (!is_null($val = $this->NumberOfMonths)) {$jqOptions['numberOfMonths'] = $val;}
			if (!is_null($val = $this->OnChangeMonthYear)) {$jqOptions['onChangeMonthYear'] = $val;}
			if (!is_null($val = $this->OnClose)) {$jqOptions['onClose'] = $val;}
			if (!is_null($val = $this->OnSelect)) {$jqOptions['onSelect'] = $val;}
			if (!is_null($val = $this->PrevText)) {$jqOptions['prevText'] = $val;}
			if (!is_null($val = $this->SelectOtherMonths)) {$jqOptions['selectOtherMonths'] = $val;}
			if (!is_null($val = $this->ShortYearCutoff)) {$jqOptions['shortYearCutoff'] = $val;}
			if (!is_null($val = $this->ShowAnim)) {$jqOptions['showAnim'] = $val;}
			if (!is_null($val = $this->ShowButtonPanel)) {$jqOptions['showButtonPanel'] = $val;}
			if (!is_null($val = $this->ShowCurrentAtPos)) {$jqOptions['showCurrentAtPos'] = $val;}
			if (!is_null($val = $this->ShowMonthAfterYear)) {$jqOptions['showMonthAfterYear'] = $val;}
			if (!is_null($val = $this->ShowOn)) {$jqOptions['showOn'] = $val;}
			if (!is_null($val = $this->ShowOptions)) {$jqOptions['showOptions'] = $val;}
			if (!is_null($val = $this->ShowOtherMonths)) {$jqOptions['showOtherMonths'] = $val;}
			if (!is_null($val = $this->ShowWeek)) {$jqOptions['showWeek'] = $val;}
			if (!is_null($val = $this->StepMonths)) {$jqOptions['stepMonths'] = $val;}
			if (!is_null($val = $this->WeekHeader)) {$jqOptions['weekHeader'] = $val;}
			if (!is_null($val = $this->YearRange)) {$jqOptions['yearRange'] = $val;}
			if (!is_null($val = $this->YearSuffix)) {$jqOptions['yearSuffix'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'datepicker';
		}

		/**
		 * Returns the script that attaches the JQueryUI widget to the html object.
		 *
		 * @return string
		 */
		public function GetEndScript() {
			$strId = $this->GetJqControlId();
			$jqOptions = $this->makeJqOptions();
			$strFunc = $this->getJqSetupFunction();

			if ($strId !== $this->ControlId && QApplication::$RequestMode == QRequestMode::Ajax) {
				// If events are not attached to the actual object being drawn, then the old events will not get
				// deleted during redraw. We delete the old events here. This must happen before any other event processing code.
				QApplication::ExecuteControlCommand($strId, 'off', QJsPriority::High);
			}

			// Attach the javascript widget to the html object
			if (empty($jqOptions)) {
				QApplication::ExecuteControlCommand($strId, $strFunc, QJsPriority::High);
			} else {
				QApplication::ExecuteControlCommand($strId, $strFunc, $jqOptions, QJsPriority::High);
			}

			return parent::GetEndScript();
		}

		/**
		 * Removes the datepicker functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Opens the datepicker in a dialog box.
		 * 
		 * 	* date Type: String or Date The initial date.
		 * 	* onSelect Type: Function() A callback function when a date is
		 * selected. The function receives the date text and date picker instance
		 * as parameters.
		 * 	* options Type: Options The new options for the date picker.
		 * 	* pos Type: Number[2] or MouseEvent The position of the top/left of
		 * the dialog as [x, y] or a MouseEvent that contains the coordinates. If
		 * not specified the dialog is centered on the screen.
		 * @param $date
		 * @param $onSelect
		 * @param $options
		 * @param $pos
		 */
		public function Dialog($date, $onSelect = null, $options = null, $pos = null) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "dialog", $date, $onSelect, $options, $pos, QJsPriority::Low);
		}
		/**
		 * Returns the current date for the datepicker or null if no date has
		 * been selected.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function GetDate() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "getDate", QJsPriority::Low);
		}
		/**
		 * Close a previously opened date picker.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Hide() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "hide", QJsPriority::Low);
		}
		/**
		 * Determine whether a date picker has been disabled.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function IsDisabled() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "isDisabled", QJsPriority::Low);
		}
		/**
		 * Gets the value currently associated with the specified optionName. 
		 * 
		 * Note: For options that have objects as their value, you can get the
		 * value of a specific key by using dot notation. For example, "foo.bar"
		 * would get the value of the bar property on the foo option.
		 * 
		 * 	* optionName Type: String The name of the option to get.
		 * @param $optionName
		 */
		public function Option($optionName) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $optionName, QJsPriority::Low);
		}
		/**
		 * Gets an object containing key/value pairs representing the current
		 * datepicker options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the datepicker option associated with the specified
		 * optionName. 
		 * 
		 * Note: For options that have objects as their value, you can set the
		 * value of just one property by using dot notation for optionName. For
		 * example, "foo.bar" would update only the bar property of the foo
		 * option.
		 * 
		 * 	* optionName Type: String The name of the option to set.
		 * 	* value Type: Object A value to set for the option.
		 * @param $optionName
		 * @param $value
		 */
		public function Option2($optionName, $value) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $optionName, $value, QJsPriority::Low);
		}
		/**
		 * Sets one or more options for the datepicker.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}
		/**
		 * Redraw the date picker, after having made some external modifications.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Refresh() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "refresh", QJsPriority::Low);
		}
		/**
		 * Sets the date for the datepicker. The new date may be a Date object or
		 * a string in the current date format (e.g., "01/26/2009"), a number of
		 * days from today (e.g., +7) or a string of values and periods ("y" for
		 * years, "m" for months, "w" for weeks, "d" for days, e.g., "+1m +7d"),
		 * or null to clear the selected date.
		 * 
		 * 	* date Type: String or Date The new date.
		 * @param $date
		 */
		public function SetDate($date) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "setDate", $date, QJsPriority::Low);
		}
		/**
		 * Open the date picker. If the datepicker is attached to an input, the
		 * input must be visible for the datepicker to be shown.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Show() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "show", QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'AltField': return $this->mixAltField;
				case 'AltFormat': return $this->strAltFormat;
				case 'AppendText': return $this->strAppendText;
				case 'AutoSize': return $this->blnAutoSize;
				case 'OnBeforeShow': return $this->mixOnBeforeShow;
				case 'OnBeforeShowDay': return $this->mixOnBeforeShowDay;
				case 'ButtonImage': return $this->strButtonImage;
				case 'ButtonImageOnly': return $this->blnButtonImageOnly;
				case 'ButtonText': return $this->strButtonText;
				case 'OnCalculateWeek': return $this->mixOnCalculateWeek;
				case 'ChangeMonth': return $this->blnChangeMonth;
				case 'ChangeYear': return $this->blnChangeYear;
				case 'CloseText': return $this->strCloseText;
				case 'ConstrainInput': return $this->blnConstrainInput;
				case 'CurrentText': return $this->strCurrentText;
				case 'JqDateFormat': return $this->strJqDateFormat;
				case 'DayNames': return $this->arrDayNames;
				case 'DayNamesMin': return $this->arrDayNamesMin;
				case 'DayNamesShort': return $this->arrDayNamesShort;
				case 'DefaultDate': return $this->mixDefaultDate;
				case 'Duration': return $this->mixDuration;
				case 'FirstDay': return $this->intFirstDay;
				case 'GotoCurrent': return $this->blnGotoCurrent;
				case 'HideIfNoPrevNext': return $this->blnHideIfNoPrevNext;
				case 'IsRTL': return $this->blnIsRTL;
				case 'MaxDate': return $this->mixMaxDate;
				case 'MinDate': return $this->mixMinDate;
				case 'MonthNames': return $this->arrMonthNames;
				case 'MonthNamesShort': return $this->arrMonthNamesShort;
				case 'NavigationAsDateFormat': return $this->blnNavigationAsDateFormat;
				case 'NextText': return $this->strNextText;
				case 'NumberOfMonths': return $this->mixNumberOfMonths;
				case 'OnChangeMonthYear': return $this->mixOnChangeMonthYear;
				case 'OnClose': return $this->mixOnClose;
				case 'OnSelect': return $this->mixOnSelect;
				case 'PrevText': return $this->strPrevText;
				case 'SelectOtherMonths': return $this->blnSelectOtherMonths;
				case 'ShortYearCutoff': return $this->mixShortYearCutoff;
				case 'ShowAnim': return $this->strShowAnim;
				case 'ShowButtonPanel': return $this->blnShowButtonPanel;
				case 'ShowCurrentAtPos': return $this->intShowCurrentAtPos;
				case 'ShowMonthAfterYear': return $this->blnShowMonthAfterYear;
				case 'ShowOn': return $this->strShowOn;
				case 'ShowOptions': return $this->mixShowOptions;
				case 'ShowOtherMonths': return $this->blnShowOtherMonths;
				case 'ShowWeek': return $this->blnShowWeek;
				case 'StepMonths': return $this->intStepMonths;
				case 'WeekHeader': return $this->strWeekHeader;
				case 'YearRange': return $this->strYearRange;
				case 'YearSuffix': return $this->strYearSuffix;
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'AltField':
					$this->mixAltField = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'altField', $mixValue);
					break;

				case 'AltFormat':
					try {
						$this->strAltFormat = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'altFormat', $this->strAltFormat);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AppendText':
					try {
						$this->strAppendText = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'appendText', $this->strAppendText);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AutoSize':
					try {
						$this->blnAutoSize = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'autoSize', $this->blnAutoSize);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnBeforeShow':
					try {
						$this->mixOnBeforeShow = new QJsClosure($mixValue, array("input","inst"));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnBeforeShowDay':
					try {
						$this->mixOnBeforeShowDay = new QJsClosure($mixValue, array("date"));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ButtonImage':
					try {
						$this->strButtonImage = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'buttonImage', $this->strButtonImage);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ButtonImageOnly':
					try {
						$this->blnButtonImageOnly = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'buttonImageOnly', $this->blnButtonImageOnly);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ButtonText':
					try {
						$this->strButtonText = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'buttonText', $this->strButtonText);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnCalculateWeek':
					try {
						$this->mixOnCalculateWeek = new QJsClosure($mixValue, array(""));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ChangeMonth':
					try {
						$this->blnChangeMonth = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'changeMonth', $this->blnChangeMonth);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ChangeYear':
					try {
						$this->blnChangeYear = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'changeYear', $this->blnChangeYear);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CloseText':
					try {
						$this->strCloseText = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'closeText', $this->strCloseText);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ConstrainInput':
					try {
						$this->blnConstrainInput = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'constrainInput', $this->blnConstrainInput);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CurrentText':
					try {
						$this->strCurrentText = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'currentText', $this->strCurrentText);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'JqDateFormat':
					try {
						$this->strJqDateFormat = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'dateFormat', $this->strJqDateFormat);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DayNames':
					try {
						$this->arrDayNames = QType::Cast($mixValue, QType::ArrayType);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'dayNames', $this->arrDayNames);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DayNamesMin':
					try {
						$this->arrDayNamesMin = QType::Cast($mixValue, QType::ArrayType);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'dayNamesMin', $this->arrDayNamesMin);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DayNamesShort':
					try {
						$this->arrDayNamesShort = QType::Cast($mixValue, QType::ArrayType);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'dayNamesShort', $this->arrDayNamesShort);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DefaultDate':
					$this->mixDefaultDate = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'defaultDate', $mixValue);
					break;

				case 'Duration':
					try {
						$this->mixDuration = QType::Cast($mixValue, 'QJsClosure');
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'duration', $this->mixDuration);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'FirstDay':
					try {
						$this->intFirstDay = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'firstDay', $this->intFirstDay);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'GotoCurrent':
					try {
						$this->blnGotoCurrent = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'gotoCurrent', $this->blnGotoCurrent);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'HideIfNoPrevNext':
					try {
						$this->blnHideIfNoPrevNext = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'hideIfNoPrevNext', $this->blnHideIfNoPrevNext);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'IsRTL':
					try {
						$this->blnIsRTL = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'isRTL', $this->blnIsRTL);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MaxDate':
					$this->mixMaxDate = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'maxDate', $mixValue);
					break;

				case 'MinDate':
					$this->mixMinDate = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'minDate', $mixValue);
					break;

				case 'MonthNames':
					try {
						$this->arrMonthNames = QType::Cast($mixValue, QType::ArrayType);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'monthNames', $this->arrMonthNames);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MonthNamesShort':
					try {
						$this->arrMonthNamesShort = QType::Cast($mixValue, QType::ArrayType);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'monthNamesShort', $this->arrMonthNamesShort);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NavigationAsDateFormat':
					try {
						$this->blnNavigationAsDateFormat = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'navigationAsDateFormat', $this->blnNavigationAsDateFormat);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NextText':
					try {
						$this->strNextText = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'nextText', $this->strNextText);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NumberOfMonths':
					$this->mixNumberOfMonths = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'numberOfMonths', $mixValue);
					break;

				case 'OnChangeMonthYear':
					try {
						$this->mixOnChangeMonthYear = new QJsClosure($mixValue, array("year","month","inst"));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnClose':
					try {
						$this->mixOnClose = new QJsClosure($mixValue, array("dateText","inst"));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSelect':
					try {
						$this->mixOnSelect = new QJsClosure($mixValue, array("dateText","inst"));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PrevText':
					try {
						$this->strPrevText = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'prevText', $this->strPrevText);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SelectOtherMonths':
					try {
						$this->blnSelectOtherMonths = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'selectOtherMonths', $this->blnSelectOtherMonths);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShortYearCutoff':
					$this->mixShortYearCutoff = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'shortYearCutoff', $mixValue);
					break;

				case 'ShowAnim':
					try {
						$this->strShowAnim = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showAnim', $this->strShowAnim);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowButtonPanel':
					try {
						$this->blnShowButtonPanel = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showButtonPanel', $this->blnShowButtonPanel);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowCurrentAtPos':
					try {
						$this->intShowCurrentAtPos = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showCurrentAtPos', $this->intShowCurrentAtPos);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowMonthAfterYear':
					try {
						$this->blnShowMonthAfterYear = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showMonthAfterYear', $this->blnShowMonthAfterYear);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowOn':
					try {
						$this->strShowOn = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showOn', $this->strShowOn);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowOptions':
					$this->mixShowOptions = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showOptions', $mixValue);
					break;

				case 'ShowOtherMonths':
					try {
						$this->blnShowOtherMonths = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showOtherMonths', $this->blnShowOtherMonths);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowWeek':
					try {
						$this->blnShowWeek = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'showWeek', $this->blnShowWeek);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'StepMonths':
					try {
						$this->intStepMonths = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'stepMonths', $this->intStepMonths);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'WeekHeader':
					try {
						$this->strWeekHeader = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'weekHeader', $this->strWeekHeader);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'YearRange':
					try {
						$this->strYearRange = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'yearRange', $this->strYearRange);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'YearSuffix':
					try {
						$this->strYearSuffix = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'yearSuffix', $this->strYearSuffix);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		* If this control is attachable to a codegenerated control in a ModelConnector, this function will be
		* used by the ModelConnector designer dialog to display a list of options for the control.
		* @return QModelConnectorParam[]
		**/
		public static function GetModelConnectorParams() {
			return array_merge(parent::GetModelConnectorParams(), array(
				new QModelConnectorParam (get_called_class(), 'AltFormat', 'The dateFormat to be used for the altField option. This allows onedate format to be shown to the user for selection purposes, while adifferent format is actually sent behind the scenes. For a full listof the possible formats see the formatDate function', QType::String),
				new QModelConnectorParam (get_called_class(), 'AppendText', 'The text to display after each date field, e.g., to show the requiredformat.', QType::String),
				new QModelConnectorParam (get_called_class(), 'AutoSize', 'Set to true to automatically resize the input field to accommodatedates in the current dateFormat.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'OnBeforeShow', 'Type:Function( Element input, Object inst )Default:nullA function thattakes an input field and current datepicker instance and returns anoptions object to update the datepicker with. It is called just beforethe datepicker is displayed.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'OnBeforeShowDay', 'Type:Function( Date date )Default:nullA function that takes a date asa parameter and must return an array with: 	* [0]: true/false indicating whether or not this date is selectable	* [1]: a CSS class name to add to the dates cell or \"\" for thedefault presentation	* [2]: an optional popup tooltip for this date The function is called for each day in the datepicker before it isdisplayed.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'ButtonImage', 'A URL of an image to use to display the datepicker when the showOnoption is set to \"button\" or \"both\". If set, the buttonText optionbecomes the alt value and is not directly displayed.', QType::String),
				new QModelConnectorParam (get_called_class(), 'ButtonImageOnly', 'Whether the button image should be rendered by itself instead ofinside a button element. This option is only relevant if thebuttonImage option has also been set.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'ButtonText', 'The text to display on the trigger button. Use in conjunction with theshowOn option set to \"button\" or \"both\".', QType::String),
				new QModelConnectorParam (get_called_class(), 'OnCalculateWeek', 'Type:Function()Default:jQuery.datepicker.iso8601WeekA function tocalculate the week of the year for a given date. The defaultimplementation uses the ISO 8601 definition: weeks start on a Monday;the first week of the year contains the first Thursday of the year.', 'QJsClosure'),
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
				new QModelConnectorParam (get_called_class(), 'NavigationAsDateFormat', 'Whether the currentText, prevText and nextText options should beparsed as dates by the formatDate function, allowing them to displaythe target month names for example.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'NextText', 'The text to display for the next month link. With the standardThemeRoller styling, this value is replaced by an icon.', QType::String),
				new QModelConnectorParam (get_called_class(), 'OnChangeMonthYear', 'Type:Function( Integer year, Integer month, Object inst)Default:nullCalled when the datepicker moves to a new month and/oryear. The function receives the selected year, month (1-12), and thedatepicker instance as parameters. this refers to the associated inputfield.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'OnClose', 'Type:Function( String dateText, Object inst )Default:nullCalled whenthe datepicker is closed, whether or not a date is selected. Thefunction receives the selected date as text (\"\" if none) and thedatepicker instance as parameters. this refers to the associated inputfield.', 'QJsClosure'),
				new QModelConnectorParam (get_called_class(), 'OnSelect', 'Type:Function( String dateText, Object inst )Default:nullCalled whenthe datepicker is selected. The function receives the selected date astext and the datepicker instance as parameters. this refers to theassociated input field.', 'QJsClosure'),
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