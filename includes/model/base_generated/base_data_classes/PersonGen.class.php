<?php
	/**
	 * The abstract PersonGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Person subclass which
	 * extends this PersonGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Person class.
	 * 
	 * @package My Application
	 * @subpackage GeneratedDataObjects
	 * @property-read integer $Id the value for intId (Read-Only PK)
	 * @property string $FirstName the value for strFirstName (Not Null)
	 * @property string $LastName the value for strLastName (Not Null)
	 * @property Login $Login the value for the Login object that uniquely references this Person
	 * @property-read Project $_ProjectAsTeamMember the value for the private _objProjectAsTeamMember (Read-Only) if set due to an expansion on the team_member_project_assn association table
	 * @property-read Project[] $_ProjectAsTeamMemberArray the value for the private _objProjectAsTeamMemberArray (Read-Only) if set due to an ExpandAsArray on the team_member_project_assn association table
	 * @property-read Address $_Address the value for the private _objAddress (Read-Only) if set due to an expansion on the address.person_id reverse relationship
	 * @property-read Address[] $_AddressArray the value for the private _objAddressArray (Read-Only) if set due to an ExpandAsArray on the address.person_id reverse relationship
	 * @property-read Project $_ProjectAsManager the value for the private _objProjectAsManager (Read-Only) if set due to an expansion on the project.manager_person_id reverse relationship
	 * @property-read Project[] $_ProjectAsManagerArray the value for the private _objProjectAsManagerArray (Read-Only) if set due to an ExpandAsArray on the project.manager_person_id reverse relationship
	 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class PersonGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column person.id
		 * @var integer intId
		 */
		protected $intId;
		const IdDefault = null;


		/**
		 * Protected member variable that maps to the database column person.first_name
		 * @var string strFirstName
		 */
		protected $strFirstName;
		const FirstNameMaxLength = 50;
		const FirstNameDefault = null;


		/**
		 * Protected member variable that maps to the database column person.last_name
		 * @var string strLastName
		 */
		protected $strLastName;
		const LastNameMaxLength = 50;
		const LastNameDefault = null;


		/**
		 * Private member variable that stores a reference to a single ProjectAsTeamMember object
		 * (of type Project), if this Person object was restored with
		 * an expansion on the team_member_project_assn association table.
		 * @var Project _objProjectAsTeamMember;
		 */
		private $_objProjectAsTeamMember;

		/**
		 * Private member variable that stores a reference to an array of ProjectAsTeamMember objects
		 * (of type Project[]), if this Person object was restored with
		 * an ExpandAsArray on the team_member_project_assn association table.
		 * @var Project[] _objProjectAsTeamMemberArray;
		 */
		private $_objProjectAsTeamMemberArray = array();

		/**
		 * Private member variable that stores a reference to a single Address object
		 * (of type Address), if this Person object was restored with
		 * an expansion on the address association table.
		 * @var Address _objAddress;
		 */
		private $_objAddress;

		/**
		 * Private member variable that stores a reference to an array of Address objects
		 * (of type Address[]), if this Person object was restored with
		 * an ExpandAsArray on the address association table.
		 * @var Address[] _objAddressArray;
		 */
		private $_objAddressArray = array();

		/**
		 * Private member variable that stores a reference to a single ProjectAsManager object
		 * (of type Project), if this Person object was restored with
		 * an expansion on the project association table.
		 * @var Project _objProjectAsManager;
		 */
		private $_objProjectAsManager;

		/**
		 * Private member variable that stores a reference to an array of ProjectAsManager objects
		 * (of type Project[]), if this Person object was restored with
		 * an ExpandAsArray on the project association table.
		 * @var Project[] _objProjectAsManagerArray;
		 */
		private $_objProjectAsManagerArray = array();

		/**
		 * Protected array of virtual attributes for this object (e.g. extra/other calculated and/or non-object bound
		 * columns from the run-time database query result for this object).  Used by InstantiateDbRow and
		 * GetVirtualAttribute.
		 * @var string[] $__strVirtualAttributeArray
		 */
		protected $__strVirtualAttributeArray = array();

		/**
		 * Protected internal member variable that specifies whether or not this object is Restored from the database.
		 * Used by Save() to determine if Save() should perform a db UPDATE or INSERT.
		 * @var bool __blnRestored;
		 */
		protected $__blnRestored;




		///////////////////////////////
		// PROTECTED MEMBER OBJECTS
		///////////////////////////////

		/**
		 * Protected member variable that contains the object which points to
		 * this object by the reference in the unique database column login.person_id.
		 *
		 * NOTE: Always use the Login property getter to correctly retrieve this Login object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Login objLogin
		 */
		protected $objLogin;
		
		/**
		 * Used internally to manage whether the adjoined Login object
		 * needs to be updated on save.
		 * 
		 * NOTE: Do not manually update this value 
		 */
		protected $blnDirtyLogin;





		///////////////////////////////
		// CLASS-WIDE LOAD AND COUNT METHODS
		///////////////////////////////

		/**
		 * Static method to retrieve the Database object that owns this class.
		 * @return QDatabaseBase reference to the Database object that can query this class
		 */
		public static function GetDatabase() {
			return QApplication::$Database[1];
		}

		/**
		 * Load a Person from PK Info
		 * @param integer $intId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Person
		 */
		public static function Load($intId, $objOptionalClauses = null) {
			// Use QuerySingle to Perform the Query
			return Person::QuerySingle(
				QQ::AndCondition(
					QQ::Equal(QQN::Person()->Id, $intId)
				),
				$objOptionalClauses
			);
		}

		/**
		 * Load all People
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Person[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			if (func_num_args() > 1) {
				throw new QCallerException("LoadAll must be called with an array of optional clauses as a single argument");
			}
			// Call Person::QueryArray to perform the LoadAll query
			try {
				return Person::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all People
		 * @return int
		 */
		public static function CountAll() {
			// Call Person::QueryCount to perform the CountAll query
			return Person::QueryCount(QQ::All());
		}




		///////////////////////////////
		// QCUBED QUERY-RELATED METHODS
		///////////////////////////////

		/**
		 * Internally called method to assist with calling Qcubed Query for this class
		 * on load methods.
		 * @param QQueryBuilder &$objQueryBuilder the QueryBuilder object that will be created
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause object or array of QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with (sending in null will skip the PrepareStatement step)
		 * @param boolean $blnCountOnly only select a rowcount
		 * @return string the query statement
		 */
		protected static function BuildQueryStatement(&$objQueryBuilder, QQCondition $objConditions, $objOptionalClauses, $mixParameterArray, $blnCountOnly) {
			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Create/Build out the QueryBuilder object with Person-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'person');
			Person::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('person');

			// Set "CountOnly" option (if applicable)
			if ($blnCountOnly)
				$objQueryBuilder->SetCountOnlyFlag();

			// Apply Any Conditions
			if ($objConditions)
				try {
					$objConditions->UpdateQueryBuilder($objQueryBuilder);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			// Iterate through all the Optional Clauses (if any) and perform accordingly
			if ($objOptionalClauses) {
				if ($objOptionalClauses instanceof QQClause)
					$objOptionalClauses->UpdateQueryBuilder($objQueryBuilder);
				else if (is_array($objOptionalClauses))
					foreach ($objOptionalClauses as $objClause)
						$objClause->UpdateQueryBuilder($objQueryBuilder);
				else
					throw new QCallerException('Optional Clauses must be a QQClause object or an array of QQClause objects');
			}

			// Get the SQL Statement
			$strQuery = $objQueryBuilder->GetStatement();

			// Prepare the Statement with the Query Parameters (if applicable)
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
		 * Static Qcubed Query method to query for a single Person object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Person the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Person::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			
			// Perform the Query, Get the First Row, and Instantiate a new Person object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			
			// Do we have to expand anything?
			if ($objQueryBuilder->ExpandAsArrayNodes) {
				$objToReturn = array();
				while ($objDbRow = $objDbResult->GetNextRow()) {
					$objItem = Person::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNodes, $objToReturn, $objQueryBuilder->ColumnAliasArray);
					if ($objItem)
						$objToReturn[] = $objItem;					
				}			
				// Since we only want the object to return, lets return the object and not the array.
				return $objToReturn[0];
			} else {
				// No expands just return the first row
				$objToReturn = null;
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn = Person::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
			}
			
			return $objToReturn;
		}

		/**
		 * Static Qcubed Query method to query for an array of Person objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Person[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Person::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Person::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcubed Query method to query for a count of Person objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Person::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and return the row_count
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);

			// Figure out if the query is using GroupBy
			$blnGrouped = false;

			if ($objOptionalClauses) foreach ($objOptionalClauses as $objClause) {
				if ($objClause instanceof QQGroupBy) {
					$blnGrouped = true;
					break;
				}
			}

			if ($blnGrouped)
				// Groups in this query - return the count of Groups (which is the count of all rows)
				return $objDbResult->CountRows();
			else {
				// No Groups - return the sql-calculated count(*) value
				$strDbRow = $objDbResult->FetchRow();
				return QType::Cast($strDbRow[0], QType::Integer);
			}
		}

		public static function QueryArrayCached(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			$strQuery = Person::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			
			$objCache = new QCache('qquery/person', $strQuery);
			$cacheData = $objCache->GetData();
			
			if (!$cacheData || $blnForceUpdate) {
				$objDbResult = $objQueryBuilder->Database->Query($strQuery);
				$arrResult = Person::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
				$objCache->SaveData(serialize($arrResult));
			} else {
				$arrResult = unserialize($cacheData);
			}
			
			return $arrResult;
		}

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Person
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'person';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'id', $strAliasPrefix . 'id');
			$objBuilder->AddSelectItem($strTableName, 'first_name', $strAliasPrefix . 'first_name');
			$objBuilder->AddSelectItem($strTableName, 'last_name', $strAliasPrefix . 'last_name');
		}



		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Person from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Person::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $arrPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Person
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $arrPreviousItems = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow) {
				return null;
			}
			// See if we're doing an array expansion on the previous item
			$strAlias = $strAliasPrefix . 'id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (($strExpandAsArrayNodes) && is_array($arrPreviousItems) && count($arrPreviousItems)) {
				foreach ($arrPreviousItems as $objPreviousItem) {            
					if ($objPreviousItem->intId == $objDbRow->GetColumn($strAliasName, 'Integer')) {        
						// We are.  Now, prepare to check for ExpandAsArray clauses
						$blnExpandedViaArray = false;
						if (!$strAliasPrefix)
							$strAliasPrefix = 'person__';

						// Expanding many-to-many references: ProjectAsTeamMember
						$strAlias = $strAliasPrefix . 'projectasteammember__project_id__id';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if ($intPreviousChildItemCount = count($objPreviousItem->_objProjectAsTeamMemberArray)) {
								$objPreviousChildItems = $objPreviousItem->_objProjectAsTeamMemberArray;
								$objChildItem = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasteammember__project_id__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_objProjectAsTeamMemberArray[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_objProjectAsTeamMemberArray[] = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasteammember__project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}


						// Expanding reverse references: Address
						$strAlias = $strAliasPrefix . 'address__id';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if ($intPreviousChildItemCount = count($objPreviousItem->_objAddressArray)) {
								$objPreviousChildItems = $objPreviousItem->_objAddressArray;
								$objChildItem = Address::InstantiateDbRow($objDbRow, $strAliasPrefix . 'address__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_objAddressArray[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_objAddressArray[] = Address::InstantiateDbRow($objDbRow, $strAliasPrefix . 'address__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

						// Expanding reverse references: ProjectAsManager
						$strAlias = $strAliasPrefix . 'projectasmanager__id';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if ($intPreviousChildItemCount = count($objPreviousItem->_objProjectAsManagerArray)) {
								$objPreviousChildItems = $objPreviousItem->_objProjectAsManagerArray;
								$objChildItem = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasmanager__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_objProjectAsManagerArray[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_objProjectAsManagerArray[] = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasmanager__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

						// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
						if ($blnExpandedViaArray) {
							return false;
						} else if ($strAliasPrefix == 'person__') {
							$strAliasPrefix = null;
						}
					}
				}
			}

			// Create a new instance of the Person object
			$objToReturn = new Person();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'id'] : $strAliasPrefix . 'id';
			$objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'first_name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'first_name'] : $strAliasPrefix . 'first_name';
			$objToReturn->strFirstName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'last_name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'last_name'] : $strAliasPrefix . 'last_name';
			$objToReturn->strLastName = $objDbRow->GetColumn($strAliasName, 'VarChar');

			if (isset($arrPreviousItems) && is_array($arrPreviousItems)) {
				foreach ($arrPreviousItems as $objPreviousItem) {
					if ($objToReturn->Id != $objPreviousItem->Id) {
						continue;
					}
					if (array_diff($objPreviousItem->_objProjectAsTeamMemberArray, $objToReturn->_objProjectAsTeamMemberArray) != null) {
						continue;
					}
					if (array_diff($objPreviousItem->_objAddressArray, $objToReturn->_objAddressArray) != null) {
						continue;
					}
					if (array_diff($objPreviousItem->_objLoginArray, $objToReturn->_objLoginArray) != null) {
						continue;
					}
					if (array_diff($objPreviousItem->_objProjectAsManagerArray, $objToReturn->_objProjectAsManagerArray) != null) {
						continue;
					}

					// complete match - all primary key columns are the same
					return null;
				}
			}

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'person__';


			// Check for Login Unique ReverseReference Binding
			$strAlias = $strAliasPrefix . 'login__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if ($objDbRow->ColumnExists($strAliasName)) {
				if (!is_null($objDbRow->GetColumn($strAliasName)))
					$objToReturn->objLogin = Login::InstantiateDbRow($objDbRow, $strAliasPrefix . 'login__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					// We ATTEMPTED to do an Early Bind but the Object Doesn't Exist
					// Let's set to FALSE so that the object knows not to try and re-query again
					$objToReturn->objLogin = false;
			}


			// Check for ProjectAsTeamMember Virtual Binding
			$strAlias = $strAliasPrefix . 'projectasteammember__project_id__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProjectAsTeamMemberArray[] = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasteammember__project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProjectAsTeamMember = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasteammember__project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}


			// Check for Address Virtual Binding
			$strAlias = $strAliasPrefix . 'address__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objAddressArray[] = Address::InstantiateDbRow($objDbRow, $strAliasPrefix . 'address__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objAddress = Address::InstantiateDbRow($objDbRow, $strAliasPrefix . 'address__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for ProjectAsManager Virtual Binding
			$strAlias = $strAliasPrefix . 'projectasmanager__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProjectAsManagerArray[] = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasmanager__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProjectAsManager = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasmanager__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of People from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Person[]
		 */
		public static function InstantiateDbResult(QDatabaseResultBase $objDbResult, $strExpandAsArrayNodes = null, $strColumnAliasArray = null) {
			$objToReturn = array();
			
			if (!$strColumnAliasArray)
				$strColumnAliasArray = array();

			// If blank resultset, then return empty array
			if (!$objDbResult)
				return $objToReturn;

			// Load up the return array with each row
			if ($strExpandAsArrayNodes) {
				$objToReturn = array();
				while ($objDbRow = $objDbResult->GetNextRow()) {
					$objItem = Person::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objToReturn, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Person::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}



		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Person object,
		 * by Id Index(es)
		 * @param integer $intId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Person
		*/
		public static function LoadById($intId, $objOptionalClauses = null) {
			return Person::QuerySingle(
				QQ::AndCondition(
					QQ::Equal(QQN::Person()->Id, $intId)
				),
				$objOptionalClauses
			);
		}
			
		/**
		 * Load an array of Person objects,
		 * by LastName Index(es)
		 * @param string $strLastName
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Person[]
		*/
		public static function LoadArrayByLastName($strLastName, $objOptionalClauses = null) {
			// Call Person::QueryArray to perform the LoadArrayByLastName query
			try {
				return Person::QueryArray(
					QQ::Equal(QQN::Person()->LastName, $strLastName),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count People
		 * by LastName Index(es)
		 * @param string $strLastName
		 * @return int
		*/
		public static function CountByLastName($strLastName) {
			// Call Person::QueryCount to perform the CountByLastName query
			return Person::QueryCount(
				QQ::Equal(QQN::Person()->LastName, $strLastName)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////
			/**
		 * Load an array of Project objects for a given ProjectAsTeamMember
		 * via the team_member_project_assn table
		 * @param integer $intProjectId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Person[]
		*/
		public static function LoadArrayByProjectAsTeamMember($intProjectId, $objOptionalClauses = null) {
			// Call Person::QueryArray to perform the LoadArrayByProjectAsTeamMember query
			try {
				return Person::QueryArray(
					QQ::Equal(QQN::Person()->ProjectAsTeamMember->ProjectId, $intProjectId),
					$objOptionalClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count People for a given ProjectAsTeamMember
		 * via the team_member_project_assn table
		 * @param integer $intProjectId
		 * @return int
		*/
		public static function CountByProjectAsTeamMember($intProjectId) {
			return Person::QueryCount(
				QQ::Equal(QQN::Person()->ProjectAsTeamMember->ProjectId, $intProjectId)
			);
		}




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Person
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `person` (
							`first_name`,
							`last_name`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strFirstName) . ',
							' . $objDatabase->SqlVariable($this->strLastName) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intId = $objDatabase->InsertId('person', 'id');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`person`
						SET
							`first_name` = ' . $objDatabase->SqlVariable($this->strFirstName) . ',
							`last_name` = ' . $objDatabase->SqlVariable($this->strLastName) . '
						WHERE
							`id` = ' . $objDatabase->SqlVariable($this->intId) . '
					');
				}

		
		
				// Update the adjoined Login object (if applicable)
				// TODO: Make this into hard-coded SQL queries
				if ($this->blnDirtyLogin) {
					// Unassociate the old one (if applicable)
					if ($objAssociated = Login::LoadByPersonId($this->intId)) {
						$objAssociated->PersonId = null;
						$objAssociated->Save();
					}

					// Associate the new one (if applicable)
					if ($this->objLogin) {
						$this->objLogin->PersonId = $this->intId;
						$this->objLogin->Save();
					}

					// Reset the "Dirty" flag
					$this->blnDirtyLogin = false;
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Update __blnRestored and any Non-Identity PK Columns (if applicable)
			$this->__blnRestored = true;


			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Person
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Person with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			
			
			// Update the adjoined Login object (if applicable) and perform a delete

			// Optional -- if you **KNOW** that you do not want to EVER run any level of business logic on the disassocation,
			// you *could* override Delete() so that this step can be a single hard coded query to optimize performance.
			if ($objAssociated = Login::LoadByPersonId($this->intId)) {
				$objAssociated->Delete();
			}

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`person`
				WHERE
					`id` = ' . $objDatabase->SqlVariable($this->intId) . '');
		}

		/**
		 * Delete all People
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`person`');
		}

		/**
		 * Truncate person table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `person`');
		}

		/**
		 * Reload this Person from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Person object.');

			// Reload the Object
			$objReloaded = Person::Load($this->intId);

			// Update $this's local variables to match
			$this->strFirstName = $objReloaded->strFirstName;
			$this->strLastName = $objReloaded->strLastName;
		}



		////////////////////
		// PUBLIC OVERRIDERS
		////////////////////

				/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
				case 'Id':
					/**
					 * Gets the value for intId (Read-Only PK)
					 * @return integer
					 */
					return $this->intId;

				case 'FirstName':
					/**
					 * Gets the value for strFirstName (Not Null)
					 * @return string
					 */
					return $this->strFirstName;

				case 'LastName':
					/**
					 * Gets the value for strLastName (Not Null)
					 * @return string
					 */
					return $this->strLastName;


				///////////////////
				// Member Objects
				///////////////////
		
		
				case 'Login':
					/**
					 * Gets the value for the Login object that uniquely references this Person
					 * by objLogin (Unique)
					 * @return Login
					 */
					try {
						if ($this->objLogin === false)
							// We've attempted early binding -- and the reverse reference object does not exist
							return null;
						if (!$this->objLogin)
							$this->objLogin = Login::LoadByPersonId($this->intId);
						return $this->objLogin;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_ProjectAsTeamMember':
					/**
					 * Gets the value for the private _objProjectAsTeamMember (Read-Only)
					 * if set due to an expansion on the team_member_project_assn association table
					 * @return Project
					 */
					return $this->_objProjectAsTeamMember;

				case '_ProjectAsTeamMemberArray':
					/**
					 * Gets the value for the private _objProjectAsTeamMemberArray (Read-Only)
					 * if set due to an ExpandAsArray on the team_member_project_assn association table
					 * @return Project[]
					 */
					return (array) $this->_objProjectAsTeamMemberArray;

				case '_Address':
					/**
					 * Gets the value for the private _objAddress (Read-Only)
					 * if set due to an expansion on the address.person_id reverse relationship
					 * @return Address
					 */
					return $this->_objAddress;

				case '_AddressArray':
					/**
					 * Gets the value for the private _objAddressArray (Read-Only)
					 * if set due to an ExpandAsArray on the address.person_id reverse relationship
					 * @return Address[]
					 */
					return (array) $this->_objAddressArray;

				case '_ProjectAsManager':
					/**
					 * Gets the value for the private _objProjectAsManager (Read-Only)
					 * if set due to an expansion on the project.manager_person_id reverse relationship
					 * @return Project
					 */
					return $this->_objProjectAsManager;

				case '_ProjectAsManagerArray':
					/**
					 * Gets the value for the private _objProjectAsManagerArray (Read-Only)
					 * if set due to an ExpandAsArray on the project.manager_person_id reverse relationship
					 * @return Project[]
					 */
					return (array) $this->_objProjectAsManagerArray;


				case '__Restored':
					return $this->__blnRestored;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

				/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
				case 'FirstName':
					/**
					 * Sets the value for strFirstName (Not Null)
					 * @param string $mixValue
					 * @return string
					 */
					try {
						return ($this->strFirstName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LastName':
					/**
					 * Sets the value for strLastName (Not Null)
					 * @param string $mixValue
					 * @return string
					 */
					try {
						return ($this->strLastName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
				case 'Login':
					/**
					 * Sets the value for the Login object referenced by objLogin (Unique)
					 * @param Login $mixValue
					 * @return Login
					 */
					if (is_null($mixValue)) {
						$this->objLogin = null;

						// Make sure we update the adjoined Login object the next time we call Save()
						$this->blnDirtyLogin = true;

						return null;
					} else {
						// Make sure $mixValue actually is a Login object
						try {
							$mixValue = QType::Cast($mixValue, 'Login');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}

						// Are we setting objLogin to a DIFFERENT $mixValue?
						if ((!$this->Login) || ($this->Login->Id != $mixValue->Id)) {
							// Yes -- therefore, set the "Dirty" flag to true
							// to make sure we update the adjoined Login object the next time we call Save()
							$this->blnDirtyLogin = true;

							// Update Local Member Variable
							$this->objLogin = $mixValue;
						} else {
							// Nope -- therefore, make no changes
						}

						// Return $mixValue
						return $mixValue;
					}
					break;

				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * Lookup a VirtualAttribute value (if applicable).  Returns NULL if none found.
		 * @param string $strName
		 * @return string
		 */
		public function GetVirtualAttribute($strName) {
			if (array_key_exists($strName, $this->__strVirtualAttributeArray))
				return $this->__strVirtualAttributeArray[$strName];
			return null;
		}



		///////////////////////////////
		// ASSOCIATED OBJECTS' METHODS
		///////////////////////////////

			
		
		// Related Objects' Methods for Address
		//-------------------------------------------------------------------

		/**
		 * Gets all associated Addresses as an array of Address objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Address[]
		*/ 
		public function GetAddressArray($objOptionalClauses = null) {
			if ((is_null($this->intId)))
				return array();

			try {
				return Address::LoadArrayByPersonId($this->intId, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated Addresses
		 * @return int
		*/ 
		public function CountAddresses() {
			if ((is_null($this->intId)))
				return 0;

			return Address::CountByPersonId($this->intId);
		}

		/**
		 * Associates a Address
		 * @param Address $objAddress
		 * @return void
		*/ 
		public function AssociateAddress(Address $objAddress) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateAddress on this unsaved Person.');
			if ((is_null($objAddress->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateAddress on this Person with an unsaved Address.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`address`
				SET
					`person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objAddress->Id) . '
			');
		}

		/**
		 * Unassociates a Address
		 * @param Address $objAddress
		 * @return void
		*/ 
		public function UnassociateAddress(Address $objAddress) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAddress on this unsaved Person.');
			if ((is_null($objAddress->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAddress on this Person with an unsaved Address.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`address`
				SET
					`person_id` = null
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objAddress->Id) . ' AND
					`person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Unassociates all Addresses
		 * @return void
		*/ 
		public function UnassociateAllAddresses() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAddress on this unsaved Person.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`address`
				SET
					`person_id` = null
				WHERE
					`person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Deletes an associated Address
		 * @param Address $objAddress
		 * @return void
		*/ 
		public function DeleteAssociatedAddress(Address $objAddress) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAddress on this unsaved Person.');
			if ((is_null($objAddress->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAddress on this Person with an unsaved Address.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`address`
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objAddress->Id) . ' AND
					`person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Deletes all associated Addresses
		 * @return void
		*/ 
		public function DeleteAllAddresses() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAddress on this unsaved Person.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`address`
				WHERE
					`person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

			
		
		// Related Objects' Methods for ProjectAsManager
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ProjectsAsManager as an array of Project objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/ 
		public function GetProjectAsManagerArray($objOptionalClauses = null) {
			if ((is_null($this->intId)))
				return array();

			try {
				return Project::LoadArrayByManagerPersonId($this->intId, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ProjectsAsManager
		 * @return int
		*/ 
		public function CountProjectsAsManager() {
			if ((is_null($this->intId)))
				return 0;

			return Project::CountByManagerPersonId($this->intId);
		}

		/**
		 * Associates a ProjectAsManager
		 * @param Project $objProject
		 * @return void
		*/ 
		public function AssociateProjectAsManager(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProjectAsManager on this unsaved Person.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProjectAsManager on this Person with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`project`
				SET
					`manager_person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objProject->Id) . '
			');
		}

		/**
		 * Unassociates a ProjectAsManager
		 * @param Project $objProject
		 * @return void
		*/ 
		public function UnassociateProjectAsManager(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsManager on this unsaved Person.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsManager on this Person with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`project`
				SET
					`manager_person_id` = null
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objProject->Id) . ' AND
					`manager_person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Unassociates all ProjectsAsManager
		 * @return void
		*/ 
		public function UnassociateAllProjectsAsManager() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsManager on this unsaved Person.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`project`
				SET
					`manager_person_id` = null
				WHERE
					`manager_person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Deletes an associated ProjectAsManager
		 * @param Project $objProject
		 * @return void
		*/ 
		public function DeleteAssociatedProjectAsManager(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsManager on this unsaved Person.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsManager on this Person with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`project`
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objProject->Id) . ' AND
					`manager_person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Deletes all associated ProjectsAsManager
		 * @return void
		*/ 
		public function DeleteAllProjectsAsManager() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsManager on this unsaved Person.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`project`
				WHERE
					`manager_person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

			
		// Related Many-to-Many Objects' Methods for ProjectAsTeamMember
		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated ProjectsAsTeamMember as an array of Project objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/ 
		public function GetProjectAsTeamMemberArray($objOptionalClauses = null) {
			if ((is_null($this->intId)))
				return array();

			try {
				return Project::LoadArrayByPersonAsTeamMember($this->intId, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated ProjectsAsTeamMember
		 * @return int
		*/ 
		public function CountProjectsAsTeamMember() {
			if ((is_null($this->intId)))
				return 0;

			return Project::CountByPersonAsTeamMember($this->intId);
		}

		/**
		 * Checks to see if an association exists with a specific ProjectAsTeamMember
		 * @param Project $objProject
		 * @return bool
		*/
		public function IsProjectAsTeamMemberAssociated(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsProjectAsTeamMemberAssociated on this unsaved Person.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsProjectAsTeamMemberAssociated on this Person with an unsaved Project.');

			$intRowCount = Person::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Person()->Id, $this->intId),
					QQ::Equal(QQN::Person()->ProjectAsTeamMember->ProjectId, $objProject->Id)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a ProjectAsTeamMember
		 * @param Project $objProject
		 * @return void
		*/ 
		public function AssociateProjectAsTeamMember(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProjectAsTeamMember on this unsaved Person.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProjectAsTeamMember on this Person with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO `team_member_project_assn` (
					`person_id`,
					`project_id`
				) VALUES (
					' . $objDatabase->SqlVariable($this->intId) . ',
					' . $objDatabase->SqlVariable($objProject->Id) . '
				)
			');
		}

		/**
		 * Unassociates a ProjectAsTeamMember
		 * @param Project $objProject
		 * @return void
		*/ 
		public function UnassociateProjectAsTeamMember(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsTeamMember on this unsaved Person.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsTeamMember on this Person with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`team_member_project_assn`
				WHERE
					`person_id` = ' . $objDatabase->SqlVariable($this->intId) . ' AND
					`project_id` = ' . $objDatabase->SqlVariable($objProject->Id) . '
			');
		}

		/**
		 * Unassociates all ProjectsAsTeamMember
		 * @return void
		*/ 
		public function UnassociateAllProjectsAsTeamMember() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAllProjectAsTeamMemberArray on this unsaved Person.');

			// Get the Database Object for this Class
			$objDatabase = Person::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`team_member_project_assn`
				WHERE
					`person_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}




		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Person"><sequence>';
			$strToReturn .= '<element name="Id" type="xsd:int"/>';
			$strToReturn .= '<element name="FirstName" type="xsd:string"/>';
			$strToReturn .= '<element name="LastName" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Person', $strComplexTypeArray)) {
				$strComplexTypeArray['Person'] = Person::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Person::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Person();
			if (property_exists($objSoapObject, 'Id'))
				$objToReturn->intId = $objSoapObject->Id;
			if (property_exists($objSoapObject, 'FirstName'))
				$objToReturn->strFirstName = $objSoapObject->FirstName;
			if (property_exists($objSoapObject, 'LastName'))
				$objToReturn->strLastName = $objSoapObject->LastName;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Person::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCubed QUERY
	/////////////////////////////////////

	class QQNodePersonProjectAsTeamMember extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = 'projectasteammember';

		protected $strTableName = 'team_member_project_assn';
		protected $strPrimaryKey = 'person_id';
		protected $strClassName = 'Project';

		public function __get($strName) {
			switch ($strName) {
				case 'ProjectId':
					return new QQNode('project_id', 'ProjectId', 'integer', $this);
				case 'Project':
					return new QQNodeProject('project_id', 'ProjectId', 'integer', $this);
				case '_ChildTableNode':
					return new QQNodeProject('project_id', 'ProjectId', 'integer', $this);
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

	class QQNodePerson extends QQNode {
		protected $strTableName = 'person';
		protected $strPrimaryKey = 'id';
		protected $strClassName = 'Person';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new QQNode('id', 'Id', 'integer', $this);
				case 'FirstName':
					return new QQNode('first_name', 'FirstName', 'string', $this);
				case 'LastName':
					return new QQNode('last_name', 'LastName', 'string', $this);
				case 'ProjectAsTeamMember':
					return new QQNodePersonProjectAsTeamMember($this);
				case 'Address':
					return new QQReverseReferenceNodeAddress($this, 'address', 'reverse_reference', 'person_id');
				case 'Login':
					return new QQReverseReferenceNodeLogin($this, 'login', 'reverse_reference', 'person_id', 'Login');
				case 'ProjectAsManager':
					return new QQReverseReferenceNodeProject($this, 'projectasmanager', 'reverse_reference', 'manager_person_id');

				case '_PrimaryKeyNode':
					return new QQNode('id', 'Id', 'integer', $this);
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

	class QQReverseReferenceNodePerson extends QQReverseReferenceNode {
		protected $strTableName = 'person';
		protected $strPrimaryKey = 'id';
		protected $strClassName = 'Person';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new QQNode('id', 'Id', 'integer', $this);
				case 'FirstName':
					return new QQNode('first_name', 'FirstName', 'string', $this);
				case 'LastName':
					return new QQNode('last_name', 'LastName', 'string', $this);
				case 'ProjectAsTeamMember':
					return new QQNodePersonProjectAsTeamMember($this);
				case 'Address':
					return new QQReverseReferenceNodeAddress($this, 'address', 'reverse_reference', 'person_id');
				case 'Login':
					return new QQReverseReferenceNodeLogin($this, 'login', 'reverse_reference', 'person_id', 'Login');
				case 'ProjectAsManager':
					return new QQReverseReferenceNodeProject($this, 'projectasmanager', 'reverse_reference', 'manager_person_id');

				case '_PrimaryKeyNode':
					return new QQNode('id', 'Id', 'integer', $this);
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

?>