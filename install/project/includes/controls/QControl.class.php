<?php
	/**
	 * Contains the QControl Class - one of the most important classes in the framework
	 * @package Controls
	 * @filesource
	 */
	/**
	 * QControl is the user overridable Base-Class for all Controls.
	 *
	 * This class is intended to be modified. If you need to modify the class (looks or behavior), then place any
	 * custom modifications to QControl in the file.
	 *
	 * <b>Please note</b>: All custom render methods should start with a RenderHelper call and end with a RenderOutput call.
	 * Also, read the class file to learn about the wrappers (the HTML elements with the '_ctl' at the end of their
	 * 'id' attribute) and how to disable them. 
	 *
	 * @package Controls
	 */
	abstract class QControl extends QControlBase {

		/**
		 * By default, wrappers are turned on for all controls. Wrappers create an extra <div> tag around
		 * QControls, and were historically used to help manipulate QControls, and to group a name and error
		 * message with a control. However, they can at times get in the way. Now that we are using jQuery to
		 * manipulate controls, they are not needed as much, but they are still needed if you are showing
		 * and hiding items that are grouped with other items.
		 */
		//protected $blnUseWrapper = false;
	}