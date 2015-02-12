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
	protected function GetInnerHtml() {
		$strHtml = parent::GetInnerHtml();

		if (!empty($this->strLegend)) {
			$strHtml = '<legend>' . $this->strLegend . '</legend>' . _nl() . $strHtml;
		}

		return $strHtml;
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