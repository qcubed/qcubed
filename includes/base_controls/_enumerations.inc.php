<?php
	// This file describes the various enumeration classes that are used throughout the Qforms layer
	/**
	 * Contains the borders type-names that can be used in the CSS 'border' property of block-type elements
	 */
	abstract class QBorderStyle {
		/** No set border */
		const NotSet = 'NotSet';
		/** No border at all */
		const None = 'none';
		/** Border made of dots */
		const Dotted = 'dotted';
		/** BOrder made ofdashes */
		const Dashed = 'dashed';
		/** Solid line border */
		const Solid = 'solid';
		/** Double lined border */
		const Double = 'double';
		/** A 3D groove border */
		const Groove = 'groove';
		/** A 3D ridged border */
		const Ridge = 'ridge';
		/** A 3D inset border */
		const Inset = 'inset';
		/** A 3D outset border */
		const Outset = 'outset';
	}

	/**
	 * Contains the type of 'display' (CSS) style one can use for QControls
	 */
	abstract class QDisplayStyle {
		/** Hide the control */
		const None = 'none';
		/** Treat as a block element */
		const Block = 'block';
		/** Treat as an inline element */
		const Inline = 'inline';
		/** Treat as an inline-block element */
		const InlineBlock = 'inline-block';
		/** Display style not set. Browser will take care */
		const NotSet = 'NotSet';
	}

	/**
	 * Contains the text alignment CSS style options
	 */
	abstract class QTextAlign {
		/** Align the text left */
		const Left = 'left';
		/** Align the text right */
		const Right = 'right';
	}

	/**
	 * Class QRepeatDirection: Set the direction in which QRadioButtonList and QCheckBoxList will be repeated
	 */
	abstract class QRepeatDirection {
		/** Repeat Horizontally */
		const Horizontal = 'Horizontal';
		/** Repeat Vertically */
		const Vertical = 'Vertical';
	}

	/**
	 * Class QGridLines: Set the gridlines which have to be rendered for a QDataGrid. HTML5 no longer supports
	 * the "rules" attributes, so this is now handled in CSS by adding a particular class to the table. So
	 * the text below corresponds to class names added to the table.
	 */
	abstract class QGridLines {
		/** No gridlines to be rendered */
		const None = '';
		/** Horizontal gridlines but not vertical gridlines should be renderd */
		const Horizontal = 'horizontalRules';
		/** Vertical gridlines should be rendered but not horizontal ones */
		const Vertical = 'verticalRules';
		/** Both horizontal and verical gridlines have to be rendered */
		const Both = 'horizontalRules verticalRules';
	}

	/**
	 * Used usually for QListBoxes, it contains the 'multiple' option for select drop-down boxes
	 */
	abstract class QSelectionMode {
		/** Can select only one item. */
		const Single = 'Single';
		/** Can select more than one */
		const Multiple = 'Multiple';
		/** Selection mode not specified */
		const None = 'None';
	}

	/**
	 * The type of textboxes you can create. Most correspond to the input "type" attribute.
	 */
	abstract class QTextMode {
		/** Single line text inputs INPUT type="text" boxes */
		const SingleLine = 'text';
		/** Textareas */
		const MultiLine = 'MultiLine';
		/** Single line password inputs INPUT type="password" boxes */
		const Password = 'password';
		/** HTML5 Search box */
		const Search = 'search';
		/** HTML5 Number box */
		const Number = 'number';
		/** HTML5 email box.  */
		const Email = 'email';
		/** HTML5 telephone box.  */
		const Tel = 'tel';
		/** HTML5 url box.  */
		const Url = 'url';
	}

	/**
	 * Class QHorizontalAlign: Horizontal alignment of a QControl (mostly the text of the control)
	 */
	abstract class QHorizontalAlign {
		/** Not set */
		const NotSet = 'NotSet';
		/** Left align */
		const Left = 'left';
		/** Center align */
		const Center = 'center';
		/** Right align */
		const Right = 'right';
		/** Justify alignment for text */
		const Justify = 'justify';
	}

	/**
	 * Class QVerticalAlign: Vertical alignment of a QControl
	 */
	abstract class QVerticalAlign {
		/** Not set */
		const NotSet = 'NotSet';
		/** Pull to top (top alignment) */
		const Top = 'top';
		/** Center vertically */
		const Middle = 'middle';
		/** Push to bottom (bottom alignment) */
		const Bottom = 'bottom';
	}

	/**
	 * Class QBorderCollapse: css "border-collapse" property for QDataGrid
	 */
	abstract class QBorderCollapse {
		/** Not set */
		const NotSet = 'NotSet';
		/** Borders are not collapsed */
		const Separate = 'Separate';
		/** Collapse the borders */
		const Collapse = 'Collapse';
	}

	/**
	 * Contains the display options for the QDateTimePicker control
	 */
	abstract class QDateTimePickerType {
		/** Show only date */
		const Date = 'Date';
		/** Show date and time */
		const DateTime = 'DateTime';
		/** Show date and time with seconds */
		const DateTimeSeconds = 'DateTimeSeconds';
		/** Show only time (not the date) */
		const Time = 'Time';
		/** Show time with seconds (but not the date) */
		const TimeSeconds = 'TimeSeconds';
	}

	/**
	 * Class QCalendarType: [Currently unused]
	 */
	abstract class QCalendarType {
		/** Date only */
		const DateOnly = 'DateOnly';
		/** Date and time */
		const DateTime = 'DateTime';
		/** Date and time with seconds */
		const DateTimeSeconds = 'DateTimeSeconds';
		/** Time only */
		const TimeOnly = 'TimeOnly';
		/** Time with seconds */
		const TimeSecondsOnly = 'TimeSecondsOnly';
	}

	/**
	 * Order in which the listboxes of QDateTimePicker are shown/rendered
	 */
	abstract class QDateTimePickerFormat {
		/** Render Month, then Day, then Year */
		const MonthDayYear = 'MonthDayYear';
		/** Render Day first, then Month, then Year */
		const DayMonthYear = 'DayMonthYear';
		/** Render Year, then Month and then Day */
		const YearMonthDay = 'YearMonthDay';
	}

	/**
	 * Modes of CrossScripting (XSS) attack preventions supported by QCubed
	 */
	abstract class QCrossScripting {
		/** Let anything pass! */
		const Allow = 'Allow';
		/** Use the PHP's htmlentities function to convert characters */
		const HtmlEntities = 'HtmlEntities';
		/** QCubed's built-in (old/legacy) XSS-prevention technique */
		const Deny = 'Deny';
		/** QCubed's built-in (old/legacy) XSS-prevention technique */
		const Legacy = 'Legacy';
		/** Utilize the HTMLPurifier library to get the job done */
		const HTMLPurifier = 'HTMLPurifier';
	}

	/**
	 * Type of callbacks supported by QCubed
	 */
	abstract class QCallType {
		/** Server call backs which cause full refresh of the page */
		const Server = 'Server';
		/** Ajax Callbacks causing only the respective control to be refreshed */
		const Ajax = 'Ajax';
		/** No callback/undefined */
		const None = 'None';
	}

	/**
	 * Categories of ajax response
	 */
	abstract class QAjaxResponse {
		const Watcher = 'watcher';
		const Controls = 'controls';
		const CommandsHigh = 'commandsHigh';
		const CommandsMedium = 'commands';
		const CommandsLow = 'commandsLow';
		const RegC = 'regc'; // register control list
		const Html = 'html';
		const Value = 'value';
		const Id = 'id';
		const Attributes = 'attributes';
		const Css = 'css';
		const Close = 'winclose';
		const Location = 'loc';
		const Alert = 'alert';
		const StyleSheets = 'ss';
		const JavaScripts = 'js';
	}


