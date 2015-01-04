<?php
	/**
	 * This file contains the QControlProxy class
	 */

	/**
	 * Class QControlProxy is used to 'proxy' the actions for another control
	 */
	class QControlProxy extends QControl {
		/** @var string|mixed Action Parameter for which the proxy's events would fire */
		protected $strActionParameter;
		/** @var string HTML element ID which is to be rendered/sent to the browser */
		protected $strTargetControlId;
		
		public function GetControlHtml() {
			throw new QCallerException('QControlProxies cannot be rendered.  Use RenderAsEvents() within an HTML tag.');
		}

		/**
		 * Renders only the id of this Proxy essentially embedding it into (disguising it as) another element.
		 * The template must contain the element separately. This function just renders/returns 'id="XYZ"'
		 * where XYZ is the target control ID.
		 *
		 * @param null|string $strActionParameter Action parameter against which the action will be taken $strActionParameter
		 * @param bool        $blnDisplayOutput   Should the output be sent to browser (true) or returned (false)
		 * @param null        $strTargetControlId ID to be sent to the browser for this proxy's HTML element
		 * @param bool        $blnRenderControlId Control ID to be rendered or not
		 *
		 * @return string
		 */
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

		/**
		 * Renders the proxy as a clickable link (the link will not navigate)
		 *
		 * @param null|string $strActionParameter Action parameter against which the action will be taken
		 * @param bool        $blnDisplayOutput   Should the output be sent to browser (true) or returned (false)
		 * @param null|string $strTargetControlId ID to be sent to the browser for this proxy's HTML element
		 *
		 * @return string
		 */
		public function RenderAsHref($strActionParameter = null, $blnDisplayOutput = true, $strTargetControlId = null) {
			if ($strTargetControlId)
				$this->strTargetControlId = $strTargetControlId;
			else
				$this->strTargetControlId = $this->objForm->GenerateControlId();
			
			$this->strActionParameter = $strActionParameter;
			$objActions = $this->GetAllActions('QClickEvent');
			$strToReturn = '';
			foreach ($objActions as $objAction)
				$strToReturn .= $objAction->RenderScript($this);
			if ($strToReturn)
				$strToReturn = 'javascript:' . $strToReturn;
			else
				$strToReturn = 'javascript: return false;';

			/* target id needs to be rendered outside the href
			if ($blnRenderControlId && $blnDisplayOutput)
				echo sprintf("id='%s'", $this->strTargetControlId);
			*/

			//$this->blnModified = false;

				// Output or Display
			if ($blnDisplayOutput)
				print($strToReturn);
			else
				return $strToReturn;
		}

		/**
		 * Parses postback data
		 *
		 * In this class, the method does nothing and is here because of the contraints (derived from an abstract class)
		 */
		public function ParsePostData() {}

		/**
		 * Validates this control proxy
		 *
		 * @return bool Whether this control proxy is valid or not
		 */
		public function Validate() {return true;}
		
		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method
		 *
		 * @param string $strName Name of the property
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
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
		/**
		 * PHP magic method
		 *
		 * @param string $strName  Property name
		 * @param string $mixValue Property value
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				
				case 'TargetControlId': 
					try {
						return ($this->strTargetControlId = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }
			}
		}		
	}
?>