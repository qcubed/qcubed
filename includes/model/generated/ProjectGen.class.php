<?php
	/**
	 * The abstract ProjectGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Project subclass which
	 * extends this ProjectGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Project class.
	 *
	 * @package My QCubed Application
	 * @subpackage GeneratedDataObjects
	 * @property-read integer $Id the value for intId (Read-Only PK)
	 * @property integer $ProjectStatusTypeId the value for intProjectStatusTypeId (Not Null)
	 * @property integer $ManagerPersonId the value for intManagerPersonId 
	 * @property string $Name the value for strName (Not Null)
	 * @property string $Description the value for strDescription 
	 * @property QDateTime $StartDate the value for dttStartDate 
	 * @property QDateTime $EndDate the value for dttEndDate 
	 * @property double $Budget the value for fltBudget 
	 * @property double $Spent the value for fltSpent 
	 * @property Person $ManagerPerson the value for the Person object referenced by intManagerPersonId 
	 * @property-read Project $_ProjectAsRelated the value for the private _objProjectAsRelated (Read-Only) if set due to an expansion on the related_project_assn association table
	 * @property-read Project[] $_ProjectAsRelatedArray the value for the private _objProjectAsRelatedArray (Read-Only) if set due to an ExpandAsArray on the related_project_assn association table
	 * @property-read Project $_ParentProjectAsRelated the value for the private _objParentProjectAsRelated (Read-Only) if set due to an expansion on the related_project_assn association table
	 * @property-read Project[] $_ParentProjectAsRelatedArray the value for the private _objParentProjectAsRelatedArray (Read-Only) if set due to an ExpandAsArray on the related_project_assn association table
	 * @property-read Person $_PersonAsTeamMember the value for the private _objPersonAsTeamMember (Read-Only) if set due to an expansion on the team_member_project_assn association table
	 * @property-read Person[] $_PersonAsTeamMemberArray the value for the private _objPersonAsTeamMemberArray (Read-Only) if set due to an ExpandAsArray on the team_member_project_assn association table
	 * @property-read Milestone $_Milestone the value for the private _objMilestone (Read-Only) if set due to an expansion on the milestone.project_id reverse relationship
	 * @property-read Milestone[] $_MilestoneArray the value for the private _objMilestoneArray (Read-Only) if set due to an ExpandAsArray on the milestone.project_id reverse relationship
	 * @property-read boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class ProjectGen extends QBaseClass implements IteratorAggregate {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////

		/**
		 * Protected member variable that maps to the database PK Identity column project.id
		 * @var integer intId
		 */
		protected $intId;
		const IdDefault = null;


		/**
		 * Protected member variable that maps to the database column project.project_status_type_id
		 * @var integer intProjectStatusTypeId
		 */
		protected $intProjectStatusTypeId;
		const ProjectStatusTypeIdDefault = null;


		/**
		 * Protected member variable that maps to the database column project.manager_person_id
		 * @var integer intManagerPersonId
		 */
		protected $intManagerPersonId;
		const ManagerPersonIdDefault = null;


		/**
		 * Protected member variable that maps to the database column project.name
		 * @var string strName
		 */
		protected $strName;
		const NameMaxLength = 100;
		const NameDefault = null;


		/**
		 * Protected member variable that maps to the database column project.description
		 * @var string strDescription
		 */
		protected $strDescription;
		const DescriptionDefault = null;


		/**
		 * Protected member variable that maps to the database column project.start_date
		 * @var QDateTime dttStartDate
		 */
		protected $dttStartDate;
		const StartDateDefault = null;


		/**
		 * Protected member variable that maps to the database column project.end_date
		 * @var QDateTime dttEndDate
		 */
		protected $dttEndDate;
		const EndDateDefault = null;


		/**
		 * Protected member variable that maps to the database column project.budget
		 * @var double fltBudget
		 */
		protected $fltBudget;
		const BudgetDefault = null;


		/**
		 * Protected member variable that maps to the database column project.spent
		 * @var double fltSpent
		 */
		protected $fltSpent;
		const SpentDefault = null;


		/**
		 * Private member variable that stores a reference to a single ProjectAsRelated object
		 * (of type Project), if this Project object was restored with
		 * an expansion on the related_project_assn association table.
		 * @var Project _objProjectAsRelated;
		 */
		private $_objProjectAsRelated;

		/**
		 * Private member variable that stores a reference to an array of ProjectAsRelated objects
		 * (of type Project[]), if this Project object was restored with
		 * an ExpandAsArray on the related_project_assn association table.
		 * @var Project[] _objProjectAsRelatedArray;
		 */
		private $_objProjectAsRelatedArray = null;

		/**
		 * Private member variable that stores a reference to a single ParentProjectAsRelated object
		 * (of type Project), if this Project object was restored with
		 * an expansion on the related_project_assn association table.
		 * @var Project _objParentProjectAsRelated;
		 */
		private $_objParentProjectAsRelated;

		/**
		 * Private member variable that stores a reference to an array of ParentProjectAsRelated objects
		 * (of type Project[]), if this Project object was restored with
		 * an ExpandAsArray on the related_project_assn association table.
		 * @var Project[] _objParentProjectAsRelatedArray;
		 */
		private $_objParentProjectAsRelatedArray = null;

		/**
		 * Private member variable that stores a reference to a single PersonAsTeamMember object
		 * (of type Person), if this Project object was restored with
		 * an expansion on the team_member_project_assn association table.
		 * @var Person _objPersonAsTeamMember;
		 */
		private $_objPersonAsTeamMember;

		/**
		 * Private member variable that stores a reference to an array of PersonAsTeamMember objects
		 * (of type Person[]), if this Project object was restored with
		 * an ExpandAsArray on the team_member_project_assn association table.
		 * @var Person[] _objPersonAsTeamMemberArray;
		 */
		private $_objPersonAsTeamMemberArray = null;

		/**
		 * Private member variable that stores a reference to a single Milestone object
		 * (of type Milestone), if this Project object was restored with
		 * an expansion on the milestone association table.
		 * @var Milestone _objMilestone;
		 */
		private $_objMilestone;

		/**
		 * Private member variable that stores a reference to an array of Milestone objects
		 * (of type Milestone[]), if this Project object was restored with
		 * an ExpandAsArray on the milestone association table.
		 * @var Milestone[] _objMilestoneArray;
		 */
		private $_objMilestoneArray = null;

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
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column project.manager_person_id.
		 *
		 * NOTE: Always use the ManagerPerson property getter to correctly retrieve this Person object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Person objManagerPerson
		 */
		protected $objManagerPerson;



		/**
		 * Initialize each property with default values from database definition
		 */
		public function Initialize()
		{
			$this->intId = Project::IdDefault;
			$this->intProjectStatusTypeId = Project::ProjectStatusTypeIdDefault;
			$this->intManagerPersonId = Project::ManagerPersonIdDefault;
			$this->strName = Project::NameDefault;
			$this->strDescription = Project::DescriptionDefault;
			$this->dttStartDate = (Project::StartDateDefault === null)?null:new QDateTime(Project::StartDateDefault);
			$this->dttEndDate = (Project::EndDateDefault === null)?null:new QDateTime(Project::EndDateDefault);
			$this->fltBudget = Project::BudgetDefault;
			$this->fltSpent = Project::SpentDefault;
		}


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
		 * Load a Project from PK Info
		 * @param integer $intId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project
		 */
		public static function Load($intId, $objOptionalClauses = null) {
			$strCacheKey = false;
			if (QApplication::$objCacheProvider && !$objOptionalClauses && QApplication::$Database[1]->Caching) {
				$strCacheKey = QApplication::$objCacheProvider->CreateKey(QApplication::$Database[1]->Database, 'Project', $intId);
				$objCachedObject = QApplication::$objCacheProvider->Get($strCacheKey);
				if ($objCachedObject !== false) {
					return $objCachedObject;
				}
			}
			// Use QuerySingle to Perform the Query
			$objToReturn = Project::QuerySingle(
				QQ::AndCondition(
					QQ::Equal(QQN::Project()->Id, $intId)
				),
				$objOptionalClauses
			);
			if ($strCacheKey !== false) {
				QApplication::$objCacheProvider->Set($strCacheKey, $objToReturn);
			}
			return $objToReturn;
		}

		/**
		 * Load all Projects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			if (func_num_args() > 1) {
				throw new QCallerException("LoadAll must be called with an array of optional clauses as a single argument");
			}
			// Call Project::QueryArray to perform the LoadAll query
			try {
				return Project::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Projects
		 * @return int
		 */
		public static function CountAll() {
			// Call Project::QueryCount to perform the CountAll query
			return Project::QueryCount(QQ::All());
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
			$objDatabase = Project::GetDatabase();

			// Create/Build out the QueryBuilder object with Project-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'project');

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
				Project::GetSelectFields($objQueryBuilder, null, QQuery::extractSelectClause($objOptionalClauses));
			}
			$objQueryBuilder->AddFromItem('project');

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
		 * Static Qcubed Query method to query for a single Project object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Project the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Project::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Project object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);

			// Do we have to expand anything?
			if ($objQueryBuilder->ExpandAsArrayNodes) {
				$objToReturn = array();
				while ($objDbRow = $objDbResult->GetNextRow()) {
					$objItem = Project::InstantiateDbRow($objDbRow, null, $objQueryBuilder->ExpandAsArrayNodes, $objToReturn, $objQueryBuilder->ColumnAliasArray);
					if ($objItem)
						$objToReturn[] = $objItem;
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
				return Project::InstantiateDbRow($objDbRow, null, null, null, $objQueryBuilder->ColumnAliasArray);
			}
		}

		/**
		 * Static Qcubed Query method to query for an array of Project objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Project[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Project::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Project::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo query method to issue a query and get a cursor to progressively fetch its results.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return QDatabaseResultBase the cursor resource instance
		 */
		public static function QueryCursor(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the query statement
			try {
				$strQuery = Project::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
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
		 * Static Qcubed Query method to query for a count of Project objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Project::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Project::GetDatabase();

			$strQuery = Project::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);

			$objCache = new QCache('qquery/project', $strQuery);
			$cacheData = $objCache->GetData();

			if (!$cacheData || $blnForceUpdate) {
				$objDbResult = $objQueryBuilder->Database->Query($strQuery);
				$arrResult = Project::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
				$objCache->SaveData(serialize($arrResult));
			} else {
				$arrResult = unserialize($cacheData);
			}

			return $arrResult;
		}

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Project
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null, QQSelect $objSelect = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'project';
				$strAliasPrefix = '';
			}

            if ($objSelect) {
			    $objBuilder->AddSelectItem($strTableName, 'id', $strAliasPrefix . 'id');
                $objSelect->AddSelectItems($objBuilder, $strTableName, $strAliasPrefix);
            } else {
			    $objBuilder->AddSelectItem($strTableName, 'id', $strAliasPrefix . 'id');
			    $objBuilder->AddSelectItem($strTableName, 'project_status_type_id', $strAliasPrefix . 'project_status_type_id');
			    $objBuilder->AddSelectItem($strTableName, 'manager_person_id', $strAliasPrefix . 'manager_person_id');
			    $objBuilder->AddSelectItem($strTableName, 'name', $strAliasPrefix . 'name');
			    $objBuilder->AddSelectItem($strTableName, 'description', $strAliasPrefix . 'description');
			    $objBuilder->AddSelectItem($strTableName, 'start_date', $strAliasPrefix . 'start_date');
			    $objBuilder->AddSelectItem($strTableName, 'end_date', $strAliasPrefix . 'end_date');
			    $objBuilder->AddSelectItem($strTableName, 'budget', $strAliasPrefix . 'budget');
			    $objBuilder->AddSelectItem($strTableName, 'spent', $strAliasPrefix . 'spent');
            }
		}



		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Project from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Project::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $arrPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Project
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
							$strAliasPrefix = 'project__';

						// Expanding many-to-many references: ProjectAsRelated
						$strAlias = $strAliasPrefix . 'projectasrelated__child_project_id__id';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if(null === $objPreviousItem->_objProjectAsRelatedArray)
								$objPreviousItem->_objProjectAsRelatedArray = array();
							if ($intPreviousChildItemCount = count($objPreviousItem->_objProjectAsRelatedArray)) {
								$objPreviousChildItems = $objPreviousItem->_objProjectAsRelatedArray;
								$objChildItem = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasrelated__child_project_id__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_objProjectAsRelatedArray[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_objProjectAsRelatedArray[] = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasrelated__child_project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

						// Expanding many-to-many references: ParentProjectAsRelated
						$strAlias = $strAliasPrefix . 'parentprojectasrelated__project_id__id';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if(null === $objPreviousItem->_objParentProjectAsRelatedArray)
								$objPreviousItem->_objParentProjectAsRelatedArray = array();
							if ($intPreviousChildItemCount = count($objPreviousItem->_objParentProjectAsRelatedArray)) {
								$objPreviousChildItems = $objPreviousItem->_objParentProjectAsRelatedArray;
								$objChildItem = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'parentprojectasrelated__project_id__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_objParentProjectAsRelatedArray[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_objParentProjectAsRelatedArray[] = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'parentprojectasrelated__project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

						// Expanding many-to-many references: PersonAsTeamMember
						$strAlias = $strAliasPrefix . 'personasteammember__person_id__id';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if(null === $objPreviousItem->_objPersonAsTeamMemberArray)
								$objPreviousItem->_objPersonAsTeamMemberArray = array();
							if ($intPreviousChildItemCount = count($objPreviousItem->_objPersonAsTeamMemberArray)) {
								$objPreviousChildItems = $objPreviousItem->_objPersonAsTeamMemberArray;
								$objChildItem = Person::InstantiateDbRow($objDbRow, $strAliasPrefix . 'personasteammember__person_id__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_objPersonAsTeamMemberArray[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_objPersonAsTeamMemberArray[] = Person::InstantiateDbRow($objDbRow, $strAliasPrefix . 'personasteammember__person_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}


						// Expanding reverse references: Milestone
						$strAlias = $strAliasPrefix . 'milestone__id';
						$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
						if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
							(!is_null($objDbRow->GetColumn($strAliasName)))) {
							if(null === $objPreviousItem->_objMilestoneArray)
								$objPreviousItem->_objMilestoneArray = array();
							if ($intPreviousChildItemCount = count($objPreviousItem->_objMilestoneArray)) {
								$objPreviousChildItems = $objPreviousItem->_objMilestoneArray;
								$objChildItem = Milestone::InstantiateDbRow($objDbRow, $strAliasPrefix . 'milestone__', $strExpandAsArrayNodes, $objPreviousChildItems, $strColumnAliasArray);
								if ($objChildItem) {
									$objPreviousItem->_objMilestoneArray[] = $objChildItem;
								}
							} else {
								$objPreviousItem->_objMilestoneArray[] = Milestone::InstantiateDbRow($objDbRow, $strAliasPrefix . 'milestone__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
							}
							$blnExpandedViaArray = true;
						}

						// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
						if ($blnExpandedViaArray) {
							return false;
						} else if ($strAliasPrefix == 'project__') {
							$strAliasPrefix = null;
						}
					}
				}
			}

			// Create a new instance of the Project object
			$objToReturn = new Project();
			$objToReturn->__blnRestored = true;

			$strAlias = $strAliasPrefix . 'id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->intId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAlias = $strAliasPrefix . 'project_status_type_id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->intProjectStatusTypeId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAlias = $strAliasPrefix . 'manager_person_id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->intManagerPersonId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAlias = $strAliasPrefix . 'name';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->strName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAlias = $strAliasPrefix . 'description';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->strDescription = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAlias = $strAliasPrefix . 'start_date';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->dttStartDate = $objDbRow->GetColumn($strAliasName, 'Date');
			$strAlias = $strAliasPrefix . 'end_date';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->dttEndDate = $objDbRow->GetColumn($strAliasName, 'Date');
			$strAlias = $strAliasPrefix . 'budget';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->fltBudget = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAlias = $strAliasPrefix . 'spent';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$objToReturn->fltSpent = $objDbRow->GetColumn($strAliasName, 'Float');

			if (isset($arrPreviousItems) && is_array($arrPreviousItems)) {
				foreach ($arrPreviousItems as $objPreviousItem) {
					if ($objToReturn->Id != $objPreviousItem->Id) {
						continue;
					}
					$prevCnt = count($objPreviousItem->_objProjectAsRelatedArray);
					$cnt = count($objToReturn->_objProjectAsRelatedArray);
					if ($prevCnt != $cnt)
						continue;
					if ($prevCnt == 0 || $cnt == 0 || !array_diff($objPreviousItem->_objProjectAsRelatedArray, $objToReturn->_objProjectAsRelatedArray)) {
						continue;
					}

					$prevCnt = count($objPreviousItem->_objParentProjectAsRelatedArray);
					$cnt = count($objToReturn->_objParentProjectAsRelatedArray);
					if ($prevCnt != $cnt)
						continue;
					if ($prevCnt == 0 || $cnt == 0 || !array_diff($objPreviousItem->_objParentProjectAsRelatedArray, $objToReturn->_objParentProjectAsRelatedArray)) {
						continue;
					}

					$prevCnt = count($objPreviousItem->_objPersonAsTeamMemberArray);
					$cnt = count($objToReturn->_objPersonAsTeamMemberArray);
					if ($prevCnt != $cnt)
						continue;
					if ($prevCnt == 0 || $cnt == 0 || !array_diff($objPreviousItem->_objPersonAsTeamMemberArray, $objToReturn->_objPersonAsTeamMemberArray)) {
						continue;
					}

					$prevCnt = count($objPreviousItem->_objMilestoneArray);
					$cnt = count($objToReturn->_objMilestoneArray);
					if ($prevCnt != $cnt)
					    continue;
					if ($prevCnt == 0 || $cnt == 0 || !array_diff($objPreviousItem->_objMilestoneArray, $objToReturn->_objMilestoneArray)) {
						continue;
					}


					// complete match - all primary key columns are the same
					return null;
				}
			}

			// Instantiate Virtual Attributes
			$strVirtualPrefix = $strAliasPrefix . '__';
			$strVirtualPrefixLength = strlen($strVirtualPrefix);
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				if (strncmp($strColumnName, $strVirtualPrefix, $strVirtualPrefixLength) == 0)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'project__';

			// Check for ManagerPerson Early Binding
			$strAlias = $strAliasPrefix . 'manager_person_id__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objManagerPerson = Person::InstantiateDbRow($objDbRow, $strAliasPrefix . 'manager_person_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);



			// Check for ProjectAsRelated Virtual Binding
			$strAlias = $strAliasPrefix . 'projectasrelated__child_project_id__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$blnExpanded = $strExpandAsArrayNodes && array_key_exists($strAlias, $strExpandAsArrayNodes);
			if ($blnExpanded && null === $objToReturn->_objProjectAsRelatedArray)
				$objToReturn->_objProjectAsRelatedArray = array();
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if ($blnExpanded)
					$objToReturn->_objProjectAsRelatedArray[] = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasrelated__child_project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProjectAsRelated = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'projectasrelated__child_project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for ParentProjectAsRelated Virtual Binding
			$strAlias = $strAliasPrefix . 'parentprojectasrelated__project_id__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$blnExpanded = $strExpandAsArrayNodes && array_key_exists($strAlias, $strExpandAsArrayNodes);
			if ($blnExpanded && null === $objToReturn->_objParentProjectAsRelatedArray)
				$objToReturn->_objParentProjectAsRelatedArray = array();
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if ($blnExpanded)
					$objToReturn->_objParentProjectAsRelatedArray[] = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'parentprojectasrelated__project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objParentProjectAsRelated = Project::InstantiateDbRow($objDbRow, $strAliasPrefix . 'parentprojectasrelated__project_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for PersonAsTeamMember Virtual Binding
			$strAlias = $strAliasPrefix . 'personasteammember__person_id__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$blnExpanded = $strExpandAsArrayNodes && array_key_exists($strAlias, $strExpandAsArrayNodes);
			if ($blnExpanded && null === $objToReturn->_objPersonAsTeamMemberArray)
				$objToReturn->_objPersonAsTeamMemberArray = array();
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if ($blnExpanded)
					$objToReturn->_objPersonAsTeamMemberArray[] = Person::InstantiateDbRow($objDbRow, $strAliasPrefix . 'personasteammember__person_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objPersonAsTeamMember = Person::InstantiateDbRow($objDbRow, $strAliasPrefix . 'personasteammember__person_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}


			// Check for Milestone Virtual Binding
			$strAlias = $strAliasPrefix . 'milestone__id';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			$blnExpanded = $strExpandAsArrayNodes && array_key_exists($strAlias, $strExpandAsArrayNodes);
			if ($blnExpanded && null === $objToReturn->_objMilestoneArray)
				$objToReturn->_objMilestoneArray = array();
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if ($blnExpanded)
					$objToReturn->_objMilestoneArray[] = Milestone::InstantiateDbRow($objDbRow, $strAliasPrefix . 'milestone__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objMilestone = Milestone::InstantiateDbRow($objDbRow, $strAliasPrefix . 'milestone__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of Projects from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Project[]
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
					$objItem = Project::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objToReturn, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Project::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}


		/**
		 * Instantiate a single Project object from a query cursor (e.g. a DB ResultSet).
		 * Cursor is automatically moved to the "next row" of the result set.
		 * Will return NULL if no cursor or if the cursor has no more rows in the resultset.
		 * @param QDatabaseResultBase $objDbResult cursor resource
		 * @return Project next row resulting from the query
		 */
		public static function InstantiateCursor(QDatabaseResultBase $objDbResult) {
			// If blank resultset, then return empty result
			if (!$objDbResult) return null;

			// If empty resultset, then return empty result
			$objDbRow = $objDbResult->GetNextRow();
			if (!$objDbRow) return null;

			// We need the Column Aliases
			$strColumnAliasArray = $objDbResult->QueryBuilder->ColumnAliasArray;
			if (!$strColumnAliasArray) $strColumnAliasArray = array();

			// Pull Expansions (if applicable)
			$strExpandAsArrayNodes = $objDbResult->QueryBuilder->ExpandAsArrayNodes;

			// Load up the return result with a row and return it
			return Project::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, null, $strColumnAliasArray);
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////

		/**
		 * Load a single Project object,
		 * by Id Index(es)
		 * @param integer $intId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project
		*/
		public static function LoadById($intId, $objOptionalClauses = null) {
			return Project::QuerySingle(
				QQ::AndCondition(
					QQ::Equal(QQN::Project()->Id, $intId)
				),
				$objOptionalClauses
			);
		}

		/**
		 * Load an array of Project objects,
		 * by ProjectStatusTypeId Index(es)
		 * @param integer $intProjectStatusTypeId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/
		public static function LoadArrayByProjectStatusTypeId($intProjectStatusTypeId, $objOptionalClauses = null) {
			// Call Project::QueryArray to perform the LoadArrayByProjectStatusTypeId query
			try {
				return Project::QueryArray(
					QQ::Equal(QQN::Project()->ProjectStatusTypeId, $intProjectStatusTypeId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Projects
		 * by ProjectStatusTypeId Index(es)
		 * @param integer $intProjectStatusTypeId
		 * @return int
		*/
		public static function CountByProjectStatusTypeId($intProjectStatusTypeId) {
			// Call Project::QueryCount to perform the CountByProjectStatusTypeId query
			return Project::QueryCount(
				QQ::Equal(QQN::Project()->ProjectStatusTypeId, $intProjectStatusTypeId)
			);
		}

		/**
		 * Load an array of Project objects,
		 * by ManagerPersonId Index(es)
		 * @param integer $intManagerPersonId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/
		public static function LoadArrayByManagerPersonId($intManagerPersonId, $objOptionalClauses = null) {
			// Call Project::QueryArray to perform the LoadArrayByManagerPersonId query
			try {
				return Project::QueryArray(
					QQ::Equal(QQN::Project()->ManagerPersonId, $intManagerPersonId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Projects
		 * by ManagerPersonId Index(es)
		 * @param integer $intManagerPersonId
		 * @return int
		*/
		public static function CountByManagerPersonId($intManagerPersonId) {
			// Call Project::QueryCount to perform the CountByManagerPersonId query
			return Project::QueryCount(
				QQ::Equal(QQN::Project()->ManagerPersonId, $intManagerPersonId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////
			/**
		 * Load an array of Project objects for a given ProjectAsRelated
		 * via the related_project_assn table
		 * @param integer $intChildProjectId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/
		public static function LoadArrayByProjectAsRelated($intChildProjectId, $objOptionalClauses = null) {
			// Call Project::QueryArray to perform the LoadArrayByProjectAsRelated query
			try {
				return Project::QueryArray(
					QQ::Equal(QQN::Project()->ProjectAsRelated->ChildProjectId, $intChildProjectId),
					$objOptionalClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Projects for a given ProjectAsRelated
		 * via the related_project_assn table
		 * @param integer $intChildProjectId
		 * @return int
		*/
		public static function CountByProjectAsRelated($intChildProjectId) {
			return Project::QueryCount(
				QQ::Equal(QQN::Project()->ProjectAsRelated->ChildProjectId, $intChildProjectId)
			);
		}
			/**
		 * Load an array of Project objects for a given ParentProjectAsRelated
		 * via the related_project_assn table
		 * @param integer $intProjectId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/
		public static function LoadArrayByParentProjectAsRelated($intProjectId, $objOptionalClauses = null) {
			// Call Project::QueryArray to perform the LoadArrayByParentProjectAsRelated query
			try {
				return Project::QueryArray(
					QQ::Equal(QQN::Project()->ParentProjectAsRelated->ProjectId, $intProjectId),
					$objOptionalClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Projects for a given ParentProjectAsRelated
		 * via the related_project_assn table
		 * @param integer $intProjectId
		 * @return int
		*/
		public static function CountByParentProjectAsRelated($intProjectId) {
			return Project::QueryCount(
				QQ::Equal(QQN::Project()->ParentProjectAsRelated->ProjectId, $intProjectId)
			);
		}
			/**
		 * Load an array of Person objects for a given PersonAsTeamMember
		 * via the team_member_project_assn table
		 * @param integer $intPersonId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/
		public static function LoadArrayByPersonAsTeamMember($intPersonId, $objOptionalClauses = null) {
			// Call Project::QueryArray to perform the LoadArrayByPersonAsTeamMember query
			try {
				return Project::QueryArray(
					QQ::Equal(QQN::Project()->PersonAsTeamMember->PersonId, $intPersonId),
					$objOptionalClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Projects for a given PersonAsTeamMember
		 * via the team_member_project_assn table
		 * @param integer $intPersonId
		 * @return int
		*/
		public static function CountByPersonAsTeamMember($intPersonId) {
			return Project::QueryCount(
				QQ::Equal(QQN::Project()->PersonAsTeamMember->PersonId, $intPersonId)
			);
		}





		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Project
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `project` (
							`project_status_type_id`,
							`manager_person_id`,
							`name`,
							`description`,
							`start_date`,
							`end_date`,
							`budget`,
							`spent`
						) VALUES (
							' . $objDatabase->SqlVariable($this->intProjectStatusTypeId) . ',
							' . $objDatabase->SqlVariable($this->intManagerPersonId) . ',
							' . $objDatabase->SqlVariable($this->strName) . ',
							' . $objDatabase->SqlVariable($this->strDescription) . ',
							' . $objDatabase->SqlVariable($this->dttStartDate) . ',
							' . $objDatabase->SqlVariable($this->dttEndDate) . ',
							' . $objDatabase->SqlVariable($this->fltBudget) . ',
							' . $objDatabase->SqlVariable($this->fltSpent) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intId = $objDatabase->InsertId('project', 'id');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`project`
						SET
							`project_status_type_id` = ' . $objDatabase->SqlVariable($this->intProjectStatusTypeId) . ',
							`manager_person_id` = ' . $objDatabase->SqlVariable($this->intManagerPersonId) . ',
							`name` = ' . $objDatabase->SqlVariable($this->strName) . ',
							`description` = ' . $objDatabase->SqlVariable($this->strDescription) . ',
							`start_date` = ' . $objDatabase->SqlVariable($this->dttStartDate) . ',
							`end_date` = ' . $objDatabase->SqlVariable($this->dttEndDate) . ',
							`budget` = ' . $objDatabase->SqlVariable($this->fltBudget) . ',
							`spent` = ' . $objDatabase->SqlVariable($this->fltSpent) . '
						WHERE
							`id` = ' . $objDatabase->SqlVariable($this->intId) . '
					');
				}

			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Update __blnRestored and any Non-Identity PK Columns (if applicable)
			$this->__blnRestored = true;


			$this->DeleteCache();

			// Return
			return $mixToReturn;
		}

		/**
		 * Delete this Project
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Project with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`project`
				WHERE
					`id` = ' . $objDatabase->SqlVariable($this->intId) . '');

			$this->DeleteCache();
		}

        /**
 	     * Delete this Project ONLY from the cache
 		 * @return void
		 */
		public function DeleteCache() {
			if (QApplication::$objCacheProvider && QApplication::$Database[1]->Caching) {
				$strCacheKey = QApplication::$objCacheProvider->CreateKey(QApplication::$Database[1]->Database, 'Project', $this->intId);
				QApplication::$objCacheProvider->Delete($strCacheKey);
			}
		}

		/**
		 * Delete all Projects
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`project`');

			if (QApplication::$objCacheProvider && QApplication::$Database[1]->Caching) {
				QApplication::$objCacheProvider->DeleteAll();
			}
		}

		/**
		 * Truncate project table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `project`');

			if (QApplication::$objCacheProvider && QApplication::$Database[1]->Caching) {
				QApplication::$objCacheProvider->DeleteAll();
			}
		}

		/**
		 * Reload this Project from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Project object.');

			$this->DeleteCache();

			// Reload the Object
			$objReloaded = Project::Load($this->intId);

			// Update $this's local variables to match
			$this->ProjectStatusTypeId = $objReloaded->ProjectStatusTypeId;
			$this->ManagerPersonId = $objReloaded->ManagerPersonId;
			$this->strName = $objReloaded->strName;
			$this->strDescription = $objReloaded->strDescription;
			$this->dttStartDate = $objReloaded->dttStartDate;
			$this->dttEndDate = $objReloaded->dttEndDate;
			$this->fltBudget = $objReloaded->fltBudget;
			$this->fltSpent = $objReloaded->fltSpent;
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

				case 'ProjectStatusTypeId':
					/**
					 * Gets the value for intProjectStatusTypeId (Not Null)
					 * @return integer
					 */
					return $this->intProjectStatusTypeId;

				case 'ManagerPersonId':
					/**
					 * Gets the value for intManagerPersonId 
					 * @return integer
					 */
					return $this->intManagerPersonId;

				case 'Name':
					/**
					 * Gets the value for strName (Not Null)
					 * @return string
					 */
					return $this->strName;

				case 'Description':
					/**
					 * Gets the value for strDescription 
					 * @return string
					 */
					return $this->strDescription;

				case 'StartDate':
					/**
					 * Gets the value for dttStartDate 
					 * @return QDateTime
					 */
					return $this->dttStartDate;

				case 'EndDate':
					/**
					 * Gets the value for dttEndDate 
					 * @return QDateTime
					 */
					return $this->dttEndDate;

				case 'Budget':
					/**
					 * Gets the value for fltBudget 
					 * @return double
					 */
					return $this->fltBudget;

				case 'Spent':
					/**
					 * Gets the value for fltSpent 
					 * @return double
					 */
					return $this->fltSpent;


				///////////////////
				// Member Objects
				///////////////////
				case 'ManagerPerson':
					/**
					 * Gets the value for the Person object referenced by intManagerPersonId 
					 * @return Person
					 */
					try {
						if ((!$this->objManagerPerson) && (!is_null($this->intManagerPersonId)))
							$this->objManagerPerson = Person::Load($this->intManagerPersonId);
						return $this->objManagerPerson;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_ProjectAsRelated':
					/**
					 * Gets the value for the private _objProjectAsRelated (Read-Only)
					 * if set due to an expansion on the related_project_assn association table
					 * @return Project
					 */
					return $this->_objProjectAsRelated;

				case '_ProjectAsRelatedArray':
					/**
					 * Gets the value for the private _objProjectAsRelatedArray (Read-Only)
					 * if set due to an ExpandAsArray on the related_project_assn association table
					 * @return Project[]
					 */
					return $this->_objProjectAsRelatedArray;

				case '_ParentProjectAsRelated':
					/**
					 * Gets the value for the private _objParentProjectAsRelated (Read-Only)
					 * if set due to an expansion on the related_project_assn association table
					 * @return Project
					 */
					return $this->_objParentProjectAsRelated;

				case '_ParentProjectAsRelatedArray':
					/**
					 * Gets the value for the private _objParentProjectAsRelatedArray (Read-Only)
					 * if set due to an ExpandAsArray on the related_project_assn association table
					 * @return Project[]
					 */
					return $this->_objParentProjectAsRelatedArray;

				case '_PersonAsTeamMember':
					/**
					 * Gets the value for the private _objPersonAsTeamMember (Read-Only)
					 * if set due to an expansion on the team_member_project_assn association table
					 * @return Person
					 */
					return $this->_objPersonAsTeamMember;

				case '_PersonAsTeamMemberArray':
					/**
					 * Gets the value for the private _objPersonAsTeamMemberArray (Read-Only)
					 * if set due to an ExpandAsArray on the team_member_project_assn association table
					 * @return Person[]
					 */
					return $this->_objPersonAsTeamMemberArray;

				case '_Milestone':
					/**
					 * Gets the value for the private _objMilestone (Read-Only)
					 * if set due to an expansion on the milestone.project_id reverse relationship
					 * @return Milestone
					 */
					return $this->_objMilestone;

				case '_MilestoneArray':
					/**
					 * Gets the value for the private _objMilestoneArray (Read-Only)
					 * if set due to an ExpandAsArray on the milestone.project_id reverse relationship
					 * @return Milestone[]
					 */
					return $this->_objMilestoneArray;


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
				case 'ProjectStatusTypeId':
					/**
					 * Sets the value for intProjectStatusTypeId (Not Null)
					 * @param integer $mixValue
					 * @return integer
					 */
					try {
						return ($this->intProjectStatusTypeId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ManagerPersonId':
					/**
					 * Sets the value for intManagerPersonId 
					 * @param integer $mixValue
					 * @return integer
					 */
					try {
						$this->objManagerPerson = null;
						return ($this->intManagerPersonId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Name':
					/**
					 * Sets the value for strName (Not Null)
					 * @param string $mixValue
					 * @return string
					 */
					try {
						return ($this->strName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Description':
					/**
					 * Sets the value for strDescription 
					 * @param string $mixValue
					 * @return string
					 */
					try {
						return ($this->strDescription = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'StartDate':
					/**
					 * Sets the value for dttStartDate 
					 * @param QDateTime $mixValue
					 * @return QDateTime
					 */
					try {
						return ($this->dttStartDate = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'EndDate':
					/**
					 * Sets the value for dttEndDate 
					 * @param QDateTime $mixValue
					 * @return QDateTime
					 */
					try {
						return ($this->dttEndDate = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Budget':
					/**
					 * Sets the value for fltBudget 
					 * @param double $mixValue
					 * @return double
					 */
					try {
						return ($this->fltBudget = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Spent':
					/**
					 * Sets the value for fltSpent 
					 * @param double $mixValue
					 * @return double
					 */
					try {
						return ($this->fltSpent = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
				case 'ManagerPerson':
					/**
					 * Sets the value for the Person object referenced by intManagerPersonId 
					 * @param Person $mixValue
					 * @return Person
					 */
					if (is_null($mixValue)) {
						$this->intManagerPersonId = null;
						$this->objManagerPerson = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Person object
						try {
							$mixValue = QType::Cast($mixValue, 'Person');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}

						// Make sure $mixValue is a SAVED Person object
						if (is_null($mixValue->Id))
							throw new QCallerException('Unable to set an unsaved ManagerPerson for this Project');

						// Update Local Member Variables
						$this->objManagerPerson = $mixValue;
						$this->intManagerPersonId = $mixValue->Id;

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



		// Related Objects' Methods for Milestone
		//-------------------------------------------------------------------

		/**
		 * Gets all associated Milestones as an array of Milestone objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Milestone[]
		*/
		public function GetMilestoneArray($objOptionalClauses = null) {
			if ((is_null($this->intId)))
				return array();

			try {
				return Milestone::LoadArrayByProjectId($this->intId, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated Milestones
		 * @return int
		*/
		public function CountMilestones() {
			if ((is_null($this->intId)))
				return 0;

			return Milestone::CountByProjectId($this->intId);
		}

		/**
		 * Associates a Milestone
		 * @param Milestone $objMilestone
		 * @return void
		*/
		public function AssociateMilestone(Milestone $objMilestone) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateMilestone on this unsaved Project.');
			if ((is_null($objMilestone->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateMilestone on this Project with an unsaved Milestone.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`milestone`
				SET
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . '
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objMilestone->Id) . ' 
			');
		}

		/**
		 * Unassociates a Milestone
		 * @param Milestone $objMilestone
		 * @return void
		*/
		public function UnassociateMilestone(Milestone $objMilestone) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateMilestone on this unsaved Project.');
			if ((is_null($objMilestone->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateMilestone on this Project with an unsaved Milestone.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`milestone`
				SET
					`project_id` = null
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objMilestone->Id) . ' AND
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Unassociates all Milestones
		 * @return void
		*/
		public function UnassociateAllMilestones() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateMilestone on this unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`milestone`
				SET
					`project_id` = null
				WHERE
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Deletes an associated Milestone
		 * @param Milestone $objMilestone
		 * @return void
		*/
		public function DeleteAssociatedMilestone(Milestone $objMilestone) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateMilestone on this unsaved Project.');
			if ((is_null($objMilestone->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateMilestone on this Project with an unsaved Milestone.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`milestone`
				WHERE
					`id` = ' . $objDatabase->SqlVariable($objMilestone->Id) . ' AND
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		/**
		 * Deletes all associated Milestones
		 * @return void
		*/
		public function DeleteAllMilestones() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateMilestone on this unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`milestone`
				WHERE
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}


		// Related Many-to-Many Objects' Methods for ProjectAsRelated
		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated ProjectsAsRelated as an array of Project objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/
		public function GetProjectAsRelatedArray($objOptionalClauses = null) {
			if ((is_null($this->intId)))
				return array();

			try {
				return Project::LoadArrayByParentProjectAsRelated($this->intId, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated ProjectsAsRelated
		 * @return int
		*/
		public function CountProjectsAsRelated() {
			if ((is_null($this->intId)))
				return 0;

			return Project::CountByParentProjectAsRelated($this->intId);
		}

		/**
		 * Checks to see if an association exists with a specific ProjectAsRelated
		 * @param Project $objProject
		 * @return bool
		*/
		public function IsProjectAsRelatedAssociated(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsProjectAsRelatedAssociated on this unsaved Project.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsProjectAsRelatedAssociated on this Project with an unsaved Project.');

			$intRowCount = Project::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Project()->Id, $this->intId),
					QQ::Equal(QQN::Project()->ProjectAsRelated->ChildProjectId, $objProject->Id)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a ProjectAsRelated
		 * @param Project $objProject
		 * @return void
		*/
		public function AssociateProjectAsRelated(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProjectAsRelated on this unsaved Project.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProjectAsRelated on this Project with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO `related_project_assn` (
					`project_id`,
					`child_project_id`
				) VALUES (
					' . $objDatabase->SqlVariable($this->intId) . ',
					' . $objDatabase->SqlVariable($objProject->Id) . '
				)
			');
		}

		/**
		 * Unassociates a ProjectAsRelated
		 * @param Project $objProject
		 * @return void
		*/
		public function UnassociateProjectAsRelated(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsRelated on this unsaved Project.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProjectAsRelated on this Project with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`related_project_assn`
				WHERE
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . ' AND
					`child_project_id` = ' . $objDatabase->SqlVariable($objProject->Id) . '
			');
		}

		/**
		 * Unassociates all ProjectsAsRelated
		 * @return void
		*/
		public function UnassociateAllProjectsAsRelated() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAllProjectAsRelatedArray on this unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`related_project_assn`
				WHERE
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		// Related Many-to-Many Objects' Methods for ParentProjectAsRelated
		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated ParentProjectsAsRelated as an array of Project objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Project[]
		*/
		public function GetParentProjectAsRelatedArray($objOptionalClauses = null) {
			if ((is_null($this->intId)))
				return array();

			try {
				return Project::LoadArrayByProjectAsRelated($this->intId, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated ParentProjectsAsRelated
		 * @return int
		*/
		public function CountParentProjectsAsRelated() {
			if ((is_null($this->intId)))
				return 0;

			return Project::CountByProjectAsRelated($this->intId);
		}

		/**
		 * Checks to see if an association exists with a specific ParentProjectAsRelated
		 * @param Project $objProject
		 * @return bool
		*/
		public function IsParentProjectAsRelatedAssociated(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsParentProjectAsRelatedAssociated on this unsaved Project.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsParentProjectAsRelatedAssociated on this Project with an unsaved Project.');

			$intRowCount = Project::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Project()->Id, $this->intId),
					QQ::Equal(QQN::Project()->ParentProjectAsRelated->ProjectId, $objProject->Id)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a ParentProjectAsRelated
		 * @param Project $objProject
		 * @return void
		*/
		public function AssociateParentProjectAsRelated(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateParentProjectAsRelated on this unsaved Project.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateParentProjectAsRelated on this Project with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO `related_project_assn` (
					`child_project_id`,
					`project_id`
				) VALUES (
					' . $objDatabase->SqlVariable($this->intId) . ',
					' . $objDatabase->SqlVariable($objProject->Id) . '
				)
			');
		}

		/**
		 * Unassociates a ParentProjectAsRelated
		 * @param Project $objProject
		 * @return void
		*/
		public function UnassociateParentProjectAsRelated(Project $objProject) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateParentProjectAsRelated on this unsaved Project.');
			if ((is_null($objProject->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateParentProjectAsRelated on this Project with an unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`related_project_assn`
				WHERE
					`child_project_id` = ' . $objDatabase->SqlVariable($this->intId) . ' AND
					`project_id` = ' . $objDatabase->SqlVariable($objProject->Id) . '
			');
		}

		/**
		 * Unassociates all ParentProjectsAsRelated
		 * @return void
		*/
		public function UnassociateAllParentProjectsAsRelated() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAllParentProjectAsRelatedArray on this unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`related_project_assn`
				WHERE
					`child_project_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		// Related Many-to-Many Objects' Methods for PersonAsTeamMember
		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated PeopleAsTeamMember as an array of Person objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Person[]
		*/
		public function GetPersonAsTeamMemberArray($objOptionalClauses = null) {
			if ((is_null($this->intId)))
				return array();

			try {
				return Person::LoadArrayByProjectAsTeamMember($this->intId, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated PeopleAsTeamMember
		 * @return int
		*/
		public function CountPeopleAsTeamMember() {
			if ((is_null($this->intId)))
				return 0;

			return Person::CountByProjectAsTeamMember($this->intId);
		}

		/**
		 * Checks to see if an association exists with a specific PersonAsTeamMember
		 * @param Person $objPerson
		 * @return bool
		*/
		public function IsPersonAsTeamMemberAssociated(Person $objPerson) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsPersonAsTeamMemberAssociated on this unsaved Project.');
			if ((is_null($objPerson->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsPersonAsTeamMemberAssociated on this Project with an unsaved Person.');

			$intRowCount = Project::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Project()->Id, $this->intId),
					QQ::Equal(QQN::Project()->PersonAsTeamMember->PersonId, $objPerson->Id)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a PersonAsTeamMember
		 * @param Person $objPerson
		 * @return void
		*/
		public function AssociatePersonAsTeamMember(Person $objPerson) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociatePersonAsTeamMember on this unsaved Project.');
			if ((is_null($objPerson->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociatePersonAsTeamMember on this Project with an unsaved Person.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO `team_member_project_assn` (
					`project_id`,
					`person_id`
				) VALUES (
					' . $objDatabase->SqlVariable($this->intId) . ',
					' . $objDatabase->SqlVariable($objPerson->Id) . '
				)
			');
		}

		/**
		 * Unassociates a PersonAsTeamMember
		 * @param Person $objPerson
		 * @return void
		*/
		public function UnassociatePersonAsTeamMember(Person $objPerson) {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociatePersonAsTeamMember on this unsaved Project.');
			if ((is_null($objPerson->Id)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociatePersonAsTeamMember on this Project with an unsaved Person.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`team_member_project_assn`
				WHERE
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . ' AND
					`person_id` = ' . $objDatabase->SqlVariable($objPerson->Id) . '
			');
		}

		/**
		 * Unassociates all PeopleAsTeamMember
		 * @return void
		*/
		public function UnassociateAllPeopleAsTeamMember() {
			if ((is_null($this->intId)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAllPersonAsTeamMemberArray on this unsaved Project.');

			// Get the Database Object for this Class
			$objDatabase = Project::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`team_member_project_assn`
				WHERE
					`project_id` = ' . $objDatabase->SqlVariable($this->intId) . '
			');
		}

		
		///////////////////////////////
		// METHODS TO EXTRACT INFO ABOUT THE CLASS
		///////////////////////////////

		/**
		 * Static method to retrieve the Database object that owns this class.
		 * @return string Name of the table from which this class has been created.
		 */
		public static function GetTableName() {
			return "project";
		}

		/**
		 * Static method to retrieve the Table name from which this class has been created.
		 * @return string Name of the table from which this class has been created.
		 */
		public static function GetDatabaseName() {
			return QApplication::$Database[Project::GetDatabaseIndex()]->Database;
		}

		/**
		 * Static method to retrieve the Database index in the configuration.inc.php file.
		 * This can be useful when there are two databases of the same name which create
		 * confusion for the developer. There are no internal uses of this function but are
		 * here to help retrieve info if need be!
		 * @return int position or index of the database in the config file.
		 */
		public static function GetDatabaseIndex() {
			return 1;
		}

		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Project"><sequence>';
			$strToReturn .= '<element name="Id" type="xsd:int"/>';
			$strToReturn .= '<element name="ProjectStatusTypeId" type="xsd:int"/>';
			$strToReturn .= '<element name="ManagerPerson" type="xsd1:Person"/>';
			$strToReturn .= '<element name="Name" type="xsd:string"/>';
			$strToReturn .= '<element name="Description" type="xsd:string"/>';
			$strToReturn .= '<element name="StartDate" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="EndDate" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Budget" type="xsd:float"/>';
			$strToReturn .= '<element name="Spent" type="xsd:float"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Project', $strComplexTypeArray)) {
				$strComplexTypeArray['Project'] = Project::GetSoapComplexTypeXml();
				Person::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Project::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Project();
			if (property_exists($objSoapObject, 'Id'))
				$objToReturn->intId = $objSoapObject->Id;
			if (property_exists($objSoapObject, 'ProjectStatusTypeId'))
				$objToReturn->intProjectStatusTypeId = $objSoapObject->ProjectStatusTypeId;
			if ((property_exists($objSoapObject, 'ManagerPerson')) &&
				($objSoapObject->ManagerPerson))
				$objToReturn->ManagerPerson = Person::GetObjectFromSoapObject($objSoapObject->ManagerPerson);
			if (property_exists($objSoapObject, 'Name'))
				$objToReturn->strName = $objSoapObject->Name;
			if (property_exists($objSoapObject, 'Description'))
				$objToReturn->strDescription = $objSoapObject->Description;
			if (property_exists($objSoapObject, 'StartDate'))
				$objToReturn->dttStartDate = new QDateTime($objSoapObject->StartDate);
			if (property_exists($objSoapObject, 'EndDate'))
				$objToReturn->dttEndDate = new QDateTime($objSoapObject->EndDate);
			if (property_exists($objSoapObject, 'Budget'))
				$objToReturn->fltBudget = $objSoapObject->Budget;
			if (property_exists($objSoapObject, 'Spent'))
				$objToReturn->fltSpent = $objSoapObject->Spent;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Project::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objManagerPerson)
				$objObject->objManagerPerson = Person::GetSoapObjectFromObject($objObject->objManagerPerson, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intManagerPersonId = null;
			if ($objObject->dttStartDate)
				$objObject->dttStartDate = $objObject->dttStartDate->qFormat(QDateTime::FormatSoap);
			if ($objObject->dttEndDate)
				$objObject->dttEndDate = $objObject->dttEndDate->qFormat(QDateTime::FormatSoap);
			return $objObject;
		}


		////////////////////////////////////////
		// METHODS for JSON Object Translation
		////////////////////////////////////////

		// this function is required for objects that implement the
		// IteratorAggregate interface
		public function getIterator() {
			///////////////////
			// Member Variables
			///////////////////
			$iArray['Id'] = $this->intId;
			$iArray['ProjectStatusTypeId'] = $this->intProjectStatusTypeId;
			$iArray['ManagerPersonId'] = $this->intManagerPersonId;
			$iArray['Name'] = $this->strName;
			$iArray['Description'] = $this->strDescription;
			$iArray['StartDate'] = $this->dttStartDate;
			$iArray['EndDate'] = $this->dttEndDate;
			$iArray['Budget'] = $this->fltBudget;
			$iArray['Spent'] = $this->fltSpent;
			return new ArrayIterator($iArray);
		}

		// this function returns a Json formatted string using the
		// IteratorAggregate interface
		public function getJson() {
			return json_encode($this->getIterator());
		}

		/**
		 * Default "toJsObject" handler
		 * Specifies how the object should be displayed in JQuery UI lists and menus. Note that these lists use
		 * value and label differently.
		 *
		 * value 	= The short form of what to display in the list and selection.
		 * label 	= [optional] If defined, is what is displayed in the menu
		 * id 		= Primary key of object.
		 *
		 * @return an array that specifies how to display the object
		 */
		public function toJsObject () {
			return JavaScriptHelper::toJsObject(array('value' => $this->__toString(), 'id' =>  $this->intId ));
		}



	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCubed QUERY
	/////////////////////////////////////

    /**
     * @uses QQAssociationNode
     *
     * @property-read QQNode $ChildProjectId
     * @property-read QQNodeProject $Project
     * @property-read QQNodeProject $_ChildTableNode
     **/
	class QQNodeProjectProjectAsRelated extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = 'projectasrelated';

		protected $strTableName = 'related_project_assn';
		protected $strPrimaryKey = 'project_id';
		protected $strClassName = 'Project';

		public function __get($strName) {
			switch ($strName) {
				case 'ChildProjectId':
					return new QQNode('child_project_id', 'ChildProjectId', 'integer', $this);
				case 'Project':
					return new QQNodeProject('child_project_id', 'ChildProjectId', 'integer', $this);
				case '_ChildTableNode':
					return new QQNodeProject('child_project_id', 'ChildProjectId', 'integer', $this);
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
     * @uses QQAssociationNode
     *
     * @property-read QQNode $ProjectId
     * @property-read QQNodeProject $Project
     * @property-read QQNodeProject $_ChildTableNode
     **/
	class QQNodeProjectParentProjectAsRelated extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = 'parentprojectasrelated';

		protected $strTableName = 'related_project_assn';
		protected $strPrimaryKey = 'child_project_id';
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

    /**
     * @uses QQAssociationNode
     *
     * @property-read QQNode $PersonId
     * @property-read QQNodePerson $Person
     * @property-read QQNodePerson $_ChildTableNode
     **/
	class QQNodeProjectPersonAsTeamMember extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = 'personasteammember';

		protected $strTableName = 'team_member_project_assn';
		protected $strPrimaryKey = 'project_id';
		protected $strClassName = 'Person';

		public function __get($strName) {
			switch ($strName) {
				case 'PersonId':
					return new QQNode('person_id', 'PersonId', 'integer', $this);
				case 'Person':
					return new QQNodePerson('person_id', 'PersonId', 'integer', $this);
				case '_ChildTableNode':
					return new QQNodePerson('person_id', 'PersonId', 'integer', $this);
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
     * @uses QQNode
     *
     * @property-read QQNode $Id
     * @property-read QQNode $ProjectStatusTypeId
     * @property-read QQNode $ManagerPersonId
     * @property-read QQNodePerson $ManagerPerson
     * @property-read QQNode $Name
     * @property-read QQNode $Description
     * @property-read QQNode $StartDate
     * @property-read QQNode $EndDate
     * @property-read QQNode $Budget
     * @property-read QQNode $Spent
     *
     * @property-read QQNodeProjectProjectAsRelated $ProjectAsRelated
     * @property-read QQNodeProjectParentProjectAsRelated $ParentProjectAsRelated
     * @property-read QQNodeProjectPersonAsTeamMember $PersonAsTeamMember
     *
     * @property-read QQReverseReferenceNodeMilestone $Milestone

     * @property-read QQNode $_PrimaryKeyNode
     **/
	class QQNodeProject extends QQNode {
		protected $strTableName = 'project';
		protected $strPrimaryKey = 'id';
		protected $strClassName = 'Project';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new QQNode('id', 'Id', 'Integer', $this);
				case 'ProjectStatusTypeId':
					return new QQNode('project_status_type_id', 'ProjectStatusTypeId', 'Integer', $this);
				case 'ManagerPersonId':
					return new QQNode('manager_person_id', 'ManagerPersonId', 'Integer', $this);
				case 'ManagerPerson':
					return new QQNodePerson('manager_person_id', 'ManagerPerson', 'Integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'VarChar', $this);
				case 'Description':
					return new QQNode('description', 'Description', 'Blob', $this);
				case 'StartDate':
					return new QQNode('start_date', 'StartDate', 'Date', $this);
				case 'EndDate':
					return new QQNode('end_date', 'EndDate', 'Date', $this);
				case 'Budget':
					return new QQNode('budget', 'Budget', 'Float', $this);
				case 'Spent':
					return new QQNode('spent', 'Spent', 'Float', $this);
				case 'ProjectAsRelated':
					return new QQNodeProjectProjectAsRelated($this);
				case 'ParentProjectAsRelated':
					return new QQNodeProjectParentProjectAsRelated($this);
				case 'PersonAsTeamMember':
					return new QQNodeProjectPersonAsTeamMember($this);
				case 'Milestone':
					return new QQReverseReferenceNodeMilestone($this, 'milestone', 'reverse_reference', 'project_id');

				case '_PrimaryKeyNode':
					return new QQNode('id', 'Id', 'Integer', $this);
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
     * @property-read QQNode $Id
     * @property-read QQNode $ProjectStatusTypeId
     * @property-read QQNode $ManagerPersonId
     * @property-read QQNodePerson $ManagerPerson
     * @property-read QQNode $Name
     * @property-read QQNode $Description
     * @property-read QQNode $StartDate
     * @property-read QQNode $EndDate
     * @property-read QQNode $Budget
     * @property-read QQNode $Spent
     *
     * @property-read QQNodeProjectProjectAsRelated $ProjectAsRelated
     * @property-read QQNodeProjectParentProjectAsRelated $ParentProjectAsRelated
     * @property-read QQNodeProjectPersonAsTeamMember $PersonAsTeamMember
     *
     * @property-read QQReverseReferenceNodeMilestone $Milestone

     * @property-read QQNode $_PrimaryKeyNode
     **/
	class QQReverseReferenceNodeProject extends QQReverseReferenceNode {
		protected $strTableName = 'project';
		protected $strPrimaryKey = 'id';
		protected $strClassName = 'Project';
		public function __get($strName) {
			switch ($strName) {
				case 'Id':
					return new QQNode('id', 'Id', 'integer', $this);
				case 'ProjectStatusTypeId':
					return new QQNode('project_status_type_id', 'ProjectStatusTypeId', 'integer', $this);
				case 'ManagerPersonId':
					return new QQNode('manager_person_id', 'ManagerPersonId', 'integer', $this);
				case 'ManagerPerson':
					return new QQNodePerson('manager_person_id', 'ManagerPerson', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'Description':
					return new QQNode('description', 'Description', 'string', $this);
				case 'StartDate':
					return new QQNode('start_date', 'StartDate', 'QDateTime', $this);
				case 'EndDate':
					return new QQNode('end_date', 'EndDate', 'QDateTime', $this);
				case 'Budget':
					return new QQNode('budget', 'Budget', 'double', $this);
				case 'Spent':
					return new QQNode('spent', 'Spent', 'double', $this);
				case 'ProjectAsRelated':
					return new QQNodeProjectProjectAsRelated($this);
				case 'ParentProjectAsRelated':
					return new QQNodeProjectParentProjectAsRelated($this);
				case 'PersonAsTeamMember':
					return new QQNodeProjectPersonAsTeamMember($this);
				case 'Milestone':
					return new QQReverseReferenceNodeMilestone($this, 'milestone', 'reverse_reference', 'project_id');

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
