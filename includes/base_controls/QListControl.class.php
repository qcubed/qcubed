<?php
	/**
	 * This file contains the QListControl class.
	 * 
	 * @package Controls
	 */

	/**
	 * Abstract object which is extended by anything which involves lists of selectable items.
	 * 
	 * This object is the foundation for the ListBox, CheckBoxList, RadioButtonList 
	 * and TreeNav. 
	 * 
	 * @property integer $ItemCount the current count of ListItems in the control.
	 * @property integer $SelectedIndex is the index number of the control that is selected. "-1" means that nothing is selected. If multiple items are selected, it will return the lowest index number of all ListItems that are currently selected. Set functionality: selects that specific ListItem and will unselect all other currently selected ListItems.
	 * @property string $SelectedName simply returns ListControl::SelectedItem->Name, or null if nothing is selected.
	 * @property-read QListItem $SelectedItem (readonly!) returns the ListItem object, itself, that is selected (or the ListItem with the lowest index number of a ListItems that are currently selected if multiple items are selected). It will return null if nothing is selected.
	 * @property-read array $SelectedItems returns an array of selected ListItems (if any).
	 * @property mixed $SelectedValue simply returns ListControl::SelectedItem->Value, or null if nothing is selected.
	 * @property array $SelectedNames returns an array of all selected names
	 * @property array $SelectedValues returns an array of all selected values
	 * @package Controls
	 */
	abstract class QListControl extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		/**
		 * @access protected
		 * @var object
		 */
		protected $objItemsArray = array();

		//////////
		// Methods
		//////////
		

		/**
		 * Add one ListItem to the ListControl
		 * 
		 * Allows you to add a ListItem to the ListItem array within the ListControl. Items are appended to 
		 * the array. This method exhibits polymorphism: you can either pass in a ListItem object or you 
		 * can pass in three strings.
		 * 
		 * <code>
		 *  // Method 1: adding a created ListItem
		 *  $objListItem = new QListItem($name, $value, $blnIsSelected);
		 *  $lstList->AddItem($objListItem);
		 *  
		 *  // Method 2: adding a list item using direct strings
		 *  $lstList->AddItem($name, $value, $blnIsSelected);
		 *  
		 * </code>
		 * 
		 * @see QListItem::__construct()
		 * @param mixed $mixListItemOrName QListItem or Name of the ListItem
		 * @param string $strValue Value of the ListItem
		 * @param boolean $blnSelected set the html selected attribute for the ListItem
		 * @param string $strItemGroup allows you to apply grouping (<optgroup> tag)
		 * @param string $strOverrideParameters OverrideParameters for ListItemStyle
		 */
		public function AddItem($mixListItemOrName, $strValue = null, $blnSelected = null, $strItemGroup = null, $strOverrideParameters = null) {
			$this->blnModified = true;
			if (gettype($mixListItemOrName) == QType::Object)
				$objListItem = QType::Cast($mixListItemOrName, "QListItem");
			elseif ($strOverrideParameters)			
				// The OverrideParameters can only be included if they are not null, because OverrideAttributes in QBaseClass can't except a NULL Value
				$objListItem = new QListItem($mixListItemOrName, $strValue, $blnSelected, $strItemGroup, $strOverrideParameters);
			else 
				$objListItem = new QListItem($mixListItemOrName, $strValue, $blnSelected, $strItemGroup);

			array_push($this->objItemsArray, $objListItem);
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
			$this->blnModified = true;
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) || 
				($intIndex > count($this->objItemsArray)))
				throw new QIndexOutOfRangeException($intIndex, "AddItemAt()");
			for ($intCount = count($this->objItemsArray); $intCount > $intIndex; $intCount--) {
				$this->objItemsArray[$intCount] = $this->objItemsArray[$intCount - 1];
			}
			
			$this->objItemsArray[$intIndex] = $objListItem;
		}

		/**
		 * Adds an array of items, or an array of key=>value pairs. Convenient for adding a list from a type table.
		 * When passing key=>val pairs, mixSelectedValues can be an array, or just a single value to compare against to indicate what is selected.
		 *
		 * @param array  $mixItemArray          Array of QListItems or key=>val pairs.
		 * @param mixed  $mixSelectedValues     Array of selected values, or value of one selection
		 * @param string $strItemGroup          allows you to apply grouping (<optgroup> tag)
		 * @param string $strOverrideParameters OverrideParameters for ListItemStyle
		 *
		 * @throws Exception|QInvalidCastException
		 */
		public function AddItems(array $mixItemArray, $mixSelectedValues = null, $strItemGroup = null, $strOverrideParameters = null) {
			$this->blnModified = true;
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
				$this->AddItem($item, $val, $blnSelected, $strItemGroup, $strOverrideParameters);
			};
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
				($intIndex >= count($this->objItemsArray)))
				throw new QIndexOutOfRangeException($intIndex, "GetItem()");

			return $this->objItemsArray[$intIndex];
		}

		/**
		 * This will return an array of ALL the QListItems associated with this QListControl.
		 * Please note that while each individual item can be altered, altering the array, itself,
		 * will not affect any change on the QListControl.  So existing QListItems may be modified,
		 * but to add / remove items from the QListControl, you should use AddItem() and RemoveItem().
		 * @return QListItem[]
		 */
		public function GetAllItems() {
			return $this->objItemsArray;
		}

		/**
		 * Removes all the items in objItemsArray
		 */
		public function RemoveAllItems() {
			$this->blnModified = true;
			$this->objItemsArray = array();
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
			$this->blnModified = true;
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			if (($intIndex < 0) ||
				($intIndex > (count($this->objItemsArray) - 1)))
				throw new QIndexOutOfRangeException($intIndex, "RemoveItem()");
			for ($intCount = $intIndex; $intCount < count($this->objItemsArray) - 1; $intCount++) {
				$this->objItemsArray[$intCount] = $this->objItemsArray[$intCount + 1];
			}
			
			$this->objItemsArray[$intCount] = null;
			unset($this->objItemsArray[$intCount]);
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
			$this->blnModified = true;
			try {
				$intIndex = QType::Cast($intIndex, QType::Integer);
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$this->objItemsArray[$intIndex] = $objListItem;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Property Name
		 *
		 * @return array|bool|int|mixed|null|QControl|QForm|string
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "ItemCount":
					if ($this->objItemsArray)
						return count($this->objItemsArray);
					else
						return 0;
				case "SelectedIndex":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $intIndex;
					}
					return -1;
				case "SelectedName":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex]->Name;
					}
					return null;
				case "SelectedValue":
				case "Value":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex]->Value;
					}
					return null;
				case "SelectedItem":
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							return $this->objItemsArray[$intIndex];
					}
					return null;
				case "SelectedItems":
					$objToReturn = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($objToReturn, $this->objItemsArray[$intIndex]);
