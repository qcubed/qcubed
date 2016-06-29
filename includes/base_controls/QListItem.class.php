<?php
	/**
	 * QListItem.class.php contains the QListItem class
	 * @package Controls
	 */

	/**
	 * Utilized by the {@link QListControl} class which contains a private array of ListItems. Originally these
	 * represented items in a select list, but now represent items in any kind of control that has repetitive items
	 * in it. This includes list controls, menus, drop-downs, and hierarchical lists. This is a general purpose container
	 * for the options in each item. Note that not all the options are used by every control, and we don't do any drawing here.
	 *
	 * @package Controls
	 * @property boolean        $Selected  is a boolean of whether or not this item is selected or not (do only! use during initialization, otherwise this should be set by the {@link QListControl}!)
	 * @property string         $ItemGroup is the group (if any) in which the Item should be displayed
	 * @property string         $Label     is optional text to display instead of the Name for certain controls.
	 */
	class QListItem extends QListItemBase implements JsonSerializable {

		///////////////////////////
		// Private Member Variables
		///////////////////////////
		/** @var bool Is the item selected? */
		protected $blnSelected = false;
		/** @var null|string Group to which the item belongs, if control supports groups. */
		protected $strItemGroup = null;
		/** @var string Label text for the item. */
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
		 * @param array|string   $mixOverrideParameters
		 *                              allows you to override item styles.  It is either a string formatted as Property=Value
		 *                              or an array of the format array(property => value)
		 *
		 * @throws Exception|QCallerException
		 * @return QListItem
		 */
		public function __construct($strName, $strValue = null, $blnSelected = false, $strItemGroup = null, $mixOverrideParameters = null) {
			parent::__construct ($strName, $strValue);
			$this->blnSelected = $blnSelected;
			$this->strItemGroup = $strItemGroup;

			// Override parameters get applied here
			$strOverrideArray = func_get_args();
			if (count($strOverrideArray) > 4)	{
				throw new QCallerException ("Please provide either a string, or an array, but not multiple parameters");
			}
			if ($mixOverrideParameters) {
				$this->GetStyle()->OverrideAttributes($mixOverrideParameters);
			}
		}

		/**
		 * Returns the details of the control as javascript string. This is customized for the JQuery UI autocomplete. If your
		 * widget requires something else, you will need to subclass and override this.
		 * @return string
		 */
		public function toJsObject() {
			$strId = $this->strValue;
			if (is_null($strId)) {
				$strId = $this->strId;
			}

			$a = array('value' => $this->strName, 'id' => $strId);
			if ($this->strLabel) {
				$a['label'] = $this->strLabel;
			}
			if ($this->strItemGroup) {
				$a['category'] = $this->strItemGroup;
			}
			return JavaScriptHelper::toJsObject($a);
		}

		/**
		 * Returns the details of the control as javascript string. This is customized for the JQuery UI autocomplete. If your
		 * widget requires something else, you will need to subclass and override this.
		 * @return string
		 */
		public function jsonSerialize() {
			$strId = $this->strValue;
			if (!$strId) {
				$strId = $this->strId;
			}

			$a = array('value' => $this->strName, 'id' => $strId);
			if ($this->strLabel) {
				$a['label'] = $this->strLabel;
			}
			if ($this->strItemGroup) {
				$a['category'] = $this->strItemGroup;
			}
			return $a;
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
				case "Selected": return $this->blnSelected;
				case "ItemGroup": return $this->strItemGroup;
				case "Label": return $this->strLabel;

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
