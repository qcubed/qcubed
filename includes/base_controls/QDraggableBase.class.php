<?php
	/**
	 * QDraggable Base File
	 * 
	 * The QDraggableBase class defined here provides an interface between the generated
	 * QDraggableGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QDraggable.class.php file instead.
	 */

	/**
	 * Implements the jQuery UI Draggable capabilities on to a control.
	 * 
	 * This class is designed to work as a kind of add-on class to a QControl, giving its capabilities
	 * to the control. To make a QControl draggable, simply set $ctl->Dragable = true. You can then 
	 * get to this class to further manipulate the aspects of the draggable through $ctl->DragObj.
	 *
	 * @property-read Integer $DeltaX Amount of change in left that happened on the last drag
	 * @property-read Integer $DeltaY Amount of change in top that happened on the last drag
	 * @property mixed $Handle A drag handle. Can be a control, a selector or array of controls or jQuery selectors.
	 * 
	 * @link http://jqueryui.com/draggable/
	 * @package Controls\Base
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
		

		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			QApplication::ExecuteJsFunction('qcubed.draggable', $this->getJqControlId(), $this->ControlId, QJsPriority::High);
			return $strJS;
		}


		public function __set($strName, $mixValue) {
			switch ($strName) {
				case '_DragData': // Internal only. Do not use. Used by JS above to keep track of user selection.
					try {
						$data = QType::Cast($mixValue, QType::ArrayType);
						$this->aryOriginalPosition = $data['originalPosition'];
						$this->aryNewPosition = $data['position'];

						// update parent's coordinates
						$this->objParentControl->getWrapperStyler()->Top = $this->aryNewPosition['top'];
						$this->objParentControl->getWrapperStyler()->Left = $this->aryNewPosition['left'];
						break;
						
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					
				case 'Handle':
					// Override to let you set the handle to: 
					//	a QControl, or selector, or array of QControls or selectors
					if ($mixValue instanceof QControl) {
						parent::__set($strName, '#' . $mixValue->ControlId);
					} elseif (is_array($mixValue)) {
						$aHandles = array();
						foreach ($mixValue as $mixItem) {
							if ($mixItem instanceof QControl) {
								$aHandles[] = '#' . $mixItem->ControlId;
							} elseif (is_string($mixItem)) {
								$aHandles[] = $mixItem;
							}
						}
						parent::__set($strName, join(',', $aHandles));
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