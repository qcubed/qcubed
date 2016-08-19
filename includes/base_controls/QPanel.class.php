<?php
	/**
	 * The QPanel Class is defined here.
	 * QPanel class can be used to create composite controls which are to be rendered as blocks (not inline)
	 * @package Controls
	 */
	class QPanel extends QBlockControl {
		///////////////////////////
		// Protected Member Variables
		///////////////////////////
		/** @var string HTML tag to the used for the Block Control */
		protected $strTagName = 'div';
		/** @var string Default display style for the control. See QDisplayStyle class for available list */
		protected $strDefaultDisplayStyle = QDisplayStyle::Block;
		/** @var bool Is the control a block element? */
		protected $blnIsBlockElement = true;
		/** @var bool Use htmlentities for the control? */
		protected $blnHtmlEntities = false;
	}