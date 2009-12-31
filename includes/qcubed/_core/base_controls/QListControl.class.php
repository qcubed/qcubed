<?php
	/**
	 * This file contains the QListControl class.
	 * 
	 * @package Controls
	 */

	/**
	 * Abstract object which is extended by anything which involves lists of selectable items.
	 * 
	 * This object is the foundation for the ListBox, CheckBoxList, RadioButtonList 
	 * and TreeNav. 
	 * 
	 * @property integer $ItemCount the current count of ListItems in the control.
	 * @property integer $SelectedIndex is the index number of the control that is selected. "-1" means that nothing is selected. If multiple items are selected, it will return the lowest index number of all ListItems that are currently selected. Set functionality: selects that specific ListItem and will unselect all other currently selected ListItems.
	 * @property string $SelectedName simply returns ListControl::SelectedItem->Name, or null if nothing is selected.
	 * @property-read QListItem $SelectedItem (readonly!) returns the ListItem object, itself, that is selected (or the ListItem with the lowest index number of a ListItems that are currently selected if multiple items are selected). It will return null if nothing is selected.
	 * @property-read array $SelectedItems returns an array of selected ListItems (if any).
	 * @property mixed $SelectedValue simply returns ListControl::SelectedItem->Value, or null if nothing is selected.
	 * @property array $SelectedNames returns an array of all selected names
	 * @property array $SelectedValues returns an array of all selected values
	 * @package Controls
	 */
	abstract class QListControl extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		/**
		 * @access protected
		 * @var object
		 */
		protected $objItemsArray = array();

		//////////
		// Methods
		//////////
		

		/**
		 * Add one ListItem to the ListControl
		 * 
		 * Allows you to add a ListItem to the ListItem array within the ListControl. Items are appended to 
		 * the array. This method exhibits polymorphism: you can either pass in a ListItem object or you 
		 * can pass in three strings.
		 * 
		 * <code>
		 *  // Method 1: adding a created ListItem
		 *  $objListItem = new QListItem($name, $value, $blnIsSelected);
		 *  $lstList->AddItem($objListItem);
		 *  
		 *  // Method 2: adding a list item using direct strings
		 *  $lstList->AddItem($name, $value, $blnIsSelected);
		 *  
		 * </code>
		 * 
		 * @see QListItem::__construct()
		 * @param mixed $mixListItemOrName QListItem or Name of the ListItem
		 * @param string $strValue Value of the ListItem
		 * @param boolean $blnSelected set the html selected attribute for the ListItem
		 * @param string $strItemGroup allows you to apply grouping (<optgroup> tag)
		 * @param string $strOverrideParameters OverrideParameters for ListItemStyle
		 */
		public function AddItem($mixListItemOrName, $strValue = null, $blnSelected = null, $strItemGroup = null, $strOverrideParameters = null) {
			$this->blnModified = true;
			if (gettype($mixListItemOrName) == QType::Object)
				$objListItem = QType::Cast($mixListItemOrName, "QListItem");
			elseif ($strOverrideParameters)			
				// The OverrideParameters can only be included if they are not null, because OverrideAttributes in QBaseClass can't except a NULL Value
				$objListItem = new QListItem($mixListItemOrName, $strValue, $blnSelected, $strItemGroup, $strOverrideParameters);
			else 
				$objListItem = new QListItem($mixListItemOrName, $strValue, $blnSelected, $strItemGroup);

			array_push($this->objItemsArray, $objListItem);
		}

		/**
		 * Allows you to add a ListItem at a certain index
		 * 
		 * Unlike AddItem, this will insert the ListItem at whatever index is passed to the function.  Additionally,
		 * only a ListItem object can be passed (as opposed to an object or strings)
		 * 
		 * @param integer $intIndex index at which the item should be inserted
		 * @param QListItem $objListItem the ListItem which shall be inserted
		 */
		public function AddItemAt($intIndex, QListItem $objListItem) {
			$this->blnModified = true;
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) || 
				($intIndex > count($this->objItemsArray)))
				throw new QIndexOutOfRangeException($intIndex, "AddItemAt()");
			for ($intCount = count($this->objItemsArray); $intCount > $intIndex; $intCount--) {
				$this->objItemsArray[$intCount] = $this->objItemsArray[$intCount - 1];
			}
			
			$this->objItemsArray[$intIndex] = $objListItem;
		}

		/**
		 * Retrieve the ListItem at the specified index location
		 * 
		 * @param integer $intIndex
		 * @return QListItem 
		 */
		public function GetItem($intIndex) {
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) || 
				($intIndex >= count($this->objItemsArray)))
				throw new QIndexOutOfRangeException($intIndex, "GetItem()");

			return $this->objItemsArray[$intIndex];
		}

		/**
		 * Removes all the items in objItemsArray
		 */
		public function RemoveAllItems() {
			$this->blnModified = true;
			$this->objItemsArray = array();
		}
		
		/**
		 * Removes a ListItem at the specified index location
		 * 
		 * @param integer $intIndex
		 */
		public function RemoveItem($intIndex) {
			$this->blnModified = true;
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) ||
				($intIndex > (count($this->objItemsArray) - 1)))
				throw new QIndexOutOfRangeException($intIndex, "RemoveItem()");
			for ($intCount = $intIndex; $intCount < count($this->objItemsArray) - 1; $intCount++) {
				$this->objItemsArray[$intCount] = $this->objItemsArray[$intCount + 1];
			}
			
			$this->objItemsArray[$intCount] = null;
			unset($this->objItemsArray[$intCount]);
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case "ItemCount":
					if ($this->objItemsArray)
						return count($this->objItemsArray);
					else
						return 0;
				case "SelectedIndex":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $intIndex;
					}
					return -1;
				case "SelectedName":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex]->Name;
					}
					return null;
				case "SelectedValue":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex]->Value;
					}
					return null;
				case "SelectedItem":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex];
					}
					return null;
				case "SelectedItems":
					$objToReturn = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($objToReturn, $this->objItemsArray[$intIndex]);
