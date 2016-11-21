<?php
	/**
	 * QListItemManagerager.trait.php contains the QListItemManager trait
	 * @package Controls
	 */

	/**
	 * This is a trait that presents an interface for managing an item list. It is used by the QListControl, QHListControl,
	 * and the QHListItem classes, the latter because a QHListItem can itself contain a list of other items.
	 *
	 * Note that some abstract methods are declared here that must be implemented by the using class:
	 * GetId()	- returns the id
	 * MarkAsModified() - marks the object as modified. Optional.
	 *
	 * @package Controls
	 */
	trait QListItemManager {
		///////////////////////////
		// Private Member Variables
		///////////////////////////
		/** @var QListItemBase[] an array of subitems if this is a recursive item.  */
		protected $objListItemArray;

		/**
		 * Add a base list item to the list.
		 *
		 * @param QListItemBase $objListItem
		 */
		public function AddListItem(QListItemBase $objListItem) {
			if ($strControlId = $this->GetId()) {
				$num = 0;
				if ($this->objListItemArray) {
					$num = count($this->objListItemArray);
				}
				$objListItem->SetId($strControlId . '_' . $num);	// auto assign the id based on parent id
				$objListItem->Reindex();
			}
			$this->objListItemArray[] = $objListItem;
			$this->MarkAsModified();
		}


		/**
		 * Allows you to add a ListItem at a certain index
		 * Unlike AddItem, this will insert the ListItem at whatever index is passed to the function.  Additionally,
		 * only a ListItem object can be passed (as opposed to an object or strings)
		 *
		 * @param integer   	$intIndex    index at which the item should be inserted
		 * @param QListItemBase $objListItem the ListItem which shall be inserted
		 *
		 * @throws QIndexOutOfRangeException
		 * @throws Exception|QInvalidCastException
		 */
		public function AddItemAt($intIndex, QListItemBase $objListItem) {
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
		 * Reindex the ids of the items based on the current item. We manage all the ids in the list internally
		 * to be able to get to an item in the list quickly, and to make sure the ids are unique.
		 */
		public function Reindex() {
			if ($this->GetId() && $this->objListItemArray) for ($i = 0; $i < $this->GetItemCount(); $i++) {
				$this->objListItemArray[$i]->SetId($this->GetId() . '_' . $i);	// assign the id based on parent id
				$this->objListItemArray[$i]->Reindex();
			}
		}

		/**
		 * Stub function. The including function needs to implement this.
		 */
		abstract public function MarkAsModified();

		/**
		 * Returns the id of the item, however the item stores it.
		 * @return string
		 */
		abstract public function GetId();

		/**
		 * Adds an array of items,
		 *
		 * @param QListItemBase[]  $objListItemArray          Array of QListItems or key=>val pairs.
		 *
		 * @throws Exception|QInvalidCastException
		 */
		public function AddListItems(array $objListItemArray) {
			try {
				$objListItemArray = QType::Cast($objListItemArray, QType::ArrayType);
				if ($objListItemArray) {
					if (!reset($objListItemArray) instanceof QListItemBase) {
						throw new QCallerException ('Not an array of QListItemBase types');
					}
				}
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			if ($this->objListItemArray) {
				$this->objListItemArray = array_merge ($this->objListItemArray, $objListItemArray);
			} else {
				$this->objListItemArray = $objListItemArray;
			}
			$this->Reindex();
			$this->MarkAsModified();
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
			$this->MarkAsModified();
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
			$this->MarkAsModified();
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
			$objListItem->SetId($this->GetId() . '_' . $intIndex);
			$this->objListItemArray[$intIndex] = $objListItem;
			$objListItem->Reindex();
			$this->MarkAsModified();
		}

		/**
		 * Return the count of the items.
		 *
		 * @return int
		 */
		public function GetItemCount() {
			$count = 0;
			if ($this->objListItemArray) {
				$count = count($this->objListItemArray);
			}
			return $count;
		}

		/**
		 * Finds the item by id recursively. Makes use of the fact that we maintain the ids in order to efficiently
		 * find the item.
		 *
		 * @param string $strId If this is a sub-item, it will be an id fragment
		 * @return null|QListItem
		 */
		public function FindItem($strId) {
			if (!$this->objListItemArray) return null;
			$objFoundItem = null;
			$a = explode ('_', $strId, 3);
			if (isset($a[1]) &&
					$a[1] < count ($this->objListItemArray)) {	// just in case
				$objFoundItem = $this->objListItemArray[$a[1]];
			}
			if (isset($a[2])) { // a recursive list
				$objFoundItem = $objFoundItem->FindItem ($a[1] . '_' . $a[2]);
			}

			return $objFoundItem;
		}

		/**
		 * Returns the first tiem found with the given value.
		 *
		 * @param $strValue
		 * @return null|QListItemBase
		 */
		public function FindItemByValue($strValue) {
			if (!$this->objListItemArray) return null;
			foreach ($this->objListItemArray as $objItem) {
				if ($objItem->Value == $strValue) {
					return $objItem;
				}
			}
			return null;
		}
	}
