<?php
	/**
	 * QHListItem.class.php contains the QHListItem class
	 * @package Controls
	 */

	/**
	 * Represents an item in a hierarchical item list. Uses the QListItemManager trait to manage the interface for adding
	 * sub-items.
	 *
	 * @package Controls
	 * @property string $Anchor If set, the anchor text to print in the href= string when drawing as an anchored item.
	 */
	class QHListItem extends QListItemBase {

		/** Allows items to have sub items, and manipulate them with the same interface */
		use QListItemManager;

		///////////////////////////
		// Private Member Variables
		///////////////////////////
		/** @var  string|null if this has an anchor, what to redirect to. Could be javascript or a page. */
		protected $strAnchor;
		/** @var  string|null  a custom tag to draw the item with.*/
		protected $strTag;
		/** @var  QTagStyler for styling the subtag if needed. */
		protected $objSubTagStyler;


		/////////////////////////
		// Methods
		/////////////////////////
		/**
		 * Creates a QListItem
		 *
		 * @param string  $strName		is the displayed Name or Text of the Item
		 * @param string|null  $strValue     is any text that represents the value of the ListItem (e.g. maybe a DB Id)
		 * @param string|null $strAnchor 	is an href anchor that will be associated with item
		 *
		 * @throws Exception|QCallerException
		 */
		public function __construct($strName, $strValue = null, $strAnchor = null) {
			parent::__construct ($strName, $strValue);
			$this->strAnchor = $strAnchor;
		}

		/**
		 * Add an item by a QHListItem or a name,value pair
		 * @param string|QHListItem $mixListItemOrName
		 * @param string|null $strValue
		 * @param null|string $strAnchor
		 */
		public function AddItem($mixListItemOrName, $strValue = null, $strAnchor = null) {
			if (gettype($mixListItemOrName) == QType::Object) {
				$objListItem = QType::Cast($mixListItemOrName, "QHListItem");
			}
			else {
				$objListItem = new QHListItem($mixListItemOrName, $strValue, $strAnchor);
			}

			$this->AddListItem ($objListItem);
		}

		/**
		 * Adds an array of items, or an array of key=>value pairs.
		 * @param array $objItemArray	An array of QHListItems or key=>val pairs to be sent to contructor.
		 */
		public function AddItems($objItemArray) {
			if (!$objItemArray) return;

			if (!is_object(reset($objItemArray))) {
				foreach ($objItemArray as $key=>$val) {
					$this->AddItem ($key, $val);
				}
			} else {
				$this->AddListItems ($objItemArray);
			}
		}

		/**
		 * Returns a QTagStyler for styling the sub tag.
		 * @return QTagStyler
		 */
		public function GetSubTagStyler() {
			if (!$this->objSubTagStyler) {
				$this->objSubTagStyler = new QTagStyler();
			}
			return $this->objSubTagStyler;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP magic method
		 * @param string $strName
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "Anchor": return $this->strAnchor;
				case "Tag": return $this->strTag;

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
		 * PHP magic method
		 * @param string $strName
		 * @param string $mixValue
		 *
		 * @return mixed
		 * @throws Exception|QCallerException|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "Anchor":
					try {
						$this->strAnchor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Tag":
					try {
						$this->strTag = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}