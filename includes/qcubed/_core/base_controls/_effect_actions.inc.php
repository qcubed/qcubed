<?php
/**
 * This file contains all the actions that perform jQuery effects. 
 *
 * @package Actions
 */

/**
 * Base class for all jQuery-based effects.
 * 
 * @package Actions
 */
abstract class QJQAction extends QAction {
	protected $strControlId = null;
	protected $strMethod = null;
	
	protected function __construct(QControl $objControl, $strMethod) {
		$this->strControlId = $objControl->ControlId;
		$this->strMethod = QType::Cast($strMethod, QType::String);
		$this->setJavaScripts($objControl);
	}

	private function setJavaScripts($objControl) {
		$objControl->AddJavascriptFile(__JQUERY_BASE__);
		$objControl->AddJavascriptFile(__JQUERY_EFFECTS__ . '/effects.core.js');
		
		switch($this->strMethod) {
			case 'blind' :
			case 'bounce' :
			case 'clip' :
			case 'drop' :            
			case 'explode' :
			case 'fold' :
			case 'highlight' :                
			case 'scale':            
			case 'shake' :    
			case 'slide' :                                    
			case 'transfer' :
			case 'pulsate' :
				$objControl->AddJavascriptFile(__JQUERY_EFFECTS__ . '/effects.' . $this->strMethod . '.js');
				break;

			// The following two effects have a dependency on the scale effect
			case 'puff':
			case 'size':
				$objControl->AddJavascriptFile(__JQUERY_EFFECTS__ . '/effects.scale.js');
				break;
		}
	}
}

/**
 * Show a control (if it's hidden)
 * 
 * @package Actions
 */
class QJQShowAction extends QJQAction {
	public function __construct(QControl $objControl, $strMethod = "slow") {        
		parent::__construct($objControl, $strMethod);
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').show('%s');", $this->strControlId, $this->strMethod);
	}
}

/**
 * Show a control (if it's hidden) using additional visual effects.
 * 
 * @package Actions
 */
class QJQShowEffectAction extends QJQAction {
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, $strMethod = "default", $strOptions = "", $strSpeed = 1000) {        
		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);
		
		parent::__construct($objControl, $strMethod);
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').show('%s', {%s}, %s);", $this->strControlId, $this->strMethod, $this->strOptions, $this->strSpeed);
	}
}		


/**
 * Toggle visibility of a control, using additional visual effects
 *
 * @package Actions
 */
class QJQToggleEffectAction extends QJQAction {
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, $strMethod = "slow", $strOptions = "", $strSpeed = 1000) {
		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);

		parent::__construct($objControl, $strMethod);
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').toggle('%s', {%s}, %s);", $this->strControlId, $this->strMethod, $this->strOptions, $this->strSpeed);
	}
}	


/**
 * Toggle visibility of a control.
 * 
 * @package Actions
 */
class QJQToggleAction extends QJQAction {
	public function __construct(QControl $objControl, $strMethod = "slow") {
		parent::__construct($objControl, $strMethod);
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').toggle('%s');", $this->strControlId, $this->strMethod);		  
	}
}

/**
 * Hide a control (if it's visible)
 * 
 * @package Actions
 */
class QJQHideAction extends QJQAction {
	public function __construct(QControl $objControl, $strMethod = "slow") {
		parent::__construct($objControl, $strMethod);
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').hide('%s');", $this->strControlId, $this->strMethod);		  
	}
}

/**
 * Hide a control, using additional visual effects.
 * 
 * @package Actions
 */
class QJQHideEffectAction extends QJQAction {
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, $strMethod = "blind", $strOptions = "", $strSpeed = 1000) {
		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);
		
		parent::__construct($objControl, $strMethod);
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').hide('%s', {%s}, %s);", $this->strControlId, $this->strMethod, $this->strOptions, $this->strSpeed);		  
	}
}

/**
 * Make a control bounce up and down.
 * 
 * @package Actions
 */
class QJQBounceAction extends QJQAction {
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, $strOptions = "", $strSpeed = 1000) {
		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);        

		parent::__construct($objControl, 'bounce');
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s_ctl').effect('bounce', {%s}, %s);", $this->strControlId, $this->strOptions, $this->strSpeed);
	}
}

/**
 * Make a control shake left and right
 * 
 * @package Actions
 */
class QJQShakeAction extends QJQAction {
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, $strOptions = "", $strSpeed = 1000) {
		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);
		
		parent::__construct($objControl, 'shake');
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s_ctl').effect('shake', {%s}, %s);", $this->strControlId, $this->strOptions, $this->strSpeed);		  
	}
}

/**
 * Highlight a control
 *
 * @package Actions
 */
class QJQHighlightAction extends QJQAction {
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, $strOptions = "", $strSpeed = 1000) {
		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);
		
		parent::__construct($objControl, 'highlight');
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').effect('highlight', {%s}, %s);", $this->strControlId, $this->strOptions, $this->strSpeed);
	}
}

/**
 * Pulsate the contents of a control
 * 
 * @package Actions
 */
class QJQPulsateAction extends QJQAction {
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, $strOptions = "", $strSpeed = 1000) {
		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);
		
		parent::__construct($objControl, 'pulsate');
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').effect('pulsate', {%s}, %s);", $this->strControlId, $this->strOptions, $this->strSpeed);
	}
}

/**
 * Resize a control
 * 
 * @package Actions
 */
class QJQSizeAction extends QJQAction {
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, $strOptions, $strSpeed = 1000) {
		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);
		
		parent::__construct($objControl, 'pulsate');
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').effect('size', {%s}, %s);", $this->strControlId, $this->strOptions, $this->strSpeed);
	}
}

/**
 * Transfer the border of a control to another control
 * 
 * @package Actions
 */
class QJQTransferAction extends QJQAction {
	protected $strTargetControlId = null;
	protected $strOptions = null;
	protected $strSpeed = null;

	public function __construct(QControl $objControl, QControl $objTargetControl, $strOptions = "", $strSpeed = 1000) {
		$this->strTargetControlId = $objTargetControl->ControlId;

		$this->strOptions = QType::Cast($strOptions, QType::String);
		$this->strSpeed = QType::Cast($strSpeed, QType::String);

		parent::__construct($objControl, 'transfer');
	}

	public function RenderScript(QControl $objControl) {
		return sprintf("$('#%s').effect('transfer', {to: '#%s_ctl' %s}, %s);", $this->strControlId, $this->strTargetControlId, $this->strOptions, $this->strSpeed);
	}
}
?>
