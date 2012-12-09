<?php
	/**
     * QDataGrid
     * 
	 * @package Controls
	 */
     
     
	/**
	 * QDataGrid
	 * 
	 * @package Controls  
	 * @author Qcubed
	 * @copyright 
	 * @version 2011
	 * @access public
	 */
	class QDataGrid extends QDataGridBase  {
	    /** @type int|0 Set the space between the cells */
		protected $intCellSpacing = 0;
        /** @type int|0 Set the space between the cell wall and the cell content */
		protected $intCellPadding = 0;

		///////////////////////////
		// DataGrid Preferences
		///////////////////////////

		// Feel free to specify global display preferences/defaults for all QDataGrid controls
        
		/**
		 * QDataGrid::__construct()
		 * 
		 * @param mixed $objParentObject The Datagrid's parent
		 * @param string $strControlId Control ID
		 * @return void
		 */
		public function __construct($objParentObject, $strControlId = null) {
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException  $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// For example... let's default the CssClass to datagrid
			$this->strCssClass = 'datagrid';
		}

		// Override any of these methods/variables below to alter the way the DataGrid gets rendered

//		protected function GetPaginatorRowHtml() {}

//		protected function GetHeaderRowHtml() {}

//		protected $blnShowFooter = true;		
//		protected function GetFooterRowHtml() {
//			return sprintf('<tr><td colspan="%s" style="text-align: center">Some Footer Can Go Here</td></tr>', count($this->objColumnArray));
//		}

//		protected function GetDataGridRowHtml($objObject) {}
	}
?>