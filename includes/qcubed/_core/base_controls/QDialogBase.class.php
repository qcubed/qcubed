<?php
	/* Custom event classes for this control */
	/**
	 * This event is triggered when a dialog attempts to close. If the beforeClose
	 * 		event handler (callback function) returns false, the close will be
	 * 		prevented.
	 */
	class QDialog_BeforeCloseEvent extends QEvent {
		const EventName = 'QDialog_BeforeClose';
	}

	/**
	 * This event is triggered when dialog is opened.
	 */
	class QDialog_OpenEvent extends QEvent {
		const EventName = 'QDialog_Open';
	}

	/**
	 * This event is triggered when the dialog gains focus.
	 */
	class QDialog_FocusEvent extends QEvent {
		const EventName = 'QDialog_Focus';
	}

	/**
	 * This event is triggered at the beginning of the dialog being dragged.
	 */
	class QDialog_DragStartEvent extends QEvent {
		const EventName = 'QDialog_DragStart';
	}

	/**
	 * This event is triggered when the dialog is dragged.
	 */
	class QDialog_DragEvent extends QEvent {
		const EventName = 'QDialog_Drag';
	}

	/**
	 * This event is triggered after the dialog has been dragged.
	 */
	class QDialog_DragStopEvent extends QEvent {
		const EventName = 'QDialog_DragStop';
	}

	/**
	 * This event is triggered at the beginning of the dialog being resized.
	 */
	class QDialog_ResizeStartEvent extends QEvent {
		const EventName = 'QDialog_ResizeStart';
	}

	/**
	 * This event is triggered when the dialog is resized. demo
	 */
	class QDialog_ResizeEvent extends QEvent {
		const EventName = 'QDialog_Resize';
	}

	/**
	 * This event is triggered after the dialog has been resized.
	 */
	class QDialog_ResizeStopEvent extends QEvent {
		const EventName = 'QDialog_ResizeStop';
	}

	/**
	 * This event is triggered when the dialog is closed.
	 */
	class QDialog_CloseEvent extends QEvent {
		const EventName = 'QDialog_Close';
	}


	/**
	 * @property boolean $Disabled Disables (true) or enables (false) the dialog. Can be set when initialising
	 * 		(first creating) the dialog.
	 * @property boolean $AutoOpen When autoOpen is true the dialog will open automatically when dialog is
	 * 		called. If false it will stay hidden until .dialog("open") is called on it.
	 * @property mixed $Buttons Specifies which buttons should be displayed on the dialog. The property key
	 * 		is the text of the button. The value is the callback function for when the
	 * 		button is clicked.  The context of the callback is the dialog element; if
	 * 		you need access to the button, it is available as the target of the event
	 * 		object.
	 * @property array $Buttons1 Specifies which buttons should be displayed on the dialog. Each element of
	 * 		the array must be an Object defining the properties to set on the button.
	 * @property boolean $CloseOnEscape Specifies whether the dialog should close when it has focus and the user
	 * 		presses the esacpe (ESC) key.
	 * @property string $CloseText Specifies the text for the close button. Note that the close text is
	 * 		visibly hidden when using a standard theme.
	 * @property string $DialogClass The specified class name(s) will be added to the dialog, for additional
	 * 		theming.
	 * @property boolean $Draggable If set to true, the dialog will be draggable will be draggable by the
	 * 		titlebar.
	 * @property integer $Height The height of the dialog, in pixels. Specifying 'auto' is also supported to
	 * 		make the dialog adjust based on its content.
	 * @property string $Hide The effect to be used when the dialog is closed.
	 * @property integer $MaxHeight The maximum height to which the dialog can be resized, in pixels.
	 * @property integer $MaxWidth The maximum width to which the dialog can be resized, in pixels.
	 * @property integer $MinHeight The minimum height to which the dialog can be resized, in pixels.
	 * @property integer $MinWidth The minimum width to which the dialog can be resized, in pixels.
	 * @property boolean $Modal If set to true, the dialog will have modal behavior; other items on the
	 * 		page will be disabled (i.e. cannot be interacted with). Modal dialogs
	 * 		create an overlay below the dialog but above other page elements.
	 * @property mixed $Position Specifies where the dialog should be displayed. Possible values: 1) a
	 * 		single string representing position within viewport: 'center', 'left',
	 * 		'right', 'top', 'bottom'. 2) an array containing an x,y coordinate pair in
	 * 		pixel offset from left, top corner of viewport (e.g. [350,100]) 3) an array
	 * 		containing x,y position string values (e.g. ['right','top'] for top right
	 * 		corner).
	 * @property boolean $Resizable If set to true, the dialog will be resizeable.
	 * @property string $Show The effect to be used when the dialog is opened.
	 * @property boolean $Stack Specifies whether the dialog will stack on top of other dialogs. This will
	 * 		cause the dialog to move to the front of other dialogs when it gains focus.
	 * @property string $Title Specifies the title of the dialog. Any valid HTML may be set as the title.
	 * 		The title can also be specified by the title attribute on the dialog source
	 * 		element.
	 * @property integer $Width The width of the dialog, in pixels.
	 * @property integer $ZIndex The starting z-index for the dialog.
	 * @property QJsClosure $OnBeforeClose This event is triggered when a dialog attempts to close. If the beforeClose
	 * 		event handler (callback function) returns false, the close will be
	 * 		prevented.
	 * @property QJsClosure $OnOpen This event is triggered when dialog is opened.
	 * @property QJsClosure $OnFocus This event is triggered when the dialog gains focus.
	 * @property QJsClosure $OnDragStart This event is triggered at the beginning of the dialog being dragged.
	 * @property QJsClosure $OnDrag This event is triggered when the dialog is dragged.
	 * @property QJsClosure $OnDragStop This event is triggered after the dialog has been dragged.
	 * @property QJsClosure $OnResizeStart This event is triggered at the beginning of the dialog being resized.
	 * @property QJsClosure $OnResize This event is triggered when the dialog is resized. demo
	 * @property QJsClosure $OnResizeStop This event is triggered after the dialog has been resized.
	 * @property QJsClosure $OnClose This event is triggered when the dialog is closed.
	 */

	class QDialogBase extends QPanel	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var boolean */
		protected $blnAutoOpen = null;
		/** @var mixed */
		protected $mixButtons = null;
		/** @var array */
		protected $arrButtons1 = null;
		/** @var boolean */
		protected $blnCloseOnEscape = null;
		/** @var string */
		protected $strCloseText = null;
		/** @var string */
		protected $strDialogClass = null;
		/** @var boolean */
		protected $blnDraggable = null;
		/** @var integer */
		protected $intHeight = null;
		/** @var string */
		protected $strHide = null;
		/** @var integer */
		protected $intMaxHeight = null;
		/** @var integer */
		protected $intMaxWidth = null;
		/** @var integer */
		protected $intMinHeight = null;
		/** @var integer */
		protected $intMinWidth = null;
		/** @var boolean */
		protected $blnModal = null;
		/** @var mixed */
		protected $mixPosition = null;
		/** @var boolean */
		protected $blnResizable = null;
		/** @var string */
		protected $strShow = null;
		/** @var boolean */
		protected $blnStack = null;
		/** @var string */
		protected $strTitle = null;
		/** @var integer */
		protected $intWidth = null;
		/** @var integer */
		protected $intZIndex = null;
		/** @var QJsClosure */
		protected $mixOnBeforeClose = null;
		/** @var QJsClosure */
		protected $mixOnOpen = null;
		/** @var QJsClosure */
		protected $mixOnFocus = null;
		/** @var QJsClosure */
		protected $mixOnDragStart = null;
		/** @var QJsClosure */
		protected $mixOnDrag = null;
		/** @var QJsClosure */
		protected $mixOnDragStop = null;
		/** @var QJsClosure */
		protected $mixOnResizeStart = null;
		/** @var QJsClosure */
		protected $mixOnResize = null;
		/** @var QJsClosure */
		protected $mixOnResizeStop = null;
		/** @var QJsClosure */
		protected $mixOnClose = null;

		/** @var array $custom_events Event Class Name => Event Property Name */
		protected static $custom_events = array(
			'QDialog_BeforeCloseEvent' => 'OnBeforeClose',
			'QDialog_OpenEvent' => 'OnOpen',
			'QDialog_FocusEvent' => 'OnFocus',
			'QDialog_DragStartEvent' => 'OnDragStart',
			'QDialog_DragEvent' => 'OnDrag',
			'QDialog_DragStopEvent' => 'OnDragStop',
			'QDialog_ResizeStartEvent' => 'OnResizeStart',
			'QDialog_ResizeEvent' => 'OnResize',
			'QDialog_ResizeStopEvent' => 'OnResizeStop',
			'QDialog_CloseEvent' => 'OnClose',
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
			$strJqOptions .= $this->makeJsProperty('AutoOpen', 'autoOpen');
			$strJqOptions .= $this->makeJsProperty('Buttons', 'buttons');
			$strJqOptions .= $this->makeJsProperty('Buttons1', 'buttons');
			$strJqOptions .= $this->makeJsProperty('CloseOnEscape', 'closeOnEscape');
			$strJqOptions .= $this->makeJsProperty('CloseText', 'closeText');
			$strJqOptions .= $this->makeJsProperty('DialogClass', 'dialogClass');
			$strJqOptions .= $this->makeJsProperty('Draggable', 'draggable');
			$strJqOptions .= $this->makeJsProperty('Height', 'height');
			$strJqOptions .= $this->makeJsProperty('Hide', 'hide');
			$strJqOptions .= $this->makeJsProperty('MaxHeight', 'maxHeight');
			$strJqOptions .= $this->makeJsProperty('MaxWidth', 'maxWidth');
			$strJqOptions .= $this->makeJsProperty('MinHeight', 'minHeight');
			$strJqOptions .= $this->makeJsProperty('MinWidth', 'minWidth');
			$strJqOptions .= $this->makeJsProperty('Modal', 'modal');
			$strJqOptions .= $this->makeJsProperty('Position', 'position');
			$strJqOptions .= $this->makeJsProperty('Resizable', 'resizable');
			$strJqOptions .= $this->makeJsProperty('Show', 'show');
			$strJqOptions .= $this->makeJsProperty('Stack', 'stack');
			$strJqOptions .= $this->makeJsProperty('Title', 'title');
			$strJqOptions .= $this->makeJsProperty('Width', 'width');
			$strJqOptions .= $this->makeJsProperty('ZIndex', 'zIndex');
			$strJqOptions .= $this->makeJsProperty('OnBeforeClose', 'beforeClose');
			$strJqOptions .= $this->makeJsProperty('OnOpen', 'open');
			$strJqOptions .= $this->makeJsProperty('OnFocus', 'focus');
			$strJqOptions .= $this->makeJsProperty('OnDragStart', 'dragStart');
			$strJqOptions .= $this->makeJsProperty('OnDrag', 'drag');
			$strJqOptions .= $this->makeJsProperty('OnDragStop', 'dragStop');
			$strJqOptions .= $this->makeJsProperty('OnResizeStart', 'resizeStart');
			$strJqOptions .= $this->makeJsProperty('OnResize', 'resize');
			$strJqOptions .= $this->makeJsProperty('OnResizeStop', 'resizeStop');
			$strJqOptions .= $this->makeJsProperty('OnClose', 'close');
			if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
			return $strJqOptions;
		}

		protected function getJqControlId() {
			return $this->ControlId;
		}

		protected function getJqSetupFunction() {
			return 'dialog';
		}

		public function GetControlJavaScript() {
			return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
		}

		public function GetEndScript() {
			return  $this->GetControlJavaScript() . '; ' . parent::GetEndScript();
		}

		/**
		 * Remove the dialog functionality completely. This will return the element
		 * back to its pre-init state.
		 */
		public function Destroy() {
			$args = array();
			$args[] = "destroy";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Disable the dialog.
		 */
		public function Disable() {
			$args = array();
			$args[] = "disable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Enable the dialog.
		 */
		public function Enable() {
			$args = array();
			$args[] = "enable";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Get or set any dialog option. If no value is specified, will act as a
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
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Set multiple dialog options at once by providing an options object.
		 * @param $options
		 */
		public function Option1($options) {
			$args = array();
			$args[] = "option";
			$args[] = $options;

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Close the dialog.
		 */
		public function Close() {
			$args = array();
			$args[] = "close";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Returns true if the dialog is currently open.
		 */
		public function IsOpen() {
			$args = array();
			$args[] = "isOpen";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Move the dialog to the top of the dialogs stack.
		 */
		public function MoveToTop() {
			$args = array();
			$args[] = "moveToTop";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
				$this->getJqControlId(),
				substr($strArgs, 1, strlen($strArgs)-2));
			QApplication::ExecuteJavaScript($strJs);
		}

		/**
		 * Open the dialog.
		 */
		public function Open() {
			$args = array();
			$args[] = "open";

			$strArgs = JavaScriptHelper::toJsObject($args);
			$strJs = sprintf('jQuery("#%s").dialog(%s)', 
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
			if (array_key_exists($strEventClass, QDialog::$custom_events))
				return QDialog::$custom_events[$strEventClass];
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
				case 'AutoOpen': return $this->blnAutoOpen;
				case 'Buttons': return $this->mixButtons;
				case 'Buttons1': return $this->arrButtons1;
				case 'CloseOnEscape': return $this->blnCloseOnEscape;
				case 'CloseText': return $this->strCloseText;
				case 'DialogClass': return $this->strDialogClass;
				case 'Draggable': return $this->blnDraggable;
				case 'Height': return $this->intHeight;
				case 'Hide': return $this->strHide;
				case 'MaxHeight': return $this->intMaxHeight;
				case 'MaxWidth': return $this->intMaxWidth;
				case 'MinHeight': return $this->intMinHeight;
				case 'MinWidth': return $this->intMinWidth;
				case 'Modal': return $this->blnModal;
				case 'Position': return $this->mixPosition;
				case 'Resizable': return $this->blnResizable;
				case 'Show': return $this->strShow;
				case 'Stack': return $this->blnStack;
				case 'Title': return $this->strTitle;
				case 'Width': return $this->intWidth;
				case 'ZIndex': return $this->intZIndex;
				case 'OnBeforeClose': return $this->mixOnBeforeClose;
				case 'OnOpen': return $this->mixOnOpen;
				case 'OnFocus': return $this->mixOnFocus;
				case 'OnDragStart': return $this->mixOnDragStart;
				case 'OnDrag': return $this->mixOnDrag;
				case 'OnDragStop': return $this->mixOnDragStop;
				case 'OnResizeStart': return $this->mixOnResizeStart;
				case 'OnResize': return $this->mixOnResize;
				case 'OnResizeStop': return $this->mixOnResizeStop;
				case 'OnClose': return $this->mixOnClose;
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

				case 'AutoOpen':
					try {
						$this->blnAutoOpen = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Buttons':
					$this->mixButtons = $mixValue;
					break;

				case 'Buttons1':
					try {
						$this->arrButtons1 = QType::Cast($mixValue, QType::ArrayType);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CloseOnEscape':
					try {
						$this->blnCloseOnEscape = QType::Cast($mixValue, QType::Boolean);
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

				case 'DialogClass':
					try {
						$this->strDialogClass = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Draggable':
					try {
						$this->blnDraggable = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Height':
					try {
						$this->intHeight = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Hide':
					try {
						$this->strHide = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MaxHeight':
					try {
						$this->intMaxHeight = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MaxWidth':
					try {
						$this->intMaxWidth = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MinHeight':
					try {
						$this->intMinHeight = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MinWidth':
					try {
						$this->intMinWidth = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Modal':
					try {
						$this->blnModal = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Position':
					$this->mixPosition = $mixValue;
					break;

				case 'Resizable':
					try {
						$this->blnResizable = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Show':
					try {
						$this->strShow = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Stack':
					try {
						$this->blnStack = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Title':
					try {
						$this->strTitle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Width':
					try {
						$this->intWidth = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ZIndex':
					try {
						$this->intZIndex = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnBeforeClose':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnBeforeClose = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnOpen':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnOpen = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnFocus':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnFocus = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnDragStart':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnDragStart = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnDrag':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnDrag = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnDragStop':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnDragStop = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnResizeStart':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnResizeStart = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnResize':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnResize = QType::Cast($mixValue, 'QJsClosure');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'OnResizeStop':
					try {
						if ($mixValue instanceof QJavaScriptAction) {
						    /** @var QJavaScriptAction $mixValue */
						    $mixValue = new QJsClosure($mixValue->RenderScript($this));
						}
						$this->mixOnResizeStop = QType::Cast($mixValue, 'QJsClosure');
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
