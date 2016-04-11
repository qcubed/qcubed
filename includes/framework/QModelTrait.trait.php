<?php

/**
 * Class QModelTrait
 *
 * This trait class is a mixin helper for all the generated Model classes. It works together with the code generator
 * to create particular functions that are common to all the classes. For historical reasons, and to prevent problems
 * with polymorhpism, this is a trait and not a base class.
 */
trait QModelTrait {

	/*** Requirements of Model classes ***/

	/*
	 * The generated model classes must implement the following functions and members.
	 */

	/**
	 * Returns the value of the primary key for this object. If a composite primary key, this should return a string representation
	 * of the combined keys such that the combination will be unique.
	 * @return integer|string
	 */
	// protected function PrimaryKey();

	/**
	 * A helper function to get the primary key associated with this object type from a query result row.
	 *
	 * @param QDatabaseRowBase $objDbRow
	 * @param string $strAliasPrefix	Prefix to use if this is a row expansion (as in, a join)
	 * @param string[] $strColumnAliasArray Array of column aliases associateing our column names with the minimized names in the query.
	 * @return mixed The primary key found in the row
	 */
	// protected static function GetRowPrimaryKey($objDbRow, $strAliasPrefix, $strColumnAliasArray){}

	/**
	 * Return the database object associated with this object.
	 *
	 * @return QDatabaseBase
	 */
	// public static function GetDatabase(){}

	/**
	 * Return the name of the database table associated with this object.
	 *
	 * @return string
	 */
	// public static function GetTableName(){}

	/**
	 * Add select fields to the query as part of the query building process. The superclass should override this to add the necessary fields
	 * to the query builder object. The default is to add all the fields in the object.
	 *
	 * @param QQueryBuilder $objBuilder
	 * @param string|null $strPrefix	optional prefix to be used if this is an extended query (as in, a join)
	 * @param QQSelect|null $objSelect optional QQSelect clause to select specific fields, rather than the entire set of fields in the object
	 */
	//public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null, QQSelect $objSelect = null){}


	/***  Implementation ***/

	/**
	 * Takes a query builder object and outputs the sql query that corresponds to its structure and the given parameters.
	 *
	 * @param QQueryBuilder &$objQueryBuilder the QueryBuilder object that will be created
	 * @param QQCondition $objConditions any conditions on the query, itself
	 * @param QQClause[] $objOptionalClauses additional optional QQClause object or array of QQClause objects for this query
	 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with (sending in null will skip the PrepareStatement step)
	 * @param boolean $blnCountOnly only select a rowcount
	 * @return string the query statement
	 * @throws QCallerException
	 * @throws Exception
	 */
	protected static function BuildQueryStatement(&$objQueryBuilder, QQCondition $objConditions, $objOptionalClauses, $mixParameterArray, $blnCountOnly) {
		// Get the Database Object for this Class
		$objDatabase = static::GetDatabase();
		$strTableName = static::GetTableName();

		// Create/Build out the QueryBuilder object with class-specific SELECT and FROM fields
		$objQueryBuilder = new QQueryBuilder($objDatabase, $strTableName);

		$blnAddAllFieldsToSelect = true;
		if ($objDatabase->OnlyFullGroupBy) {
			// see if we have any group by or aggregation clauses, if yes, don't add the fields to select clause
			if ($objOptionalClauses instanceof QQClause) {
				if ($objOptionalClauses instanceof QQAggregationClause || $objOptionalClauses instanceof QQGroupBy) {
					$blnAddAllFieldsToSelect = false;
				}
			} else if (is_array($objOptionalClauses)) {
				foreach ($objOptionalClauses as $objClause) {
					if ($objClause instanceof QQAggregationClause || $objClause instanceof QQGroupBy) {
						$blnAddAllFieldsToSelect = false;
						break;
					}
				}
			}
		}
		if ($blnAddAllFieldsToSelect) {
			static::BaseNode()->PutSelectFields($objQueryBuilder, null, QQuery::extractSelectClause($objOptionalClauses));
		}
		$objQueryBuilder->AddFromItem($strTableName);

		// Set "CountOnly" option (if applicable)
		if ($blnCountOnly)
			$objQueryBuilder->SetCountOnlyFlag();

		// Apply Any Conditions
		if ($objConditions)
			try {
				$objConditions->UpdateQueryBuilder($objQueryBuilder);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				$objExc->IncrementOffset();
				throw $objExc;
			}

		// Iterate through all the Optional Clauses (if any) and perform accordingly
		if ($objOptionalClauses) {
			if ($objOptionalClauses instanceof QQClause) {
				try {
					$objOptionalClauses->UpdateQueryBuilder($objQueryBuilder);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}

			}
			else if (is_array($objOptionalClauses)) {
				foreach ($objOptionalClauses as $objClause) {
					try {
						$objClause->UpdateQueryBuilder($objQueryBuilder);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						$objExc->IncrementOffset();
						throw $objExc;
					}

				}
			}
			else
				throw new QCallerException('Optional Clauses must be a QQClause object or an array of QQClause objects');
		}

		// Get the SQL Statement
		$strQuery = $objQueryBuilder->GetStatement();

		// Substitute the correct sql variable names for the placeholders specified in the query, if any.
		if ($mixParameterArray) {
			if (is_array($mixParameterArray)) {
				if (count($mixParameterArray))
					$strQuery = $objDatabase->PrepareStatement($strQuery, $mixParameterArray);

				// Ensure that there are no other Unresolved Named Parameters
				if (strpos($strQuery, chr(QQNamedValue::DelimiterCode) . '{') !== false)
					throw new QCallerException('Unresolved named parameters in the query');
			} else
				throw new QCallerException('Parameter Array must be an array of name-value parameter pairs');
		}

		// Return the Objects
		return $strQuery;
	}