//							$objToReturn[count($objToReturn)] = $this->objItemsArray[$intIndex];
					}
					return $objToReturn;
				case "SelectedNames":
					$strNamesArray = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($strNamesArray, $this->objItemsArray[$intIndex]->Name);
//							$strNamesArray[count($strNamesArray)] = $this->objItemsArray[$intIndex]->Name;
					}
					return $strNamesArray;
				case "SelectedValues":
					$objToReturn = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($objToReturn, $this->objItemsArray[$intIndex]->Value);
//							$objToReturn[count($objToReturn)] = $this->objItemsArray[$intIndex]->Value;
					}
					return $objToReturn;
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;
			switch ($strName) {
				case "SelectedIndex":
					try {
						$mixValue = QType::Cast($mixValue, QType::Integer);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					// Special Case
					if ($mixValue == -1)
						$mixValue = null;

					if (($mixValue < 0) ||
						($mixValue > (count($this->objItemsArray) - 1)))
						throw new QIndexOutOfRangeException($mixValue, "SelectedIndex");
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++)
						if ($mixValue === $intIndex)
							$this->objItemsArray[$intIndex]->Selected = true;
						else
							$this->objItemsArray[$intIndex]->Selected = false;
					return $mixValue;
					break;

				case "SelectedName":
					foreach ($this->objItemsArray as $objItem)
						if ($objItem->Name == $mixValue)
							$objItem->Selected = true;
						else
							$objItem->Selected = false;
					return $mixValue;
					break;

				case "SelectedValue":
					foreach ($this->objItemsArray as $objItem)
						if ($objItem->Value == $mixValue)
							$objItem->Selected = true;
						else
							$objItem->Selected = false;
					return $mixValue;
					break;


				case "SelectedNames":
					try {
						$mixValue = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					foreach ($this->objItemsArray as $objItem) {
						$objItem->Selected = false;
						foreach ($mixValue as $mixName) {
							if ($objItem->Name == $mixName) {
								$objItem->Selected = true;
								break;
							}
						}
					}
					return $mixValue;
					break;

				case "SelectedValues":
					try {
						$mixValue = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					foreach ($this->objItemsArray as $objItem) {
						$objItem->Selected = false;
						foreach ($mixValue as $mixName) {
							if ($objItem->Value == $mixName) {
								$objItem->Selected = true;
								break;
							}
						}
					}
					return $mixValue;
					break;

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
	}
?>