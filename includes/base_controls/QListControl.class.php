<?php
	/**
	 * This file contains the QListControl class.
	 * 
	 * @package Controls
	 */

	/**
	 * Abstract object which is extended by anything which involves lists of selectable items.
	 * This object is the foundation for the ListBox, CheckBoxList, RadioButtonList
	 * and TreeNav. Subclasses can be used as objects to specify one-to-many and many-to-many relationships.
	 *
	 * @property-read integer        $ItemCount      the current count of ListItems in the control.
	 * @property integer        $SelectedIndex  is the index number of the control that is selected. "-1" means that nothing is selected. If multiple items are selected, it will return the lowest index number of all ListItems that are currently selected. Set functionality: selects that specific ListItem and will unselect all other currently selected ListItems.
	 * @property string         $SelectedName   simply returns ListControl::SelectedItem->Name, or null if nothing is selected.
	 * @property-read QListItem $SelectedItem   (readonly!) returns the ListItem object, itself, that is selected (or the ListItem with the lowest index number of a ListItems that are currently selected if multiple items are selected). It will return null if nothing is selected.
	 * @property-read array     $SelectedItems  returns an array of selected ListItems (if any).
	 * @property mixed          $SelectedValue  simply returns ListControl::SelectedItem->Value, or null if nothing is selected.
	 * @property array          $SelectedNames  returns an array of all selected names
	 * @property array          $SelectedValues returns an array of all selected values
	 * @property string  		$ItemStyle     {@link QListItemStyle}
	 * @see     QListItemStyle
	 * @package Controls
	 */
	abstract class QListControl extends QControl {

		use QListItemManager;

		/** @var null|QListItemStyle The common style for all elements in the list */
		protected $objItemStyle = null;

		//////////
		// Methods
		//////////

		public function AddItem($mixListItemOrName, $strValue = null, $blnSelected = null, $strItemGroup = null, $mixOverrideParameters = null) {
			if (gettype($mixListItemOrName) == QType::Object) {
				$objListItem = QType::Cast($mixListItemOrName, "QListItem");
			}
			elseif ($mixOverrideParameters) {
				// The OverrideParameters can only be included if they are not null, because OverrideAttributes in QBaseClass can't except a NULL Value
				$objListItem = new QListItem($mixListItemOrName, $strValue, $blnSelected, $strItemGroup, $mixOverrideParameters);
			}
			else {
				$objListItem = new QListItem($mixListItemOrName, $strValue, $blnSelected, $strItemGroup);
			}

			$this->AddListItem ($objListItem);
		}

		/**
		 * Adds an array of items, or an array of key=>value pairs. Convenient for adding a list from a type table.
		 * When passing key=>val pairs, mixSelectedValues can be an array, or just a single value to compare against to indicate what is selected.
		 *
		 * @param array  $mixItemArray          Array of QListItems or key=>val pairs.
		 * @param mixed  $mixSelectedValues     Array of selected values, or value of one selection
		 * @param string $strItemGroup          allows you to apply grouping (<optgroup> tag)
		 * @param string $mixOverrideParameters OverrideParameters for ListItemStyle
		 *
		 * @throws Exception|QInvalidCastException
		 */
		public function AddItems(array $mixItemArray, $mixSelectedValues = null, $strItemGroup = null, $mixOverrideParameters = null) {
			try {
				$mixItemArray = QType::Cast($mixItemArray, QType::ArrayType);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			foreach ($mixItemArray as $val => $item) {
				if ($val === '') {
					$val = null; // these are equivalent when specified as a key of an array
				}
				if ($mixSelectedValues && is_array($mixSelectedValues)) {
					$blnSelected = in_array($val, $mixSelectedValues);
				} else {
					$blnSelected = ($val === $mixSelectedValues);	// differentiate between null and 0 values
				}
				$this->AddItem($item, $val, $blnSelected, $strItemGroup, $mixOverrideParameters);
			};
			$this->Reindex();
			$this->MarkAsModified();
		}

		/**
		 * Return the id. Used by QListItemManager trait.
		 * @return string
		 */
		public function GetId() {
			return $this->strControlId;
		}

		/**
		 * Recursively unselects all the items and subitems in the list.
		 *
		 * @param bool $blnMarkAsModified
		 */
		public function UnselectAllItems($blnMarkAsModified = true) {
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				$objItem->Selected = false;
			}
			if ($blnMarkAsModified) {
				$this->MarkAsModified();
			}
		}


		/**
		 * Selects the given items by Id, and unselects items that are not in the list.
		 * @param string[] $strIdArray
		 * @param bool $blnMarkAsModified
		 */
		public function SetSelectedItemsById(array $strIdArray, $blnMarkAsModified = true) {
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				$strId = $objItem->GetId();
				$objItem->Selected = in_array($strId, $strIdArray);
			}
			if ($blnMarkAsModified) {
				$this->MarkAsModified();
			}
		}

		/**
		 * Set the selected item by index. This can only set top level items. Lower level items are untouched.
		 * @param integer[] $intIndexArray
		 * @param bool $blnMarkAsModified
		 */
		public function SetSelectedItemsByIndex(array $intIndexArray, $blnMarkAsModified = true) {
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				$objItem->Selected = in_array($intIndex, $intIndexArray);
			}
			if ($blnMarkAsModified) {
				$this->MarkAsModified();
			}
		}

		/**
		 * Set the selected items by value. We equate nulls and empty strings, but must be careful not to equate
		 * those with a zero.
		 *
		 * @param array $mixValueArray
		 * @param bool $blnMarkAsModified
		 */
		public function SetSelectedItemsByValue(array $mixValueArray, $blnMarkAsModified = true) {
			$intCount = $this->GetItemCount();

			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				$mixCurVal = $objItem->Value;
				$blnSelected = false;
				foreach ($mixValueArray as $mixValue) {
					if (!$mixValue) {
						if ($mixValue === null || $mixValue === '') {
							if ($mixCurVal === null || $mixCurVal === '') {
								$blnSelected = true;
							}
						} else {
							if (!($mixCurVal === null || $mixCurVal === '')) {
								$$blnSelected = true;
							}
						}
					}
					elseif ($mixCurVal == $mixValue) {
						$blnSelected = true;
					}
				}
				$objItem->Selected = $blnSelected;
			}
			if ($blnMarkAsModified) {
				$this->MarkAsModified();
			}
		}


		/**
		 * Set the selected items by name.
		 * @param string[] $strNameArray
		 * @param bool $blnMarkAsModified
		 */
		public function SetSelectedItemsByName(array $strNameArray, $blnMarkAsModified = true) {
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				$strName = $objItem->Name;
				$objItem->Selected = in_array($strName, $strNameArray);
			}
			if ($blnMarkAsModified) {
				$this->MarkAsModified();
			}
		}


		public function GetFirstSelectedItem() {
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				if ($objItem->Selected) {
					return $objItem;
				}
			}
			return null;
		}

		public function GetSelectedItems() {
			$aResult = array();
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				if ($objItem->Selected) {
					$aResult[] = $objItem;
				}
			}
			return $aResult;
		}

		/**
		 * Returns the current state of the control to be able to restore it later.
		 */
		public function GetState(){
			return array('SelectedValues'=>$this->SelectedValues);
		}

		/**
		 * Restore the  state of the control.
		 */
		public function PutState($state) {
			if (!empty($state['SelectedValues'])) {
				$this->SelectedValues = $state['SelectedValues'];
			}
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Property Name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "ItemCount":
					return $this->GetItemCount();

				case "SelectedIndex":
					for ($intIndex = 0; $intIndex < $this->GetItemCount(); $intIndex++) {
						if ($this->GetItem($intIndex)->Selected)
							return $intIndex;
					}
					return -1;

				case "SelectedName": // assumes first selected item is the selection
					if ($objItem = $this->GetFirstSelectedItem()) {
						return $objItem->Name;
					}
					return null;

				case "SelectedValue":
				case "Value":
					if ($objItem = $this->GetFirstSelectedItem()) {
						return $objItem->Value;
					}
					return null;

				case "SelectedItem":
					if ($objItem = $this->GetFirstSelectedItem()) {
						return $objItem;
					}
					elseif ($this->GetItemCount()) {
						return $this->GetItem (0);
					}
					return null;
				case "SelectedItems":
					return $this->GetSelectedItems();

				case "SelectedNames":
					$objItems = $this->GetSelectedItems();
					$strNamesArray = array();
					foreach ($objItems as $objItem) {
						$strNamesArray[] = $objItem->Name;
					}
					return $strNamesArray;

				case "SelectedValues":
					$objItems = $this->GetSelectedItems();
					$values = array();
					foreach ($objItems as $objItem) {
						$values[] = $objItem->Value;
					}
					return $values;

				case "ItemStyle":
					return $this->objItemStyle;

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
		/**
		 * PHP __set magic method implementation
		 *
		 * @param string $strName  Property Name
		 * @param string $mixValue Propety Value
		 *
		 * @return mixed|void
		 * @throws QIndexOutOfRangeException|Exception|QCallerException|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "SelectedIndex":
					try {
						$mixValue = QType::Cast($mixValue, QType::Integer);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					$itemCount = $this->GetItemCount();
					if (($mixValue < -1) ||	// special case to unselect all
						($mixValue > ($itemCount - 1)))
						throw new QIndexOutOfRangeException($mixValue, "SelectedIndex");

					$this->SetSelectedItemsByIndex(array($mixValue));
					return $mixValue;

				case "SelectedName":
					$this->SetSelectedItemsByName(array($mixValue));
					return $mixValue;

				case "SelectedValue":
				case "Value": // most common situation
					$this->SetSelectedItemsByValue(array($mixValue));
					return $mixValue;

				case "SelectedNames":
					try {
						$mixValue = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					$this->SetSelectedItemsByName($mixValue);
					return $mixValue;

				case "SelectedValues":
					try {
						$mixValues = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					$this->SetSelectedItemsByValue($mixValue);
					return $mixValues;

				case "ItemStyle":
					try {
						$this->blnModified = true;
						$this->objItemStyle = QType::Cast($mixValue, "QListItemStyle");
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;


				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					return null;
			}
		}

	}
?>