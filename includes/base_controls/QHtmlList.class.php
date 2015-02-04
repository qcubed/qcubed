<?php
/**
 * Class QHtmlList
 * A control that lets you dynamically create an html unordered or ordered list with
 * sub-lists. These structures are often used as the basis for javascript widgets like
 * menu bars.
 *
 * Also supports data binding. When using the data binder, it will recreate the item list each time it draws,
 * and then delete the item list so that the list does not get stored in the formstate. It is common for lists like
 * this to associate items in a database with items in a list through the value attribute of each item.
 * In an effort to make sure that database ids are not exposed to the client (for security reasons), the value
 * attribute is encrypted.
 *
 * @property string  $Tag		Tag for main wrapping object
 * @property string  $ItemTag 	Tag for each item
 */


class QHtmlList extends QListControl {
	/** @var callable */
    protected $mixDataBinder = null;
	/** @var string  top level tag */
	protected $strTag = 'ul';
	/** @var string  item tag */
	protected $strItemTag = 'li';

    /**
     * A binder that will generate the item list. The binder should add
     * list items to this control. The current list items will be cleared first.
     * @param callable $mixDataBinder
     */
    public function SetDataBinder(callable $mixDataBinder) {
        $this->mixDataBinder = $mixDataBinder;
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
     * Returns the HTML for the control and all subitems.
     * 
     * @return string
     */
    public function GetControlHtml() {
		$strHtml = '';
        if ($this->mixDataBinder) {
            call_user_func($this->mixDataBinder, $this);
        }
        if ($this->GetItemCount()) {
			$strHtml = '';
            foreach ($this->GetAllItems() as $objItem) {
                $strHtml .= $this->GetItemHtml($objItem);
            }

			$strHtml = $this->RenderTag($this->strTag, null, null, $strHtml);
        }
		if ($this->mixDataBinder) {
			$this->RemoveAllItems();
		}

		return $strHtml;
	}

	/**
	 * Return the html to draw an item.
	 *
	 * @param QListItem $objItem
	 * @return string
	 */
	protected function GetItemHtml (QListItem $objItem) {
		$strHtml = $this->GetItemText($objItem);
		$strHtml .= "\n";
		if ($objItem->GetItemCount()) {
			$strSubHtml = '';
			foreach ($objItem->GetAllItems() as $objSubItem) {
				$strSubHtml .= $this->GetItemHtml($objSubItem);
			}
			$strHtml .= QHtml::RenderTag($this->strTag, $this->GetSubTagAttributes($objItem), $strSubHtml);
		}
		$objStyler = $this->GetItemStyler($objItem);
		$strHtml = QHtml::RenderTag($this->strItemTag, $objStyler->RenderHtmlAttributes(), $strHtml);

		return $strHtml;
	}

	/**
	 * Return the text html of the item.
	 *
	 * @param QListItem $objItem
	 * @return string
	 */
	protected function GetItemText (QListItem $objItem) {
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
	 * @param QListItem $objItem
	 * @return QListItemStyle
	 */
	protected function GetItemStyler (QListItem $objItem) {
		if ($this->objItemStyle) {
			$objStyler = clone $this->objItemStyle;
		}
		else {
			$objStyler = new QListItemStyle();
		}
		$objStyler->SetHtmlAttribute('id', $objItem->ControlId);
		if ($objStyle = $objItem->ItemStyle) {
			$objStyler->Override($objStyle);
		}
		return $objStyler;
	}

	/**
	 * Return the attributes for the sub tag that wraps the item tags
	 * @param QListItem $objItem
	 * @return null|array|string
	 */
	protected function GetSubTagAttributes(QListItem $objItem) {
		return null;
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

