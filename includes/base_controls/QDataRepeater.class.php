<?php

/**
 * The QDataRepeater is a generic html base object for creating an object that contains a list of items tied
 * to the database. To specify how to draw the items, you can either create a template file, override the
 * GetItemHtml method, override the GetItemInnerHtml and GetItemAttributes methods, or specify
 * corresponding callbacks for those methods.
 *
 * The callbacks below can be specified as either a string, or an array. If a string, it should be the name of a
 * public method in the parent form. If an array, it should be a PHP Callable array. If your callback is a method in
 * a form, do NOT pass the form object in to the array, but rather just pass the name of the method as a string.
 * (This is due to a problem PHP has with serializing recursive objects.) If its a method in a control, pass an array
 * with the control and method name, i.e. [$objControl, 'RenderMethod']
 *
 * @package Controls
 *
 * @property-read 	integer $CurrentItemIndex	The zero-based index of the item being drawn.
 * @property 		string  $TagName			The tag name to be used as the main object
 * @property 		string  $ItemTagName		The tag name to used for each item (if Template is not defined)
 * @property 		string 	$Template			A php template file that will be evaluated for each item. The template will have
 * 												$_ITEM as the item in the DataSource array, $_CONTROL as this control, and $_FORM as
 * 												the form object. If you provide a template, the callbacks will not be used.
 * @property-write 	string $ItemHtmlCallback	A Callable which will be called to get the html for each item.
 * 												Parameters passed are the item from the DataSource array, and the index of the
 * 												item being drawn. The callback should return the entire html for the item. If
 * 												you provide this callback, the ItemAttributesCallback and ItemInnerHtmlCallback
 * 												will not be used.
 * @property-write 	string $ItemAttributesCallback	A Callable which will be called to get the attributes for each item.
 * 												Use this with the ItemInnerHtmlCallback and the ItemTagName. The callback
 * 												will be passed the item and the index of the item. It should return key/value
 * 												pairs which will be used as the attributes for the item's tag. Use only
 * 												if you are not using a Template or the ItemHtmlCallback.
 * @property-write 	string $ItemInnerHtmlCallback	A Callable which will be called to get the inner html for each item.
 * 												Use this with the ItemAttributesCallback and the ItemTagName. The callback
 * 												will be passed the item and the index of the item. It should return the complete
 * 												text to appear inside the open and close tags for the item.	 *
 */
class QDataRepeater extends QPaginatedControl {
	///////////////////////////
	// Private Member Variables
	///////////////////////////

	// APPEARANCE
	/** @var string */
	protected $strTemplate = null;
	/** @var integer */
	protected $intCurrentItemIndex = null;

	/** @var string  */
	protected $strTagName = 'div';
	/** @var string  */
	protected $strItemTagName = 'div';

	/** @var  Callable | string */
	protected $itemHtmlCallback;
	/** @var  Callable | string */
	protected $itemAttributesCallback;
	/** @var  Callable | string */
	protected $itemInnerHtmlCallback;


	//////////
	// Methods
	//////////
	public function ParsePostData() {}

	/**
	 * Returns the html corresponding to a given item. You have many ways of rendering an item:
	 * 	- Specify a template that will get evaluated for each item. See EvaluateTemplate for more info.
	 *  - Specify a HtmlCallback callable to be called for each item to get the html for the item.
	 *  - Override this routine.
	 *  - Specify the item's tag name, and then use the helper functions or callbacks to return just the
	 *    attributes and/or inner html of the object.
	 *
	 * @param $objItem
	 * @return string
	 * @throws QCallerException
	 */
	protected function GetItemHtml($objItem) {
		if ($this->strTemplate) {
			return $this->EvaluateTemplate($this->strTemplate);
		} elseif ($this->itemHtmlCallback) {
			if (is_string($this->itemHtmlCallback)) {
				$strMethod = $this->itemHtmlCallback;
				return $this->objForm->$strMethod($objItem, $this->intCurrentItemIndex);
			} else {
				return call_user_func($this->itemHtmlCallback, $objItem, $this->intCurrentItemIndex);
			}
		}

		if (!$this->strItemTagName) {
			throw new QCallerException ("You must specify an item tag name before rendering the list.");
		}

		$strToReturn = QHtml::RenderTag($this->strItemTagName, $this->GetItemAttributes($objItem), $this->GetItemInnerHtml($objItem));
		return $strToReturn;
	}

	/**
	 * Return the attributes that go in the item tag, as an array of key=>value pairs. Values will be escaped for you.
	 * If you define AttributesCallback, it will be used to determine
	 * the attributes.
	 *
	 * @param $objItem
	 * @return array
	 */
	protected function GetItemAttributes ($objItem) {
		if ($this->itemAttributesCallback) {
			if (is_string($this->itemAttributesCallback)) {
				$strMethod = $this->itemAttributesCallback;
				return $this->objForm->$strMethod($objItem, $this->intCurrentItemIndex);
			} else {
				return call_user_func($this->itemAttributesCallback, $objItem, $this->intCurrentItemIndex);
			}
		}
		return null;
	}

