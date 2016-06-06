<?php

/**
 * Class NavPanel
 *
 * This is a basic starting navigation panel that appears at the top of every list form.
 * This particular panel just loads a template to navigate back to the main
 * form list. You can modify this however you want to suit your application.
 * A list of links, button bar, or a menu bar are possibilities.
 */
class NavPanel extends QPanel {

	public function __construct ($objParent, $strControlId = null) {
		parent::__construct($objParent, $strControlId);
		$this->strTemplate = 'nav_panel.tpl.php';
	}
}