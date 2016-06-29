<?php
	/**
	 * Sortable Base File
	 * 
	 * The QSortableBase class defined here provides an interface between the generated
	 * QSortableGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QSortable.class.php file instead.
	 *
	 */

	/**
	 * Impelements a JQuery UI Sortable 
	 * 
	 * Sortable is a group of panels that can be dragged to reorder them. You will need to put
	 * some care into the css styling of the objects so that the css allows them to be moved. It
	 * will use the top level html objects inside the panel to decide what to sort. Make sure
	 * they have ids so it can return the ids of the items in sort order.
	 * 
	 * @property-read Array $ItemArray	List of ControlIds in sort order.
	 * 
	 * @link http://jqueryui.com/sortable/
	 * @package Controls\Base
	 */
	class QSortableBase extends QSortableGen	{
		/** @var array */
		protected $aryItemArray = null;
		

		// Find out what the sort order is at the beginning so that aryItemArray is up to date
		public function MakeJqOptions () {
			$jqOptions = parent::MakeJqOptions();

			// TODO: Put this in the qcubed.js file, or something like it.
			$jqOptions['create'] =  new QJsClosure('
					var ary = jQuery(this).sortable("toArray");
						var str = ary.join(",");
			 			qcubed.recordControlModification("$this->ControlId", "_ItemArray", str);
				');
			return $jqOptions;
		}		
		public function GetEndScript() {
			$strJS = parent::GetEndScript();
			
			$strCtrlJs =<<<FUNC
			;\$j('#{$this->ControlId}').on("sortstop", function (event, ui) {
						var ary = jQuery(this).sortable("toArray");
						var str = ary.join(",");
			 			qcubed.recordControlModification("$this->ControlId", "_ItemArray", str);
					})						
FUNC;
			QApplication::ExecuteJavaScript($strCtrlJs, QJsPriority::High);
			
			return $strJS;
		}


		public function __set($strName, $mixValue) {			
			switch ($strName) {
				case '_ItemArray': // Internal only. Do not use. Used by JS above to track selections.
					try {
						$data = QType::Cast($mixValue, QType::String);
						$a = explode (",", $data);
						$this->aryItemArray = $a;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					 
				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
			
		}
		
		public function __get($strName) {
			switch ($strName) {
				case 'ItemArray': return $this->aryItemArray;
				default: 
					try { 
						return parent::__get($strName); 
					} catch (QCallerException $objExc) { 
						$objExc->IncrementOffset(); 
						throw $objExc; 
					}
			}
		}
	}