	/**
	 * Static Qcubed Query method to query for a single <?php echo $objTable->ClassName  ?> object.
	 * Uses BuildQueryStatment to perform most of the work.
	 * @param QQCondition $objConditions any conditions on the query, itself
	 * @param null $objOptionalClauses
	 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
	 * @throws Exception
	 * @throws QCallerException
	 * @return null|QModelBase the queried object
	 */
	public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
		// Get the Query Statement
		try {
			$strQuery = static::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Perform the Query, Get the First Row, and Instantiate a new object
		$objDbResult = $objQueryBuilder->Database->Query($strQuery);

		// Do we have to expand anything?
		if ($objQueryBuilder->ExpandAsArrayNode) {
			$objToReturn = array();
			$objPrevItemArray = array();
			while ($objDbRow = $objDbResult->GetNextRow()) {
				$objItem = static::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNode, $objPrevItemArray, $objQueryBuilder->ColumnAliasArray);
				if ($objItem) {
					$objToReturn[] = $objItem;
					$pk = $objItem->PrimaryKey();
					if ($pk) {
						$objPrevItemArray[$pk][] = $objItem;
					} else {
						$objPrevItemArray[] = $objItem;
					}
				}
			}
			if (count($objToReturn)) {
				// Since we only want the object to return, lets return the object and not the array.
				return $objToReturn[0];
			} else {
				return null;
			}
		} else {
			// No expands just return the first row
			$objDbRow = $objDbResult->GetNextRow();
			if(null === $objDbRow)
				return null;
			return static::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
		}
	}

	/**
	 * Static Qcubed Query method to query for an array of objects.
	 * Uses BuildQueryStatment to perform most of the work.
	 *
	 * @param QQCondition $objConditions any conditions on the query, itself
	 * @param QQClause[]|null $objOptionalClauses additional optional QQClause objects for this query
	 * @param mixed[]|null $mixParameterArray an array of name-value pairs to substitute in to the placeholders in the query, if needed
	 * @return mixed[] an array of objects
	 * @throws Exception
	 * @throws QCallerException
	 */
	public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
		// Get the Query Statement
		try {
			$strQuery = static::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Perform the Query and Instantiate the Array Result
		$objDbResult = $objQueryBuilder->Database->Query($strQuery);
		return static::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
	}

	/**
	 * Static Qcubed query method to issue a query and get a cursor to progressively fetch its results.
	 * Uses BuildQueryStatment to perform most of the work.
	 *
	 * @param QQCondition $objConditions any conditions on the query, itself
	 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
	 * @param mixed[] $mixParameterArray an array of name-value pairs to substitute in to the placeholders in the query, if needed
	 * @return QDatabaseResultBase the cursor resource instance
	 * @throws Exception
	 * @throws QCallerException
	 */
	public static function QueryCursor(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
		// Get the query statement
		try {
			$strQuery = static::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Perform the query
		$objDbResult = $objQueryBuilder->Database->Query($strQuery);

		// Return the results cursor
		$objDbResult->QueryBuilder = $objQueryBuilder;
		return $objDbResult;
	}

	/**
	 * Static Qcubed Query method to query for a count of objects.
	 * Uses BuildQueryStatment to perform most of the work.
	 *
	 * @param QQCondition $objConditions any conditions on the query, itself
	 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
	 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
	 * @return integer the count of queried objects as an integer
	 */
	public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
		// Get the Query Statement
		try {
			$strQuery = static::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
		} catch (QCallerException $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}

		// Perform the Query and return the row_count
		$objDbResult = $objQueryBuilder->Database->Query($strQuery);

		// Figure out if the query is using GroupBy
		$blnGrouped = false;

		if ($objOptionalClauses) {
			if ($objOptionalClauses instanceof QQClause) {
				if ($objOptionalClauses instanceof QQGroupBy) {
					$blnGrouped = true;
				}
			} else if (is_array($objOptionalClauses)) {
				foreach ($objOptionalClauses as $objClause) {
					if ($objClause instanceof QQGroupBy) {
						$blnGrouped = true;
						break;
					}
				}
			} else {
				throw new QCallerException('Optional Clauses must be a QQClause object or an array of QQClause objects');
			}
		}

		if ($blnGrouped)
			// Groups in this query - return the count of Groups (which is the count of all rows)
			return $objDbResult->CountRows();
		else {
			// No Groups - return the sql-calculated count(*) value
			$strDbRow = $objDbResult->FetchRow();
			return (integer)$strDbRow[0];
		}
	}

	public static function QueryArrayCached(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null, $blnForceUpdate = false) {
		$strQuery = static::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

		$strTableName = static::GetTableName();
		$objCache = new QCache(sprintf('qquery/%s', $strTableName), $strQuery);
		$cacheData = $objCache->GetData();

		if (!$cacheData || $blnForceUpdate) {
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			$arrResult = static::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNode, $objQueryBuilder->ColumnAliasArray);
			$objCache->SaveData(serialize($arrResult));
		} else {
			$arrResult = unserialize($cacheData);
		}

		return $arrResult;
	}

	/**
	 * Do a possible array expansion on the given node. If the node is an ExpandAsArray node,
	 * it will add to the corresponding array in the object. Otherwise, it will follow the node
	 * so that any leaf expansions can be handled.
	 *
	 * @param QDatabaseRowBase $objDbRow
	 * @param string $strAliasPrefix
	 * @param QQNode $objNode
	 * @param QModelBase[] $objPreviousItemArray
	 * @param string[] $strColumnAliasArray
	 * @return boolean|null Returns true if the we used the row for an expansion, false if we already expanded this node in a previous row, or null if no expansion data was found
	 */
	public static function ExpandArray ($objDbRow, $strAliasPrefix, $objNode, $objPreviousItemArray, $strColumnAliasArray) {
		if (!$objNode->ChildNodeArray) {
			return null;
		}
		$blnExpanded = null;

		$pk = static::GetRowPrimaryKey ($objDbRow, $strAliasPrefix, $strColumnAliasArray);

		foreach ($objPreviousItemArray as $objPreviousItem) {
			if ($pk != $objPreviousItem->PrimaryKey()) {
				continue;
			}

			foreach ($objNode->ChildNodeArray as $objChildNode) {
				$strPropName = $objChildNode->_PropertyName;
				$strClassName = $objChildNode->_ClassName;
				$strLongAlias = $objChildNode->FullAlias();
				$blnExpandAsArray = false;

				if ($objChildNode->ExpandAsArray) {
					$strPostfix = 'Array';
					$blnExpandAsArray = true;
				} else {
					$strPostfix = '';
				}
				$nodeType = $objChildNode->_Type;
				if ($nodeType == 'reverse_reference') {
					$strPrefix = '_obj';
				} elseif ($nodeType == 'association') {
					$objChildNode = $objChildNode->FirstChild();
					if ($objChildNode->IsType) {
						$strPrefix = '_int';
					} else {
						$strPrefix = '_obj';
					}
				} else {
					$strPrefix = 'obj';
				}

				$strVarName = $strPrefix . $strPropName . $strPostfix;

				if ($blnExpandAsArray) {
					if (null === $objPreviousItem->$strVarName) {
						$objPreviousItem->$strVarName = array();
					}
					if (count($objPreviousItem->$strVarName)) {
						$objPreviousChildItems = $objPreviousItem->$strVarName;
						$nextAlias = $objChildNode->FullAlias() . '__';

						$objChildItem = $strClassName::InstantiateDbRow ($objDbRow, $nextAlias, $objChildNode, $objPreviousChildItems, $strColumnAliasArray, true);

						if ($objChildItem) {
							$objPreviousItem->{$strVarName}[] = $objChildItem;
							$blnExpanded = true;
						} elseif ($objChildItem === false) {
							$blnExpanded = true;
						}
					}
				} elseif (!$objChildNode->IsType) {

					// Follow single node if keys match
					if (null === $objPreviousItem->$strVarName) {
						return false;
					}
					$objPreviousChildItems = array($objPreviousItem->$strVarName);
					$blnResult = $strClassName::ExpandArray ($objDbRow, $strLongAlias . '__', $objChildNode, $objPreviousChildItems, $strColumnAliasArray);

					if ($blnResult) {
						$blnExpanded = true;
					}
				}
			}
		}
		return $blnExpanded;
	}

	/**
	 * Return an object corresponding to the given key, or null.
	 *
	 * The key might be null if:
	 * 	The table has no primary key, or
	 *  SetSkipPrimaryKey was used in a query with QSelect.
	 *
	 * Otherwise, the default here is to use the local cache.
	 *
	 * Note that you rarely would want to change this. Caching at this level happens
	 * after a query has executed. Using a cache like APC or MemCache at this point would
	 * be really expensive, and would only be worth it for a large table.
	 *
	 * @param $key
	 * @return null|object
	 */
	public static function GetFromCache($key) {
		if ($key===null) return null;
		if (QApplication::$blnLocalCache === true && !empty(static::$objCacheArray[$key])) {
			return clone(static::$objCacheArray[$key]);
		}
		elseif (QApplication::$objCacheProvider) {
			$strCacheKey = QApplication::$objCacheProvider->CreateKey(static::GetDatabase()->Database, __CLASS__, $key);
			return QApplication::$objCacheProvider->Get($strCacheKey);
		}
		return null;
	}

	/**
	 * Put the current object in the cache for future reference.
	 */
	public function WriteToCache() {
		$key = $this->PrimaryKey();
		if ($key === null) return;
		$obj = clone($this);
		if (QApplication::$blnLocalCache === true) static::$objCacheArray[$key] = $obj;
		if (QApplication::$objCacheProvider) {
			$strCacheKey = QApplication::$objCacheProvider->CreateKey(static::GetDatabase()->Database, __CLASS__, $key);
			QApplication::$objCacheProvider->Set($strCacheKey, $obj);
		}
	}

	/**
	 * Delete this particular object from the cache
	 * @return void
	 */
	public function DeleteFromCache() {
		$key = $this->PrimaryKey();
		if ($key === null) return;
		unset (static::$objCacheArray[$key]);
		if (QApplication::$objCacheProvider) {
			$strCacheKey = QApplication::$objCacheProvider->CreateKey(static::GetDatabase()->Database, __CLASS__, $key);
			QApplication::$objCacheProvider->Delete($strCacheKey);
		}
	}


	/**
	 * Clears the caches associated with this table.
	 */
	public static function ClearCache() {
		static::$objCacheArray = array();
		if (QApplication::$objCacheProvider) {
			QApplication::$objCacheProvider->DeleteAll();
		}
	}

} 