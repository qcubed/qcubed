<?php
/**
 * QTag
 * Code that encapsulates the rendering of an HTML tag. This can be used to render simple tags without the overhead
 * of the QControl mechanism.
 *
 * This class outputs the HTML for one HTML tag, including the attributes inside the tag and the inner html between the
 * opening and closing tags. If it represents a void element, which is self closing, no inner html or closing tag
 * will be printed, and the tag will be correctly terminated.
 *
 * It will normally print the opening and closing tags on their own lines, with the inner html indented once and in-between
 * the two tags. If you define the __MINIMIZE__ constant or set QApplication::$Minimize variable, it will all be printed on one line with no indents.
 *
 * This control can be used as a drawing aid to draw complex QControls.
 */

class QTag extends QHtmlAttributeManager {

	/** @var  string The tag */
	protected $strTag;
	/** @var  bool True to render without a closing tag or inner html */
	protected $blnIsVoidElement = false;

	/**
	 * @param null|string $strTag
	 * @param bool $blnIsVoidElement
	 * @throws QCallerException
	 */

	public function __construct($strTag = null, $blnIsVoidElement = false) {
		if ($strTag) {
			$this->strTag = $strTag;
		} elseif (!isset($this->strTag)) {
			throw new QCallerException ('Must set tag either with subclass or constructor');
		}
		$this->blnIsVoidElement = $blnIsVoidElement;
	}

	/**
	 * Render the tag and everything between the opening and closing tags. Does this in two modes:
	 * - Developer mode (default) will put the opening and closing tags on separate lines, with the
	 *   innerHtml indented in between them.
	 * - Minimize mode (set the __MINIMIZE__ global constant) will put everything on one line, and draw a little faster.
	 * @return string
	 */
	/**
	 * @param bool $blnDisplayOutput
	 * @param null|string $strInnerText
	 * @param null|array $attributeOverrides
	 * @param null|array $styleOverrides
	 * @return string
	 */
	protected function render($blnDisplayOutput = true, $strInnerText = null, $attributeOverrides = null, $styleOverrides =  null) {
		if (is_null($strInnerText)) {
			$strInnerText = $this->getInnerHtml();
		}
		$strOut = $this->RenderTag($this->strTag,
			$attributeOverrides,
			$styleOverrides,
			$strInnerText,
			$this->blnIsVoidElement);

		if ($blnDisplayOutput) {
			print ($strOut);
			return '';
		} else {
			return $strOut;
		}
	}

	/**
	 * Returns the html that sits between the tags. Do NOT escape the html, that will be handled at render time.
	 *
	 * This implementation just returns nothing to allow for subclassing. Future implementations could implement
	 * a callback or store text internally.
	 *
	 * @return string
	 */
	protected function getInnerHtml() {
		return '';
	}
 }

