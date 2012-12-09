<?php
	/**
	 * The QDroppableBase class defined here provides an interface between the generated
	 * QDroppableGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QDroppable.class.php file instead.
	 * 
	 * This class is designed to work as a kind of add-on class to a QControl, giving its capabilities
	 * to the control. To make a QControl droppable, simply set $ctl->Droppable = true. You can then 
	 * get to this class to further manipulate the aspects of the droppable through $ctl->DropObj.
	 *
	 */

	/**
	 * @property String $DroppedId ControlId of a control that was dropped onto this
	 */
	class QDroppableBase extends QDroppableGen
	{

		/** @var string */
		protected $strDroppedId = null;

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
		

		// These functions are used to keep track of the selected value, and to implement 
		// optional autocomplete functionality.
		
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			
			$strJS .=<<<FUNC
			.on("drop", function (event, ui) {
			 			qcubed.recordControlModification("$this->ControlId", "_DroppedId", ui.draggable.attr("id"));
					})						
FUNC;
			
			return $strJS;
		}


		public function __set($strName, $mixValue) {
			switch ($strName) {
				case '_DroppedId': // Internal only. Do not use. Used by JS above to track user actions.
					try {
						$this->strDroppedId = QType::Cast($mixValue, QType::String);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
					
				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
			
		}
		
		public function __get($strName) {
			switch ($strName) {
				case 'DroppedId': return $this->strDroppedId;
				
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