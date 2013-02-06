<?php
	/**
	 * contains the QDataGrid class
	 *
	 * @package Controls
	 * @filesource
	 */


	/**
	 * QDataGrid can help generate tables automatically with pagination. It can also be used to
	 * render data directly from database by using a 'DataSource'. The code-generated search pages you get for
	 * every table in your database are all QDataGrids
	 *
	 * @package Controls
	 */
	class QDataGrid extends QDataGridBase  {
	         /** @var int $intCellSpacing Set the space between the cells */
		protected $intCellSpacing = 0;
                 /** @type int $intCellPadding Set the space between the cell wall and the cell content */
		protected $intCellPadding = 0;

		///////////////////////////
		// DataGrid Preferences
		///////////////////////////

		// Feel free to specify global display preferences/defaults for all QDataGrid controls

		/**
		 * QDataGrid::__construct()
		 *
		 * @param mixed  $objParentObject The Datagrid's parent
		 * @param string $strControlId    Control ID
		 *
		 * @throws QCallerException
		 * @return \QDataGrid
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