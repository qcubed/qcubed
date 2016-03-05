<?php
/**
 * Class QHListControl
 *
 * A control that lets you dynamically create an html unordered or ordered hierarchical list with
 * sub-lists. These structures are often used as the basis for javascript widgets like
 * menu bars.
 *
 * Also supports data binding. When using the data binder, it will recreate the item list each time it draws,
 * and then delete the item list so that the list does not get stored in the formstate. It is common for lists like
 * this to associate items in a database with items in a list through the value attribute of each item.
 * In an effort to make sure that database ids are not exposed to the client (for security reasons), the value
 * attribute is encrypted.
 *
 * @property string  	$Tag			Tag for main wrapping object
 * @property string  	$ItemTag 		Tag for each item
 * @property bool  		$EncryptValues 	Whether to encrypt the values that are printed in the html. Useful if the values
 * 										are something you want to publicly hide, like database ids. True by default.
 */


class QHListControl extends QControl {

	use QListItemManager, QDataBinder;

	/** @var string  top level tag */
	protected $strTag = 'ul';
	/** @var string  item tag */
	protected $strItemTag = 'li';
	/** @var null|QListItemStyle The common style for all elements in the list */
	protected $objItemStyle = null;
	/** @var null|QCryptography the temporary cryptography object for encrypting database values sent to the client */
	protected $objCrypt = null;
	/** @var bool Whether to encrypt values */
	protected $blnEncryptValues = true;

	/**
	 * Adds an item to the list.
	 *
	 * @param QHListItem|string $mixListItemOrName
	 * @param null|string $strValue
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
	 * Adds an array of items to the list. The array can also be an array of key>val pairs
	 * @param array $objItemArray	An array of QHListItems or key=>val pairs to be sent to constructor.
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
	 * This is not a typical input control, so there is no post data to read.
	 */
	public function ParsePostData() {}

	/**
	 * Validate the submitted data
	 * @return bool
	 */
    public function Validate() {return true;}

	/**
	 * Return the id. Used by QListItemManager trait.
	 * @return string
	 */
	public function GetId() {
		return $this->strControlId;
	}

	/**
     * Returns the HTML for the control and all subitems.
     * 
     * @return string
     */
    public function GetControlHtml() {
		$strHtml = '';
        if ($this->HasDataBinder()) {
            $this->CallDataBinder();
        }
        if ($this->GetItemCount()) {
			$strHtml = '';
            foreach ($this->GetAllItems() as $objItem) {
                $strHtml .= $this->GetItemHtml($objItem);
            }

			$strHtml = $this->RenderTag($this->strTag, null, null, $strHtml);
        }
		if ($this->HasDataBinder()) {
			$this->RemoveAllItems();
		}

		return $strHtml;
	}

	/**
	 * Return the html to draw an item.
	 *
	 * @param mixed $objItem
	 * @return string
	 */
	protected function GetItemHtml ($objItem) {
		$strHtml = $this->GetItemText($objItem);
		$strHtml .= "\n";
		if ($objItem->GetItemCount()) {
			$strSubHtml = '';
			foreach ($objItem->GetAllItems() as $objSubItem) {
				$strSubHtml .= $this->GetItemHtml($objSubItem);
			}
			$strTag = $objItem->Tag;
			if (!$strTag) {
				$strTag = $this->strTag;
			}
			$strHtml .= QHtml::RenderTag($strTag, $this->GetSubTagAttributes($objItem), $strSubHtml);
		}
		$objStyler = $this->GetItemStyler($objItem);
		$strHtml = QHtml::RenderTag($this->strItemTag, $objStyler->RenderHtmlAttributes(), $strHtml);

		return $strHtml;
	}

	/**
	 * Return the text html of the item.
	 *
	 * @param mixed $objItem
	 * @return string
	 */
	protected function GetItemText ($objItem) {
		$strHtml = QApplication::HtmlEntities($objItem->Text);

		if ($strAnchor = $objItem->Anchor) {
			$strHtml = QHtml::RenderTag('a', ['href' => $strAnchor], $strHtml, false, true);
		}
		return $strHtml;
	}

	/**
	 * Return the item styler for the given item. Combines the generic item styles found in this class with
	 * any specific item styles found in the item.
	 *
	 * @param mixed $objItem
	 * @return QListItemStyle
	 */
	protected function GetItemStyler ($objItem) {
		if ($this->objItemStyle) {
			$objStyler = clone $this->objItemStyle;
		}
		else {
			$objStyler = new QListItemStyle();
		}
		$objStyler->SetHtmlAttribute('id', $objItem->Id);

		// since we are going to embed the value in the tag, we are going to encrypt it in case its a database record id.
		if ($objItem->Value) {
			if ($this->blnEncryptValues) {
				$strValue = $this->EncryptValue($objItem->Value);
			} else {
				$strValue = $objItem->Value;
			}
			$objStyler->SetDataAttribute('value', $strValue);
		}
		if ($objStyle = $objItem->ItemStyle) {
			$objStyler->Override($objStyle);
		}
		return $objStyler;
	}

	/**
	 * Return the encrypted value of the given object
	 *
	 * @param string $value
	 * @return string
	 */
	protected function EncryptValue($value) {
		if (!$this->objCrypt) {
			$this->objCrypt = new QCryptography(null, true);
		}
		return $this->objCrypt->Encrypt($value);
	}

	/**
	 * Return the decrypted value of the given value string.
	 *
	 * @param $strEncryptedValue
	 * @return string
	 */
	public function DecryptValue ($strEncryptedValue) {
		if (!$this->objCrypt) {
			$this->objCrypt = new QCryptography(null, true);
		}
		return $this->objCrypt->Decrypt($strEncryptedValue);
	}

	/**
	 * Return the attributes for the sub tag that wraps the item tags
	 * @param mixed $objItem
	 * @return array|null|string
	 */
	protected function GetSubTagAttributes($objItem) {
		return $objItem->GetSubTagStyler()->RenderHtmlAttributes();
	}


	/////////////////////////
	// Public Properties: GET
	/////////////////////////
	/**
	 * PHP magic function
	 * @param string $strName
	 *
	 * @return mixed
	 * @throws Exception|QCallerException
	 */
	public function __get($strName) {
		switch ($strName) {
			// APPEARANCE
			case "Tag": return $this->strTag;
			case "ItemTag": return $this->strItemTag;
			case "EncryptValues": return $this->blnEncryptValues;
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
			// APPEARANCE
			case "Tag":
				try {
					$this->strTag = QType::Cast($mixValue, QType::String);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "ItemTag":
				try {
					$this->strItemTag = QType::Cast($mixValue, QType::String);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "EncryptValues":
				try {
					$this->blnEncryptValues = QType::Cast($mixValue, QType::Boolean);
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

