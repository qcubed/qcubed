<?php
	/**
	 * Contains the QPaginator Class - the paginator control for QDataGrid and QDataRepeater controls
	 * @package Controls
	 * @filesource
	 */

	/**
	 * Class QPaginator - The paginator control which can be attached to a QDataRepeater or QDataGrid
	 * This class will take care of the number of pages, current page, next/previous links and so on
	 * automatically.
	 */
	class QPaginator extends QPaginatorBase {
		// APPEARANCE
		protected $intIndexCount = 10;

		//////////
		// Methods
		//////////
		/**
		 * Constructor
		 * @param QControl|QForm $objParentObject
		 * @param null|string    $strControlId
		 */
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->CssClass = 'paginator';
			//$this->strLabelForPrevious = QApplication::Translate('<<');
			//$this->strLabelForNext = QApplication::Translate('>>');

		}
	}
?>