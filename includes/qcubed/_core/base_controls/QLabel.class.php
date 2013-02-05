<?php
	/**
	 * @package Controls
	 */
	class QLabel extends QBlockControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////
		/** @var string $strTagName The HTML tag which should be used to wrap the label's text */
		protected $strTagName = 'span';
		/**
		 * @var bool $blnHtmlEntities Whether the HtmlEntities should be applied to all lables to be created
		 */
		protected $blnHtmlEntities = true;
	}
?>