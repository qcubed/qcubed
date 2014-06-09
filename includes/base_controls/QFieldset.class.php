<?php
/**
 * Encapsulates a fieldset, which has a legend that acts as a label. HTML5 defines a new Name element, which
 * is not yet supported in IE of this writing, but other browsers support it. So, if its defined, we will output
 * it in the html, but it will not affect what appears on the screen unless you draw the Name too.
 *
 * @package Controls\Base
 *
 * @property string $Legend is the legend that will be output for the fieldset.
 */

class QFieldset extends QBlockControl {
	/** @var string HTML tag to the used for the Block Control */
	protected $strTagName = 'fieldset';
	/** @var string Default display style for the control. See QDisplayStyle class for available list */
	protected $strDefaultDisplayStyle = QDisplayStyle::Block;
	/** @var bool Is the control a block element? */
	protected $blnIsBlockElement = true;
	/** @var bool Use htmlentities for the control? */
	protected $blnHtmlEntities = false;
	/** @var  string legend */
	protected $strLegend;

	/**
	 * We will output style tags and such, but fieldset styling is not well supported across browsers.
	 */
	protected function GetControlHtml() {
		$strStyle = $this->GetStyleAttributes();

		if ($strStyle)
			$strStyle = sprintf('style="%s"', $strStyle);

		if ($this->strFormat)
			$strText = sprintf($this->strFormat, $this->strText);
		else
			$strText = $this->strText;

		$strTemplateEvaluated = '';
		if ($this->strTemplate) {
			global $_CONTROL;
			$objCurrentControl = $_CONTROL;
			$_CONTROL = $this;
			$strTemplateEvaluated = $this->objForm->EvaluateTemplate($this->strTemplate);
			$_CONTROL = $objCurrentControl;
		}

		$strLegend = '';
		if (!empty($this->strLegend)) {
			$strLegend = '<legend>' . $this->strLegend . '</legend>';
		}

		$strToReturn = sprintf('<%s id="%s" %s%s>%s%s%s%s</%s>',
			$this->strTagName,
			$this->strControlId,
			$this->GetAttributes(),
			$strStyle,
			$strLegend,
			($this->blnHtmlEntities) ? QApplication::HtmlEntities($strText) : $strText,
			$strTemplateEvaluated,
			($this->blnAutoRenderChildren) ? $this->RenderChildren(false) : '',
			$this->strTagName);


		return $strToReturn;
	}

	/////////////////////////
	// Public Properties: GET
	/////////////////////////
	public function __get($strName) {
		switch ($strName) {
			// APPEARANCE
			case "Legend": return $this->strLegend;

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

	/////////////////////////
	// Public Properties: SET
	/////////////////////////
	public function __set($strName, $mixValue) {
		$this->blnModified = true;

		switch ($strName) {
			// APPEARANCE
			case "Legend":
				try {
					$this->strLegend = QType::Cast($mixValue, QType::String);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

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


}