<?php
	/**
	 * This file contains the QControlProxy class
	 */

	/**
	 * Class QControlProxy is used to 'proxy' the actions for another control
	 */
	class QControlProxy extends QControl {
		protected $strTargetControlId;
		
		public function GetControlHtml() {
			throw new QCallerException('QControlProxies cannot be rendered.  Use RenderAsEvents() within an HTML tag.');
		}

		public function RenderAsScript($strEventType='QClickEvent') {
			$objActions 	= $this->GetAllActions($strEventType);
			$strToReturn 	= '';
			foreach ($objActions as $objAction) {
				$strToReturn .= $objAction->RenderScript($this);
			}
			return $strToReturn;
		}
		
		public function RenderAsEvents($strActionParameter = null, $blnDisplayOutput = true, $strTargetControlId = null, $blnRenderControlId = true) {
			if ($strTargetControlId)
				$this->strTargetControlId = $strTargetControlId;
			else
				$this->strTargetControlId = $this->objForm->GenerateControlId();
				
			$this->strActionParameter = $strActionParameter;
			$strToReturn = $this->GetActionAttributes();
			
			QApplication::ExecuteJavaScript($strToReturn);
			
			if ($blnRenderControlId && $blnDisplayOutput)
				echo sprintf("id='%s'", $this->strTargetControlId);
			else if($blnRenderControlId)
				return sprintf("id='%s'", $this->strTargetControlId);
			else
				return "";				
		}

		public function RenderAsHref($strActionParameter = null, $blnDisplayOutput = true, $strTargetControlId = null, $blnRenderControlId = true) {
			if ($strTargetControlId)
				$this->strTargetControlId = $strFormId;
			else
				$this->strTargetControlId = $this->objForm->GenerateControlId();
			
			$this->strActionParameter = $strActionParameter;
			
			$strToReturn = $this->RenderAsScript('QClickEvent');
			
			if ($strToReturn)
				$strToReturn = 'javascript:' . $strToReturn;
			else
				$strToReturn = 'javascript: return false;';

			if ($blnRenderControlId && $blnDisplayOutput)
				echo sprintf("id='%s'", $this->strTargetControlId);
				
				// Output or Display
			if ($blnDisplayOutput)
				print($strToReturn);
			else
				return $strToReturn;
		}

		public function ParsePostData() {}
		public function Validate() {return true;}
		
			/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				
				case 'TargetControlId': return $this->strTargetControlId;
				
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				
				case 'TargetControlId': 
					try {
						return ($this->strFoo = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }
			}
		}		
	}
?>
