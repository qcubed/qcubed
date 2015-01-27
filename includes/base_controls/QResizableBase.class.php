<?php
	/**
	 * Resizable Base Control
	 * 
	 * The QResizableBase class defined here provides an interface between the generated
	 * QResizableGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QResizable.class.php file instead.
	 *
	 */

	/**
	 * Implements the JQuery UI Resizable capabilities into a QControl
	 * 
	 * This class is designed to work as a kind of add-on class to a QControl, giving its capabilities
	 * to the control. To make a QControl resizable, simply set $ctl->Resizable = true. You can then 
	 * get to this class to further manipulate the aspects of the resizable through $ctl->ResizeObj.
	 * 
	 * @property-read Integer $DeltaX Amount of change in width that happened on the last drag
	 * @property-read Integer $DeltaY Amount of change in height that happened on the last drag
	 * 
	 * @link http://jqueryui.com/resizable/
	 * @package Controls\Base
	 * 
	 */
	class QResizableBase extends QResizableGen
	{
		/** @var array */
		protected $aryOriginalSize = null;
		/** @var array */
		protected $aryNewSize = null;
		
		// redirect all js requests to the parent control
		public function getJqControlId() {
			return $this->objParentControl->ControlId;
		}
		
		public function Render($blnDisplayOutput = true) {}
		protected function GetControlHtml() {}
		public function Validate() {return true;}
		public function ParsePostData() {}
		
		// These functions are used to keep track of the selected value, and to implement 
		// optional autocomplete functionality.
		
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			$strJS .=<<<FUNC
			.on("resizestart", function () {
						var c = jQuery(this);
						c.data ("oW", c.width());
						c.data ("oH", c.height());
			})						
			.on("resizestop", function () {
						var c = jQuery(this);
			 			qcubed.recordControlModification("$this->ControlId", "_ResizeData", c.data("oW") + "," + c.data("oH") + "," + c.width() + "," + c.height());
					})						
FUNC;
			
			return $strJS;
		}


		public function __set($strName, $mixValue) {
			switch ($strName) {
				case '_ResizeData': // Internal only. Do not use. Called by JS above to keep track of user selection.
					try {
						$data = QType::Cast($mixValue, QType::String);
						$a = explode (",", $data);
						$this->aryOriginalSize['width'] = $a[0];
						$this->aryOriginalSize['height'] = $a[1];
						$this->aryNewSize['width'] = $a[2];
						$this->aryNewSize['height'] = $a[3];
						
						// update dimensions
						$this->Width = $this->aryNewSize['width'];
						$this->Height = $this->aryNewSize['height'];
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
		
		public function __get($strName) {
			switch ($strName) {
				case 'DeltaX': 
					if ($this->aryOriginalSize) {
						return $this->aryNewSize['width'] - $this->aryOriginalSize['width'];
					} else {
						return 0;
					}
					
				case 'DeltaY': 
					if ($this->aryOriginalSize) {
						return $this->aryNewSize['height'] - $this->aryOriginalSize['height'];
					} else {
						return 0;
					}
				
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}
		
	}
?>