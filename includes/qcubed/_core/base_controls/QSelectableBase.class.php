<?php
	/**
	 * The QSelectableBase class defined here provides an interface between the generated
	 * QSelectableGen class, and QCubed. This file is part of the core and will be overwritten
	 * when you update QCubed. To override, make your changes to the QSelectable.class.php file instead.
	 *
	 */

	/**
	 * @property-read Array $SelectedItems ControlIds of the items selected
	 */
	class QSelectableBase extends QSelectableGen
	{
		/** @var array */
		protected $arySelectedItems = null;
		

		// These functions are used to keep track of the selected items 
		
		public function GetControlJavaScript() {
			$strJS = parent::GetControlJavaScript();
			
			$strJS .=<<<FUNC
			.bind("selectablestop", function (event, ui) {
				var strItems;
				
				strItems = "";
				jQuery(".ui-selected", this).each(function() {
					strItems = strItems + "," + this.id;
				});
				
				if (strItems) {
					strItems = strItems.substring (1);
				}
				qcubed.recordControlModification("$this->ControlId", "SelectedItems", strItems);
				
			})
FUNC;
			
			return $strJS;
		}


		public function __set($strName, $mixValue) {
			$this->blnModified = true;
			
			switch ($strName) {
				case 'SelectedItems':
					try {
						if (is_array($mixValue)) {
							foreach ($mixValue as &$val) {
								$val = '"#' . $val . '"';
							}
							$items = join (',', $mixValue);
							
							$strJS =<<<FUNC
								
								var item = jQuery("#$this->ControlId");
								
								jQuery(".ui-selectee", item).each(function() {
									jQuery(this).removeClass('ui-selected');
								});
								
								jQuery($items).each(function() {
									jQuery(this).addClass('ui-selected');
								});
FUNC;
							$this->arySelectedItems = $mixValue;
							QApplication::ExecuteJavascript ($strJS);
						} else {
							// this is coming from our javascript above.
							$strItems = QType::Cast($mixValue, QType::String);
							$this->arySelectedItems = split (",", $strItems);
						}
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
				case 'SelectedItems': return $this->arySelectedItems;
				
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
?>