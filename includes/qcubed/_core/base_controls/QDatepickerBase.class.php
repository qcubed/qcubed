<?php
	/* Custom event classes for this control */
	/**
	 * Can be a function that takes an input field and current datepicker instance
	 * 		and returns an options object to update the datepicker with. It is called
	 * 		just before the datepicker is displayed.
	 */
	class QDatepicker_BeforeShowEvent extends QEvent {
		const EventName = 'QDatepicker_BeforeShow';
	}

	/**
	 * The function takes a date as a parameter and must return an array with [0]
	 * 		equal to true/false indicating whether or not this date is selectable, [1]
	 * 		equal to a CSS class name(s) or '' for the default presentation, and [2] an
	 * 		optional popup tooltip for this date. It is called for each day in the
	 * 		datepicker before it is displayed.
	 */
	class QDatepicker_BeforeShowDayEvent extends QEvent {
		const EventName = 'QDatepicker_BeforeShowDay';
	}

	/**
	 * Allows you to define your own event when the datepicker moves to a new
	 * 		month and/or year. The function receives the selected year, month (1-12),
	 * 		and the datepicker instance as parameters. this refers to the associated
	 * 		input field.
	 */
	class QDatepicker_ChangeMonthYearEvent extends QEvent {
		const EventName = 'QDatepicker_ChangeMonthYear';
	}

	/**
	 * Allows you to define your own event when the datepicker is closed, whether
	 * 		or not a date is selected. The function receives the selected date as text
	 * 		('' if none) and the datepicker instance as parameters. this refers to the
	 * 		associated input field.
	 */
	class QDatepicker_CloseEvent extends QEvent {
		const EventName = 'QDatepicker_Close';
	}

	/**
	 * Allows you to define your own event when the datepicker is selected. The
	 * 		function receives the selected date as text and the datepicker instance as
	 * 		parameters. this refers to the associated input field.
	 */
	class QDatepicker_SelectEvent extends QEvent {
		const EventName = 'QDatepicker_Select';
	}


	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the datepicker. Can be set when
	 * 		initialising (first creating) the datepicker.
	 * @property mixed $AltField The jQuery selector for another field that is to be updated with the
	 * 		selected date from the datepicker. Use the <a
	 * 		href="/UI/Datepicker#option-altFormat" title="UI/Datepicker">altFormat</a>
	 * 		setting to change the format of the date within this field. Leave as blank
	 * 		for no alternate field.
	 * @property string $AltFormat The <a href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a> to be used for the <a
	 * 		href="/UI/Datepicker#option-altField" title="UI/Datepicker">altField</a>
	 * 		option. This allows one date format to be shown to the user for selection
	 * 		purposes, while a different format is actually sent behind the scenes. For
	 * 		a full list of the possible formats see the formatDate function
	 * @property string $AppendText The text to display after each date field, e.g. to show the required
	 * 		format.
	 * @property boolean $AutoSize Set to true to automatically resize the input field to accomodate dates in
	 * 		the current <a href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a>.
	 * @property string $ButtonImage The URL for the popup button image. If set, <a
	 * 		href="/UI/Datepicker#option-buttonText"
	 * 		title="UI/Datepicker">buttonText</a> becomes the alt value and is not
	 * 		directly displayed.
	 * @property boolean $ButtonImageOnly Set to true to place an image after the field to use as the trigger without
	 * 		it appearing on a button.
	 * @property string $ButtonText The text to display on the trigger button. Use in conjunction with <a
	 * 		href="/UI/Datepicker#option-showOn" title="UI/Datepicker">showOn</a> equal
	 * 		to 'button' or 'both'.
	 * @property QJsClosure $CalculateWeek A function to calculate the week of the year for a given date. The default
	 * 		implementation uses the ISO 8601 definition: weeks start on a Monday; the
	 * 		first week of the year contains the first Thursday of the year.
	 * @property boolean $ChangeMonth Allows you to change the month by selecting from a drop-down list. You can
	 * 		enable this feature by setting the attribute to true.
	 * @property boolean $ChangeYear Allows you to change the year by selecting from a drop-down list. You can
	 * 		enable this feature by setting the attribute to true. Use the <a
	 * 		href="/UI/Datepicker#option-yearRange" title="UI/Datepicker">yearRange</a>
	 * 		option to control which years are made available for selection.
	 * @property string $CloseText The text to display for the close link. This attribute is one of the
	 * 		regionalisation attributes. Use the <a
	 * 		href="/UI/Datepicker#option-showButtonPanel"
	 * 		title="UI/Datepicker">showButtonPanel</a> to display this button.
	 * @property boolean $ConstrainInput When true entry in the input field is constrained to those characters
	 * 		allowed by the current <a href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a>.
	 * @property string $CurrentText The text to display for the current day link. This attribute is one of the
	 * 		regionalisation attributes. Use the <a
	 * 		href="/UI/Datepicker#option-showButtonPanel"
	 * 		title="UI/Datepicker">showButtonPanel</a> to display this button.
	 * @property string $JqDateFormat The format for parsed and displayed dates. This attribute is one of the
	 * 		regionalisation attributes. For a full list of the possible formats see the
	 * 		<a href="/UI/Datepicker/formatDate"
	 * 		title="UI/Datepicker/formatDate">formatDate</a> function.
	 * @property array $DayNames The list of long day names, starting from Sunday, for use as requested via
	 * 		the <a href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a> setting. They also appear as popup
	 * 		hints when hovering over the corresponding column headings. This attribute
	 * 		is one of the regionalisation attributes.
	 * @property array $DayNamesMin The list of minimised day names, starting from Sunday, for use as column
	 * 		headers within the datepicker. This attribute is one of the regionalisation
	 * 		attributes.
	 * @property array $DayNamesShort The list of abbreviated day names, starting from Sunday, for use as
	 * 		requested via the <a href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a> setting. This attribute is one of the
	 * 		regionalisation attributes.
	 * @property mixed $DefaultDate Set the date to highlight on first opening if the field is blank. Specify
	 * 		either an actual date via a Date object or as a string in the current <a
	 * 		href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a>, or a number of days from today (e.g.
	 * 		+7) or a string of values and periods ('y' for years, 'm' for months, 'w'
	 * 		for weeks, 'd' for days, e.g. '+1m +7d'), or null for today.
	 * @property mixed $Duration Control the speed at which the datepicker appears, it may be a time in
	 * 		milliseconds or a string representing one of the three predefined speeds
	 * 		("slow", "normal", "fast").
	 * @property integer $FirstDay Set the first day of the week: Sunday is 0, Monday is 1, ... This attribute
	 * 		is one of the regionalisation attributes.
	 * @property boolean $GotoCurrent When true the current day link moves to the currently selected date instead
	 * 		of today.
	 * @property boolean $HideIfNoPrevNext Normally the previous and next links are disabled when not applicable (see
	 * 		<a href="/UI/Datepicker#option-minDate"
	 * 		title="UI/Datepicker">minDate</a>/<a href="/UI/Datepicker#option-maxDate"
	 * 		title="UI/Datepicker">maxDate</a>). You can hide them altogether by setting
	 * 		this attribute to true.
	 * @property boolean $IsRTL True if the current language is drawn from right to left. This attribute is
	 * 		one of the regionalisation attributes.
	 * @property mixed $MaxDate Set a maximum selectable date via a Date object or as a string in the
	 * 		current <a href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a>, or a number of days from today (e.g.
	 * 		+7) or a string of values and periods ('y' for years, 'm' for months, 'w'
	 * 		for weeks, 'd' for days, e.g. '+1m +1w'), or null for no limit.
	 * @property mixed $MinDate Set a minimum selectable date via a Date object or as a string in the
	 * 		current <a href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a>, or a number of days from today (e.g.
	 * 		+7) or a string of values and periods ('y' for years, 'm' for months, 'w'
	 * 		for weeks, 'd' for days, e.g. '-1y -1m'), or null for no limit.
	 * @property array $MonthNames The list of full month names, for use as requested via the <a
	 * 		href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a> setting. This attribute is one of the
	 * 		regionalisation attributes.
	 * @property array $MonthNamesShort The list of abbreviated month names, as used in the month header on each
	 * 		datepicker and as requested via the <a
	 * 		href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a> setting. This attribute is one of the
	 * 		regionalisation attributes.
	 * @property boolean $NavigationAsDateFormat When true the <a href="/UI/Datepicker/formatDate"
	 * 		title="UI/Datepicker/formatDate">formatDate</a> function is applied to the
	 * 		<a href="/UI/Datepicker#option-prevText"
	 * 		title="UI/Datepicker">prevText</a>, <a
	 * 		href="/UI/Datepicker#option-nextText" title="UI/Datepicker">nextText</a>,
	 * 		and <a href="/UI/Datepicker#option-currentText"
	 * 		title="UI/Datepicker">currentText</a> values before display, allowing them
	 * 		to display the target month names for example.
	 * @property string $NextText The text to display for the next month link. This attribute is one of the
	 * 		regionalisation attributes. With the standard ThemeRoller styling, this
	 * 		value is replaced by an icon.
	 * @property mixed $NumberOfMonths Set how many months to show at once. The value can be a straight integer,
	 * 		or can be a two-element array to define the number of rows and columns to
	 * 		display.
	 * @property string $PrevText The text to display for the previous month link. This attribute is one of
	 * 		the regionalisation attributes. With the standard ThemeRoller styling, this
	 * 		value is replaced by an icon.
	 * @property boolean $SelectOtherMonths When true days in other months shown before or after the current month are
	 * 		selectable. This only applies if <a
	 * 		href="/UI/Datepicker#option-showOtherMonths"
	 * 		title="UI/Datepicker">showOtherMonths</a> is also true.
	 * @property mixed $ShortYearCutoff Set the cutoff year for determining the century for a date (used in
	 * 		conjunction with <a href="/UI/Datepicker#option-dateFormat"
	 * 		title="UI/Datepicker">dateFormat</a> 'y'). If a numeric value (0-99) is
	 * 		provided then this value is used directly. If a string value is provided
	 * 		then it is converted to a number and added to the current year. Once the
	 * 		cutoff year is calculated, any dates entered with a year value less than or
	 * 		equal to it are considered to be in the current century, while those
	 * 		greater than it are deemed to be in the previous century.
	 * @property string $ShowAnim Set the name of the animation used to show/hide the datepicker. Use 'show'
	 * 		(the default), 'slideDown', 'fadeIn', any of the show/hide jQuery UI
	 * 		effects, or '' for no animation.
	 * @property boolean $ShowButtonPanel Whether to show the button panel.
	 * @property integer $ShowCurrentAtPos Specify where in a multi-month display the current month shows, starting
	 * 		from 0 at the top/left.
	 * @property boolean $ShowMonthAfterYear Whether to show the month after the year in the header. This attribute is
	 * 		one of the regionalisation attributes.
	 * @property string $ShowOn Have the datepicker appear automatically when the field receives focus
	 * 		('focus'), appear only when a button is clicked ('button'), or appear when
	 * 		either event takes place ('both').
	 * @property array $ShowOptions If using one of the jQuery UI effects for <a
	 * 		href="/UI/Datepicker#option-showAnim" title="UI/Datepicker">showAnim</a>,
	 * 		you can provide additional settings for that animation via this option.
	 * @property boolean $ShowOtherMonths Display dates in other months (non-selectable) at the start or end of the
	 * 		current month. To make these days selectable use <a
	 * 		href="/UI/Datepicker#option-selectOtherMonths"
	 * 		title="UI/Datepicker">selectOtherMonths</a>.
	 * @property boolean $ShowWeek When true a column is added to show the week of the year. The <a
	 * 		href="/UI/Datepicker#option-calculateWeek"
	 * 		title="UI/Datepicker">calculateWeek</a> option determines how the week of
	 * 		the year is calculated. You may also want to change the <a
	 * 		href="/UI/Datepicker#option-firstDay" title="UI/Datepicker">firstDay</a>
	 * 		option.
	 * @property integer $StepMonths Set how many months to move when clicking the Previous/Next links.
	 * @property string $WeekHeader The text to display for the week of the year column heading. This attribute
	 * 		is one of the regionalisation attributes. Use <a
	 * 		href="/UI/Datepicker#option-showWeek" title="UI/Datepicker">showWeek</a> to
	 * 		display this column.
	 * @property string $YearRange Control the range of years displayed in the year drop-down: either relative
	 * 		to today's year (-nn:+nn), relative to the currently selected year
	 * 		(c-nn:c+nn), absolute (nnnn:nnnn), or combinations of these formats
	 * 		(nnnn:-nn). Note that this option only affects what appears in the
	 * 		drop-down, to restrict which dates may be selected use the <a
	 * 		href="/UI/Datepicker#option-minDate" title="UI/Datepicker">minDate</a>
	 * 		and/or <a href="/UI/Datepicker#option-maxDate"
	 * 		title="UI/Datepicker">maxDate</a> options.
	 * @property string $YearSuffix Additional text to display after the year in the month headers. This
	 * 		attribute is one of the regionalisation attributes.
	 * @property QJsClosure $OnBeforeShow Can be a function that takes an input field and current datepicker instance
	 * 		and returns an options object to update the datepicker with. It is called
	 * 		just before the datepicker is displayed.
	 * @property QJsClosure $OnBeforeShowDay The function takes a date as a parameter and must return an array with [0]
	 * 		equal to true/false indicating whether or not this date is selectable, [1]
	 * 		equal to a CSS class name(s) or '' for the default presentation, and [2] an
	 * 		optional popup tooltip for this date. It is called for each day in the
	 * 		datepicker before it is displayed.
	 * @property QJsClosure $OnChangeMonthYear Allows you to define your own event when the datepicker moves to a new
	 * 		month and/or year. The function receives the selected year, month (1-12),
	 * 		and the datepicker instance as parameters. this refers to the associated
	 * 		input field.
	 * @property QJsClosure $OnClose Allows you to define your own event when the datepicker is closed, whether
	 * 		or not a date is selected. The function receives the selected date as text
	 * 		('' if none) and the datepicker instance as parameters. this refers to the
	 * 		associated input field.
	 * @property QJsClosure $OnSelect Allows you to define your own event when the datepicker is selected. The
	 * 		function receives the selected date as text and the datepicker instance as
	 * 		parameters. this refers to the associated input field.
	 */

	class QDatepickerBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var mixed */
		protected $mixAltField = null;
		/** @var string */
		protected $strAltFormat = null;
		/** @var string */
		protected $strAppendText = null;
		/** @var boolean */
		protected $blnAutoSize = null;
		/** @var string */
		protected $strButtonImage = null;
		/** @var boolean */
		protected $blnButtonImageOnly = null;
		/** @var string */
		protected $strButtonText = null;
		/** @var QJsClosure */
		protected $mixCalculateWeek;
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
		/** @var mixed */
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
		/** @var array */
		protected $arrShowOptions = null;
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
		/** @var QJsClosure */
		protected $mixOnBeforeShow = null;
		/** @var QJsClosure */
		protected $mixOnBeforeShowDay = null;
		/** @var QJsClosure */
		protected $mixOnChangeMonthYear = null;
		/** @var QJsClosure */
		protected $mixOnClose = null;
		/** @var QJsClosure */
		protected $mixOnSelect = null;

		/** @var array $custom_events Event Class Name => Event Property Name */
		protected static $custom_events = array(
			'QDatepicker_BeforeShowEvent' => 'OnBeforeShow',
			'QDatepicker_BeforeShowDayEvent' => 'OnBeforeShowDay',
			'QDatepicker_ChangeMonthYearEvent' => 'OnChangeMonthYear',
			'QDatepicker_CloseEvent' => 'OnClose',
			'QDatepicker_SelectEvent' => 'OnSelect',
		);
		
		protected function makeJsProperty($strProp, $strKey) {
			$objValue = $this->$strProp;
			if (null === $objValue) {
				return '';
			}

			return $strKey . ': ' . JavaScriptHelper::toJsObject($objValue) . ', ';
		}

		protected function makeJqOptions() {
			$strJqOptions = '';
			$strJqOptions .= $this->makeJsProperty('Disabled', 'disabled');
			$strJqOptions .= $this->makeJsProperty('AltField', 'altField');
			$strJqOptions .= $this->makeJsProperty('AltFormat', 'altFormat');
			$strJqOptions .= $this->makeJsProperty('AppendText', 'appendText');
			$strJqOptions .= $this->makeJsProperty('AutoSize', 'autoSize');
			$strJqOptions .= $this->makeJsProperty('ButtonImage', 'buttonImage');
			$strJqOptions .= $this->makeJsProperty('ButtonImageOnly', 'buttonImageOnly');
			$strJqOptions .= $this->makeJsProperty('ButtonText', 'buttonText');
			$strJqOptions .= $this->makeJsProperty('CalculateWeek', 'calculateWeek');
			$strJqOptions .= $this->makeJsProperty('ChangeMonth', 'changeMonth');
			$strJqOptions .= $this->makeJsProperty('ChangeYear', 'changeYear');
			$strJqOptions .= $this->makeJsProperty('CloseText', 'closeText');
			$strJqOptions .= $this->makeJsProperty('ConstrainInput', 'constrainInput');
			$strJqOptions .= $this->makeJsProperty('CurrentText', 'currentText');
			$strJqOptions .= $this->makeJsProperty('JqDateFormat', 'dateFormat');
			$strJqOptions .= $this->makeJsProperty('DayNames', 'dayNames');
			$strJqOptions .= $this->makeJsProperty('DayNamesMin', 'dayNamesMin');
			$strJqOptions .= $this->makeJsProperty('DayNamesShort', 'dayNamesShort');
			$strJqOptions .= $this->makeJsProperty('DefaultDate', 'defaultDate');
			$strJqOptions .= $this->makeJsProperty('Duration', 'duration');
			$strJqOptions .= $this->makeJsProperty('FirstDay', 'firstDay');
			$strJqOptions .= $this->makeJsProperty('GotoCurrent', 'gotoCurrent');
			$strJqOptions .= $this->makeJsProperty('HideIfNoPrevNext', 'hideIfNoPrevNext');
			$strJqOptions .= $this->makeJsProperty('IsRTL', 'isRTL');
			$strJqOptions .= $this->makeJsProperty('MaxDate', 'maxDate');
			$strJqOptions .= $this->makeJsProperty('MinDate', 'minDate');
			$strJqOptions .= $this->makeJsProperty('MonthNames', 'monthNames');
			$strJqOptions .= $this->makeJsProperty('MonthNamesShort', 'monthNamesShort');
			$strJqOptions .= $this->makeJsProperty('NavigationAsDateFormat', 'navigationAsDateFormat');
			$strJqOptions .= $this->makeJsProperty('NextText', 'nextText');
			$strJqOptions .= $this->makeJsProperty('NumberOfMonths', 'numberOfMonths');
			$strJqOptions .= $this->makeJsProperty('PrevText', 'prevText');
			$strJqOptions .= $this->makeJsProperty('SelectOtherMonths', 'selectOtherMonths');
			$strJqOptions .= $this->makeJsProperty('ShortYearCutoff', 'shortYearCutoff');
			$strJqOptions .= $this->makeJsProperty('ShowAnim', 'showAnim');
			$strJqOptions .= $this->makeJsProperty('ShowButtonPanel', 'showButtonPanel');
			$strJqOptions .= $this->makeJsProperty('ShowCurrentAtPos', 'showCurrentAtPos');
			$strJqOptions .= $this->makeJsProperty('ShowMonthAfterYear', 'showMonthAfterYear');
			$strJqOptions .= $this->makeJsProperty('ShowOn', 'showOn');
			$strJqOptions .= $this->makeJsProperty('ShowOptions', 'showOptions');
			$strJqOptions .= $this->makeJsProperty('ShowOtherMonths', 'showOtherMonths');
			$strJqOptions .= $this->makeJsProperty('ShowWeek', 'showWeek');
			$strJqOptions .= $this->makeJsProperty('StepMonths', 'stepMonths');
			$strJqOptions .= $this->makeJsProperty('WeekHeader', 'weekHeader');
			$strJqOptions .= $this->makeJsProperty('YearRange', 'yearRange');
			$strJqOptions .= $this->makeJsProperty('YearSuffix', 'yearSuffix');
			$strJqOptions .= $this->makeJsProperty('OnBeforeShow', 'beforeShow');
			$strJqOptions .= $this->makeJsProperty('OnBeforeShowDay', 'beforeShowDay');
			$strJqOptions .= $this->makeJsProperty('OnChangeMonthYear', 'onChangeMonthYear');
			$strJqOptions .= $this->makeJsProperty('OnClose', 'onClose');
			$strJqOptions .= $this->makeJsProperty('OnSelect', 'onSelect');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'datepicker';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			return  $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}

		/**
		 * Remove the datepicker functionality completely. This will return the
		 * element back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the datepicker.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the datepicker.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any datepicker option. If no value is specified, will act as a
		 * getter.
		 * @param $optionName
		 * @param $value
		 */
		public function Option($optionName, $value = null) {
			$args = array();
			$args[] = "option";
			$args[] = $optionName;
			if ($value !== null) {
				$args[] = $value;
			}

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple datepicker options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Open a datepicker in a "dialog" box.
		 * dateText: the initial date for the date picker as either a Date or a
		 * string in the current date format.
		 * onSelect: A callback function when a date is selected. The function
		 * receives the date text and date picker instance as parameters.
		 * settings: The new settings for the date picker.
		 * pos: The position of the top/left of the dialog as [x, y] or a MouseEvent
		 * that contains the coordinates. If not specified the dialog is centered on
		 * the screen.
		 * @param $date
		 * @param $onSelect
		 * @param $settings
		 * @param $pos
		 */
		public function Dialog($date, $onSelect = null, $settings = null, $pos = null) {
			$args = array();
			$args[] = "dialog";
			$args[] = $date;
			if ($onSelect !== null) {
				$args[] = $onSelect;
			}
			if ($settings !== null) {
				$args[] = $settings;
			}
			if ($pos !== null) {
				$args[] = $pos;
			}

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Determine whether a date picker has been disabled.
		 */
		public function IsDisabled() {
			$args = array();
			$args[] = "isDisabled";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Close a previously opened date picker.
		 */
		public function Hide() {
			$args = array();
			$args[] = "hide";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Call up a previously attached date picker.
		 */
		public function Show() {
			$args = array();
			$args[] = "show";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Redraw a date picker, after having made some external modifications.
		 */
		public function Refresh() {
			$args = array();
			$args[] = "refresh";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Returns the current date for the datepicker or null if no date has been
		 * selected.
		 */
		public function GetDate() {
			$args = array();
			$args[] = "getDate";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Sets the current date for the datepicker. The new date may be a Date object
		 * or a string in the current date format (e.g. '01/26/2009'), a number of
		 * days from today (e.g. +7) or a string of values and periods ('y' for years,
		 * 'm' for months, 'w' for weeks, 'd' for days, e.g. '+1m +7d'), or null to
		 * clear the selected date.
		 * @param $date
		 */
		public function SetDate($date) {
			$args = array();
			$args[] = "setDate";
			$args[] = $date;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").datepicker(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * returns the property name corresponding to the given custom event
		 * @param QEvent $objEvent the custom event
		 * @return the property name corresponding to $objEvent
		 */
		protected function getCustomEventPropertyName(QEvent $objEvent) {
			$strEventClass = get_class($objEvent);
			if (array_key_exists($strEventClass, QDatepicker::$custom_events))
				return QDatepicker::$custom_events[$strEventClass];
			return null;
		}

		/**
		 * Wraps $objAction into an object (typically a QJsClosure) that can be assigned to the corresponding Event
		 * property (e.g. OnFocus)
		 * @param QEvent $objEvent
		 * @param QAction $objAction
		 * @return mixed the wrapped object
		 */
		protected function createEventWrapper(QEvent $objEvent, QAction $objAction) {
			$objAction->Event = $objEvent;
			return new QJsClosure($objAction->RenderScript($this));
		}

		/**
		 * If $objEvent is one of the custom events (as determined by getCustomEventPropertyName() method)
		 * the corresponding JQuery event is used and if needed a no-script action is added. Otherwise the normal
		 * QCubed AddAction is performed.
		 * @param QEvent  $objEvent
		 * @param QAction $objAction
		 */
		public function AddAction($objEvent, $objAction) {
			$strEventName = $this->getCustomEventPropertyName($objEvent);
			if ($strEventName) {
				$this->$strEventName = $this->createEventWrapper($objEvent, $objAction);
				if ($objAction instanceof QAjaxAction) {
					$objAction = new QNoScriptAjaxAction($objAction);
					parent::AddAction($objEvent, $objAction);
				} else if (!($objAction instanceof QJavaScriptAction)) {
					throw new Exception('handling of "' . get_class($objAction) . '" actions with "' . get_class($objEvent) . '" events not yet implemented');
				}
			} else {
				parent::AddAction($objEvent, $objAction);
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case 'Disabled': return $this->blnDisabled;
				case 'AltField': return $this->mixAltField;
				case 'AltFormat': return $this->strAltFormat;
				case 'AppendText': return $this->strAppendText;
				case 'AutoSize': return $this->blnAutoSize;
				case 'ButtonImage': return $this->strButtonImage;
				case 'ButtonImageOnly': return $this->blnButtonImageOnly;
				case 'ButtonText': return $this->strButtonText;
				case 'CalculateWeek': return $this->mixCalculateWeek;
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
				case 'PrevText': return $this->strPrevText;
				case 'SelectOtherMonths': return $this->blnSelectOtherMonths;
				case 'ShortYearCutoff': return $this->mixShortYearCutoff;
				case 'ShowAnim': return $this->strShowAnim;
				case 'ShowButtonPanel': return $this->blnShowButtonPanel;
				case 'ShowCurrentAtPos': return $this->intShowCurrentAtPos;
				case 'ShowMonthAfterYear': return $this->blnShowMonthAfterYear;
				case 'ShowOn': return $this->strShowOn;
				case 'ShowOptions': return $this->arrShowOptions;
				case 'ShowOtherMonths': return $this->blnShowOtherMonths;
				case 'ShowWeek': return $this->blnShowWeek;
				case 'StepMonths': return $this->intStepMonths;
				case 'WeekHeader': return $this->strWeekHeader;
				case 'YearRange': return $this->strYearRange;
				case 'YearSuffix': return $this->strYearSuffix;
				case 'OnBeforeShow': return $this->mixOnBeforeShow;
				case 'OnBeforeShowDay': return $this->mixOnBeforeShowDay;
				case 'OnChangeMonthYear': return $this->mixOnChangeMonthYear;
				case 'OnClose': return $this->mixOnClose;
				case 'OnSelect': return $this->mixOnSelect;
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
			$this->blnModified = true;

			switch ($strName) {
				case 'Disabled':
					try {
						$this->blnDisabled = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AltField':
					$this->mixAltField = $mixValue;
					break;

				case 'AltFormat':
					try {
						$this->strAltFormat = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AppendText':
					try {
						$this->strAppendText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AutoSize':
					try {
						$this->blnAutoSize = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ButtonImage':
					try {
						$this->strButtonImage = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ButtonImageOnly':
					try {
						$this->blnButtonImageOnly = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ButtonText':
					try {
						$this->strButtonText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CalculateWeek':
					try {
						$this->mixCalculateWeek = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ChangeMonth':
					try {
						$this->blnChangeMonth = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ChangeYear':
					try {
						$this->blnChangeYear = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CloseText':
					try {
						$this->strCloseText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ConstrainInput':
					try {
						$this->blnConstrainInput = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CurrentText':
					try {
						$this->strCurrentText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'JqDateFormat':
					try {
						$this->strJqDateFormat = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DayNames':
					try {
						$this->arrDayNames = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DayNamesMin':
					try {
						$this->arrDayNamesMin = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DayNamesShort':
					try {
						$this->arrDayNamesShort = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DefaultDate':
					$this->mixDefaultDate = $mixValue;
					break;

				case 'Duration':
					$this->mixDuration = $mixValue;
					break;

				case 'FirstDay':
					try {
						$this->intFirstDay = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'GotoCurrent':
					try {
						$this->blnGotoCurrent = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'HideIfNoPrevNext':
					try {
						$this->blnHideIfNoPrevNext = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'IsRTL':
					try {
						$this->blnIsRTL = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MaxDate':
					$this->mixMaxDate = $mixValue;
					break;

				case 'MinDate':
					$this->mixMinDate = $mixValue;
					break;

				case 'MonthNames':
					try {
						$this->arrMonthNames = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MonthNamesShort':
					try {
						$this->arrMonthNamesShort = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NavigationAsDateFormat':
					try {
						$this->blnNavigationAsDateFormat = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NextText':
					try {
						$this->strNextText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NumberOfMonths':
					$this->mixNumberOfMonths = $mixValue;
					break;

				case 'PrevText':
					try {
						$this->strPrevText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SelectOtherMonths':
					try {
						$this->blnSelectOtherMonths = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShortYearCutoff':
					$this->mixShortYearCutoff = $mixValue;
					break;

				case 'ShowAnim':
					try {
						$this->strShowAnim = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowButtonPanel':
					try {
						$this->blnShowButtonPanel = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowCurrentAtPos':
					try {
						$this->intShowCurrentAtPos = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowMonthAfterYear':
					try {
						$this->blnShowMonthAfterYear = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowOn':
					try {
						$this->strShowOn = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowOptions':
					try {
						$this->arrShowOptions = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowOtherMonths':
					try {
						$this->blnShowOtherMonths = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShowWeek':
					try {
						$this->blnShowWeek = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'StepMonths':
					try {
						$this->intStepMonths = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'WeekHeader':
					try {
						$this->strWeekHeader = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'YearRange':
					try {
						$this->strYearRange = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'YearSuffix':
					try {
						$this->strYearSuffix = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnBeforeShow':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnBeforeShow = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnBeforeShowDay':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnBeforeShowDay = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnChangeMonthYear':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnChangeMonthYear = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnClose':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnClose = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnSelect':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnSelect = QType::Cast($mixValue, 'QJsClosure');
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
	}

?>