//							$objToReturn[count($objToReturn)] = $this->objItemsArray[$intIndex];
					}
					return $objToReturn;
				case "SelectedNames":
					$strNamesArray = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($strNamesArray, $this->objItemsArray[$intIndex]->Name);
//							$strNamesArray[count($strNamesArray)] = $this->objItemsArray[$intIndex]->Name;
					}
					return $strNamesArray;
				case "SelectedValues":
					$objToReturn = array();
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++) {
						if ($this->objItemsArray[$intIndex]->Selected)
							array_push($objToReturn, $this->objItemsArray[$intIndex]->Value);
//							$objToReturn[count($objToReturn)] = $this->objItemsArray[$intIndex]->Value;
					}
					return $objToReturn;
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
		 * @param string $strName Property Name
		 * @param string $mixValue Propety Value
		 *
		 * @return mixed
		 * @throws QIndexOutOfRangeException
		 * @throws Exception|QCallerException
		 * @throws Exception|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			$this->blnModified = true;
			switch ($strName) {
				case "SelectedIndex":
					try {
						$mixValue = QType::Cast($mixValue, QType::Integer);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					// Special Case
					if ($mixValue == -1)
						$mixValue = null;

					if (($mixValue < 0) ||
						($mixValue > (count($this->objItemsArray) - 1)))
						throw new QIndexOutOfRangeException($mixValue, "SelectedIndex");
					for ($intIndex = 0; $intIndex < count($this->objItemsArray); $intIndex++)
						if ($mixValue === $intIndex)
							$this->objItemsArray[$intIndex]->Selected = true;
						else
							$this->objItemsArray[$intIndex]->Selected = false;
					return $mixValue;
					break;

				case "SelectedName":
					foreach ($this->objItemsArray as $objItem)
						if ($objItem->Name == $mixValue)
							$objItem->Selected = true;
						else
							$objItem->Selected = false;
					return $mixValue;
					break;

				case "SelectedValue":
				case "Value": // most common situation
					foreach ($this->objItemsArray as $objItem)
						if (!$mixValue) {
							if ($mixValue === null || $mixValue === '') {
								if ($objItem->Value === null || $objItem->Value === '') {
									$objItem->Selected = true;
								} else {
									$objItem->Selected = false;
								}
							} else {
								if ($objItem->Value === null || $objItem->Value === '') {
									$objItem->Selected = false;
								} else {
									$objItem->Selected = true;
								}
							}
						} elseif ($objItem->Value == $mixValue) {
							$objItem->Selected = true;
						} else {
							$objItem->Selected = false;
						}
					return $mixValue;
					break;


				case "SelectedNames":
					try {
						$mixValue = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					foreach ($this->objItemsArray as $objItem) {
						$objItem->Selected = false;
						foreach ($mixValue as $mixName) {
							if ($objItem->Name == $mixName) {
								$objItem->Selected = true;
								break;
							}
						}
					}
					return $mixValue;
					break;

				case "SelectedValues":
					try {
						$mixValues = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					foreach ($this->objItemsArray as $objItem) {
						$objItem->Selected = false;
						$mixCurVal = $objItem->Value;
						foreach ($mixValues as $mixValue) {
							if (!$mixValue) {
								if ($mixValue === null || $mixValue === '') {
									if ($mixCurVal === null || $mixCurVal === '') {
										$objItem->Selected = true;
										break;
									}
								} else {
									if (!($mixCurVal === null || $mixCurVal === '')) {
										$objItem->Selected = true;
										break;
									}
								}
							}
							elseif ($mixCurVal == $mixValue) {
								$objItem->Selected = true;
								break;
							}
						}
					}
					return $mixValues;
					break;

				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**** Codegen Helpers, used during the Codegen process only. ****/

		public static function Codegen_VarName($strPropName) {
			return 'lst' . $strPropName;
		}

		/**
		 * @param QCodeGen $objCodeGen
		 * @param QColumn|QManyToManyReference|QReverseReference $objColumn
		 * @return string
		 */
		public static function Codegen_MetaVariableDeclaration (QCodeGen $objCodeGen, $objColumn) {
			$strClassName = $objCodeGen->MetaControlControlClass($objColumn);
			$strPropName = QCodeGen::MetaControlPropertyName ($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);

			$strRet = <<<TMPL
		/**
		 * @var {$strClassName} {$strControlVarName}
		 * @access protected
		 */
		protected \${$strControlVarName};


TMPL;

			if (($objColumn instanceof QColumn && !$objColumn->Reference->IsType) ||
				($objColumn instanceof QManyToManyReference && !$objColumn->IsTypeAssociation) ||
				($objColumn instanceof QReverseReference)) {
				$strRet .= <<<TMPL
		/**
		* @var obj{$strPropName}Condition
		* @access protected
		*/
		protected \$obj{$strPropName}Condition;

		/**
		* @var obj{$strPropName}Clauses
		* @access protected
		*/
		protected \$obj{$strPropName}Clauses;

TMPL;
			}
			return $strRet;
		}


		/**
		 * Generate code that will be inserted into the MetaControl to connect a database object with this control.
		 * This is called during the codegen process. This is designed to handle most of the code needed to
		 * generate QListControl derivatives, but with a few places to insert customization depending on the actual
		 * control being generated.
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public static function Codegen_MetaCreate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strControlVarName = $objCodeGen->MetaControlVariableName($objColumn);
			$strLabelName = addslashes(QCodeGen::MetaControlControlName($objColumn));
			$strPropName = QCodeGen::MetaControlPropertyName ($objColumn);

			// Read the control type in case we are generating code for a similar class
			$strControlType = $objCodeGen->MetaControlControlClass($objColumn);

			// Create a control designed just for selecting from a type table
			if (($objColumn instanceof QColumn && $objColumn->Reference->IsType) ||
				($objColumn instanceof QManyToManyReference && $objColumn->IsTypeAssociation)) {
				$strRet=<<<TMPL
		/**
		 * Create and setup {$strControlType} {$strControlVarName}
		 * @param string \$strControlId optional ControlId to use
		 * @return {$strControlType}
		 */

		public function {$strControlVarName}_Create(\$strControlId = null) {

TMPL;
				// Allow the codegen process to either create custom ids based on the field/table names, or to be
				// Specified by the developer.
				$strControlIdOverride = $objCodeGen->GenerateControlId($objTable, $objColumn);

				if ($strControlIdOverride) {
					$strRet .= <<<TMPL
			if (!\$strControlId) {
				\$strControlId = '$strControlIdOverride';
			}

TMPL;
				}
				$strRet .= <<<TMPL
			\$this->{$strControlVarName} = new {$strControlType}(\$this->objParentObject, \$strControlId);
			\$this->{$strControlVarName}->Name = QApplication::Translate('{$strLabelName}');

TMPL;
			} else {	// Create a control that presents a list taken from the database

				$strRet = <<<TMPL
		/**
		 * Create and setup {$strControlType} {$strControlVarName}
		 * @param string \$strControlId optional ControlId to use
		 * @param QQCondition \$objConditions override the default condition of QQ::All() to the query, itself
		 * @param QQClause[] \$objClauses additional QQClause object or array of QQClause objects for the query
		 * @return QListBox
		 */

		public function {$strControlVarName}_Create(\$strControlId = null, QQCondition \$objCondition = null, \$objClauses = null) {
			\$this->obj{$strPropName}Condition = \$objCondition;
			\$this->obj{$strPropName}Clauses = \$objClauses;
			\$this->{$strControlVarName} = new {$strControlType}(\$this->objParentObject, \$strControlId);
			\$this->{$strControlVarName}->Name = QApplication::Translate('{$strLabelName}');

TMPL;
			}

			if ($objColumn instanceof QColumn && $objColumn->NotNull) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->Required = true;

TMPL;
			}

			if ($strMethod = QCodeGen::$PreferredRenderMethod) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->PreferredRenderMethod = '$strMethod';

TMPL;
			}

			$strRet .= static::Codegen_MetaCreateOptions ($objCodeGen, $objTable, $objColumn, $strControlVarName);
			$strRet .= static::Codegen_MetaRefresh ($objCodeGen, $objTable, $objColumn, true);

			$strRet .= <<<TMPL
			return \$this->{$strControlVarName};
		}

TMPL;

			if ($objColumn instanceof QColumn && $objColumn->Reference->IsType ||
				$objColumn instanceof QManyToManyReference && $objColumn->IsTypeAssociation) {
				if ($objColumn instanceof QColumn) {
					$strVarType = $objColumn->Reference->VariableType;
				} else {
					$strVarType = $objColumn->ObjectDescription;
				}
				$strRet .= <<<TMPL

		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		public function {$strControlVarName}_GetItems() {
			return {$strVarType}::\$NameArray;
		}


TMPL;
			}
			elseif ($objColumn instanceof QManyToManyReference) {
				$strRefVarName = $objColumn->VariableName;
				$strVarType = $objColumn->VariableType;
				$strRefTable = $objColumn->AssociatedTable;
				$strRefPropName = $objColumn->OppositeObjectDescription;
				$strRefPK = $objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName;
				$strPK = $objTable->PrimaryKeyColumnArray[0]->PropertyName;

				$strRet .= <<<TMPL
		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		public function {$strControlVarName}_GetItems() {
			\$a = array();
			\$objCondition = \$this->obj{$strPropName}Condition;
			if (is_null(\$objCondition)) \$objCondition = QQ::All();
			\$objClauses = \$this->obj{$strPropName}Clauses;

			\$objClauses[] =
				QQ::Expand(QQN::{$strVarType}()->{$strRefPropName}->{$objTable->ClassName}, QQ::Equal(QQN::{$strVarType}()->{$strRefPropName}->{$objColumn->PropertyName}, \$this->{$strObjectName}->{$strRefPK}));

			\$obj{$strVarType}Cursor = {$strVarType}::QueryCursor(\$objCondition, \$objClauses);

			// Iterate through the Cursor
			while (\${$strRefVarName} = {$strVarType}::InstantiateCursor(\$obj{$strVarType}Cursor)) {
				\$objListItem = new QListItem(\${$strRefVarName}->__toString(), \${$strRefVarName}->{$strRefPK}, \${$strRefVarName}->_{$strRefPropName} !== null);
				\$a[] = \$objListItem;
			}
			return \$a;
		}

TMPL;
			}
			else {
				if ($objColumn instanceof QColumn) {
					$strRefVarType = $objColumn->Reference->VariableType;
					$strRefVarName = $objColumn->Reference->VariableName;
					$strRefPropName = $objColumn->Reference->PropertyName;
					$strRefTable = $objColumn->Reference->Table;
				}
				elseif ($objColumn instanceof QReverseReference) {
					$strRefVarType = $objColumn->VariableType;
					$strRefVarName = $objColumn->VariableName;
					$strRefPropName = $objColumn->PropertyName;
					$strRefTable = $objColumn->Table;
				}
				$strRet .= <<<TMPL

		/**
		 *	Create item list for use by {$strControlVarName}
		 */
		 public function {$strControlVarName}_GetItems() {
			\$a = array();
			\$objCondition = \$this->obj{$strPropName}Condition;
			if (is_null(\$objCondition)) \$objCondition = QQ::All();
			\${$strRefVarName}Cursor = {$strRefVarType}::QueryCursor(\$objCondition, \$this->obj{$strPropName}Clauses);

			// Iterate through the Cursor
			while (\${$strRefVarName} = {$strRefVarType}::InstantiateCursor(\${$strRefVarName}Cursor)) {
				\$objListItem = new QListItem(\${$strRefVarName}->__toString(), \${$strRefVarName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName});
				if ((\$this->{$strObjectName}->{$strPropName}) && (\$this->{$strObjectName}->{$strPropName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName} == \${$strRefVarName}->{$objCodeGen->GetTable($strRefTable)->PrimaryKeyColumnArray[0]->PropertyName}))
					\$objListItem->Selected = true;
				\$a[] = \$objListItem;
			}
			return \$a;
		 }


TMPL;
			}

			return $strRet;
		}

		/**
		 * Generate code to reload data from the MetaControl into this control, or load it for the first time
		 *
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param boolean $blnInit	Generate initialization code instead of reload
		 */
		public static function Codegen_MetaRefresh(QCodeGen $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strPropName = QCodeGen::MetaControlPropertyName($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);

			$strRet = '';
			$strTabs = "\t\t\t";

			if (!$blnInit) {
				$strTabs = "\t\t\t\t";
				$strRet .= $strTabs . "\$this->{$strControlVarName}->RemoveAllItems();\n";
			}
			$strRet .= $strTabs . "if (!\$this->blnEditMode && \$this->{$strControlVarName}->Required) \$this->{$strControlVarName}->AddItem(QApplication::Translate('- Select One -'), null);\n";

			$options = $objColumn->Options;
			if (!$options || !isset ($options['NoAutoLoad'])) {
				$strRet .= $strTabs . "\$this->{$strControlVarName}->AddItems(\$this->{$strControlVarName}_GetItems());\n";
			}

			if ($objColumn instanceof QColumn) {
				$strRet .= $strTabs . "\$this->{$strControlVarName}->SelectedValue = \$this->{$strObjectName}->{$objColumn->PropertyName};\n";
			}
			elseif ($objColumn instanceof QReverseReference && $objColumn->Unique) {
				$strRet .= $strTabs . "if (\$this->{$strObjectName}->{$objColumn->ObjectPropertyName})\n";
				$strRet .= $strTabs . "\t\$this->{$strControlVarName}->SelectedValue = \$this->{$strObjectName}->{$objColumn->ObjectPropertyName}->{$objCodeGen->GetTable($objColumn->Table)->PrimaryKeyColumnArray[0]->PropertyName};\n";
			}
			elseif ($objColumn instanceof QManyToManyReference) {
				if ($objColumn->IsTypeAssociation) {
					$strRet .= $strTabs . "\$this->{$strControlVarName}->SelectedValues = array_keys(\$this->{$strObjectName}->Get{$objColumn->ObjectDescription}Array());\n";
				} else {
					//$strRet .= $strTabs . "\$this->{$strControlVarName}->SelectedValues = \$this->{$strObjectName}->Get{$objColumn->ObjectDescription}Keys();\n";
				}
			}
			if (!$blnInit) {
				$strRet = "\t\t\tif (\$this->{$strControlVarName}) { \n" . $strRet . "\t\t\t}\n";
			}
			return $strRet;
		}

		/**
		 * Generate the code to move data from the control to the database.
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public static function Codegen_MetaUpdate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::MetaControlPropertyName($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);
			$strRet = '';
			if ($objColumn instanceof QColumn) {
				$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->PropertyName} = \$this->{$strControlVarName}->SelectedValue;

TMPL;
			}
			elseif ($objColumn instanceof QReverseReference) {
				$strRet = <<<TMPL
				if (\$this->{$strControlVarName}) \$this->{$strObjectName}->{$objColumn->ObjectPropertyName} = {$objColumn->VariableType}::Load(\$this->{$strControlVarName}->SelectedValue);

TMPL;
			}
			return $strRet;
		}

		/**
		 * Generate helper functions for the update process.
		 * @param QCodeGen $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @return string
		 */
		public static function Codegen_MetaUpdateMethod(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::MetaControlPropertyName($objColumn);
			$strControlVarName = static::Codegen_VarName($strPropName);
			$strRet = <<<TMPL
		protected function {$strControlVarName}_Update() {
			if (\$this->{$strControlVarName}) {

TMPL;

			if ($objColumn instanceof QManyToManyReference) {
				if ($objColumn->IsTypeAssociation) {
					$strRet .= <<<TMPL
				\$this->{$strObjectName}->UnassociateAll{$objColumn->ObjectDescriptionPlural}();
				\$this->{$strObjectName}->Associate{$objColumn->ObjectDescription}(\$this->{$strControlVarName}->SelectedValues);

TMPL;
				} else {
					$strRet .= <<<TMPL
				\$this->{$strObjectName}->UnassociateAll{$objColumn->ObjectDescriptionPlural}();
				foreach(\$this->{$strControlVarName}->SelectedValues as \$id) {
					\$this->{$strObjectName}->Associate{$objColumn->ObjectDescription}ByKey(\$id);
				}

TMPL;
				}
			}

			$strRet .= <<<TMPL
			}
		}

TMPL;

			return $strRet;
		}
	}
?>