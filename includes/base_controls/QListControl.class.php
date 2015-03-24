<?php
	/**
	 * This file contains the QListControl class.
	 * 
	 * @package Controls
	 */

	/**
	 * Abstract object which is extended by anything which involves lists of selectable items.
	 * This object is the foundation for the ListBox, CheckBoxList, RadioButtonList
	 * and TreeNav. Subclasses can be used as objects to specify one-to-many and many-to-many relationships.
	 *
	 * @property-read integer        $ItemCount      the current count of ListItems in the control.
	 * @property integer        $SelectedIndex  is the index number of the control that is selected. "-1" means that nothing is selected. If multiple items are selected, it will return the lowest index number of all ListItems that are currently selected. Set functionality: selects that specific ListItem and will unselect all other currently selected ListItems.
	 * @property string         $SelectedName   simply returns ListControl::SelectedItem->Name, or null if nothing is selected.
	 * @property-read QListItem $SelectedItem   (readonly!) returns the ListItem object, itself, that is selected (or the ListItem with the lowest index number of a ListItems that are currently selected if multiple items are selected). It will return null if nothing is selected.
	 * @property-read array     $SelectedItems  returns an array of selected ListItems (if any).
	 * @property mixed          $SelectedValue  simply returns ListControl::SelectedItem->Value, or null if nothing is selected.
	 * @property array          $SelectedNames  returns an array of all selected names
	 * @property array          $SelectedValues returns an array of all selected values
	 * @property string  		$ItemStyle     {@link QListItemStyle}
	 * @see     QListItemStyle
	 * @package Controls
	 */
	abstract class QListControl extends QControl {

		use QListItemManager;

		/** @var null|QListItemStyle The common style for all elements in the list */
		protected $objItemStyle = null;

		//////////
		// Methods
		//////////

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

			$this->AddListItem ($objListItem);
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
			$this->MarkAsModified();
		}

		/**
		 * Return the id. Used by QListItemManager trait.
		 * @return string
		 */
		public function GetId() {
			return $this->strControlId;
		}

		/**
		 * Unselect all the items and subitems in the list.
		 *
		 * @param bool $blnMarkAsModified
		 */
		public function UnselectAllItems($blnMarkAsModified = true) {
			$this->UpdateAllItemsSelected(false, $blnMarkAsModified);
		}

		/**
		 * Select all the items and subitems in the list.
		 *
		 * @param bool $blnMarkAsModified
		 */
		public function SelectAllItems($blnMarkAsModified = true) {
			$this->UpdateAllItemsSelected(true, $blnMarkAsModified);
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
				$strId = $objItem->GetId();
				$objItem->Selected = in_array($strId, $strIdArray);
			}
			if ($blnMarkAsModified) {
				$this->MarkAsModified();
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
				$this->MarkAsModified();
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
				$this->MarkAsModified();
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
			}
			if ($blnMarkAsModified) {
				$this->MarkAsModified();
			}
		}


		public function GetFirstSelectedItem() {
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				if ($objItem->Selected) {
					return $objItem;
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
			}
			return $aResult;
		}

		/**
		 * Returns the current state of the control to be able to restore it later.
		 */
		public function GetState(){
			return array('SelectedValues'=>$this->SelectedValues);
		}

		/**
		 * Restore the  state of the control.
		 */
		public function PutState($state) {
			if (!empty($state['SelectedValues'])) {
				$this->SelectedValues = $state['SelectedValues'];
			}
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		/**
		 * PHP __get magic method implementation
		 * @param string $strName Property Name
		 *
		 * @return mixed
		 * @throws Exception|QCallerException
		 */
		public function __get($strName) {
			switch ($strName) {
				case "ItemCount":
					return $this->GetItemCount();

				case "SelectedIndex":
					for ($intIndex = 0; $intIndex < $this->GetItemCount(); $intIndex++) {
						if ($this->GetItem($intIndex)->Selected)
							return $intIndex;
					}
					return -1;

				case "SelectedName": // assumes first selected item is the selection
					if ($objItem = $this->GetFirstSelectedItem()) {
						return $objItem->Name;
					}
					return null;

				case "SelectedValue":
				case "Value":
					if ($objItem = $this->GetFirstSelectedItem()) {
						return $objItem->Value;
					}
					return null;

				case "SelectedItem":
					if ($objItem = $this->GetFirstSelectedItem()) {
						return $objItem;
					}
					elseif ($this->GetItemCount()) {
						return $this->GetItem (0);
					}
					return null;
				case "SelectedItems":
					return $this->GetSelectedItems();

				case "SelectedNames":
					$objItems = $this->GetSelectedItems();
					$strNamesArray = array();
					foreach ($objItems as $objItem) {
						$strNamesArray[] = $objItem->Name;
					}
					return $strNamesArray;

				case "SelectedValues":
					$objItems = $this->GetSelectedItems();
					$values = array();
					foreach ($objItems as $objItem) {
						$values[] = $objItem->Value;
					}
					return $values;

				case "ItemStyle":
					return $this->objItemStyle;

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
		 *
		 * @param string $strName  Property Name
		 * @param string $mixValue Propety Value
		 *
		 * @return mixed|void
		 * @throws QIndexOutOfRangeException|Exception|QCallerException|QInvalidCastException
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				case "SelectedIndex":
					try {
						$mixValue = QType::Cast($mixValue, QType::Integer);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					$itemCount = $this->GetItemCount();
					if (($mixValue < -1) ||	// special case to unselect all
						($mixValue > ($itemCount - 1)))
						throw new QIndexOutOfRangeException($mixValue, "SelectedIndex");

					$this->SetSelectedItemsByIndex(array($mixValue));
					return $mixValue;

				case "SelectedName":
					$this->SetSelectedItemsByName(array($mixValue));
					return $mixValue;

				case "SelectedValue":
				case "Value": // most common situation
					$this->SetSelectedItemsByValue(array($mixValue));
					return $mixValue;

				case "SelectedNames":
					try {
						$mixValue = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					$this->SetSelectedItemsByName($mixValue);
					return $mixValue;

				case "SelectedValues":
					try {
						$mixValues = QType::Cast($mixValue, QType::ArrayType);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					$this->SetSelectedItemsByValue($mixValue);
					return $mixValues;

				case "ItemStyle":
					try {
						$this->blnModified = true;
						$this->objItemStyle = QType::Cast($mixValue, "QListItemStyle");
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;


				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					return null;
			}
		}

		/* === Codegen Helpers, used during the Codegen process only. === */

		/**
		 * Returns the variable name for a control of this type during code generation process
		 *
		 * @param string $strPropName Property name for which the control to be generated is being generated
		 *
		 * @return string Variable name
		 */
		public static function Codegen_VarName($strPropName) {
			return 'lst' . $strPropName;
		}

		/**
		 * @param QCodeGen                                       $objCodeGen
		 * @param QColumn|QManyToManyReference|QReverseReference $objColumn
		 *
		 * @return string
		 */
		public static function Codegen_ConnectorVariableDeclaration (QCodeGen $objCodeGen, $objColumn) {
			$strClassName = $objCodeGen->ModelConnectorControlClass($objColumn);
			$strPropName = QCodeGen::ModelConnectorPropertyName ($objColumn);
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
		 * Generate code that will be inserted into the ModelConnector to connect a database object with this control.
		 * This is called during the codegen process. This is designed to handle most of the code needed to
		 * generate QListControl derivatives, but with a few places to insert customization depending on the actual
		 * control being generated.
		 *
		 * @param QDatabaseCodeGen                               $objCodeGen
		 * @param QTable                                         $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string
		 */
		public static function Codegen_ConnectorCreate(QDatabaseCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strControlVarName = $objCodeGen->ModelConnectorVariableName($objColumn);
			$strLabelName = addslashes(QCodeGen::ModelConnectorControlName($objColumn));
			$strPropName = QCodeGen::ModelConnectorPropertyName ($objColumn);

			// Read the control type in case we are generating code for a similar class
			$strControlType = $objCodeGen->ModelConnectorControlClass($objColumn);

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

TMPL;

			}
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

			$strRet .= static::Codegen_ConnectorCreateOptions ($objCodeGen, $objTable, $objColumn, $strControlVarName);
			$strRet .= static::Codegen_ConnectorRefresh ($objCodeGen, $objTable, $objColumn, true);

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
				$strRefVarName = null;
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
				//$strPK = $objTable->PrimaryKeyColumnArray[0]->PropertyName;

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
					//$strRefPropName = $objColumn->Reference->PropertyName;
					$strRefTable = $objColumn->Reference->Table;
				}
				elseif ($objColumn instanceof QReverseReference) {
					$strRefVarType = $objColumn->VariableType;
					$strRefVarName = $objColumn->VariableName;
					//$strRefPropName = $objColumn->PropertyName;
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
		 * Generate code to reload data from the ModelConnector into this control, or load it for the first time
		 *
		 * @param QDatabaseCodeGen                               $objCodeGen
		 * @param QTable                                         $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param boolean                                        $blnInit Generate initialization code instead of reload
		 *
		 * @return string
		 */
		public static function Codegen_ConnectorRefresh(QDatabaseCodeGen $objCodeGen, QTable $objTable, $objColumn, $blnInit = false) {
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
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
		 *
		 * @param QCodeGen                                       $objCodeGen
		 * @param QTable                                         $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string
		 */
		public static function Codegen_ConnectorUpdate(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
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
		 *
		 * @param QCodeGen                                       $objCodeGen
		 * @param QTable                                         $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 *
		 * @return string
		 */
		public static function Codegen_ConnectorUpdateMethod(QCodeGen $objCodeGen, QTable $objTable, $objColumn) {
			$strObjectName = $objCodeGen->ModelVariableName($objTable->Name);
			$strPropName = QCodeGen::ModelConnectorPropertyName($objColumn);
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

		/**
		 * Update the select field for all items
		 *
		 * @param bool $blnSelected
		 * @param bool $blnMarkAsModified
		 */
		private function UpdateAllItemsSelected($blnSelected, $blnMarkAsModified = true) {
			$intCount = $this->GetItemCount();
			for ($intIndex = 0; $intIndex < $intCount; $intIndex++) {
				$objItem = $this->GetItem($intIndex);
				$objItem->Selected = $blnSelected;
			}
			if ($blnMarkAsModified) {
				$this->MarkAsModified();
			}
		}
	}
?>
