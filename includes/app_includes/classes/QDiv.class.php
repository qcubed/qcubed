<?php
	/**
	 * Created by JetBrains PhpStorm.
	 * User: vakopian
	 * Date: 5/15/12
	 * Time: 4:15 PM
	 * To change this template use File | Settings | File Templates.
	 */
	class QDiv extends QPanel {
		public function __construct($objParentObject, $strCssClass = null, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->AutoRenderChildren = true;
			$this->UseWrapper = false;
			$this->CssClass = $strCssClass;
		}
	}
