<?php
	/**
	 * @package Controls
	 * @filesource
	 */

	/**
	 * Abstract class which is extended by things like Buttons.
	 */
	abstract class QActionControl extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		//////////
		// Methods
		//////////
		public function ParsePostData() {
		}

		public function Validate() {
			return true;
		}
	}
?>