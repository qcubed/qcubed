<?php
	/**
	 * QListItem.class.php contains the QListItem class
	 * @package Controls
	 */

	/**
	 * Utilized by the {@link QListControl} class which contains a private array of ListItems.
	 *
	 * @package Controls
	 * @property string         $Name      is what gets displayed
	 * @property string         $Value     is any text that represents the value of the ListItem (e.g. maybe a DB Id)
	 * @property boolean        $Selected  is a boolean of whether or not this item is selected or not (do only! use during initialization, otherwise this should be set by the {@link QListControl}!)
	 * @property string         $ItemGroup is the group (if any) in which the Item should be displayed
	 * @property QListItemStyle $ItemStyle is the QListItemStyle in which the Item should be rendered
	 * @property string         $Label     is optional text to display in the drop down menu of a QAutocomplete instead of the Name. The Name will still be what gets filled in to the text box.
	 * @property-read boolean   $Empty     true when both $Name and $Value are null, in which case this item will be rendered with an empty value in the list control
	 */
	class QListItem extends QBaseClass {
		///////////////////////////
		// Private Member Variables
		///////////////////////////
		/** @var null|string Name of the Item */
		protected $strName = null;
		/** @var null|string Value of the Item */
		protected $strValue = null;
		/** @var bool Is the item selected? */
		protected $blnSelected = false;
		/** @var null|string Group to which the item belongs */
		protected $strItemGroup = null;
		/** @var QListItemStyle Inline style of the item */
		protected $objItemStyle;
		/** @var string Label text for the item */
		protected $strLabel = null;

		/////////////////////////
		// Methods
		/////////////////////////
		/**
		 * Creates a QListItem
		 *
		 * @param string  $strName      is the displayed Name of the Item
		 * @param string  $strValue     is any text that represents the value of the ListItem (e.g. maybe a DB Id)
		 * @param boolean $blnSelected  is a boolean of whether or not this item is selected or not (optional)
		 * @param string  $strItemGroup is the group (if any) in which the Item should be displayed
		 * @param array   $strOverrideParameters
		 *                              allows you to override item styles.  It is either a string formatted as Property=Value
		 *                              or an array of the format array(property => value)
		 *
		 * @throws Exception|QCallerException
		 * @return QListItem
		 */
		public function __construct($strName, $strValue, $blnSelected = false, $strItemGroup = null, $strOverrideParameters = null) {
			$this->strName = $strName;
			$this->strValue = $strValue;
			$this->blnSelected = $blnSelected;
			$this->strItemGroup = $strItemGroup;

			// Override parameters get applied here
			$strOverrideArray = func_get_args();
			if (count($strOverrideArray) > 4)	{
				try {
					$strOverrideArray = array_reverse($strOverrideArray);
					array_pop($strOverrideArray);
					array_pop($strOverrideArray);
					array_pop($strOverrideArray);
					array_pop($strOverrideArray);
					$strOverrideArray = array_reverse($strOverrideArray);
					$this->objItemStyle = new QListItemStyle();
					$this->objItemStyle->OverrideAttributes($strOverrideArray);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}

		/**
		 * Returns the css style of the list item
		 * @param bool $blnIncludeCustom [Currently Unused]
		 * @param bool $blnIncludeAction [Currently Unused]
		 *
		 * @return string
		 */
		public function GetAttributes($blnIncludeCustom = true, $blnIncludeAction = true) {
			$strToReturn = $this->objItemStyle->GetAttributes();
			return $strToReturn;
		}

		/**
		 * Returns the details of the control as JSON string
		 * @return string
		 */
		public function toJsObject() {
			$a = array('value' => $this->strName, 'id' => $this->strValue);
			if ($this->strLabel) {
				$a['label'] = $this->strLabel;
			}
			if ($this->strItemGroup) {
				$a['category'] = $this->strItemGroup;
			}
			return JavaScriptHelper::toJsObject($a);
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
				case "Name": return $this->strName;
				case "Value": return $this->strValue;
				case "Selected": return $this->blnSelected;
				case "ItemGroup": return $this->strItemGroup;
				case "ItemStyle": return $this->objItemStyle;
				case "Label": return $this->strLabel;
				case "Empty": return $this->strValue == null && $this->strName == null;

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
				case "Name":
					try {
						$this->strName = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Value":
					try {
						$this->strValue = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}				
				case "Selected":
					try {
						$this->blnSelected = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemGroup":
					try {
						$this->strItemGroup = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemStyle":
					try {
						$this->objItemStyle = QType::Cast($mixValue, "QListItemStyle");
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Label":
					try {
						$this->strLabel = QType::Cast($mixValue, QType::String);
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
?>