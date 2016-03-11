<?php
	/**
	 * This file contains the QControlProxy class
	 */

	/**
	 * Class QControlProxy is used to 'proxy' the actions for another control
	 */
	class QControlProxy extends QControl {
		/**
		 * @var string HTML element ID which is to be rendered/sent to the browser
		 * @deprecated This is not needed by newer implementation which uses HTML5 data tags.
		 */
		protected $strTargetControlId;

		/** @var bool Overriding parent class */
		protected $blnActionsMustTerminate = true;
		/** @var bool Overriding parent class */
		protected $blnScriptsOnly = true;
		/** @var null Overriding parent class to turn off rendering of this control when auto-rendering */
		protected $strPreferredRenderMethod = null;

		/**
		 * Constructor Method
		 *
		 * @param QControl|QControlBase|QForm $objParent    Parent control
		 * @param null|string                 $strControlId Control ID for this control
		 *
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __construct ($objParent, $strControlId = null) {
			parent::__construct($objParent, $strControlId);
			$this->mixActionParameter = new QJsClosure('return $j(this).data("qap")');
		}

		/**
		 * @throws QCallerException
		 */
		public function GetControlHtml() {
			throw new QCallerException('QControlProxies cannot be rendered.  Use RenderAsEvents() within an HTML tag.');
		}

		/**
		 * Render as an HTML link (anchor tag)
		 *
		 * @param string      $strLabel           Text to link to
		 * @param string|null $strActionParameter Action parameter for this rendering of the control. Will be sent to the ActionParameter of the action.
		 * @param null|array  $attributes         Array of attributes to add to the tag for the link.
		 * @param string      $strTag             Tag to use. Defaults to 'a'.
		 *
		 * @return string
		 */
		public function RenderAsLink($strLabel, $strActionParameter = null, $attributes = [], $strTag = 'a', $blnHtmlEntities = true) {
			$defaults['href'] = '#';
			$defaults['data-qpxy'] = $this->strControlId;
			if ($strActionParameter) {
				$defaults['data-qap'] = $strActionParameter;
			}
			$attributes = array_merge($defaults, $attributes); // will only apply defaults that are not in attributes

			if ($blnHtmlEntities) {
				$strLabel = QApplication::HtmlEntities($strLabel);
			}

			return QHtml::RenderTag($strTag, $attributes, $strLabel);
		}

		/**
		 * Render as an HTML button.
		 *
		 * @param string      $strLabel           Text to link to
		 * @param string|null $strActionParameter Action parameter for this rendering of the control. Will be sent to the ActionParameter of the action.
		 * @param array       $attributes         Array of attributes to add to the tag for the link.
		 * @param bool        $blnHtmlEntities    False to turn off html entities.
		 *
		 * @return string
		 */
		public function RenderAsButton($strLabel, $strActionParameter = null, $attributes = [], $blnHtmlEntities = true) {
			$defaults['onclick']='return false';
			$defaults['type']='button';
			$attributes = array_merge($defaults, $attributes); // will only apply defaults that are not in attributes
			return $this->RenderAsLink($strLabel, $strActionParameter, $attributes, 'button', $blnHtmlEntities);
		}

		/**
		 * Render just attributes that can be included in any html tag to attach the proxy to the tag.
		 *
		 * @param string|null $strActionParameter
		 * @return string
		 */
		public function RenderAttributes($strActionParameter = null) {
			$attributes['data-qpxy'] = $this->ControlId;
			if ($strActionParameter) {
				$attributes['data-qap'] = $strActionParameter;
			}
			return QHtml::RenderHtmlAttributes($attributes);
		}


		/**
		 * Renders only the id of this Proxy essentially embedding it into (disguising it as) another element.
		 * The template must contain the element separately. This function just renders/returns 'id="XYZ"'
		 * where XYZ is the target control ID.
		 *
		 * @param null|string $strActionParameter Action parameter against which the action will be taken $strActionParameter
		 * @param bool        $blnDisplayOutput   Should the output be sent to browser (true) or returned (false)
		 * @param null        $strTargetControlId ID to be sent to the browser for this proxy's HTML element
		 *
		 * @deprecated         Obsolete. Above methods generate less code and are easier to use. Also, do not mix the two.
		 *
		 * @param bool        $blnRenderControlId Control ID to be rendered or not
		 *
		 * @return string
		 */
		public function RenderAsEvents($strActionParameter = null, $blnDisplayOutput = true, $strTargetControlId = null, $blnRenderControlId = true) {
			$this->RenderAttributes();
			if ($strTargetControlId)
				$this->strTargetControlId = $strTargetControlId;
			else
				$this->strTargetControlId = $this->objForm->GenerateControlId();
				
			$this->mixActionParameter = $strActionParameter;
			$strToReturn = $this->RenderActionScripts();
			
			QApplication::ExecuteJavaScript($strToReturn);
			
			if ($blnRenderControlId && $blnDisplayOutput) {
				echo sprintf("id='%s'", $this->strTargetControlId);
				return '';
			}
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
		 * @deprecated    From v2. Above ways are more efficient.
		 * @return string
		 */
		public function RenderAsHref($strActionParameter = null, $blnDisplayOutput = true, $strTargetControlId = null) {
			if ($strTargetControlId)
				$this->strTargetControlId = $strTargetControlId;
			else
				$this->strTargetControlId = $this->objForm->GenerateControlId();
			
			$this->mixActionParameter = $strActionParameter;
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

				// deprecated
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

				// Deprecated
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