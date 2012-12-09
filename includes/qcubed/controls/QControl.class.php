<?php
	/**
	 * QControl.class.php contains QControl Class
	 * @package Controls
	 */
	/**
	 * QControl is the user overridable Base-Class for all Controls.
	 *
	 * This class is intended to be modified.  Please place any custom modifications to QControl in the file.
	 * The RenderWithName function provided here is a basic rendering.  Feel free to make your own modifcations.
	 * Please note: All custom render methods should start with a RenderHelper call and end with a RenderOutput call.
	 *
	 * @package Controls
	 */
	abstract class QControl extends QControlBase {

		/**
		 * By default, wrappers are turned on for all controls. Wrappers create an extra <div> tag around
		 * QControls, and were historically used to help manipulate QControls, and to group a name and error
		 * message with a control. However, they can at times get in the way. Now that we are using jQuery to
		 * manipulate controls, they are not needed as much, but they are still useful for grouping names and
		 * error messages with a control. If you want to turn global wrappers off and rather set a wrapper for
		 * individual controls, uncomment the line below.
		 */
		//protected $blnUseWrapper = false;

	}

?>
