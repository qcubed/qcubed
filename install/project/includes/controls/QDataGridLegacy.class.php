<?php
	/**
	 * contains the QDataGridLegacy class
	 *
	 * @package Controls
	 * @filesource
	 */


	/**
	 * QDataGridLegacy can help generate tables automatically with pagination. It can also be used to
	 * render data directly from database by using a 'DataSource'. The code-generated search pages you get for
	 * every table in your database are all QDataGrids.
	 *
	 * @deprecated
	 * @package Controls
	 */
	class QDataGridLegacy extends QDataGridLegacyBase  {
		///////////////////////////
		// DataGrid Preferences
		///////////////////////////

		// Feel free to specify global display preferences/defaults for all QDataGridLegacy controls

		/**
		 * QDataGridLegacy::__construct()
		 *
		 * @param mixed  $objParentObject The Datagrid's parent
		 * @param string $strControlId    Control ID
		 *
		 * @throws QCallerException
		 * @return \QDataGridLegacy
		 */
		public function __construct($objParentObject, $strControlId = null) {
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException  $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// For example... let's default the CssClass to datagrid
			$this->CssClass = 'datagrid';
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
