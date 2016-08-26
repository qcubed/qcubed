<?php	
	/**
	 * Triggered when the resizable is created.
	 * 
	 * 	* event Type: Event 
	 * 	* ui Type: Object 
	 * 
	 * _Note: The ui object is empty but included for consistency with other
	 * events._	 */
	class QResizable_CreateEvent extends QJqUiEvent {
		const EventName = 'resizecreate';
	}
	/**
	 * This event is triggered during the resize, on the drag of the resize
	 * handler.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* element Type: jQuery The jQuery object representing the element to
	 * be resized
	 * 	* helper Type: jQuery The jQuery object representing the helper
	 * thats being resized
	 * 	* originalElement Type: jQuery The jQuery object representing the
	 * original element before it is wrapped
	 * 	* originalPosition Type: Object The position represented as { left,
	 * top } before the resizable is resized
	 * 	* originalSize Type: Object The size represented as { width, height
	 * } before the resizable is resized
	 * 	* position Type: Object The current position represented as { left,
	 * top }. The values may be changed to modify where the element will be
	 * positioned. Useful for custom resizing logic.
	 * 	* size Type: Object The current size represented as { width, height
	 * }. The values may be changed to modify where the element will be
	 * positioned. Useful for custom resizing logic.
	 * 
	 */
	class QResizable_ResizeEvent extends QJqUiEvent {
		const EventName = 'resize';
	}
	/**
	 * This event is triggered at the start of a resize operation.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* element Type: jQuery The jQuery object representing the element to
	 * be resized
	 * 	* helper Type: jQuery The jQuery object representing the helper
	 * thats being resized
	 * 	* originalElement Type: jQuery The jQuery object representing the
	 * original element before it is wrapped
	 * 	* originalPosition Type: Object The position represented as { left,
	 * top } before the resizable is resized
	 * 	* originalSize Type: Object The size represented as { width, height
	 * } before the resizable is resized
	 * 	* position Type: Object The current position represented as { left,
	 * top }
	 * 	* size Type: Object The current size represented as { width, height
	 * }
	 * 
	 */
	class QResizable_StartEvent extends QJqUiEvent {
		const EventName = 'resizestart';
	}
	/**
	 * This event is triggered at the end of a resize operation.
	 * 
	 * 	* event Type: Event 
	 * 
	 * 	* ui Type: Object 
	 * 
	 * 	* element Type: jQuery The jQuery object representing the element to
	 * be resized
	 * 	* helper Type: jQuery The jQuery object representing the helper
	 * thats being resized
	 * 	* originalElement Type: jQuery The jQuery object representing the
	 * original element before it is wrapped
	 * 	* originalPosition Type: Object The position represented as { left,
	 * top } before the resizable is resized
	 * 	* originalSize Type: Object The size represented as { width, height
	 * } before the resizable is resized
	 * 	* position Type: Object The current position represented as { left,
	 * top }
	 * 	* size Type: Object The current size represented as { width, height
	 * }
	 * 
	 */
	class QResizable_StopEvent extends QJqUiEvent {
		const EventName = 'resizestop';
	}

	/* Custom "property" event classes for this control */

	/**
	 * Generated QResizableGen class.
	 * 
	 * This is the QResizableGen class which is automatically generated
	 * by scraping the JQuery UI documentation website. As such, it includes all the options
	 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
	 * the QResizableBase class for any glue code to make this class more
	 * usable in QCubed.
	 * 
	 * @see QResizableBase
	 * @package Controls\Base
	 * @property mixed $AlsoResize
	 * One or more elements to resize synchronously with the resizable
	 * element.
	 *
	 * @property boolean $Animate
	 * Animates to the final size after resizing.
	 *
	 * @property mixed $AnimateDuration
	 * How long to animate when using the animate option.Multiple types
	 * supported:
	 * 
	 * 	* Number: Duration in milliseconds.
	 * 	* String: A named duration, such as "slow" or "fast".
	 * 

	 *
	 * @property string $AnimateEasing
	 * Which easing to apply when using the animate option.
	 *
	 * @property mixed $AspectRatio
	 * Whether the element should be constrained to a specific aspect
	 * ratio.Multiple types supported:
	 * 
	 * 	* Boolean: When set to true, the element will maintain its original
	 * aspect ratio.
	 * 	* Number: Force the element to maintain a specific aspect ratio
	 * during resizing.
	 * 

	 *
	 * @property boolean $AutoHide
	 * Whether the handles should hide when the user is not hovering over the
	 * element.
	 *
	 * @property mixed $Cancel
	 * Prevents resizing from starting on specified elements.
	 *
	 * @property mixed $Containment
	 * Constrains resizing to within the bounds of the specified element or
	 * region.Multiple types supported:
	 * 
	 * 	* Selector: The resizable element will be contained to the bounding
	 * box of the first element found by the selector. If no element is
	 * found, no containment will be set.
	 * 	* Element: The resizable element will be contained to the bounding
	 * box of this element.
	 * 	* String: Possible values: "parent" and "document".
	 * 

	 *
	 * @property integer $Delay
	 * Tolerance, in milliseconds, for when resizing should start. If
	 * specified, resizing will not start until after mouse is moved beyond
	 * duration. This can help prevent unintended resizing when clicking on
	 * an element.
	 *
	 * @property boolean $Disabled
	 * Disables the resizable if set to true.
	 *
	 * @property integer $Distance
	 * Tolerance, in pixels, for when resizing should start. If specified,
	 * resizing will not start until after mouse is moved beyond distance.
	 * This can help prevent unintended resizing when clicking on an element.
	 *
	 * @property boolean $Ghost
	 * If set to true, a semi-transparent helper element is shown for
	 * resizing.
	 *
	 * @property array $Grid
	 * Snaps the resizing element to a grid, every x and y pixels. Array
	 * values: [ x, y ].
	 *
	 * @property mixed $Handles
	 * Which handles can be used for resizing.Multiple types supported:
	 * 
	 * 	* String: A comma delimited list of any of the following: n, e, s, w,
	 * ne, se, sw, nw, all. The necessary handles will be auto-generated by
	 * the plugin.
	 * 
	 * 	* Object: 
	 * 
	 * The following keys are supported: { n, e, s, w, ne, se, sw, nw }. The
	 * value of any specified should be a jQuery selector matching the child
	 * element of the resizable to use as that handle. If the handle is not a
	 * child of the resizable, you can pass in the DOMElement or a valid
	 * jQuery object directly. 
	 * 
	 * _Note: When generating your own handles, each handle must have the
	 * ui-resizable-handle class, as well as the appropriate
	 * ui-resizable-{direction} class, .e.g., ui-resizable-s._
	 * 

	 *
	 * @property string $Helper
	 * A class name that will be added to a proxy element to outline the
	 * resize during the drag of the resize handle. Once the resize is
	 * complete, the original element is sized.
	 *
	 * @property integer $MaxHeight
	 * The maximum height the resizable should be allowed to resize to.
	 *
	 * @property integer $MaxWidth
	 * The maximum width the resizable should be allowed to resize to.
	 *
	 * @property integer $MinHeight
	 * The minimum height the resizable should be allowed to resize to.
	 *
	 * @property integer $MinWidth
	 * The minimum width the resizable should be allowed to resize to.
	 *
	 */

	abstract class QResizableGen extends QControl	{
		protected $strJavaScripts = __JQUERY_EFFECTS__;
		protected $strStyleSheets = __JQUERY_CSS__;
		/** @var mixed */
		protected $mixAlsoResize = null;
		/** @var boolean */
		protected $blnAnimate = null;
		/** @var mixed */
		protected $mixAnimateDuration = null;
		/** @var string */
		protected $strAnimateEasing = null;
		/** @var mixed */
		protected $mixAspectRatio = null;
		/** @var boolean */
		protected $blnAutoHide = null;
		/** @var mixed */
		protected $mixCancel = null;
		/** @var mixed */
		protected $mixContainment = null;
		/** @var integer */
		protected $intDelay;
		/** @var boolean */
		protected $blnDisabled = null;
		/** @var integer */
		protected $intDistance = null;
		/** @var boolean */
		protected $blnGhost = null;
		/** @var array */
		protected $arrGrid = null;
		/** @var mixed */
		protected $mixHandles = null;
		/** @var string */
		protected $strHelper = null;
		/** @var integer */
		protected $intMaxHeight = null;
		/** @var integer */
		protected $intMaxWidth = null;
		/** @var integer */
		protected $intMinHeight = null;
		/** @var integer */
		protected $intMinWidth = null;

		/**
		 * Builds the option array to be sent to the widget constructor.
		 *
		 * @return array key=>value array of options
		 */
		protected function MakeJqOptions() {
			$jqOptions = null;
			if (!is_null($val = $this->AlsoResize)) {$jqOptions['alsoResize'] = $val;}
			if (!is_null($val = $this->Animate)) {$jqOptions['animate'] = $val;}
			if (!is_null($val = $this->AnimateDuration)) {$jqOptions['animateDuration'] = $val;}
			if (!is_null($val = $this->AnimateEasing)) {$jqOptions['animateEasing'] = $val;}
			if (!is_null($val = $this->AspectRatio)) {$jqOptions['aspectRatio'] = $val;}
			if (!is_null($val = $this->AutoHide)) {$jqOptions['autoHide'] = $val;}
			if (!is_null($val = $this->Cancel)) {$jqOptions['cancel'] = $val;}
			if (!is_null($val = $this->Containment)) {$jqOptions['containment'] = $val;}
			if (!is_null($val = $this->Delay)) {$jqOptions['delay'] = $val;}
			if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
			if (!is_null($val = $this->Distance)) {$jqOptions['distance'] = $val;}
			if (!is_null($val = $this->Ghost)) {$jqOptions['ghost'] = $val;}
			if (!is_null($val = $this->Grid)) {$jqOptions['grid'] = $val;}
			if (!is_null($val = $this->Handles)) {$jqOptions['handles'] = $val;}
			if (!is_null($val = $this->Helper)) {$jqOptions['helper'] = $val;}
			if (!is_null($val = $this->MaxHeight)) {$jqOptions['maxHeight'] = $val;}
			if (!is_null($val = $this->MaxWidth)) {$jqOptions['maxWidth'] = $val;}
			if (!is_null($val = $this->MinHeight)) {$jqOptions['minHeight'] = $val;}
			if (!is_null($val = $this->MinWidth)) {$jqOptions['minWidth'] = $val;}
			return $jqOptions;
		}

		/**
		 * Return the JavaScript function to call to associate the widget with the control.
		 *
		 * @return string
		 */
		public function GetJqSetupFunction() {
			return 'resizable';
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
		 * Removes the resizable functionality completely. This will return the
		 * element back to its pre-init state.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Destroy() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", QJsPriority::Low);
		}
		/**
		 * Disables the resizable.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Disable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", QJsPriority::Low);
		}
		/**
		 * Enables the resizable.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Enable() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", QJsPriority::Low);
		}
		/**
		 * Retrieves the resizables instance object. If the element does not have
		 * an associated instance, undefined is returned. 
		 * 
		 * Unlike other widget methods, instance() is safe to call on any element
		 * after the resizable plugin has loaded.
		 * 
		 * 	* This method does not accept any arguments.
		 */
		public function Instance() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "instance", QJsPriority::Low);
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
		 * resizable options hash.
		 * 
		 * 	* This signature does not accept any arguments.
		 */
		public function Option1() {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", QJsPriority::Low);
		}
		/**
		 * Sets the value of the resizable option associated with the specified
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
		 * Sets one or more options for the resizable.
		 * 
		 * 	* options Type: Object A map of option-value pairs to set.
		 * @param $options
		 */
		public function Option3($options) {
			QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, QJsPriority::Low);
		}


		public function __get($strName) {
			switch ($strName) {
				case 'AlsoResize': return $this->mixAlsoResize;
				case 'Animate': return $this->blnAnimate;
				case 'AnimateDuration': return $this->mixAnimateDuration;
				case 'AnimateEasing': return $this->strAnimateEasing;
				case 'AspectRatio': return $this->mixAspectRatio;
				case 'AutoHide': return $this->blnAutoHide;
				case 'Cancel': return $this->mixCancel;
				case 'Containment': return $this->mixContainment;
				case 'Delay': return $this->intDelay;
				case 'Disabled': return $this->blnDisabled;
				case 'Distance': return $this->intDistance;
				case 'Ghost': return $this->blnGhost;
				case 'Grid': return $this->arrGrid;
				case 'Handles': return $this->mixHandles;
				case 'Helper': return $this->strHelper;
				case 'MaxHeight': return $this->intMaxHeight;
				case 'MaxWidth': return $this->intMaxWidth;
				case 'MinHeight': return $this->intMinHeight;
				case 'MinWidth': return $this->intMinWidth;
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
				case 'AlsoResize':
					$this->mixAlsoResize = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'alsoResize', $mixValue);
					break;

				case 'Animate':
					try {
						$this->blnAnimate = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'animate', $this->blnAnimate);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AnimateDuration':
					$this->mixAnimateDuration = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'animateDuration', $mixValue);
					break;

				case 'AnimateEasing':
					try {
						$this->strAnimateEasing = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'animateEasing', $this->strAnimateEasing);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AspectRatio':
					$this->mixAspectRatio = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'aspectRatio', $mixValue);
					break;

				case 'AutoHide':
					try {
						$this->blnAutoHide = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'autoHide', $this->blnAutoHide);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Cancel':
					$this->mixCancel = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'cancel', $mixValue);
					break;

				case 'Containment':
					$this->mixContainment = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'containment', $mixValue);
					break;

				case 'Delay':
					try {
						$this->intDelay = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'delay', $this->intDelay);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Disabled':
					try {
						$this->blnDisabled = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'disabled', $this->blnDisabled);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Distance':
					try {
						$this->intDistance = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'distance', $this->intDistance);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Ghost':
					try {
						$this->blnGhost = QType::Cast($mixValue, QType::Boolean);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'ghost', $this->blnGhost);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Grid':
					try {
						$this->arrGrid = QType::Cast($mixValue, QType::ArrayType);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'grid', $this->arrGrid);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Handles':
					$this->mixHandles = $mixValue;
					$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'handles', $mixValue);
					break;

				case 'Helper':
					try {
						$this->strHelper = QType::Cast($mixValue, QType::String);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'helper', $this->strHelper);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MaxHeight':
					try {
						$this->intMaxHeight = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'maxHeight', $this->intMaxHeight);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MaxWidth':
					try {
						$this->intMaxWidth = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'maxWidth', $this->intMaxWidth);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MinHeight':
					try {
						$this->intMinHeight = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'minHeight', $this->intMinHeight);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MinWidth':
					try {
						$this->intMinWidth = QType::Cast($mixValue, QType::Integer);
						$this->AddAttributeScript($this->getJqSetupFunction(), 'option', 'minWidth', $this->intMinWidth);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				case 'Enabled':
					$this->Disabled = !$mixValue;	// Tie in standard QCubed functionality
					parent::__set($strName, $mixValue);
					break;
					
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
				new QModelConnectorParam (get_called_class(), 'Animate', 'Animates to the final size after resizing.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'AnimateEasing', 'Which easing to apply when using the animate option.', QType::String),
				new QModelConnectorParam (get_called_class(), 'AutoHide', 'Whether the handles should hide when the user is not hovering over theelement.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Delay', 'Tolerance, in milliseconds, for when resizing should start. Ifspecified, resizing will not start until after mouse is moved beyondduration. This can help prevent unintended resizing when clicking onan element.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the resizable if set to true.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Distance', 'Tolerance, in pixels, for when resizing should start. If specified,resizing will not start until after mouse is moved beyond distance.This can help prevent unintended resizing when clicking on an element.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'Ghost', 'If set to true, a semi-transparent helper element is shown forresizing.', QType::Boolean),
				new QModelConnectorParam (get_called_class(), 'Grid', 'Snaps the resizing element to a grid, every x and y pixels. Arrayvalues: [ x, y ].', QType::ArrayType),
				new QModelConnectorParam (get_called_class(), 'Helper', 'A class name that will be added to a proxy element to outline theresize during the drag of the resize handle. Once the resize iscomplete, the original element is sized.', QType::String),
				new QModelConnectorParam (get_called_class(), 'MaxHeight', 'The maximum height the resizable should be allowed to resize to.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MaxWidth', 'The maximum width the resizable should be allowed to resize to.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MinHeight', 'The minimum height the resizable should be allowed to resize to.', QType::Integer),
				new QModelConnectorParam (get_called_class(), 'MinWidth', 'The minimum width the resizable should be allowed to resize to.', QType::Integer),
			));
		}
	}