<?php
	/**
	 * QListItemManagerager.trait.php contains the QListItemManager trait
	 * @package Controls
	 */

	/**
	 * Since QListItems can be recursive, then logically both a QListControl, and a QListItem manages a collection of
	 * QListItems. To prevent duplication of code, this trait is used by both to do that basic management of the
	 * item list itself.
	 *
	 * @package Controls
	 */
	trait QListItemManager {
		///////////////////////////
		// Private Member Variables
		///////////////////////////
		/** @var QListItem[] an array of subitems if this is a recursive item.  */
		protected $objListItemArray;

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

			if ($strControlId = $objListItem->ControlId) {
				$objListItem->ControlId = $this->ControlId . '_' . count($this->objListItemArray);	// auto assign the id based on parent id
				$objListItem->Reindex();
			}
			$this->objListItemArray[] = $objListItem;
			$this->_MarkAsModified();
		}

		/**
		 * Allows you to add a ListItem at a certain index
		 * Unlike AddItem, this will insert the ListItem at whatever index is passed to the function.  Additionally,
		 * only a ListItem object can be passed (as opposed to an object or strings)
		 *
		 * @param integer   $intIndex    index at which the item should be inserted
		 * @param QListItem $objListItem the ListItem which shall be inserted
		 *
		 * @throws QIndexOutOfRangeException
		 * @throws Exception|QInvalidCastException
		 */
		public function AddItemAt($intIndex, QListItem $objListItem) {
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if ($intIndex >= 0 &&
				(!$this->objListItemArray && $intIndex == 0 ||
					$intIndex <= count($this->objListItemArray))) {
				for ($intCount = count($this->objListItemArray); $intCount > $intIndex; $intCount--) {
					$this->objListItemArray[$intCount] = $this->objListItemArray[$intCount - 1];
				}
			} else {
				throw new QIndexOutOfRangeException($intIndex, "AddItemAt()");
			}

			$this->objListItemArray[$intIndex] = $objListItem;
			$this->Reindex();
		}

		/**
		 * Reindex the ids of the items based on the current item.
		 */
		public function Reindex() {
			if ($this->ControlId && $this->objListItemArray) for ($i = 0; $i < $this->GetItemCount(); $i++) {
				$this->objListItemArray[$i]->ControlId = $this->ControlId . '_' . $i;	// assign the id based on parent id
				$this->objListItemArray[$i]->Reindex();
			}
		}

		/**
		 * Stub function. If the object this is mixed in with is a control, then the control will override and implement.
		 */
		private function _MarkAsModified() {
			if (method_exists($this, 'MarkAsModified')) {
				$this->MarkAsModified();	// No current way around this. We are calling an unknown function, which makes this trait not self validated.
			}
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
			$this->_MarkAsModified();
		}

		/**
		 * Retrieve the ListItem at the specified index location
		 *
		 * @param integer $intIndex
		 *
		 * @throws QIndexOutOfRangeException
		 * @throws Exception|QInvalidCastException
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
				($intIndex >= count($this->objListItemArray)))
				throw new QIndexOutOfRangeException($intIndex, "GetItem()");

			return $this->objListItemArray[$intIndex];
		}

		/**
		 * This will return an array of ALL the QListItems associated with this QListControl.
		 * Please note that while each individual item can be altered, altering the array, itself,
		 * will not affect any change on the QListControl.  So existing QListItems may be modified,
		 * but to add / remove items from the QListControl, you should use AddItem() and RemoveItem().
		 * @return QListItem[]
		 */
		public function GetAllItems() {
			return $this->objListItemArray;
		}

		/**
		 * Removes all the items in objListItemArray
		 */
		public function RemoveAllItems() {
			$this->_MarkAsModified();
			$this->objListItemArray = null;
		}

		/**
		 * Removes a ListItem at the specified index location
		 *
		 * @param integer $intIndex
		 *
		 * @throws QIndexOutOfRangeException
		 * @throws Exception|QInvalidCastException
		 */
		public function RemoveItem($intIndex) {
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) ||
				($intIndex > (count($this->objListItemArray) - 1)))
				throw new QIndexOutOfRangeException($intIndex, "RemoveItem()");
			for ($intCount = $intIndex; $intCount < count($this->objListItemArray) - 1; $intCount++) {
				$this->objListItemArray[$intCount] = $this->objListItemArray[$intCount + 1];
			}

			$this->objListItemArray[$intCount] = null;
			unset($this->objListItemArray[$intCount]);
			$this->_MarkAsModified();
			$this->Reindex();
		}

		/**
		 * Replaces a QListItem at $intIndex. This combines the RemoveItem() and AddItemAt() operations.
		 *
		 * @param integer   $intIndex
		 * @param QListItem $objListItem
		 *
		 * @throws Exception|QInvalidCastException
		 */
		public function ReplaceItem($intIndex, QListItem $objListItem) {
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$objListItem->ControlId = $this->ControlId . '_' . $intIndex;
			$this->objListItemArray[$intIndex] = $objListItem;
			$objListItem->Reindex();
			$this->_MarkAsModified();
		}

		/**
		 * Finds the item by id recursively. Makes use of the fact that we maintain the ids in order to efficiently
		 * find the item.
		 *
		 * @param string $strId If this is a sub-item, it will be an id fragment
		 * @return null|QListItem
		 */
		public function FindItem($strId) {
			$objFoundItem = null;
			$a = explode ('_', $strId, 3);
			if (isset($a[1]) &&
					$this->objListItemArray &&	// just in case
					$a[1] < count ($this->objListItemArray)) {	// just in case
				$objFoundItem = $this->objListItemArray[$a[1]];
			}
			if (isset($a[2])) {
				$objFoundItem = $objFoundItem->FindItem ($a[1] . '_' . $a[2]);
			}

			return $objFoundItem;
		}

		public function GetItemCount($blnRecursive = false) {
			$count = 0;
			if ($this->objListItemArray) {
				$count = count($this->objListItemArray);
				if ($blnRecursive) {
					foreach ($this->objListItemArray as $objListItem) {
						$count += $objListItem->GetItemCount(true);
					}
				}
			}
			return $count;
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
				if ($objItem->GetItemCount()) {
					$objItem->UnselectAllItems(false);
				}
			}
			if ($blnMarkAsModified) {
				$this->_MarkAsModified();
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
				$strId = $objItem->ControlId;
				$objItem->Selected = in_array($strId, $strIdArray);
				if ($objItem->GetItemCount()) {
					$objItem->SetSelectedItemsById($strIdArray, false);
				}
			}
			if ($blnMarkAsModified) {
				$this->_MarkAsModified();
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
				$this->_MarkAsModified();
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
				$this->_MarkAsModified();
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
				if ($objItem->GetItemCount()) {
					$objItem->SetSelectedItemsByName($strNameArray, false);
				}
			}
			if ($blnMarkAsModified) {
				$this->_MarkAsModified();
			}
		}


		public function GetFirstSelectedItem() {
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				if ($objItem->Selected) {
					return $objItem;
				}
				if ($objItem2 = $objItem->GetFirstSelectedItem()) {
					return $objItem2;
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
				if ($objItems = $objItem->GetSelectedItems()) {
					$aResult = array_merge ($aResult, $objItems);
				}
			}
			return $aResult;
		}


	}