/**
	 * Contains options for the CSS 'position' property.
	 */
	abstract class QPosition {
		/** Relative to the normal position */
		const Relative = 'relative';
		/** relative to the first parent element that has a position other than static */
		const Absolute = 'absolute';
		/** Relative to the browser Window */
		const Fixed = 'fixed';
		/** Will result in 'static' positioning. Is default */
		const NotSet = 'NotSet';
	}

	/**
	 * Class QResizeHandleDirection: [Currently Unused]
	 */
	abstract class QResizeHandleDirection {
		/** vertical resize */
		const Vertical = 'Vertical';
		/** horizontal resize */
		const Horizontal = 'Horizontal';
	}

	/**
	 * Contains the CSS styles one can put for the cursor on a "div".
	 */
	abstract class QCursor {
		/** Undefined */
		const NotSet = 'NotSet';
		/** Auto */
		const Auto = 'auto';
		/** Cell selection cursor (like one used in MS Excel) */
		const Cell = 'cell';
		/** Right click context menu icon */
		const ContextMenu = 'context-menu';
		/** The cursor indicates that the column can be resized horizontally */
		const ColResize = 'col-resize';
		/** Indicates something is going to be copied */
		const Copy = 'copy';
		/** Frag the damn enemy! */
		const CrossHair = 'crosshair';
		/** Whatever the browser wants to */
		const CursorDefault = 'default';
		/** Indicating that something can be grabbed (like hand control when reading a PDF) */
		const Grab = 'grab';
		/** Indicating that something is being grabbed (closed hand control when you drag a page in a PDF reader) */
		const Grabbing = 'grabbing';
		/** When you feel like running for your life! (the cursor usually is a '?' symbol) */
		const Help = 'help';
		/** When a dragged element cannot be dropped */
		const NoDrop = 'no-drop';
		/** No cursor at all - cursor gets invisible */
		const None = 'none';
		/** When an action is not allowed (can appear on disabled controls) */
		const NotAllowed = 'not-allowed';
		/** For links (usually creates the 'hand') */
		const Pointer = 'pointer';
		/** Indicates an event in progress */
		const Progress = 'progress';
		/** The icon to move things across */
		const Move = 'move';
		/** Creates the 'I' cursor usually seen over editable controls */
		const Text = 'text';
		/** The text editing (I) cursor rotated 90 degrees for editing vertically written text */
		const VerticalText = 'vertical-text';
		/** Hourglass */
		const Wait = 'wait';
		/** Magnification glass style zoom in (+) cursor */
		const ZoomIn = 'zoom-in';
		/** Magnification glass style zoom out (-) cursor */
		const ZoomOut = 'zoom-out';
		// Resize cursors
		/** Right edge resize */
		const EResize = 'e-resize';
		/** Horizontal bi-directional resize cursor */
		const EWResize = 'ew-resize';
		/** Top edge resize */
		const NResize = 'n-resize';
		/** Top-right resize */
		const NEResize = 'ne-resize';
		/** Bidirectional North-East or South-West resize */
		const NESWResize = 'nesw-resize';
		/** Bidirectional vertical resize cursor */
		const NSResize = 'ns-resize';
		/** Top-left resize */
		const NWResize = 'nw-resize';
		/** Bidirectional North-West or South-East resize cursor */
		const NWSEResize = 'nwse-resize';
		/** Row can be resized (you might see it when trying to alter height of a row in MS Excel) */
		const RowResize = 'row-resize';
		/** Bottom edge resize */
		const SResize = 's-resize';
		/** Bottom-right resize */
		const SEResize = 'se-resize';
		/** Bottom-left resize */
		const SWResize = 'sw-resize';
		/** Left edge resize */
		const WResize = 'w-resize';
	}

	/**
	 * Contains/Defines Overflow CSS Styles to be used on QControls
	 */
	abstract class QOverflow {
		/** Not set */
		const NotSet = 'NotSet';
		/** Decided by browser */
		const Auto = 'auto';
		/** Hide the content flowing outside boundary of the HTML element */
		const Hidden = 'hidden';
		/** The overflow is clipped, but a scroll-bar is added to see the rest of the content */
		const Scroll = 'scroll';
		/** The overflow is not clipped. It renders outside the element's box. This is default */
		const Visible = 'visible';
	}

	/**
	 * Contains The 'CausesValidation' property options
	 * used by buttons which take actions on Forms and controls.
	 */
	abstract class QCausesValidation {
		/** Does not cause the validation */
		const None = false;
		/** Cause validation of all controls */
		const AllControls = true;
		/** Cause validation of the control, siblings and children */
		const SiblingsAndChildren = 2;
		/** Cause validation of siblings only */
		const SiblingsOnly = 3;
	}

	/**
	 * Image Formats
	 */
	abstract class QImageType {
		/** JPEG IMAGE */
		const Jpeg = 'jpg';
		/** PNG IMAGE */
		const Png = 'png';
		/** GIT Image */
		const Gif = 'gif';
		/** Animated GIF image */
		const AnimatedGif = 'AnimatedGif';
	}

	/**
	 * Contains the FileAssetType for an uploaded type.
	 * Is used in the upload controls
	 */
	abstract class QFileAssetType {
		/** The file is an image */
		const Image = 1;
		/** File is a PDF Document */
		const Pdf = 2;
		/** File is a document */
		const Document = 3;
	}

	/**
	 * Modes supported by Meta control for creation of new controls
	 */
	abstract class QModelConnectorCreateType {
		/** Mode to create new or edit existing entry */
		const CreateOrEdit = 1;
		/** Mode to create a new entry/object if a record for requested ID was not found */
		const CreateOnRecordNotFound = 2;
		/** Mode to only edit an entry/object but without allowing creation of a new entry */
		const EditOnly = 3;
	}

	/**
	 * Class QModelConnectorArgumentType
	 * Meta controls are created by input recieved from multiple sources. This class enumerates the three.
	 * Refer to any MetaDataGrid class's AddEditLinkColumn method to see how this is used
	 */
	abstract class QModelConnectorArgumentType {
		/** The Pathinfo supplied to the requested file */
		const PathInfo = 1;
		/** Via a querystring */
		const QueryString = 2;
		/** Via Post Data (not in use currently) */
		const PostData = 3;
	}

	/**
	 * Class QFormGen
	 * For specifying the FormGen param value. Declares what to generate for the given database object.
	 */
	abstract class QFormGen {
		/** Generate both a control and a label */
		const Both = 'both';
		/** Generate only a label */
		const LabelOnly = 'label';
		/** Generate only a control */
		const ControlOnly = 'control';
		/** Do not generate anything for this database object */
		const None = 'none';
	}

	/**
	 * Class QOrderedListType
	 * For specifying how to number an ordered html list. Goes in the type attribute.
	 */
	abstract class QOrderedListType {
		const Numbers = '1';
		const UppercaseLetters = 'A';
		const LowercaseLetters = 'a';
		const UppercaseRoman = 'I';
		const LowercaseRoman = 'i';
	}

	/**
	 * Class QUnorderedListStyle
	 * For specifying what to dislay in an unordered html list. Goes in the list-style-type style.
	 */
	abstract class QUnorderedListStyle {
		const Disc = 'disc';
		const Circle = 'circle';
		const Square = 'square';
		const None = 'none';
	}


?>
