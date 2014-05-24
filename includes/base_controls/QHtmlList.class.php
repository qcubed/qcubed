<?php
/**
 * Class QHtmlList
 * A control that lets you dynamically create an html unordered or ordered list with
 * sub-lists. These structures are often used as the basis for javascript widgets like
 * menu bars. Subclass these classes for specific javascript widget needs.
 */


class QHtmlList extends QControl {
    protected $mixDataBinder = null;
    protected $objListItems = array();
    protected $strTag = 'ul';

    public function __construct($objParentObject, $strControlId = null) {
        parent::__construct ($objParentObject, $strControlId);
    }

    /**
     * Adds a list item. Gives it an automatically generated unique id based on the
     * control id.
     *
     * @param $objListItem
     */
    public function AddListItem (QHtmlListItem $objListItem) {
        $objListItem->_Id = $this->ControlId . '_' . count($this->objListItems);
        $this->objListItems[] = $objListItem;
    }

	/**
	 * Removes all the menus.
	 */
	public function RemoveAllMenus () {
        $this->objListItems = array();
    }

    /**
     * A binder that will generate the sub items. The binder should add
     * list items to this control. The current list items will be cleared first.
     * @param $mixDataBinder
     */
    public function SetDataBinder($mixDataBinder) {
        $this->mixDataBinder = $mixDataBinder;
    }

    public function ParsePostData() {}
    public function Validate() {return true;}

    /**
     * Returns the HTML for the control and all subitems.
     * 
     * @return string
     */
    public function GetControlHtml() {
        if ($this->mixDataBinder) {
            $this->RemoveAllMenus();
            call_user_func($this->mixDataBinder, $this);
        }
        if ($this->objListItems) {
            $strStyle = $this->GetStyleAttributes();
            if ($strStyle)
                $strStyle = sprintf('style="%s"', $strStyle);

            $strAttributes = $this->GetAttributes();

            $strHtml = sprintf ('<%s id="%s" %s %s>', $this->strTag,
                $this->ControlId, $strAttributes, $strStyle);
            foreach ($this->objListItems as $objListItem) {
                $strHtml .= $objListItem->GetHtml();
            }
            $strHtml .= '</ul>';
            return $strHtml;
        }
        return '';
    }

	/**
	 * Finds the list item that corresponds to the given id.
	 * @param $strId
	 * @return null
	 */
	public function FindListItem ($strId) {
        if ($this->objListItems) {
            foreach ($this->objListItems as $objListItem) {
                if ($objListItem->Id == $strId) {
                    return $objListItem;
                }
                elseif ($objItem = $objListItem->FindSubItem ($strId)) {
                    return $objItem;
                }
            }
        }
        return null;
    }
}

/**
 * Class QHtmlListItem
 * An Html list item. List items can contain other list items as sub-items.
 */

/**
 * @package Controls
 *
 * @property string $SubTag Tag to use to bracket sub items.
 * @property string $Text Text to display on the item
 * @property-read string $Id Id of item.
 * @property string $Anchor If set, the anchor text to print in the href= string
 */

class QHtmlListItem extends QBaseClass {
	/** @var string subtag to use for bracketing sub items. */
    protected $strSubTag = 'ul';
	/** @var  string visible text of item. */
    protected $strText;
	/** @var string id to print in the tag. This is set by the QHtmlList above. */
    protected $strId;
	/** @var  string if this has an anchor, what to redirect to. Could be javascript or a page. */
    protected $strAnchor;
	/** @var QHtmlListItem[] the sub items.  */
    protected $objSubItems = array();

	/**
	 * @param $strText
	 * @param null $strAnchor
	 */
	public function __construct($strText, $strAnchor = null) {
        $this->strText = $strText;
        $this->strAnchor = $strAnchor;
    }

	/**
	 * Adds a list item as a sub-item.
	 * @param QHtmlListItem $objListItem
	 */
	public function AddListItem (QHtmlListItem $objListItem) {
        $objListItem->strId = $this->strId . '_' . count ($this->objSubItems);
        $this->objSubItems[] = $objListItem;
    }

	/**
	 * Removes all subitems.
	 */
	public function RemoveSubItems() {
        $this->objSubItems = array();
    }

	/**
	 * Returns the attribute string for rendering.
	 * @return string
	 */
	public function GetAttributes() {
        $strHtml = '';
        if ($this->strId) {
            $strHtml .= 'id="' . $this->strId . '"';
        }
        return $strHtml;
    }

	/**
	 * Returns the inner html for the list item. Override to customize how list items get drawn.
	 * @return string
	 */
	public function GetInnerHtml() {
        $strHtml = QApplication::HtmlEntities($this->strText);

        if ($this->strAnchor) {
            $strHtml = sprintf ('<a href="%s">%s</a>', $this->strAnchor, $strHtml);
        }
        return $strHtml;
    }

	/**
	 * Return the complete HTML for the list item.
	 * @return string
	 */
	public function GetHtml () {
        $strHtml = sprintf ('<li %s>', $this->GetAttributes());
        $strHtml .= $this->GetInnerHtml();
        $strHtml .= $this->RenderSubItems();
        $strHtml .= '</li>';
        return $strHtml;
    }

	/**
	 * Render the sub items.
	 * @return string
	 */
	public function RenderSubItems() {
        if ($this->objSubItems) {
            $strHtml = '<' . $this->strSubTag . '>';
            foreach ($this->objSubItems as $objListItem) {
                $strHtml .= $objListItem->GetHtml();
            }
            $strHtml .= '</' . $this->strSubTag . '>';
            return $strHtml;
        }
        return '';
    }

	/**
	 * @param $strId
	 * @return mixed
	 */
	public function FindSubItem($strId) {
        if ($this->objSubItems) {
            foreach ($this->objSubItems as $objListItem) {
                if ($objListItem->Id == $strId) {
                    return $objListItem;
                }
                elseif ($objItem = $objListItem->FindSubItem ($strId)) {
                    return $objItem;
                }
            }
        }
    }

    public function __get($strText) {
        switch ($strText) {
            case "SubTag": return $this->strSubTag;
            case "Text": return $this->strText;
            case "Id": return $this->strId;
			case "Anchor": return $this->strAnchor;

            default:
                try {
                    return parent::__get($strText);
                } catch (QCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    public function __set($strText, $mixValue) {
        switch ($strText) {
			case "Text":
				try {
					$this->strText = QType::Cast($mixValue, QType::String);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "Anchor":
				try {
					$this->strAnchor = QType::Cast($mixValue, QType::String);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "SubTag":
                try {
                    $this->strSubTag = QType::Cast($mixValue, QType::String);
                    break;
                } catch (QInvalidCastException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case "_Id":
                try {
                    $this->strId = QType::Cast($mixValue, QType::String);
                    break;
                } catch (QInvalidCastException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            default:
                try {
                    parent::__set($strText, $mixValue);
                } catch (QCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
                break;
        }
    }
}
