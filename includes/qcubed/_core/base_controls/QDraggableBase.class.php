<?php
	/**
	 * The QDraggableBase class defined here provides an interface between the generated
	 * QDraggableGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QDraggable.class.php file instead.
	 * 
	 * This class is designed to work as a kind of add-on class to a QControl, giving its capabilities
	 * to the control. To make a QControl draggable, simply set $ctl->Dragable = true. You can then 
	 * get to this class to further manipulate the aspects of the draggable through $ctl->DragObj.
	 *
	 */

	/**
	 * @property-read Integer $DeltaX Amount of change in left that happened on the last drag
	 * @property-read Integer $DeltaY Amount of change in top that happened on the last drag
	 * @property mixed $Handle A drag handle. Can be a control, array of controls, array of control ids, or jQuery selector.
	 */
	class QDraggableBase extends QDraggableGen
	{
		/** Revert Modes */
		const RevertOn = true;				// always revert
		const RevertOff = false; 			// never revert
		const RevertValid = 'valid';		// revert if dropped successfully
		const RevertInvalid = 'invalid'; 	// revert if not dropped successfully
		
		/** @var array */
		protected $aryOriginalPosition = null;
		/** @var array */
		protected $aryNewPosition = null;

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
			.on("dragstop", function (event, ui) {
			 			qcubed.recordControlModification("$this->ControlId", "_DragData", ui.originalPosition.left + "," + ui.originalPosition.top + "," + ui.position.left + "," + ui.position.top);
					})						
FUNC;
			
			return $strJS;
		}


		public function __set($strName, $mixValue) {
			switch ($strName) {
				case '_DragData': // Internal only. Do not use. Used by JS above to keep track of user selection.
					try {
						$data = QType::Cast($mixValue, QType::String);
						$a = explode (",", $data);
						$this->aryOriginalPosition['left'] = $a[0];
						$this->aryOriginalPosition['top'] = $a[1];
						$this->aryNewPosition['left'] = $a[2];
						$this->aryNewPosition['top'] = $a[3];
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
				case 'Handle':
					// Override to let you set the handle to: 
					//	a QControl or array of QControls, or
					//  a control id, or array of control ids
					if ($mixValue instanceof QControl) {
						parent::__set($strName, '#' . $mixValue->ControlId);
					} elseif (is_array($mixValue)) {
						$aHandles = array();
						foreach ($mixValue as $mixItem) {
							if ($mixItem instanceof QControl) {
								$aHandles[] = '#' . $mixItem->ControlId;
							} elseif (is_string($mixItem)) {
								if (substr($mixItem, 0, 1) == '#') {
									$aHandles[] = $mixItem;
								} else {
									$aHandles[] = '#' . $mixItem;
								}
							}
						}
						parent::__set($strName, join(',', $aHandles));
					} elseif (is_string($mixItem) && substr($mixItem, 0, 1) != '#') {
						$mixItem = '#' . $mixItem;	// turn the control id into a jQuery selector
						parent::__set($strName, $mixValue);
						
					} else {		
						parent::__set($strName, $mixValue);
					}
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
		
		public function __get($strName) {
			switch ($strName) {
				case 'DeltaX': 
					if ($this->aryOriginalPosition) {
						return $this->aryNewPosition['left'] - $this->aryOriginalPosition['left'];
					} else {
						return 0;
					}
					
				case 'DeltaY': 
					if ($this->aryOriginalPosition) {
						return $this->aryNewPosition['top'] - $this->aryOriginalPosition['top'];
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