	/**
	 * Returns the HTML between the item tags. Uses __toString on the object by default. Will use the
	 * InnerHtmlCallback if provided.
	 *
	 * @param $objItem
	 * @return mixed
	 */
	protected function GetItemInnerHtml($objItem) {
		if ($this->itemInnerHtmlCallback) {
			if (is_string($this->itemInnerHtmlCallback)) {
				$strMethod = $this->itemInnerHtmlCallback;
				return $this->objForm->$strMethod($objItem, $this->intCurrentItemIndex);
			} else {
				return call_user_func($this->itemInnerHtmlCallback, $objItem, $this->intCurrentItemIndex);
			}
		}
		return $objItem->__toString();	// default to rendering a database object
	}

	/**
	 * Returns the HTML for the control.
	 * @return string
	 */
	protected function GetControlHtml() {
		$this->DataBind();

		// Iterate through everything
		$this->intCurrentItemIndex = 0;
		$strEvalledItems = '';
		$strToReturn = '';
		if ($this->objDataSource) {
			global $_FORM;
			global $_CONTROL;
			global $_ITEM;

			$objCurrentControl = $_CONTROL;
			$_CONTROL = $this;

			foreach ($this->objDataSource as $objObject) {
				$_ITEM = $objObject;
				$strEvalledItems .= $this->GetItemHtml($objObject);
				$this->intCurrentItemIndex++;
			}

			$_CONTROL = $objCurrentControl;
		}

		$strToReturn = $this->RenderTag($this->strTagName,
			null,
			null,
			$strEvalledItems);

		$this->objDataSource = null;
		return $strToReturn;
	}

	/////////////////////////
	// Public Properties: GET
	/////////////////////////
	/**
	 * PHP magic method
	 *
	 * @param string $strName Name of the property
	 *
	 * @return int|mixed|string
	 * @throws Exception|QCallerException
	 */
	public function __get($strName) {
		switch ($strName) {
			// APPEARANCE
			case "Template": return $this->strTemplate;
			case "CurrentItemIndex": return $this->intCurrentItemIndex;
			case "TagName": return $this->strTagName;
			case "ItemTagName": return $this->strItemTagName;

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
	 *
	 * @param string $strName  Property name
	 * @param string $mixValue Property value
	 *
	 * @return mixed|void
	 * @throws Exception|QCallerException|QInvalidCastException
	 */
	public function __set($strName, $mixValue) {
		switch ($strName) {
			// APPEARANCE
			case "Template":
				try {
					$this->blnModified = true;
					if ($mixValue) {
						if (file_exists($strPath = $this->GetTemplatePath($mixValue))) {
							$this->strTemplate = QType::Cast($strPath, QType::String);
						} else {
							throw new QCallerException('Could not find template file: ' . $mixValue);
						}
					} else {
						$this->strTemplate = null;
					}
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "TagName":
				try {
					$this->blnModified = true;
					$this->strTagName = QType::Cast($mixValue, QType::String);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case 'ItemTagName':
				try {
					$this->blnModified = true;
					$this->strItemTagName = QType::Cast($mixValue, QType::String);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case 'ItemHtmlCallback':
				$this->blnModified = true;
				if (is_array($mixValue) && reset($mixValue) === $this->objForm) {
					// Do not pass [$objForm, 'methodName']. Rather, just set to 'methodName', and the form will be searched for that method.
					throw new QCallerException ('Do not pass the form object in a callable array. Instead, just pass the method name.');
				}
				$this->itemHtmlCallback = $mixValue;
				break;

			case 'ItemAttributesCallback':	// callback should return an array of key/value items
				$this->blnModified = true;
				if (is_array($mixValue) && reset($mixValue) === $this->objForm) {
					// Do not pass [$objForm, 'methodName']. Rather, just set to 'methodName', and the form will be searched for that method.
					throw new QCallerException ('Do not pass the form object in a callable array. Instead, just pass the method name.');
				}
				$this->itemAttributesCallback = $mixValue;
				break;

			case 'ItemInnerHtmlCallback':
				$this->blnModified = true;
				if (is_array($mixValue) && reset($mixValue) === $this->objForm) {
					// Do not pass [$objForm, 'methodName']. Rather, just set to 'methodName', and the form will be searched for that method.
					throw new QCallerException ('Do not pass the form object in a callable array. Instead, just pass the method name.');
				}
				$this->itemInnerHtmlCallback = $mixValue;
				break;

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