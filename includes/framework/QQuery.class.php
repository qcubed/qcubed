<?php

	/*
	*	QQuery.class.php
	*
	*	Classes to simplify the creation of SQL statements.
	*/


	/**
	 * The abstract QQNode base class. This represents an "object" in a SQL join tree. There are a number of different subclasses of
	 * the QQNode, depending on the kind of object represented. The top of the join tree is generally a table node, and
	 * the bottom is generally a column node, but that depends on the context in which the node is being used.
	 *
	 * The properties begin with underscores to prevent name conflicts with codegenerated subclasses.
	 *
	 * @property-read QQNode $_ParentNode		// Parent object in tree.
	 * @property-read string $_Name				// Default SQL name in query, or default alias
	 * @property-read string $_Alias				// Actual alias. Usually the name, unless changed by QQ::Alias() call
	 * @property-read string $_PropertyName		// The name as used in PHP
	 * @property-read string $_Type				// The type of object. A SQL type if referring to a column.
	 * @property-read string $_RootTableName		// The name of the table at the top of the tree. Rednundant, since it could be found be following the chain.
	 * @property-read string $_TableName			// The name of the table associated with this node, if its not a column node.
	 * @property-read string $_PrimaryKey
	 * @property-read string $_ClassName
	 * @property-read QQNode $_PrimaryKeyNode
	 * @property bool $ExpandAsArray True if this node should be array expanded.
	 * @property-read bool $IsType Is a type table node. For association type arrays.
	 */
	abstract class QQNode extends QBaseClass {
		/** @var null|QQNode|bool  */
		protected $objParentNode;
		/** @var  string Type node. SQL type or table type*/
		protected $strType;
		/** @var  string SQL Name of related object in the database */
		protected $strName;
		/** @var  string Alias, if one was assigned using QQ::Alias(). Otherwise, same as name. */
		protected $strAlias;
		/** @var  string resolved alias that includes parent join tables. */
		protected $strFullAlias;
		/** @var  string PHP property name of the related PHP object */
		protected $strPropertyName;
		/** @var  string copy of the root table name at the top of the node tree. */
		protected $strRootTableName;
		/** @var  string name of SQL table associated with this node. Generally set by subclasses. */
		protected $strTableName;

		/** @var  string SQL primary key, for nodes that have primary keys */
		protected $strPrimaryKey;
		/** @var  string PHP class name */
		protected $strClassName;

		// used by expansion nodes
		/** @var  bool True if this is an expand as array node point */
		protected $blnExpandAsArray;
		/** @var  QQNode[] the array of child nodes if this is an expand as array point */
		protected $objChildNodeArray;
		/** @var  bool True if this is a Type node */
		protected $blnIsType;

		abstract public function Join(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null, QQSelect $objSelect = null);

		/**
		 * Return the variable type. Should be a QDatabaseFieldType enum.
		 * @return string
		 */
		public function GetType() {
			return $this->strType;
		}

		/**
		 * Change the alias of the node, primarily for joining the same table more than once.
		 *
		 * @param $strAlias
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function SetAlias($strAlias) {
			if ($this->strFullAlias) {
				throw new Exception ("You cannot set an alias on a node after you have used it in a query. See the examples doc. You must set the alias while creating the node.");
			}
			try {
				// Changing the alias of the node. Must change pointers to the node too.
				$strNewAlias = QType::Cast($strAlias, QType::String);
				if ($this->objParentNode) {
					unset($this->objParentNode->objChildNodeArray[$this->strAlias]);
					$this->objParentNode->objChildNodeArray[$strNewAlias] = $this;
				}
				$this->strAlias = $strNewAlias;
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Aid to generating full aliases. Recursively gets and sets the parent alias, eventually creating, caching and returning
		 * an alias for itself.
		 * @return string
		 */
		public function FullAlias() {
			if ($this->strFullAlias) {
				return $this->strFullAlias;
			} else {
				assert ('!empty($this->strAlias)');	// Alias should always be set by default
				if ($this->objParentNode) {
					return $this->objParentNode->FullAlias() . '__' . $this->strAlias;
				}
				else {
					return $this->strAlias;
				}
			}
		}

		/**
		 * Returns the fields in this node. Assumes its a table node.
		 * @return string[]
		 */
		public function Fields() {return [];}

		/**
		 * Returns the primary key fields in this node. Assumes its a table node.
		 * @return string[]
		 */
		public function PrimaryKeyFields() {return [];}

		/**
		 * Merges a node tree into this node, building the child nodes. The node being received
		 * is assumed to be specially built node such that only one child node exists, if any,
		 * and the last node in the chain is designated as array expansion. The goal of all of this
		 * is to set up a node chain where intermediate nodes can be designated as being array
		 * expansion nodes, as well as the leaf nodes.
		 *
		 * @param QQNode $objNewNode
		 * @throws QCallerException
		 */
		public function _MergeExpansionNode (QQNode $objNewNode) {
			if (!$objNewNode || empty($objNewNode->objChildNodeArray)) {
				return;
			}
			if ($objNewNode->strName != $this->strName) {
				throw new QCallerException('Expansion node tables must match.');
			}

			if (!$this->objChildNodeArray) {
				$this->objChildNodeArray = $objNewNode->objChildNodeArray;
			} else {
				$objChildNode = reset($objNewNode->objChildNodeArray);
				if (isset ($this->objChildNodeArray[$objChildNode->strAlias])) {
					if ($objChildNode->blnExpandAsArray) {
						$this->objChildNodeArray[$objChildNode->strAlias]->blnExpandAsArray = true;
						// assume this is a leaf node, so don't follow any more.
					}
					else {
						$this->objChildNodeArray[$objChildNode->strAlias]->_MergeExpansionNode ($objChildNode);
					}
				} else {
					$this->objChildNodeArray[$objChildNode->strAlias] = $objChildNode;
				}
			}
		}

		/**
		 * Puts the "Select" clause fields for this node into builder.
		 *
		 * @param QQueryBuilder $objBuilder
		 * @param null|string $strPrefix
		 * @param null|QQSelect $objSelect
		 */
		public function PutSelectFields($objBuilder, $strPrefix = null, $objSelect = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = $this->strTableName;
				$strAliasPrefix = '';
			}

			if ($objSelect) {
				if (!$objSelect->SkipPrimaryKey()) {
					$strFields = $this->PrimaryKeyFields();
					foreach ($strFields as $strField) {
						$objBuilder->AddSelectItem($strTableName, $strField, $strAliasPrefix . $strField);
					}
				}
				$objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
			} else {
				$strFields = $this->Fields();
				foreach ($strFields as $strField) {
					$objBuilder->AddSelectItem($strTableName, $strField, $strAliasPrefix . $strField);
				}
			}
		}

		/**
		 * @return QQNode|null
		 */
		public function FirstChild() {
			$a = $this->objChildNodeArray;
			if ($a) {
				return reset ($a);
			} else {
				return null;
			}
		}

		/**
		 * Returns the extended table associated with the node.
		 * @return string
		 */
		public function GetTable() {
			return $this->FullAlias();
		}

		/**
		 * @param mixed $mixValue
		 * @param QQueryBuilder $objBuilder
		 * @param boolean $blnEqualityType can be null (for no equality), true (to add a standard "equal to") or false (to add a standard "not equal to")
		 * @return string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public static function GetValue($mixValue, QQueryBuilder $objBuilder, $blnEqualityType = null) {
			if ($mixValue instanceof QQNamedValue) {
				/** @var QQNamedValue $mixValue */
				return $mixValue->Parameter($blnEqualityType);
			}

			if ($mixValue instanceof QQNode) {
				/** @var QQNode $mixValue */
				if ($n = $mixValue->_PrimaryKeyNode) {
					$mixValue = $n;	// Convert table node to column node
				}
				/** @var QQColumnNode $mixValue */
				if (is_null($blnEqualityType))
					$strToReturn = '';
				else if ($blnEqualityType)
					$strToReturn = '= ';
				else
					$strToReturn = '!= ';

				try {
					return $strToReturn . $mixValue->GetColumnAlias($objBuilder);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			} else {
				if (is_null($blnEqualityType)) {
					$blnIncludeEquality = false;
					$blnReverseEquality = false;
				} else {
					$blnIncludeEquality = true;
					if ($blnEqualityType)
						$blnReverseEquality = false;
					else
						$blnReverseEquality = true;
				}

				return $objBuilder->Database->SqlVariable($mixValue, $blnIncludeEquality, $blnReverseEquality);
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case '_ParentNode':
					return $this->objParentNode;
				case '_Name':
					return $this->strName;
				case '_Alias':
					return $this->strAlias;
				case '_PropertyName':
					return $this->strPropertyName;
				case '_Type':
					return $this->strType;
				case '_RootTableName':
					return $this->strRootTableName;
				case '_TableName':
					return $this->strTableName;
				case '_PrimaryKey':
					return $this->strPrimaryKey;
				case '_ClassName':
					return $this->strClassName;
				case '_PrimaryKeyNode':
					return null;
					
				case 'ExpandAsArray':
					return $this->blnExpandAsArray;
				case 'IsType':
					return $this->blnIsType;
					
				case 'ChildNodeArray':
					return $this->objChildNodeArray;
					
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'ExpandAsArray':
					try {
						return ($this->blnExpandAsArray = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
										
				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		//////////////////
		// Helpers for Orm-generated DataGrids

		// Deprecated, soon to be removed.
		//////////////////
		/**
		 * @return string
		 * @throws Exception
		 */
		public function GetDataGridHtml() {
			// Array-ify Node Hierarchy
			$objNodeArray = array();

			$objNodeArray[] = $this;
			while ($objNodeArray[count($objNodeArray) - 1]->objParentNode)
				$objNodeArray[] = $objNodeArray[count($objNodeArray) - 1]->objParentNode;

			$objNodeArray = array_reverse($objNodeArray, false);

			// Go through the objNodeArray to build out the DataGridHtml

			// Error Behavior
			if (count($objNodeArray) < 2)
				throw new Exception('Invalid QQNode to GetDataGridHtml on');

			// Simple Two-Step Node
			else if (count($objNodeArray) == 2) {
				$strToReturn = '$_ITEM->' . $objNodeArray[1]->strPropertyName;
				if (class_exists($this->strClassName)) {
					$strToReturn = sprintf('(%s) ? %s->__toString() : null;', $strToReturn, $strToReturn);
				}
			}
			// Complex N-Step Node
			else {
				$strNodeLabelArray[0] = '$_ITEM->' . $objNodeArray[1]->strPropertyName;
				for ($intIndex = 2; $intIndex < count($objNodeArray); $intIndex++) {
					$strNodeLabelArray[$intIndex - 1] = $strNodeLabelArray[$intIndex - 2] . '->' . $objNodeArray[$intIndex]->strPropertyName;
				}

				$slice_count = count ($objNodeArray) - 2;
				$blnIsClass = class_exists($this->strClassName);

				if ($blnIsClass) {
					$slice_count++;
				}

				$aTest = array_slice ($strNodeLabelArray, 0, $slice_count);
				$strTest = implode (' && ', $aTest);
				$strLastNode = $strNodeLabelArray[count($strNodeLabelArray) - 1];

				if ($blnIsClass) {
					return sprintf ('(%s) ? %s->__toString() : null', $strTest, $strLastNode);
				} else {
					$strToReturn = sprintf ('(%s) ? %s : null', $strTest, $strLastNode);
				}
			}

			if($this->strType == QDatabaseFieldType::Time)
				return sprintf('(%s) ? %s->qFormat(QDateTime::$DefaultTimeFormat) : null', $strToReturn, $strToReturn);

			if ($this->strType == QDatabaseFieldType::Bit)
				return sprintf('(null === %s)? "" : ((%s)? "%s" : "%s")', $strToReturn, $strToReturn, QApplication::Translate('True'), QApplication::Translate('False'));


			return $strToReturn;
		}

		public function GetDataGridOrderByNode() {
			if ($this instanceof QQReverseReferenceNode)
				return $this->_PrimaryKeyNode;
			else
				return $this;
		}

		public function SetFilteredDataGridColumnFilter(QDataGridColumn $col)
		{
			if ($this->_PrimaryKeyNode) {
				$objNode = $this->_PrimaryKeyNode;
			} else {
				$objNode = $this;
			}

			switch($objNode->strType)
			{
				case QDatabaseFieldType::Bit:
					//List of true / false / any
					$col->FilterType = QFilterType::ListFilter;
					$col->FilterAddListItem("True", QQ::Equal($objNode, true));
					$col->FilterAddListItem("False", QQ::Equal($objNode, false));
					$col->FilterAddListItem("Set", QQ::IsNotNull($objNode));
					$col->FilterAddListItem("Unset", QQ::IsNull($objNode));
					break;
				case QDatabaseFieldType::Blob:
				case QDatabaseFieldType::Char:
				case QDatabaseFieldType::Time:
				case QDatabaseFieldType::VarChar:
				case QDatabaseFieldType::Date:
				case QDatabaseFieldType::DateTime:
					//LIKE
					$col->FilterType = QFilterType::TextFilter;
					$col->FilterPrefix = '%';
					$col->FilterPostfix = '%';
					$col->Filter = QQ::Like($objNode, null);
					break;
				case QDatabaseFieldType::Float:
				case QDatabaseFieldType::Integer:
					//EQUAL
					$col->FilterType = QFilterType::TextFilter;
					$col->Filter = QQ::Equal($objNode, null);
					break;
				case QType::Object:
				case QType::Resource:
				default:
					//this node points to a class, there's no way to know what to filter on
					$col->FilterType = QFilterType::None;
					$col->ClearFilter();
					break;
			}
		}

	}

	/**
	 * Class QQColumnNode
	 * A node that represents a column in a table.
	 */
	class QQColumnNode extends QQNode {
		/**
		 * Initialize a column node.
		 * @param string $strName
		 * @param string $strPropertyName
		 * @param string $strType
		 * @param QQNode|null $objParentNode
		 */
		public function __construct($strName, $strPropertyName, $strType, QQNode $objParentNode = null) {
			$this->objParentNode = $objParentNode;
			$this->strName = $strName;
			$this->strAlias = $strName;
			if ($objParentNode) $objParentNode->objChildNodeArray[$strName] = $this;

			$this->strPropertyName = $strPropertyName;
			$this->strType = $strType;
			if ($objParentNode) {
				$this->strRootTableName = $objParentNode->strRootTableName;
			} else
				$this->strRootTableName = $strName;
		}

		/**
		 * @return string
		 */
		public function GetColumnAlias(QQueryBuilder $objBuilder) {
			$this->Join($objBuilder);
			$strParentAlias = $this->objParentNode->FullAlias();
			$strTableAlias = $objBuilder->GetTableAlias($strParentAlias);
			// Pull the Begin and End Escape Identifiers from the Database Adapter
			return $this->MakeColumnAlias($objBuilder, $strTableAlias);
		}

		/**
		 * @return string
		 */
		public function MakeColumnAlias(QQueryBuilder $objBuilder, $strTableAlias) {
			$strBegin = $objBuilder->Database->EscapeIdentifierBegin;
			$strEnd = $objBuilder->Database->EscapeIdentifierEnd;

			return sprintf('%s%s%s.%s%s%s',
				$strBegin, $strTableAlias, $strEnd,
				$strBegin, $this->strName, $strEnd);
		}


		/**
		 * @return string
		 */
		public function GetTable() {
			return $this->objParentNode->FullAlias();
		}

		/**
		 * Join the node to the given query. Since this is a leaf node, we pass on the join to the parent.
		 *
		 * @param QQueryBuilder $objBuilder
		 * @param bool $blnExpandSelection
		 * @param QQCondition|null $objJoinCondition
		 * @param QQSelect|null $objSelect
		 * @throws QCallerException
		 */
		public function Join(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null, QQSelect $objSelect = null) {
			$objParentNode = $this->objParentNode;
			if (!$objParentNode) {
				throw new QCallerException('A column node must have a parent node.');
			} else {
				// Here we pass the join condition on to the parent object
				$objParentNode->Join($objBuilder, $blnExpandSelection, $objJoinCondition, $objSelect);
			}
		}

		/**
		 * Get the unaliased column name. For special situations, like order by, since you can't order by aliases.
		 * @return string
		 */
		public function GetAsManualSqlColumn() {
			if ($this->strTableName)
				return $this->strTableName . '.' . $this->strName;
			else if (($this->objParentNode) && ($this->objParentNode->strTableName))
				return $this->objParentNode->strTableName . '.' . $this->strName;
			else
				return $this->strName;
		}

	}

	/**
	 * Class QQTableNode
	 * A node that represents a regular table. This can either be a root of the query node chain, or a forward looking
	 * foreign key (as in one-to-one relationship).
	 */
	abstract class QQTableNode extends QQNode {
		/**
		 * Initialize a table node. The subclass should fill in the table name, primary key and class name.
		 *
		 * @param $strName
		 * @param null|string $strPropertyName	If it has a parent, the property the parent uses to refer to this node.
		 * @param null|string $strType If it has a parent, the type of the column in the parent that is the fk to this node. (Likely Integer).
		 * @param QQNode|null $objParentNode
		 */
		public function __construct($strName, $strPropertyName = null, $strType = null, QQNode $objParentNode = null) {
			$this->objParentNode = $objParentNode;
			$this->strName = $strName;
			$this->strAlias = $strName;
			if ($objParentNode) $objParentNode->objChildNodeArray[$strName] = $this;

			$this->strPropertyName = $strPropertyName;
			$this->strType = $strType;
			if ($objParentNode) {
				$this->strRootTableName = $objParentNode->strRootTableName;
			} else
				$this->strRootTableName = $strName;
		}

		/**
		 * Join the node to the query.
		 * Otherwise, its a straightforward
		 * one-to-one join. Conditional joins in this situation are really only useful when combined with condition
		 * clauses that select out rows that were not joined (null FK).
		 *
		 * @param QQueryBuilder $objBuilder
		 * @param bool $blnExpandSelection
		 * @param QQCondition|null $objJoinCondition
		 * @param QQSelect|null $objSelect
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function Join(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null, QQSelect $objSelect = null) {
			$objParentNode = $this->objParentNode;
			if (!$objParentNode) {
				if ($this->strTableName != $objBuilder->RootTableName) {
					throw new QCallerException('Cannot use QQNode for "' . $this->strTableName . '" when querying against the "' . $objBuilder->RootTableName . '" table', 3);
				}
			} else {

				// Special case situation to allow applying a join condition on an association table.
				// The condition must be testing against the primary key of the joined table.
				if ($objJoinCondition &&
					$this->objParentNode instanceof QQAssociationNode &&
					$objJoinCondition->EqualTables($this->objParentNode->FullAlias())) {

					$objParentNode->Join($objBuilder, $blnExpandSelection, $objJoinCondition, $objSelect);
					$objJoinCondition = null; // prevent passing join condition to this level
				} else {
					$objParentNode->Join($objBuilder, $blnExpandSelection, null, $objSelect);
					if ($objJoinCondition && !$objJoinCondition->EqualTables($this->FullAlias())) {
						throw new QCallerException("The join condition on the \"" . $this->strTableName . "\" table must only contain conditions for that table.");
					}
				}

				try {
					$strParentAlias = $objParentNode->FullAlias();
					$strAlias = $this->FullAlias();
					//$strJoinTableAlias = $strParentAlias . '__' . ($this->strAlias ? $this->strAlias : $this->strName);
					$objBuilder->AddJoinItem($this->strTableName, $strAlias,
						$strParentAlias, $this->strName, $this->strPrimaryKey, $objJoinCondition);

					if ($blnExpandSelection) {
						$this->PutSelectFields($objBuilder, $strAlias, $objSelect);
					}
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
	}

	/**
	 * Class QQReverseReferenceNode
	 *
	 * Describes a foreign key relationship that links to the primary key in the parent table. Relationship can be unique (one-to-one) or
	 * not unique (many-to-one).
	 */
	class QQReverseReferenceNode extends QQTableNode {
		/** @var string The name of the foreign key in the linked table.  */
		protected $strForeignKey;

		/**
		 * Construct the reverse reference.
		 *
		 * @param QQNode $objParentNode
		 * @param null|string $strName
		 * @param null|string $strType
		 * @param null|QQNode $strForeignKey
		 * @param null $strPropertyName		If a unique reverse relationship, the name of property that will be used in the model class.
		 * @throws QCallerException
		 */
		public function __construct(QQNode $objParentNode, $strName, $strType, $strForeignKey, $strPropertyName = null) {
			parent::__construct($strName, $strPropertyName, $strType, $objParentNode);
			if (!$objParentNode) {
				throw new QCallerException('ReverseReferenceNodes must have a Parent Node');
			}
			$objParentNode->objChildNodeArray[$strName] = $this;
			$this->strForeignKey = $strForeignKey;
		}

		/**
		 * Return true if this is a unique reverse relationship.
		 *
		 * @return bool
		 */
		public function IsUnique() {
			return !empty($this->strPropertyName);
		}

		/**
		 * Join a node to the query. Since this is a reverse looking node, conditions control which items are joined.
		 *
		 * @param QQueryBuilder $objBuilder
		 * @param bool $blnExpandSelection
		 * @param QQCondition|null $objJoinCondition
		 * @param QQSelect|null $objSelect
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function Join(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null, QQSelect $objSelect = null) {
			$objParentNode = $this->objParentNode;
			$objParentNode->Join($objBuilder, $blnExpandSelection, null, $objSelect);
			if ($objJoinCondition && !$objJoinCondition->EqualTables($this->FullAlias())) {
				throw new QCallerException("The join condition on the \"" . $this->strTableName . "\" table must only contain conditions for that table.");
			}

			try {
				$strParentAlias = $objParentNode->FullAlias();
				$strAlias = $this->FullAlias();
				//$strJoinTableAlias = $strParentAlias . '__' . ($this->strAlias ? $this->strAlias : $this->strName);
				$objBuilder->AddJoinItem($this->strTableName, $strAlias,
					$strParentAlias, $this->objParentNode->_PrimaryKey, $this->strForeignKey, $objJoinCondition);

				if ($blnExpandSelection) {
					$this->PutSelectFields($objBuilder, $strAlias, $objSelect);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

	}

	/**
	 * Class QQAssociationNode
	 *
	 * Describes a many-to-many relationship in the database that uses an association table to link two other tables together.
	 */
	class QQAssociationNode extends QQNode {
		/**
		 * @param QQNode $objParentNode
		 * @throws Exception
		 */
		public function __construct(QQNode $objParentNode) {
			$this->objParentNode = $objParentNode;
			if ($objParentNode) {
				$this->strRootTableName = $objParentNode->_RootTableName;
				$this->strAlias = $this->strName;
				$objParentNode->objChildNodeArray[$this->strAlias] = $this;
			} else {
				throw new Exception ("Association Nodes must always have a parent node");
			}
		}

		/**
		 * Join the node to the query. Join condition here gets applied to parent item.
		 *
		 * @param QQueryBuilder $objBuilder
		 * @param bool $blnExpandSelection
		 * @param QQCondition|null $objJoinCondition
		 * @param QQSelect|null $objSelect
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function Join(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null, QQSelect $objSelect = null) {
			$objParentNode = $this->objParentNode;
			$objParentNode->Join($objBuilder, $blnExpandSelection, null, $objSelect);
			if ($objJoinCondition && !$objJoinCondition->EqualTables($this->FullAlias())) {
				throw new QCallerException("The join condition on the \"" . $this->strTableName . "\" table must only contain conditions for that table.");
			}

			try {
				$strParentAlias = $objParentNode->FullAlias();
				$strAlias = $this->FullAlias();
				//$strJoinTableAlias = $strParentAlias . '__' . ($this->strAlias ? $this->strAlias : $this->strName);
				$objBuilder->AddJoinItem($this->strTableName, $strAlias,
					$strParentAlias, $objParentNode->_PrimaryKey, $this->strPrimaryKey, $objJoinCondition);

				if ($blnExpandSelection) {
					$this->PutSelectFields($objBuilder, $strAlias, $objSelect);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}


	/**
	 * Class QQNamedValue
	 *
	 * Special node for referring to a node within a custom SQL clause.
	 */
	class QQNamedValue extends QQNode
	{
		const DelimiterCode = 3;

		/**
		 * @param $strName
		 */
		public function __construct($strName) {
			$this->strName = $strName;
		}

		/**
		 * @param null $blnEqualityType
		 * @return string
		 */
		public function Parameter($blnEqualityType = null)
		{
			if (is_null($blnEqualityType))
				return chr(QQNamedValue::DelimiterCode) . '{' . $this->strName . '}';
			else if ($blnEqualityType)
				return chr(QQNamedValue::DelimiterCode) . '{=' . $this->strName . '=}';
			else
				return chr(QQNamedValue::DelimiterCode) . '{!' . $this->strName . '!}';
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 * @param bool|false $blnExpandSelection
		 * @param QQCondition|null $objJoinCondition
		 * @param QQSelect|null $objSelect
		 */
		public function Join(QQueryBuilder $objBuilder, $blnExpandSelection = false, QQCondition $objJoinCondition = null, QQSelect $objSelect = null) {
			assert(0);    // This kind of node is never a parent.
		}
	}

	abstract class QQCondition extends QBaseClass {
		protected $strOperator;
		abstract public function UpdateQueryBuilder(QQueryBuilder $objBuilder);
		public function __toString() {
			return 'QQCondition Object';
		}

		protected $blnProcessed;

		/**
		 * Used internally by QCubed Query to get an individual where clause for a given condition
		 * Mostly used for conditional joins.
		 *
		 * @param QQueryBuilder $objBuilder
		 * @param bool|false $blnProcessOnce
		 * @return null|string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function GetWhereClause(QQueryBuilder $objBuilder, $blnProcessOnce = false) {
			if ($blnProcessOnce && $this->blnProcessed)
				return null;

			$this->blnProcessed = true;

			try {
				$objConditionBuilder = new QPartialQueryBuilder($objBuilder);
				$this->UpdateQueryBuilder($objConditionBuilder);
				return $objConditionBuilder->GetWhereStatement();
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * @param string $strTableName
		 * @return bool
		 */
		public function EqualTables($strTableName) {
			return true;
		}
	}
	class QQConditionAll extends QQCondition {
		/**
		 * @param $mixParameterArray
		 * @throws QCallerException
		 */
		public function __construct($mixParameterArray) {
			if (count($mixParameterArray))
				throw new QCallerException('All clause takes in no parameters', 3);
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem('1=1');
		}
	}
	class QQConditionNone extends QQCondition {
		/**
		 * @param $mixParameterArray
		 * @throws QCallerException
		 */
		public function __construct($mixParameterArray) {
			if (count($mixParameterArray))
				throw new QCallerException('None clause takes in no parameters', 3);
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem('1=0');
		}
	}
	abstract class QQConditionLogical extends QQCondition {
		/** @var QQCondition[] */
		protected $objConditionArray;
		protected function CollapseConditions($mixParameterArray) {
			$objConditionArray = array();
			foreach ($mixParameterArray as $mixParameter) {
				if (is_array($mixParameter))
					$objConditionArray = array_merge($objConditionArray, $mixParameter);
				else
					array_push($objConditionArray, $mixParameter);
			}

			foreach ($objConditionArray as $objCondition)
				if (!($objCondition instanceof QQCondition))
					throw new QCallerException('Logical Or/And clause parameters must all be QQCondition objects', 3);

			if (count($objConditionArray))
				return $objConditionArray;
			else
				throw new QCallerException('No parameters passed in to logical Or/And clause', 3);
		}
		public function __construct($mixParameterArray) {
			$objConditionArray = $this->CollapseConditions($mixParameterArray);
			try {
				$this->objConditionArray = QType::Cast($objConditionArray, QType::ArrayType);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$intLength = count($this->objConditionArray);
			if ($intLength) {
				$objBuilder->AddWhereItem('(');
				for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
					if (!($this->objConditionArray[$intIndex] instanceof QQCondition))
						throw new QCallerException($this->strOperator . ' clause has elements that are not Conditions');
					try {
						$this->objConditionArray[$intIndex]->UpdateQueryBuilder($objBuilder);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					if (($intIndex + 1) != $intLength)
						$objBuilder->AddWhereItem($this->strOperator);
				}
				$objBuilder->AddWhereItem(')');
			}
		}

		public function EqualTables($strTableName) {
			foreach ($this->objConditionArray as $objCondition) {
				if (!$objCondition->EqualTables($strTableName)) {
					return false;
				}
			}
			return true;
		}
	}
	class QQConditionOr extends QQConditionLogical {
		protected $strOperator = 'OR';
	}
	class QQConditionAnd extends QQConditionLogical {
		protected $strOperator = 'AND';
	}

	class QQConditionNot extends QQCondition {
		protected $objCondition;
		public function __construct(QQCondition $objCondition) {
			$this->objCondition = $objCondition;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem('(NOT');
			try {
				$this->objCondition->UpdateQueryBuilder($objBuilder);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$objBuilder->AddWhereItem(')');
		}
	}

	abstract class QQConditionComparison extends QQCondition {
		/** @var QQColumnNode */
		public $objQueryNode;
		public $mixOperand;

		/**
		 * @param QQColumnNode $objQueryNode
		 * @param mixed $mixOperand
		 * @throws QInvalidCastException
		 */
		public function __construct(QQColumnNode $objQueryNode, $mixOperand = null) {
			$this->objQueryNode = $objQueryNode;

			if ($mixOperand instanceof QQNamedValue || $mixOperand === null)
				$this->mixOperand = $mixOperand;
			else if ($mixOperand instanceof QQAssociationNode)
				throw new QInvalidCastException('Comparison operand cannot be an Association-based QQNode', 3);
			else if ($mixOperand instanceof QQCondition)
				throw new QInvalidCastException('Comparison operand cannot be a QQCondition', 3);
			else if ($mixOperand instanceof QQClause)
				throw new QInvalidCastException('Comparison operand cannot be a QQClause', 3);
			else if (!($mixOperand instanceof QQNode)) {
				$this->mixOperand = $mixOperand;
			} else {
				if (!($mixOperand instanceof QQColumnNode))
					throw new QInvalidCastException('Unable to cast "' . $mixOperand->_Name . '" table to Column-based QQNode', 3);
				$this->mixOperand = $mixOperand;
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . $this->strOperator . QQNode::GetValue($this->mixOperand, $objBuilder));
		}

		/**
		 * Used by conditional joins to make sure the join conditions only apply to given table.
		 * @param $strTableName
		 * @returns bool
		 */
		public function EqualTables($strTableName) {
			return $this->objQueryNode->GetTable() == $strTableName;
		}
	}

	/**
	 * Class QQConditionIsNull
	 * Represent a test for a null item in the database.
	 */
	class QQConditionIsNull extends QQConditionComparison {
		/**
		 * @param QQColumnNode $objQueryNode
		 */
		public function __construct(QQColumnNode $objQueryNode) {
			parent::__construct($objQueryNode);
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IS NULL');
		}
	}

	class QQConditionIsNotNull extends QQConditionComparison {
		public function __construct(QQColumnNode $objQueryNode) {
			parent::__construct($objQueryNode);
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IS NOT NULL');
		}
	}

	class QQConditionIn extends QQConditionComparison {
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param mixed $mixValuesArray
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __construct(QQColumnNode $objQueryNode, $mixValuesArray) {
			parent::__construct($objQueryNode);

			if ($mixValuesArray instanceof QQNamedValue)
				$this->mixOperand = $mixValuesArray;
			else if ($mixValuesArray instanceof QQSubQueryNode)
				$this->mixOperand = $mixValuesArray;
			else {
				try {
					$this->mixOperand = QType::Cast($mixValuesArray, QType::ArrayType);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$mixOperand = $this->mixOperand;
			if ($mixOperand instanceof QQNamedValue) {
				/** @var QQNamedValue $mixOperand */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN (' . $mixOperand->Parameter() . ')');
			} else if ($mixOperand instanceof QQSubQueryNode) {
				/** @var QQSubQueryNode $mixOperand */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN ' . $mixOperand->GetColumnAlias($objBuilder));
			} else {
				$strParameters = array();
				foreach ($mixOperand as $mixParameter) {
					array_push($strParameters, $objBuilder->Database->SqlVariable($mixParameter));
				}
				if (count($strParameters))
					$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN (' . implode(',', $strParameters) . ')');
				else
					$objBuilder->AddWhereItem('1=0');
			}
		}
	}

	class QQConditionNotIn extends QQConditionComparison {
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param mixed|null $mixValuesArray
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __construct(QQColumnNode $objQueryNode, $mixValuesArray) {
			parent::__construct($objQueryNode);

			if ($mixValuesArray instanceof QQNamedValue)
				$this->mixOperand = $mixValuesArray;
			else if ($mixValuesArray instanceof QQSubQueryNode)
				$this->mixOperand = $mixValuesArray;
			else {
				try {
					$this->mixOperand = QType::Cast($mixValuesArray, QType::ArrayType);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$mixOperand = $this->mixOperand;
			if ($mixOperand instanceof QQNamedValue) {
				/** @var QQNamedValue $mixOperand */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN (' . $mixOperand->Parameter() . ')');
			} else if ($mixOperand instanceof QQSubQueryNode) {
				/** @var QQSubQueryNode $mixOperand */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN ' . $mixOperand->GetColumnAlias($objBuilder));
			} else {
				$strParameters = array();
				foreach ($mixOperand as $mixParameter) {
					array_push($strParameters, $objBuilder->Database->SqlVariable($mixParameter));
				}
				if (count($strParameters))
					$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN (' . implode(',', $strParameters) . ')');
				else
					$objBuilder->AddWhereItem('1=1');
			}
		}
	}

	class QQConditionLike extends QQConditionComparison {
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param string $strValue
		 * @throws Exception
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		public function __construct(QQColumnNode $objQueryNode, $strValue) {
			parent::__construct($objQueryNode);

			if ($strValue instanceof QQNamedValue)
				$this->mixOperand = $strValue;
			else {
				try {
					$this->mixOperand = QType::Cast($strValue, QType::String);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$mixOperand = $this->mixOperand;
			if ($mixOperand instanceof QQNamedValue) {
				/** @var QQNamedValue $mixOperand */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' LIKE ' . $mixOperand->Parameter());
			} else {
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' LIKE ' . $objBuilder->Database->SqlVariable($mixOperand));
			}
		}
	}

	class QQConditionNotLike extends QQConditionComparison {
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param mixed|null $strValue
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __construct(QQColumnNode $objQueryNode, $strValue) {
			parent::__construct($objQueryNode);

			if ($strValue instanceof QQNamedValue)
				$this->mixOperand = $strValue;
			else {
				try {
					$this->mixOperand = QType::Cast($strValue, QType::String);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$mixOperand = $this->mixOperand;
			if ($mixOperand instanceof QQNamedValue) {
				/** @var QQNamedValue $mixOperand */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT LIKE ' . $mixOperand->Parameter());
			} else {
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT LIKE ' . $objBuilder->Database->SqlVariable($mixOperand));
			}
		}
	}

	class QQConditionBetween extends QQConditionComparison {
		/** @var  mixed */
		protected $mixOperandTwo;

		/**
		 * @param QQColumnNode $objQueryNode
		 * @param mixed|null $mixMinValue
		 * @param $mixMaxValue
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __construct(QQColumnNode $objQueryNode, $mixMinValue, $mixMaxValue) {
			parent::__construct($objQueryNode);
			try {
				$this->mixOperand = $mixMinValue;
				$this->mixOperandTwo = $mixMaxValue;
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$mixOperand = $this->mixOperand;
			$mixOperandTwo = $this->mixOperandTwo;
			if ($mixOperand instanceof QQNamedValue) {
				/** @var QQNamedValue $mixOperand */
				/** @var QQNamedValue $mixOperandTwo */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' BETWEEN ' . $mixOperand->Parameter() . ' AND ' . $mixOperandTwo->Parameter());
			} else {
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' BETWEEN ' . $objBuilder->Database->SqlVariable($mixOperand) . ' AND ' . $objBuilder->Database->SqlVariable($mixOperandTwo));
			}
		}
	}

	class QQConditionNotBetween extends QQConditionComparison {
		/** @var mixed  */
		protected $mixOperandTwo;

		/**
		 * @param QQColumnNode $objQueryNode
		 * @param string $strMinValue
		 * @param string $strMaxValue
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function __construct(QQColumnNode $objQueryNode, $strMinValue, $strMaxValue) {
			parent::__construct($objQueryNode);
			try {
				$this->mixOperand = QType::Cast($strMinValue, QType::String);
				$this->mixOperandTwo = QType::Cast($strMaxValue, QType::String);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				$objExc->IncrementOffset();
				throw $objExc;
			}

			if ($strMinValue instanceof QQNamedValue)
				$this->mixOperand = $strMinValue;
			if ($strMaxValue instanceof QQNamedValue)
				$this->mixOperandTwo = $strMaxValue;

		}

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$mixOperand = $this->mixOperand;
			$mixOperandTwo = $this->mixOperandTwo;
			if ($mixOperand instanceof QQNamedValue) {
				/** @var QQNamedValue $mixOperand */
				/** @var QQNamedValue $mixOperandTwo */
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT BETWEEN ' . $mixOperand->Parameter() . ' AND ' . $mixOperandTwo->Parameter());
			} else {
				$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT BETWEEN ' . $objBuilder->Database->SqlVariable($mixOperand) . ' AND ' . $objBuilder->Database->SqlVariable($mixOperandTwo));
			}
		}
	}

	class QQConditionEqual extends QQConditionComparison {
		protected $strOperator = ' = ';

		/**
		 * @param QQueryBuilder $objBuilder
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' ' . QQNode::GetValue($this->mixOperand, $objBuilder, true));
		}
	}
	class QQConditionNotEqual extends QQConditionComparison {
		protected $strOperator = ' != ';

		/**
		 * @param QQueryBuilder $objBuilder
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' ' . QQNode::GetValue($this->mixOperand, $objBuilder, false));
		}
	}
	class QQConditionGreaterThan extends QQConditionComparison {
		protected $strOperator = ' > ';
	}
	class QQConditionLessThan extends QQConditionComparison {
		protected $strOperator = ' < ';
	}
	class QQConditionGreaterOrEqual extends QQConditionComparison {
		protected $strOperator = ' >= ';
	}
	class QQConditionLessOrEqual extends QQConditionComparison {
		protected $strOperator = ' <= ';
	}

	class QQ {
		/////////////////////////
		// QQCondition Factories
		/////////////////////////

		static public function All() {
			return new QQConditionAll(func_get_args());
		}

		static public function None() {
			return new QQConditionNone(func_get_args());
		}

		static public function OrCondition(/* array and/or parameterized list of QLoad objects*/) {
			return new QQConditionOr(func_get_args());
		}

		static public function AndCondition(/* array and/or parameterized list of QLoad objects*/) {
			return new QQConditionAnd(func_get_args());
		}

		static public function Not(QQCondition $objCondition) {
			return new QQConditionNot($objCondition);
		}

		static public function Equal(QQColumnNode $objQueryNode, $mixValue) {
			return new QQConditionEqual($objQueryNode, $mixValue);
		}
		static public function NotEqual(QQColumnNode $objQueryNode, $mixValue) {
			return new QQConditionNotEqual($objQueryNode, $mixValue);
		}
		static public function GreaterThan(QQColumnNode $objQueryNode, $mixValue) {
			return new QQConditionGreaterThan($objQueryNode, $mixValue);
		}
		static public function GreaterOrEqual(QQColumnNode $objQueryNode, $mixValue) {
			return new QQConditionGreaterOrEqual($objQueryNode, $mixValue);
		}
		static public function LessThan(QQColumnNode $objQueryNode, $mixValue) {
			return new QQConditionLessThan($objQueryNode, $mixValue);
		}
		static public function LessOrEqual(QQColumnNode $objQueryNode, $mixValue) {
			return new QQConditionLessOrEqual($objQueryNode, $mixValue);
		}
		static public function IsNull(QQColumnNode $objQueryNode) {
			return new QQConditionIsNull($objQueryNode);
		}
		static public function IsNotNull(QQColumnNode $objQueryNode) {
			return new QQConditionIsNotNull($objQueryNode);
		}
		static public function In(QQColumnNode $objQueryNode, $mixValuesArray) {
			return new QQConditionIn($objQueryNode, $mixValuesArray);
		}
		static public function NotIn(QQColumnNode $objQueryNode, $mixValuesArray) {
			return new QQConditionNotIn($objQueryNode, $mixValuesArray);
		}
		static public function Like(QQColumnNode $objQueryNode, $strValue) {
			return new QQConditionLike($objQueryNode, $strValue);
		}
		static public function NotLike(QQColumnNode $objQueryNode, $strValue) {
			return new QQConditionNotLike($objQueryNode, $strValue);
		}
		static public function Between(QQColumnNode $objQueryNode, $mixMinValue, $mixMaxValue) {
			return new QQConditionBetween($objQueryNode, $mixMinValue, $mixMaxValue);
		}
		static public function NotBetween(QQColumnNode $objQueryNode, $strMinValue, $strMaxValue) {
			return new QQConditionNotBetween($objQueryNode, $strMinValue, $strMaxValue);
		}

		////////////////////////
		// QQCondition Shortcuts
		////////////////////////
		/**
		 * @param QQColumnNode $objQueryNode
		 * @param $strSymbol
		 * @param mixed|null $mixValue
		 * @param mixed|null $mixValueTwo
		 * @return QQCondition
		 * @throws Exception
		 * @throws QCallerException
		 */
		static public function _(QQColumnNode $objQueryNode, $strSymbol, $mixValue = null, $mixValueTwo = null) {
			try {
				switch(strtolower(trim($strSymbol))) {
					case '=': return QQ::Equal($objQueryNode, $mixValue);
					case '!=': return QQ::NotEqual($objQueryNode, $mixValue);
					case '>': return QQ::GreaterThan($objQueryNode, $mixValue);
					case '<': return QQ::LessThan($objQueryNode, $mixValue);
					case '>=': return QQ::GreaterOrEqual($objQueryNode, $mixValue);
					case '<=': return QQ::LessOrEqual($objQueryNode, $mixValue);
					case 'in': return QQ::In($objQueryNode, $mixValue);
					case 'not in': return QQ::NotIn($objQueryNode, $mixValue);
					case 'like': return QQ::Like($objQueryNode, $mixValue);
					case 'not like': return QQ::NotLike($objQueryNode, $mixValue);
					case 'is null': return QQ::IsNull($objQueryNode);
					case 'is not null': return QQ::IsNotNull($objQueryNode);
					case 'between': return QQ::Between($objQueryNode, $mixValue, $mixValueTwo);
					case 'not between': return QQ::NotBetween($objQueryNode, $mixValue, $mixValueTwo);
					default:
						throw new QCallerException('Unknown Query Comparison Operation: ' . $strSymbol, 0);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/////////////////////////
		// QQSubQuery Factories
		/////////////////////////

		/**
		 * @param string $strSql
		 * @param null|QQNode[] $objParentQueryNodes	Array of nodes to specify replacement value in the sql.
		 * @return QQSubQuerySqlNode
		 */
		static public function SubSql($strSql, $objParentQueryNodes = null) {
			$objParentQueryNodeArray = func_get_args();
			return new QQSubQuerySqlNode($strSql, $objParentQueryNodeArray);
		}

		static public function Virtual($strName, QQSubQueryNode $objSubQueryDefinition = null) {
			return new QQVirtualNode($strName, $objSubQueryDefinition);
		}

		/////////////////////////
		// QQClause Factories
		/////////////////////////

		static public function Clause(/* parameterized list of QQClause objects */) {
			$objClauseArray = array();

			foreach (func_get_args() as $objClause)
				if ($objClause) {
					if (!($objClause instanceof QQClause))
						throw new QCallerException('Non-QQClause object was passed in to QQ::Clause');
					else
						array_push($objClauseArray, $objClause);
				}

			return $objClauseArray;
		}

		static public function OrderBy(/* array and/or parameterized list of QQNode objects*/) {
			return new QQOrderBy(func_get_args());
		}

		static public function GroupBy(/* array and/or parameterized list of QQNode objects*/) {
			return new QQGroupBy(func_get_args());
		}

		static public function Having(QQSubQuerySqlNode $objNode) {
			return new QQHavingClause($objNode);
		}

		static public function Count(QQColumnNode $objNode, $strAttributeName) {
			return new QQCount($objNode, $strAttributeName);
		}

		static public function Sum(QQColumnNode $objNode, $strAttributeName) {
			return new QQSum($objNode, $strAttributeName);
		}

		static public function Minimum(QQColumnNode $objNode, $strAttributeName) {
			return new QQMinimum($objNode, $strAttributeName);
		}

		static public function Maximum(QQColumnNode $objNode, $strAttributeName) {
			return new QQMaximum($objNode, $strAttributeName);
		}

		static public function Average(QQColumnNode $objNode, $strAttributeName) {
			return new QQAverage($objNode, $strAttributeName);
		}

		static public function Expand(QQNode $objNode, QQCondition $objJoinCondition = null, QQSelect $objSelect = null) {
//			if (gettype($objNode) == 'string')
//				return new QQExpandVirtualNode(new QQVirtualNode($objNode));

			if ($objNode instanceof QQVirtualNode)
				return new QQExpandVirtualNode($objNode);
			else
				return new QQExpand($objNode, $objJoinCondition, $objSelect);
		}

		static public function ExpandAsArray(QQNode $objNode, $objCondition = null, QQSelect $objSelect = null) {
			return new QQExpandAsArray($objNode, $objCondition, $objSelect);
		}

		static public function Select(/* array and/or parameterized list of QQNode objects*/) {
			if (func_num_args() == 1 && is_array($a = func_get_arg(0))) {
				return new QQSelect($a);
			} else {
				return new QQSelect(func_get_args());
			}
		}

		static public function LimitInfo($intMaxRowCount, $intOffset = 0) {
			return new QQLimitInfo($intMaxRowCount, $intOffset);
		}

		static public function Distinct() {
			return new QQDistinct();
		}

		/**
		 * @param QQClause[]|QQClause|null $objClauses QQClause object or array of QQClause objects
		 * @return QQSelect QQSelect clause containing all the nodes from all the QQSelect clauses from $objClauses,
		 * or null if $objClauses contains no QQSelect clauses
		 */
		public static function extractSelectClause($objClauses) {
			if ($objClauses instanceof QQSelect)
				return $objClauses;

			if (is_array($objClauses)) {
				$hasSelects = false;
				$objSelect = QQuery::Select();
				foreach ($objClauses as $objClause) {
					if ($objClause instanceof QQSelect) {
						$hasSelects = true;
						$objSelect->Merge($objClause);
					}
				}
				if (!$hasSelects)
					return null;
				return $objSelect;
			}
			return null;
		}

		/////////////////////////
		// Aliased QQ Node
		/////////////////////////
		/**
		 * Returns the supplied node object, after setting its alias to the value supplied
		 *
		 * @param QQNode $objNode The node object to set alias on
		 * @param string $strAlias The alias to set
		 * @return mixed The same node that was passed in, but with the alias set
		 *
		 */
		static public function Alias(QQNode $objNode, $strAlias)
		{
			$objNode->SetAlias($strAlias);
			return $objNode;
		}

		/////////////////////////
		// NamedValue QQ Node
		/////////////////////////
		static public function NamedValue($strName) {
			return new QQNamedValue($strName);
		}
	}

	abstract class QQSubQueryNode extends QQColumnNode {
	}

	class QQSubQueryCountNode extends QQSubQueryNode {
		protected $strFunctionName = 'COUNT';
	}

	class QQSubQuerySqlNode extends QQSubQueryNode {
		protected $strSql;
		/** @var QQNode[] */
		protected $objParentQueryNodes;
		/**
		 * @param $strSql
		 * @param null|QQColumnNode[] $objParentQueryNodes
		 */
		public function __construct($strSql, $objParentQueryNodes = null) {
			parent::__construct('', '', '');
			$this->objParentNode = true;
			$this->objParentQueryNodes = $objParentQueryNodes;
			$this->strSql = $strSql;
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 * @return string
		 */
		public function GetColumnAlias(QQueryBuilder $objBuilder) {
			$strSql = $this->strSql;
			for ($intIndex = 1; $intIndex < count($this->objParentQueryNodes); $intIndex++) {
				if (!is_null($this->objParentQueryNodes[$intIndex]))
					$strSql = str_replace('{' . $intIndex . '}', $this->objParentQueryNodes[$intIndex]->GetColumnAlias($objBuilder), $strSql);
			}
			return '(' . $strSql . ')';
		}
	}

	class QQVirtualNode extends QQColumnNode {
		protected $objSubQueryDefinition;

		/**
		 * @param $strName
		 * @param QQSubQueryNode|null $objSubQueryDefinition
		 */
		public function __construct($strName, QQSubQueryNode $objSubQueryDefinition = null) {
			parent::__construct('', '', '');
			$this->objParentNode = true;
			$this->strName = trim(strtolower($strName));
			$this->objSubQueryDefinition = $objSubQueryDefinition;
		}

		/**
		 * @param QQueryBuilder $objBuilder
		 * @return string
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function GetColumnAlias(QQueryBuilder $objBuilder) {
			if ($this->objSubQueryDefinition) {
				$objBuilder->SetVirtualNode($this->strName, $this->objSubQueryDefinition);
				return $this->objSubQueryDefinition->GetColumnAlias($objBuilder);
			} else {
				try {
					return $objBuilder->GetVirtualNode($this->strName)->GetColumnAlias($objBuilder);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
		public function GetAttributeName() {
			return $this->strName;
		}
	}

	abstract class QQClause extends QBaseClass {
		abstract public function UpdateQueryBuilder(QQueryBuilder $objBuilder);
		abstract public function __toString();
	}

	/**
	 * Class QQOrderBy: Represents an 'ORDER BY' statement on SQL/DB level
	 */
	class QQOrderBy extends QQClause {
		/** @var mixed[]  */
		protected $objNodeArray;

		/**
		 * CollapseNodes makes sure a node list is vetted, and turned into a node list.
		 * This also allows table nodes to be used in certain column node contexts, in which it will
		 * substitute the primary key node in this situation.
		 *
		 * @param $mixParameterArray
		 * @return array
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		protected function CollapseNodes($mixParameterArray) {
			/** @var QQNode[] $objNodeArray */
			$objNodeArray = array();
			foreach ($mixParameterArray as $mixParameter) {
				if (is_array($mixParameter)) {
					$objNodeArray = array_merge($objNodeArray, $mixParameter);
				} else {
					array_push($objNodeArray, $mixParameter);
				}
			}

			$blnPreviousIsNode = false;
			$objFinalNodeArray = array();
			foreach ($objNodeArray as $objNode) {
				if (!($objNode instanceof QQNode || $objNode instanceof QQCondition)) {
					if (!$blnPreviousIsNode)
						throw new QCallerException('OrderBy clause parameters must all be QQNode or QQCondition objects followed by an optional true/false "Ascending Order" option', 3);
					$blnPreviousIsNode = false;
					array_push($objFinalNodeArray, $objNode);
				} elseif ($objNode instanceof QQCondition) {
					$blnPreviousIsNode = true;
					array_push($objFinalNodeArray, $objNode);
				} else {
					if (!$objNode->_ParentNode) {
						throw new QInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based QQNode', 4);
					}
					if ($objNode->_PrimaryKeyNode) { // if a table node, then use the primary key of the table
						array_push($objFinalNodeArray, $objNode->_PrimaryKeyNode);
					} else {
						array_push($objFinalNodeArray, $objNode);
					}
					$blnPreviousIsNode = true;
				}
			}

			if (count($objFinalNodeArray)) {
				return $objFinalNodeArray;
			} else {
				throw new QCallerException('No parameters passed in to OrderBy clause', 3);
			}
		}

		/**
		 * Constructor function
		 *
		 * @param $mixParameterArray
		 *
		 * @throws QCallerException|QInvalidCastException
		 */
		public function __construct($mixParameterArray) {
			$this->objNodeArray = $this->CollapseNodes($mixParameterArray);
		}

		/**
		 * Updates the query builder according to this clause
		 *
		 * @param QQueryBuilder $objBuilder
		 *
		 * @throws Exception|QCallerException
		 */
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$intLength = count($this->objNodeArray);
			for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
				$objNode = $this->objNodeArray[$intIndex];
				if ($objNode instanceof QQColumnNode) {
					/** @var QQColumnNode $objNode */
					$strOrderByCommand = $objNode->GetColumnAlias($objBuilder);
				} else if ($objNode instanceof QQCondition) {
					/** @var QQCondition $objNode */
					$strOrderByCommand = $objNode->GetWhereClause($objBuilder);
				} else {
					$strOrderByCommand = '';
				}

				// Check to see if they want a ASC/DESC declarator
				if ((($intIndex + 1) < $intLength) &&
					!($this->objNodeArray[$intIndex + 1] instanceof QQNode)) {
					if ((!$this->objNodeArray[$intIndex + 1]) ||
						(trim(strtolower($this->objNodeArray[$intIndex + 1])) == 'desc'))
						$strOrderByCommand .= ' DESC';
					else
						$strOrderByCommand .= ' ASC';
					$intIndex++;
				}

				$objBuilder->AddOrderByItem($strOrderByCommand);
			}
		}

		/**
		 * This is used primarly by datagrids wanting to use the "old Beta 2" style of
		 * Manual Queries.  This allows a datagrid to use QQ::OrderBy even though
		 * the manually-written Load method takes in Beta 2 string-based SortByCommand information.
		 *
		 * @return string
		 */
		public function GetAsManualSql() {
			$strOrderByArray = array();
			$intLength = count($this->objNodeArray);
			for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
				$strOrderByCommand = $this->objNodeArray[$intIndex]->GetAsManualSqlColumn();

				// Check to see if they want a ASC/DESC declarator
				if ((($intIndex + 1) < $intLength) &&
					!($this->objNodeArray[$intIndex + 1] instanceof QQNode)) {
					if ((!$this->objNodeArray[$intIndex + 1]) ||
						(trim(strtolower($this->objNodeArray[$intIndex + 1])) == 'desc'))
						$strOrderByCommand .= ' DESC';
					else
						$strOrderByCommand .= ' ASC';
					$intIndex++;
				}

				array_push($strOrderByArray, $strOrderByCommand);
			}

			return implode(',', $strOrderByArray);
		}

		public function __toString() {
			return 'QQOrderBy Clause';
		}
	}

	class QQDistinct extends QQClause {
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->SetDistinctFlag();
		}
		public function __toString() {
			return 'QQDistinct Clause';
		}
	}

	class QQLimitInfo extends QQClause {
		protected $intMaxRowCount;
		protected $intOffset;
		public function __construct($intMaxRowCount, $intOffset = 0) {
			try {
				$this->intMaxRowCount = QType::Cast($intMaxRowCount, QType::Integer);
				$this->intOffset = QType::Cast($intOffset, QType::Integer);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->intOffset)
				$objBuilder->SetLimitInfo($this->intOffset . ',' . $this->intMaxRowCount);
			else
				$objBuilder->SetLimitInfo($this->intMaxRowCount);
		}
		public function __toString() {
			return 'QQLimitInfo Clause';
		}

		public function __get($strName) {
			switch ($strName) {
				case 'MaxRowCount':
					return $this->intMaxRowCount;
				case 'Offset':
					return $this->intOffset;
			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}
		}
	}

	class QQExpandVirtualNode extends QQClause {
		protected $objNode;
		public function __construct(QQVirtualNode $objNode) {
			$this->objNode = $objNode;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			try {
				$objBuilder->AddSelectFunction(null, $this->objNode->GetColumnAlias($objBuilder), $this->objNode->GetAttributeName());
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		public function __toString() {
			return 'QQExpandVirtualNode Clause';
		}
	}
	class QQExpand extends QQClause {
		/** @var QQNode */
		protected $objNode;
		protected $objJoinCondition;
		protected $objSelect;

		public function __construct($objNode, QQCondition $objJoinCondition = null, QQSelect $objSelect  = null) {
			// Check against root and table QQNodes
			if ($objNode instanceof QQAssociationNode)
				throw new QCallerException('Expand clause parameter cannot be an association table node. Try expanding one level deeper.', 2);
			else if (!($objNode instanceof QQNode))
				throw new QCallerException('Expand clause parameter must be a QQNode object', 2);
			else if (!$objNode->_ParentNode)
				throw new QInvalidCastException('Cannot expand on this kind of node.', 3);

			$this->objNode = $objNode;
			$this->objJoinCondition = $objJoinCondition;
			$this->objSelect = $objSelect;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$this->objNode->Join($objBuilder, true, $this->objJoinCondition, $this->objSelect);
		}
		public function __toString() {
			return 'QQExpand Clause';
		}
	}

	/*
	 * Allows a custom sql injection as a having clause. Its up to you to make sure its correct, but you can use subquery placeholders
	 * to expand column names. Standard SQL has limited Having capabilities, but many SQL engines have useful extensions.
	 */
	class QQHavingClause extends QQClause {
		protected $objNode;
		public function __construct(QQSubQueryNode $objSubQueryDefinition) {
			$this->objNode = $objSubQueryDefinition;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddHavingItem (
				$this->objNode->GetColumnAlias($objBuilder)
			);
		}
		public function GetAttributeName() {
			return $this->objNode->strName;
		}
		public function __toString() {
			return "Having Clause";
		}

	}

	abstract class QQAggregationClause extends QQClause {
		/** @var QQNode */
		protected $objNode;
		protected $strAttributeName;
		protected $strFunctionName;
		public function __construct(QQColumnNode $objNode, $strAttributeName) {
			$this->objNode = $objNode;
			$this->strAttributeName = $strAttributeName;
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$objBuilder->AddSelectFunction($this->strFunctionName, $this->objNode->GetColumnAlias($objBuilder), $this->strAttributeName);
		}
	}
	class QQCount extends QQAggregationClause {
		protected $strFunctionName = 'COUNT';
		public function __toString() {
			return 'QQCount Clause';
		}
	}
	class QQSum extends QQAggregationClause {
		protected $strFunctionName = 'SUM';
		public function __toString() {
			return 'QQSum Clause';
		}
	}
	class QQMinimum extends QQAggregationClause {
		protected $strFunctionName = 'MIN';
		public function __toString() {
			return 'QQMinimum Clause';
		}
	}
	class QQMaximum extends QQAggregationClause {
		protected $strFunctionName = 'MAX';
		public function __toString() {
			return 'QQMaximum Clause';
		}
	}
	class QQAverage extends QQAggregationClause {
		protected $strFunctionName = 'AVG';
		public function __toString() {
			return 'QQAverage Clause';
		}
	}

	class QQExpandAsArray extends QQClause {
		/** @var QQNode|QQAssociationNode */
		protected $objNode;
		protected $objCondition;
		protected $objSelect;

		/**
		 * @param QQNode $objNode
		 * @param null|mixed $objCondition
		 * @param QQSelect|null $objSelect
		 * @throws QCallerException
		 */
		public function __construct(QQNode $objNode, $objCondition = null, QQSelect $objSelect = null) {
			// For backwards compatibility with v2, which did not have a condition parameter, we will detect what the 2nd param is.
			// Ensure that this is an QQAssociationNode
			if ((!($objNode instanceof QQAssociationNode)) && (!($objNode instanceof QQReverseReferenceNode)))
				throw new QCallerException('ExpandAsArray clause parameter must be an Association or ReverseReference node', 2);

			if ($objCondition instanceof QQSelect) {
				$this->objNode = $objNode;
				$this->objSelect = $objCondition;
			} else {
				if (!is_null($objCondition)) {
					/*
					if ($objNode instanceof QQAssociationNode) {
						throw new QCallerException('Join conditions can only be applied to reverse reference nodes here. Try putting a condition on the next level down.', 2);
					}*/
					if (!($objCondition instanceof QQCondition)) {
						throw new QCallerException('Condition clause parameter must be a QQCondition dervied class.', 2);
					}
				}
				$this->objNode = $objNode;
				$this->objSelect = $objSelect;
				$this->objCondition = $objCondition;
			}

		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			if ($this->objNode instanceof QQAssociationNode) {
				// The below works because all code generated association nodes will have a _ChildTableNode parameter.
				$this->objNode->_ChildTableNode->Join($objBuilder, true, $this->objCondition, $this->objSelect);
			}
			else {
				$this->objNode->Join($objBuilder, true, $this->objCondition, $this->objSelect);
			}
			$objBuilder->AddExpandAsArrayNode($this->objNode);
		}
		public function __toString() {
			return 'QQExpandAsArray Clause';
		}
	}

	class QQGroupBy extends QQClause {
		/** @var QQColumnNode[] */
		protected $objNodeArray;

		/**
		 * CollapseNodes makes sure a node list is vetted, and turned into a node list.
		 * This also allows table nodes to be used in certain column node contexts, in which it will
		 * substitute the primary key node in this situation.
		 *
		 * @param $mixParameterArray
		 * @return QColumnNode[]
		 * @throws QCallerException
		 * @throws QInvalidCastException
		 */
		protected function CollapseNodes($mixParameterArray) {
			$objNodeArray = array();
			foreach ($mixParameterArray as $mixParameter) {
				if (is_array($mixParameter)) {
					$objNodeArray = array_merge($objNodeArray, $mixParameter);
				} else {
					array_push($objNodeArray, $mixParameter);
				}
			}

			$objFinalNodeArray = array();
			foreach ($objNodeArray as $objNode) {
				/** @var QQNode $objNode */
				if ($objNode instanceof QQAssociationNode)
					throw new QCallerException('GroupBy clause parameter cannot be an association table node.', 3);
				else if (!($objNode instanceof QQNode))
					throw new QCallerException('GroupBy clause parameters must all be QQNode objects.', 3);

				if (!$objNode->_ParentNode)
					throw new QInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based QQNode', 4);

				if ($objNode->_PrimaryKeyNode) {
					array_push($objFinalNodeArray, $objNode->_PrimaryKeyNode);	// if a table node, use the primary key of the table instead
				} else
					array_push($objFinalNodeArray, $objNode);
			}

			if (count($objFinalNodeArray))
				return $objFinalNodeArray;
			else
				throw new QCallerException('No parameters passed in to Expand clause', 3);
		}
		public function __construct($mixParameterArray) {
			$this->objNodeArray = $this->CollapseNodes($mixParameterArray);
		}
		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
			$intLength = count($this->objNodeArray);
			for ($intIndex = 0; $intIndex < $intLength; $intIndex++)
				$objBuilder->AddGroupByItem($this->objNodeArray[$intIndex]->GetColumnAlias($objBuilder));
		}
		public function __toString() {
			return 'QQGroupBy Clause';
		}
	}


	class QQSelect extends QQClause {
		/** @var QQNode[] */
		protected $arrNodeObj = array();
		protected $blnSkipPrimaryKey = false;

		/**
		 * @param QQNode[] $arrNodeObj
		 * @throws QCallerException
		 */
		public function __construct($arrNodeObj) {
			$this->arrNodeObj = $arrNodeObj;
			foreach ($this->arrNodeObj as $objNode) {
				if (!($objNode instanceof QQColumnNode)) {
					throw new QCallerException('Select nodes must be column nodes.', 3);
				}
			}
		}

		public function UpdateQueryBuilder(QQueryBuilder $objBuilder) {
		}

		public function AddSelectItems(QQueryBuilder $objBuilder, $strTableName, $strAliasPrefix) {
			foreach ($this->arrNodeObj as $objNode) {
				$strNodeTable = $objNode->GetTable();
				if ($strNodeTable == $strTableName) {
					$objBuilder->AddSelectItem($strTableName, $objNode->_Name, $strAliasPrefix . $objNode->_Name);
				}
			}
		}

		public function Merge(QQSelect $objSelect = null) {
			if ($objSelect) {
				foreach ($objSelect->arrNodeObj as $objNode) {
					array_push($this->arrNodeObj, $objNode);
				}
				if ($objSelect->blnSkipPrimaryKey) {
					$this->blnSkipPrimaryKey = true;
				}
			}
		}

		/**
		 * @return boolean
		 */
		public function SkipPrimaryKey() {
			return $this->blnSkipPrimaryKey;
		}

		/**
		 * @param boolean $blnSkipPrimaryKey
		 */
		public function SetSkipPrimaryKey($blnSkipPrimaryKey) {
			$this->blnSkipPrimaryKey = $blnSkipPrimaryKey;
		}

		public function __toString() {
			return 'QQSelectColumn Clause';
		}
	}

	// Users can use the QQuery or the shortcut "QQ"
	class QQuery extends QQ {}

	/**
	 * QQueryBuilder class
	 * @property QDatabaseBase $Database
	 * @property string $RootTableName
	 * @property string[] $ColumnAliasArray
	 * @property QQNode $ExpandAsArrayNode
	 */
	class QQueryBuilder extends QBaseClass {
		/** @var string[]  */
		protected $strSelectArray;
		/** @var string[]  */
		protected $strColumnAliasArray;
		/** @var int  */
		protected $intColumnAliasCount = 0;
		/** @var string[]  */
		protected $strTableAliasArray;
		/** @var int  */
		protected $intTableAliasCount = 0;
		/** @var string[] */
		protected $strFromArray;
		/** @var string[] */
		protected $strJoinArray;
		/** @var string[] */
		protected $strJoinConditionArray;
		/** @var string[] */
		protected $strWhereArray;
		/** @var string[] */
		protected $strOrderByArray;
		/** @var string[] */
		protected $strGroupByArray;
		/** @var string[] */
		protected $strHavingArray;
		/** @var QQVirtualNode[] */
		protected $objVirtualNodeArray;
		/** @var  string */
		protected $strLimitInfo;
		/** @var  bool */
		protected $blnDistinctFlag;
		/** @var  QQNode */
		protected $objExpandAsArrayNode;
		/** @var  bool */
		protected $blnCountOnlyFlag;

		/** @var QDatabaseBase  */
		protected $objDatabase;
		/** @var string  */
		protected $strRootTableName;
		/** @var string  */
		protected $strEscapeIdentifierBegin;
		/** @var string  */
		protected $strEscapeIdentifierEnd;

		/**
		 * @param QDatabaseBase $objDatabase
		 * @param string $strRootTableName
		 */
		public function __construct(QDatabaseBase $objDatabase, $strRootTableName) {
			$this->objDatabase = $objDatabase;
			$this->strEscapeIdentifierBegin = $objDatabase->EscapeIdentifierBegin;
			$this->strEscapeIdentifierEnd = $objDatabase->EscapeIdentifierEnd;
			$this->strRootTableName = $strRootTableName;

			$this->strSelectArray = array();
			$this->strColumnAliasArray = array();
			$this->strTableAliasArray = array();
			$this->strFromArray = array();
			$this->strJoinArray = array();
			$this->strJoinConditionArray = array();
			$this->strWhereArray = array();
			$this->strOrderByArray = array();
			$this->strGroupByArray = array();
			$this->strHavingArray = array();
			$this->objVirtualNodeArray = array();
		}

		/**
		 * @param string $strTableName
		 * @param string $strColumnName
		 * @param string $strFullAlias
		 */
		public function AddSelectItem($strTableName, $strColumnName, $strFullAlias) {
			$strTableAlias = $this->GetTableAlias($strTableName);

			if (!array_key_exists($strFullAlias, $this->strColumnAliasArray)) {
				$strColumnAlias = 'a' . $this->intColumnAliasCount++;
				$this->strColumnAliasArray[$strFullAlias] = $strColumnAlias;
			} else {
				$strColumnAlias = $this->strColumnAliasArray[$strFullAlias];
			}

			$this->strSelectArray[$strFullAlias] = sprintf('%s%s%s.%s%s%s AS %s%s%s',
				$this->strEscapeIdentifierBegin, $strTableAlias, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strColumnName, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strColumnAlias, $this->strEscapeIdentifierEnd);
		}

		/**
		 * @param string $strFunctionName
		 * @param string $strColumnName
		 * @param string $strFullAlias
		 */
		public function AddSelectFunction($strFunctionName, $strColumnName, $strFullAlias) {
			$this->strSelectArray[$strFullAlias] = sprintf('%s(%s) AS %s__%s%s',
				$strFunctionName, $strColumnName,
				$this->strEscapeIdentifierBegin, $strFullAlias, $this->strEscapeIdentifierEnd);
		}

		/**
		 * @param string $strTableName
		 */
		public function AddFromItem($strTableName) {
			$strTableAlias = $this->GetTableAlias($strTableName);

			$this->strFromArray[$strTableName] = sprintf('%s%s%s AS %s%s%s',
				$this->strEscapeIdentifierBegin, $strTableName, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strTableAlias, $this->strEscapeIdentifierEnd);
		}

		/**
		 * @param string $strTableName
		 * @return string
		 */
		public function GetTableAlias($strTableName) {
			if (!array_key_exists($strTableName, $this->strTableAliasArray)) {
				$strTableAlias = 't' . $this->intTableAliasCount++;
				$this->strTableAliasArray[$strTableName] = $strTableAlias;
				return $strTableAlias;
			} else {
				return $this->strTableAliasArray[$strTableName];
			}
		}

		/**
		 * @param string $strJoinTableName
		 * @param  string $strJoinTableAlias
		 * @param  string $strTableName
		 * @param  string $strColumnName
		 * @param  string $strLinkedColumnName
		 * @param QQCondition|null $objJoinCondition
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function AddJoinItem($strJoinTableName, $strJoinTableAlias, $strTableName, $strColumnName, $strLinkedColumnName, QQCondition $objJoinCondition = null) {
			$strJoinItem = sprintf('LEFT JOIN %s%s%s AS %s%s%s ON %s%s%s.%s%s%s = %s%s%s.%s%s%s',
				$this->strEscapeIdentifierBegin, $strJoinTableName, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd,

				$this->strEscapeIdentifierBegin, $this->GetTableAlias($strTableName), $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strColumnName, $this->strEscapeIdentifierEnd,

				$this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $strLinkedColumnName, $this->strEscapeIdentifierEnd);

				$strJoinIndex = $strJoinItem;
			try {
				$strConditionClause = null;
				if ($objJoinCondition &&
					($strConditionClause = $objJoinCondition->GetWhereClause($this, false)))
					$strJoinItem .= ' AND ' . $strConditionClause;
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			/* If this table has already been joined, then we need to check for the following:
				1. Condition wasn't specified before and we aren't specifying one now
					Do Nothing --b/c nothing was changed or updated
				2. Condition wasn't specified before but we ARE specifying one now
					Update the indexed item in the joinArray with the new JoinItem WITH Condition
				3. Condition WAS specified before but we aren't specifying one now
					Do Nothing -- we need to keep the old condition intact
				4. Condition WAS specified before and we are specifying the SAME one now
					Do Nothing --b/c nothing was changed or updated
				5. Condition WAS specified before and we are specifying a DIFFERENT one now
					Throw exception
			*/
			if (array_key_exists($strJoinIndex, $this->strJoinArray)) {
				// Case 1 and 2
				if (!array_key_exists($strJoinIndex, $this->strJoinConditionArray)) {

					// Case 1
					if (!$strConditionClause) {
						return;

					// Case 2
					} else {
						$this->strJoinArray[$strJoinIndex] = $strJoinItem;
						$this->strJoinConditionArray[$strJoinIndex] = $strConditionClause;
						return;
					}
				}

				// Case 3
				if (!$strConditionClause)
					return;

				// Case 4
				if ($strConditionClause == $this->strJoinConditionArray[$strJoinIndex])
					return;

				// Case 5
				throw new QCallerException('You have two different Join Conditions on the same Expanded Table: ' . $strJoinIndex . "\r\n[" . $this->strJoinConditionArray[$strJoinIndex] . ']   vs.   [' . $strConditionClause . ']');
			}

			// Create the new JoinItem in the JoinArray
			$this->strJoinArray[$strJoinIndex] = $strJoinItem;

			// If there is a condition, record that condition against this JoinIndex
			if ($strConditionClause)
				$this->strJoinConditionArray[$strJoinIndex] = $strConditionClause;
		}

		/**
		 * @param  string $strJoinTableName
		 * @param  string $strJoinTableAlias
		 * @param QQCondition $objJoinCondition
		 * @throws Exception
		 * @throws QCallerException
		 */
		public function AddJoinCustomItem($strJoinTableName, $strJoinTableAlias, QQCondition $objJoinCondition) {
			$strJoinItem = sprintf('LEFT JOIN %s%s%s AS %s%s%s ON ',
				$this->strEscapeIdentifierBegin, $strJoinTableName, $this->strEscapeIdentifierEnd,
				$this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd
			);

			$strJoinIndex = $strJoinItem;

			try {
				if (($strConditionClause = $objJoinCondition->GetWhereClause($this, true)))
					$strJoinItem .= ' AND ' . $strConditionClause;
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->strJoinArray[$strJoinIndex] = $strJoinItem;
		}

		/**
		 * @param  string $strSql
		 */
		public function AddJoinCustomSqlItem($strSql) {
			$this->strJoinArray[$strSql] = $strSql;
		}

		/**
		 * @param  string $strItem
		 */
		public function AddWhereItem($strItem) {
			array_push($this->strWhereArray, $strItem);
		}

		/**
		 * @param  string $strItem
		 */
		public function AddOrderByItem($strItem) {
			array_push($this->strOrderByArray, $strItem);
		}

		/**
		 * @param  string $strItem
		 */
		public function AddGroupByItem($strItem) {
			array_push($this->strGroupByArray, $strItem);
		}

		/**
		 * @param  string $strItem
		 */
		public function AddHavingItem ($strItem) {
			array_push($this->strHavingArray, $strItem);
		}

		/**
		 * @param $strLimitInfo
		 */
		public function SetLimitInfo($strLimitInfo) {
			$this->strLimitInfo = $strLimitInfo;
		}

		public function SetDistinctFlag() {
			$this->blnDistinctFlag = true;
		}

		public function SetCountOnlyFlag() {
			$this->blnCountOnlyFlag = true;
		}

		/**
		 * @param string $strName
		 * @param QQSubQueryNode $objNode
		 */
		public function SetVirtualNode($strName, QQSubQueryNode $objNode) {
			$this->objVirtualNodeArray[trim(strtolower($strName))] = $objNode;
		}

		/**
		 * @param string $strName
		 * @return QQVirtualNode
		 * @throws QCallerException
		 */
		public function GetVirtualNode($strName) {
			$strName = trim(strtolower($strName));
			if (array_key_exists($strName, $this->objVirtualNodeArray))
				return $this->objVirtualNodeArray[$strName];
			else throw new QCallerException('Undefined Virtual Node: ' . $strName);
		}

		/**
		 * @param QQNode $objNode
		 * @throws QCallerException
		 */
		public function AddExpandAsArrayNode(QQNode $objNode) {
			/** @var QQReverseReferenceNode|QQAssociationNode $objNode */
			// build child nodes and find top node of given node
			$objNode->ExpandAsArray = true;
			while ($objNode->_ParentNode) {
				$objNode = $objNode->_ParentNode;
			}

			if (!$this->objExpandAsArrayNode) {
				$this->objExpandAsArrayNode = $objNode;
			}
			else {
				// integrate the information into current nodes
				$this->objExpandAsArrayNode->_MergeExpansionNode ($objNode);
			}
		}

		/**
		 * @return string
		 */
		public function GetStatement() {
			// SELECT Clause
			if ($this->blnCountOnlyFlag) {
				if ($this->blnDistinctFlag) {
					$strSql = "SELECT\r\n    COUNT(*) AS q_row_count\r\n" .
						"FROM    (SELECT DISTINCT ";
					$strSql .= "    " . implode(",\r\n    ", $this->strSelectArray);
				} else
					$strSql = "SELECT\r\n    COUNT(*) AS q_row_count\r\n";
			} else {
				if ($this->blnDistinctFlag)
					$strSql = "SELECT DISTINCT\r\n";
				else
					$strSql = "SELECT\r\n";
				if ($this->strLimitInfo)
					$strSql .= $this->objDatabase->SqlLimitVariablePrefix($this->strLimitInfo) . "\r\n";
				$strSql .= "    " . implode(",\r\n    ", $this->strSelectArray);
			}

			// FROM and JOIN Clauses
			$strSql .= sprintf("\r\nFROM\r\n    %s\r\n    %s",
				implode(",\r\n    ", $this->strFromArray),
				implode("\r\n    ", $this->strJoinArray));

			// WHERE Clause
			if (count($this->strWhereArray)) {
				$strWhere = implode("\r\n    ", $this->strWhereArray);
				if (trim($strWhere) != '1=1')
					$strSql .= "\r\nWHERE\r\n    " . $strWhere;
			}

			// Additional Ordering/Grouping/Having clauses
			if (count($this->strGroupByArray))
				$strSql .= "\r\nGROUP BY\r\n    " . implode(",\r\n    ", $this->strGroupByArray);
			if (count($this->strHavingArray)) {
				$strHaving = implode("\r\n    ", $this->strHavingArray);
				$strSql .= "\r\nHaving\r\n    " . $strHaving;
			}
			if (count($this->strOrderByArray))
				$strSql .= "\r\nORDER BY\r\n    " . implode(",\r\n    ", $this->strOrderByArray);

			// Limit Suffix (if applicable)
			if ($this->strLimitInfo)
				$strSql .= "\r\n" . $this->objDatabase->SqlLimitVariableSuffix($this->strLimitInfo);

			// For Distinct Count Queries
			if ($this->blnCountOnlyFlag && $this->blnDistinctFlag)
				$strSql .= "\r\n) as q_count_table";

			return $strSql;
		}



		public function __get($strName) {
			switch ($strName) {
				case 'Database':
					return $this->objDatabase;
				case 'RootTableName':
					return $this->strRootTableName;
				case 'ColumnAliasArray':
					return $this->strColumnAliasArray;
				case 'ExpandAsArrayNode':
					return $this->objExpandAsArrayNode;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	/**
	 * 	Subclasses QQueryBuilder to handle the building of conditions for conditional expansions, subqueries, etc.
	 * 	Since regular queries use WhereClauses for conditions, we just use the where clause portion, and
	 * 	only build a condition clause appropriate for a conditional expansion.
	 */
	class QPartialQueryBuilder extends QQueryBuilder {
		protected $objParentBuilder;

		/**
		 * @param QQueryBuilder $objBuilder
		 */
		public function __construct(QQueryBuilder $objBuilder) {
			parent::__construct($objBuilder->objDatabase, $objBuilder->strRootTableName);
			$this->objParentBuilder = $objBuilder;
			$this->strColumnAliasArray = &$objBuilder->strColumnAliasArray;
			$this->strTableAliasArray = &$objBuilder->strTableAliasArray;
		}

		/**
		 * @return string
		 */
		public function GetWhereStatement() {
			return implode(' ', $this->strWhereArray);
		}

		/**
		 * @return string
		 */
		public function GetFromStatement() {
			return implode(' ', $this->strFromArray) . ' ' . implode(' ', $this->strJoinArray);
		}
	}
?>
