<?php
	require(__QCUBED_CORE__ . '/codegen/QColumn.class.php');
	require(__QCUBED_CORE__ . '/codegen/QIndex.class.php');
	require(__QCUBED_CORE__ . '/codegen/QManyToManyReference.class.php');
	require(__QCUBED_CORE__ . '/codegen/QReference.class.php');
	require(__QCUBED_CORE__ . '/codegen/QReverseReference.class.php');
	require(__QCUBED_CORE__ . '/codegen/QTable.class.php');
	require(__QCUBED_CORE__ . '/codegen/QTypeTable.class.php');
	require(__QCUBED_CORE__ . '/codegen/QModelConnectorOptions.class.php');

	/**
	 * @package Codegen
	 */
	class QDatabaseCodeGen extends QCodeGen {
		public $objSettingsXml;	// Make public so templates can use it directly.

		// Objects
		/** @var array|QTable[] Array of tables in the database */
		protected $objTableArray;
		protected $strExcludedTableArray;
		protected $objTypeTableArray;
		protected $strAssociationTableNameArray;
		/** @var QDatabaseBase The database we are dealing with */
		protected $objDb;

		protected $intDatabaseIndex;
		/** @var string The delimiter to be used for parsing comments on the DB tables for being used as the name of ModelConnector's Label */
		protected $strCommentConnectorLabelDelimiter;

		// Table Suffixes
		protected $strTypeTableSuffixArray;
		protected $intTypeTableSuffixLengthArray;
		protected $strAssociationTableSuffix;
		protected $intAssociationTableSuffixLength;

		// Table Prefix
		protected $strStripTablePrefix;
		protected $intStripTablePrefixLength;

		// Exclude Patterns & Lists
		protected $strExcludePattern;
		protected $strExcludeListArray;

		// Include Patterns & Lists
		protected $strIncludePattern;
		protected $strIncludeListArray;

		// Uniquely Associated Objects
		protected $strAssociatedObjectPrefix;
		protected $strAssociatedObjectSuffix;

		// Manual Query (e.g. "Beta 2 Query") Suppor
		protected $blnManualQuerySupport = false;

		// Relationship Scripts
		protected $strRelationships;
		protected $blnRelationshipsIgnoreCase;

		protected $strRelationshipsScriptPath;
		protected $strRelationshipsScriptFormat;
		protected $blnRelationshipsScriptIgnoreCase;

		protected $strRelationshipLinesQcubed = array();
		protected $strRelationshipLinesSql = array();

		// Type Table Items, Table Name and Column Name RegExp Patterns
		protected $strPatternTableName = '[[:alpha:]_][[:alnum:]_]*';
		protected $strPatternColumnName = '[[:alpha:]_][[:alnum:]_]*';
		protected $strPatternKeyName = '[[:alpha:]_][[:alnum:]_]*';

		protected $blnGenerateControlId;
		protected $objModelConnectorOptions;
		protected $blnAutoInitialize;

		/**
		 * @param $strTableName
		 * @return QTable|QTypeTable
		 * @throws QCallerException
		 */
		public function GetTable($strTableName) {
			$strTableName = strtolower($strTableName);
			if (array_key_exists($strTableName, $this->objTableArray))
				return $this->objTableArray[$strTableName];
			if (array_key_exists($strTableName, $this->objTypeTableArray)) 
				return $this->objTypeTableArray[$strTableName];;	// deal with table special
			throw new QCallerException(sprintf('Table does not exist or does not have a defined Primary Key: %s', $strTableName));
		}

		public function GetColumn($strTableName, $strColumnName) {
			try {
				$objTable = $this->GetTable($strTableName);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			$strColumnName = strtolower($strColumnName);
			if (array_key_exists($strColumnName, $objTable->ColumnArray))
				return $objTable->ColumnArray[$strColumnName];
			throw new QCallerException(sprintf('Column does not exist in %s: %s', $strTableName, $strColumnName));
		}

		/**
		 * Given a CASE INSENSITIVE table and column name, it will return TRUE if the Table/Column
		 * exists ANYWHERE in the already analyzed database
		 *
		 * @param string $strTableName
		 * @param string $strColumnName
		 * @return boolean true if it is found/validated
		 */
		public function ValidateTableColumn($strTableName, $strColumnName) {
			$strTableName = trim(strtolower($strTableName));
			$strColumnName = trim(strtolower($strColumnName));

			if (array_key_exists($strTableName, $this->objTableArray))
				$strTableName = $this->objTableArray[$strTableName]->Name;
			else if (array_key_exists($strTableName, $this->objTypeTableArray))
				$strTableName = $this->objTypeTableArray[$strTableName]->Name;
			else if (array_key_exists($strTableName, $this->strAssociationTableNameArray))
				$strTableName = $this->strAssociationTableNameArray[$strTableName];
			else
				return false;

			$objFieldArray = $this->objDb->GetFieldsForTable($strTableName);

			foreach ($objFieldArray as $objField) {
				if (trim(strtolower($objField->Name)) == $strColumnName)
					return true;
			}

			return false;
		}

		public function GetTitle() {
			if (!QApplication::$Database) {
				return '';
			}
			
			if (array_key_exists($this->intDatabaseIndex, QApplication::$Database)) {
				$objDatabase = QApplication::$Database[$this->intDatabaseIndex];
				return sprintf('Database Index #%s (%s / %s / %s)', $this->intDatabaseIndex, $objDatabase->Adapter, $objDatabase->Server, $objDatabase->Database);
			} else
				return sprintf('Database Index #%s (N/A)', $this->intDatabaseIndex);
		}

		public function GetConfigXml() {
			$strCrLf = "\r\n";
			$strToReturn = sprintf('		<database index="%s">%s', $this->intDatabaseIndex, $strCrLf);
			$strToReturn .= sprintf('			<className prefix="%s" suffix="%s"/>%s', $this->strClassPrefix, $this->strClassSuffix, $strCrLf);
			$strToReturn .= sprintf('			<associatedObjectName prefix="%s" suffix="%s"/>%s', $this->strAssociatedObjectPrefix, $this->strAssociatedObjectSuffix, $strCrLf);
			$strToReturn .= sprintf('			<typeTableIdentifier suffix="%s"/>%s', implode(',', $this->strTypeTableSuffixArray), $strCrLf);
			$strToReturn .= sprintf('			<associationTableIdentifier suffix="%s"/>%s', $this->strAssociationTableSuffix, $strCrLf);
			$strToReturn .= sprintf('			<stripFromTableName prefix="%s"/>%s', $this->strStripTablePrefix, $strCrLf);
			$strToReturn .= sprintf('			<excludeTables pattern="%s" list="%s"/>%s', $this->strExcludePattern, implode(',', $this->strExcludeListArray), $strCrLf);
			$strToReturn .= sprintf('			<includeTables pattern="%s" list="%s"/>%s', $this->strIncludePattern, implode(',', $this->strIncludeListArray), $strCrLf);
			$strToReturn .= sprintf('			<manualQuery support="%s"/>%s', ($this->blnManualQuerySupport) ? 'true' : 'false', $strCrLf);
			$strToReturn .= sprintf('			<relationships>%s', $strCrLf);
			if ($this->strRelationships)
				$strToReturn .= sprintf('			%s%s', $this->strRelationships, $strCrLf);
			$strToReturn .= sprintf('			</relationships>%s', $strCrLf);
			$strToReturn .= sprintf('			<relationshipsScript filepath="%s" format="%s"/>%s', $this->strRelationshipsScriptPath, $this->strRelationshipsScriptFormat, $strCrLf);
			$strToReturn .= sprintf('		</database>%s', $strCrLf);
			return $strToReturn;
		}

		public function GetReportLabel() {
			// Setup Report Label
			$intTotalTableCount = count($this->objTableArray) + count($this->objTypeTableArray);
			if ($intTotalTableCount == 0)
				$strReportLabel = 'There were no tables available to attempt code generation.';
			else if ($intTotalTableCount == 1)
				$strReportLabel = 'There was 1 table available to attempt code generation:';
			else
				$strReportLabel = 'There were ' . $intTotalTableCount . ' tables available to attempt code generation:';

			return $strReportLabel;
		}

		public function GenerateAll() {
			$strReport = '';

			// Iterate through all the tables, generating one class at a time
			if ($this->objTableArray) foreach ($this->objTableArray as $objTable) {
				if ($this->GenerateTable($objTable)) {
					$intCount = $objTable->ReferenceCount;
					if ($intCount == 0)
						$strCount = '(with no relationships)';
					else if ($intCount == 1)
						$strCount = '(with 1 relationship)';
					else
						$strCount = sprintf('(with %s relationships)', $intCount);
					$strReport .= sprintf("Successfully generated DB ORM Class:   %s %s\r\n", $objTable->ClassName, $strCount);
				} else
					$strReport .= sprintf("FAILED to generate DB ORM Class:       %s\r\n", $objTable->ClassName);
			}

			// Iterate through all the TYPE tables, generating one TYPE class at a time
			if ($this->objTypeTableArray) foreach ($this->objTypeTableArray as $objTypeTable) {
				if ($this->GenerateTypeTable($objTypeTable))
					$strReport .= sprintf("Successfully generated DB Type Class:  %s\n", $objTypeTable->ClassName);
				else
					$strReport .= sprintf("FAILED to generate DB Type class:      %s\n", $objTypeTable->ClassName);
			}

			return $strReport;
		}

		public static function GenerateAggregateHelper($objCodeGenArray) {
			$strToReturn = array();

			if (count($objCodeGenArray)) {
				// Standard ORM Tables
				$objTableArray = array();
				foreach ($objCodeGenArray as $objCodeGen) {
					$objCurrentTableArray = $objCodeGen->TableArray;
					foreach ($objCurrentTableArray as $objTable)
						$objTableArray[$objTable->ClassName] = $objTable;
				}

				$mixArgumentArray = array('objTableArray' => $objTableArray);
				if ($objCodeGenArray[0]->GenerateFiles('aggregate_db_orm', $mixArgumentArray))
					$strToReturn[] = 'Successfully generated Aggregate DB ORM file(s)';
				else
					$strToReturn[] = 'FAILED to generate Aggregate DB ORM file(s)';

				// Type Tables
				$objTableArray = array();
				foreach ($objCodeGenArray as $objCodeGen) {
					$objCurrentTableArray = $objCodeGen->TypeTableArray;
					foreach ($objCurrentTableArray as $objTable)
						$objTableArray[$objTable->ClassName] = $objTable;
				}

				$mixArgumentArray = array('objTableArray' => $objTableArray);
				if ($objCodeGenArray[0]->GenerateFiles('aggregate_db_type', $mixArgumentArray))
					$strToReturn[] = 'Successfully generated Aggregate DB Type file(s)';
				else
					$strToReturn[] = 'FAILED to generate Aggregate DB Type file(s)';
			}

			return $strToReturn;
		}

		public function __construct($objSettingsXml) {
			parent::__construct($objSettingsXml);
			// Make settings file accessible to templates
			//$this->objSettingsXml = $objSettingsXml;

			// Setup Local Arrays
			$this->strAssociationTableNameArray = array();
			$this->objTableArray = array();
			$this->objTypeTableArray = array();
			$this->strExcludedTableArray = array();

			// Set the DatabaseIndex
			$this->intDatabaseIndex = QCodeGen::LookupSetting($objSettingsXml, null, 'index', QType::Integer);

			// Append Suffix/Prefixes
			$this->strClassPrefix = QCodeGen::LookupSetting($objSettingsXml, 'className', 'prefix');
			$this->strClassSuffix = QCodeGen::LookupSetting($objSettingsXml, 'className', 'suffix');
			$this->strAssociatedObjectPrefix = QCodeGen::LookupSetting($objSettingsXml, 'associatedObjectName', 'prefix');
			$this->strAssociatedObjectSuffix = QCodeGen::LookupSetting($objSettingsXml, 'associatedObjectName', 'suffix');

			// Table Type Identifiers
			$strTypeTableSuffixList = QCodeGen::LookupSetting($objSettingsXml, 'typeTableIdentifier', 'suffix');
			$strTypeTableSuffixArray = explode(',', $strTypeTableSuffixList);
			foreach ($strTypeTableSuffixArray as $strTypeTableSuffix) {
				$this->strTypeTableSuffixArray[] = trim($strTypeTableSuffix);
				$this->intTypeTableSuffixLengthArray[] = strlen(trim($strTypeTableSuffix));
			}
			$this->strAssociationTableSuffix = QCodeGen::LookupSetting($objSettingsXml, 'associationTableIdentifier', 'suffix');
			$this->intAssociationTableSuffixLength = strlen($this->strAssociationTableSuffix);

			// Stripping TablePrefixes
			$this->strStripTablePrefix = QCodeGen::LookupSetting($objSettingsXml, 'stripFromTableName', 'prefix');
			$this->intStripTablePrefixLength = strlen($this->strStripTablePrefix);

			// Exclude/Include Tables
			$this->strExcludePattern = QCodeGen::LookupSetting($objSettingsXml, 'excludeTables', 'pattern');
			$strExcludeList = QCodeGen::LookupSetting($objSettingsXml, 'excludeTables', 'list');
			$this->strExcludeListArray = explode(',',$strExcludeList);
			array_walk($this->strExcludeListArray, 'array_trim');

			// Include Patterns
			$this->strIncludePattern = QCodeGen::LookupSetting($objSettingsXml, 'includeTables', 'pattern');
			$strIncludeList = QCodeGen::LookupSetting($objSettingsXml, 'includeTables', 'list');
			$this->strIncludeListArray = explode(',',$strIncludeList);
			array_walk($this->strIncludeListArray, 'array_trim');

			// ManualQuery Support
			$this->blnManualQuerySupport = QCodeGen::LookupSetting($objSettingsXml, 'manualQuery', 'support', QType::Boolean);

			// Relationship Scripts
			$this->strRelationships = QCodeGen::LookupSetting($objSettingsXml, 'relationships');
			$this->strRelationshipsScriptPath = QCodeGen::LookupSetting($objSettingsXml, 'relationshipsScript', 'filepath');
			$this->strRelationshipsScriptFormat = QCodeGen::LookupSetting($objSettingsXml, 'relationshipsScript', 'format');

			// Column Comment for ModelConnectorLabel setting.
			$this->strCommentConnectorLabelDelimiter = QCodeGen::LookupSetting($objSettingsXml, 'columnCommentForModelConnector', 'delimiter');

			// Check to make sure things that are required are there
			if (!$this->intDatabaseIndex)
				$this->strErrors .= "CodeGen Settings XML Fatal Error: databaseIndex was invalid or not set\r\n";

			// Aggregate RelationshipLinesQcubed and RelationshipLinesSql arrays
			if ($this->strRelationships) {
				$strLines = explode("\n", strtolower($this->strRelationships));
				if ($strLines) foreach ($strLines as $strLine) {
					$strLine = trim($strLine);

					if (($strLine) &&
						(strlen($strLine) > 2) &&
						(substr($strLine, 0, 2) != '//') &&
						(substr($strLine, 0, 2) != '--') &&
						(substr($strLine, 0, 1) != '#')) {
						$this->strRelationshipLinesQcubed[$strLine] = $strLine;
					}
				}
			}

			if ($this->strRelationshipsScriptPath) {
				if (!file_exists($this->strRelationshipsScriptPath))
					$this->strErrors .= sprintf("CodeGen Settings XML Fatal Error: relationshipsScript filepath \"%s\" does not exist\r\n", $this->strRelationshipsScriptPath);
				else {
					$strScript = strtolower(trim(file_get_contents($this->strRelationshipsScriptPath)));
					switch (strtolower($this->strRelationshipsScriptFormat)) {
						case 'qcodo':
						case 'qcubed':
							$strLines = explode("\n", $strScript);
							if ($strLines) foreach ($strLines as $strLine) {
								$strLine = trim($strLine);

								if (($strLine) &&
									(strlen($strLine) > 2) &&
									(substr($strLine, 0, 2) != '//') &&
									(substr($strLine, 0, 2) != '--') &&
									(substr($strLine, 0, 1) != '#')) {
									$this->strRelationshipLinesQcubed[$strLine] = $strLine;
								}
							}
							break;

						case 'sql':
							// Separate all commands in the script (separated by ";")
							$strCommands = explode(';', $strScript);
							if ($strCommands) foreach ($strCommands as $strCommand) {
								$strCommand = trim($strCommand);

								if ($strCommand) {
									// Take out all comment lines in the script
									$strLines = explode("\n", $strCommand);
									$strCommand = '';
									foreach ($strLines as $strLine) {
										$strLine = trim($strLine);
										if (($strLine) &&
											(substr($strLine, 0, 2) != '//') &&
											(substr($strLine, 0, 2) != '--') &&
											(substr($strLine, 0, 1) != '#')) {
											$strLine = str_replace('	', ' ', $strLine);
											$strLine = str_replace('        ', ' ', $strLine);
											$strLine = str_replace('       ', ' ', $strLine);
											$strLine = str_replace('      ', ' ', $strLine);
											$strLine = str_replace('     ', ' ', $strLine);
											$strLine = str_replace('    ', ' ', $strLine);
											$strLine = str_replace('   ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);
											$strLine = str_replace('  ', ' ', $strLine);

											$strCommand .= $strLine . ' ';
										}
									}

									$strCommand = trim($strCommand);
									if ((strpos($strCommand, 'alter table') === 0) &&
										(strpos($strCommand, 'foreign key') !== false))
										$this->strRelationshipLinesSql[$strCommand] = $strCommand;
								}
							}
							break;

						default:
							$this->strErrors .= sprintf("CodeGen Settings XML Fatal Error: relationshipsScript format \"%s\" is invalid (must be either \"qcubed\", \"qcodo\" or \"sql\")\r\n", $this->strRelationshipsScriptFormat);
							break;
					}
				}
			}

			$this->blnGenerateControlId = QCodeGen::LookupSetting($objSettingsXml, 'generateControlId', 'support', QType::Boolean);
			$this->objModelConnectorOptions = new QModelConnectorOptions();

			$this->blnAutoInitialize = QCodeGen::LookupSetting($objSettingsXml, 'createOptions', 'autoInitialize', QType::Boolean);
			
			if ($this->strErrors)
				return;

			$this->AnalyzeDatabase();
		}

		protected function AnalyzeDatabase() {
			if (!QApplication::$Database) {
				$this->strErrors = 'FATAL ERROR: No databases are listed in the configuration file.';
				return;
			}
			
			// Set aside the Database object
			if (array_key_exists($this->intDatabaseIndex, QApplication::$Database))
				$this->objDb = QApplication::$Database[$this->intDatabaseIndex];

			// Ensure the DB Exists
			if (!$this->objDb) {
				$this->strErrors = 'FATAL ERROR: No database configured at index ' . $this->intDatabaseIndex . '. Check your configuration file.';
				return;
			}

			// Ensure DB Profiling is DISABLED on this DB
			if ($this->objDb->EnableProfiling) {
				$this->strErrors = 'FATAL ERROR: Code generator cannot analyze the database at index ' . $this->intDatabaseIndex . ' while DB Profiling is enabled.';
				return;
			}

			// Get the list of Tables as a string[]
			$strTableArray = $this->objDb->GetTables();


			// ITERATION 1: Simply create the Table and TypeTable Arrays
			if ($strTableArray) {
				foreach ($strTableArray as $strTableName) {

					// Do we Exclude this Table Name? (given includeTables and excludeTables)
					// First check the lists of Excludes and the Exclude Patterns
					if (in_array($strTableName, $this->strExcludeListArray) ||
						(strlen($this->strExcludePattern) > 0 && preg_match(":" . $this->strExcludePattern . ":i", $strTableName))
					) {

						// So we THINK we may be excluding this table
						// But check against the explicit INCLUDE list and patterns
						if (in_array($strTableName, $this->strIncludeListArray) ||
							(strlen($this->strIncludePattern) > 0 && preg_match(":" . $this->strIncludePattern . ":i", $strTableName))
						) {
							// If we're here, we explicitly want to include this table
							// Therefore, do nothing
						} else {
							// If we're here, then we want to exclude this table
							$this->strExcludedTableArray[strtolower($strTableName)] = true;

							// Exit this iteration of the foreach loop
							continue;
						}
					}

					// Check to see if this table name exists anywhere else yet, and warn if it is
					foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
						if ($objCodeGen instanceof QDatabaseCodeGen) {
							foreach ($objCodeGen->objTableArray as $objPossibleDuplicate)
								if (strtolower($objPossibleDuplicate->Name) == strtolower($strTableName)) {
									$this->strErrors .= 'Duplicate Table Name Used: ' . $strTableName . "\r\n";
								}
						}
					}

					// Perform different tasks based on whether it's an Association table,
					// a Type table, or just a regular table
					$blnIsTypeTable = false;
					foreach ($this->intTypeTableSuffixLengthArray as $intIndex => $intTypeTableSuffixLength) {
						if (($intTypeTableSuffixLength) &&
							(strlen($strTableName) > $intTypeTableSuffixLength) &&
							(substr($strTableName, strlen($strTableName) - $intTypeTableSuffixLength) == $this->strTypeTableSuffixArray[$intIndex])
						) {
							// Let's mark, that we have type table
							$blnIsTypeTable = true;
							// Create a TYPE Table and add it to the array
							$objTypeTable = new QTypeTable($strTableName);
							$this->objTypeTableArray[strtolower($strTableName)] = $objTypeTable;
							// If we found type table, there is no point of iterating for other type table suffixes
							break;
//						_p("TYPE Table: $strTableName<br />", false);
						}
					}
					if (!$blnIsTypeTable) {
						// If current table wasn't type table, let's look for other table types
						if (($this->intAssociationTableSuffixLength) &&
							(strlen($strTableName) > $this->intAssociationTableSuffixLength) &&
							(substr($strTableName, strlen($strTableName) - $this->intAssociationTableSuffixLength) == $this->strAssociationTableSuffix)
						) {
							// Add this ASSOCIATION Table Name to the array
							$this->strAssociationTableNameArray[strtolower($strTableName)] = $strTableName;
//						_p("ASSN Table: $strTableName<br />", false);

						} else {
							// Create a Regular Table and add it to the array
							$objTable = new QTable($strTableName);
							$this->objTableArray[strtolower($strTableName)] = $objTable;
//						_p("Table: $strTableName<br />", false);
						}
					}
				}
			}


			// Analyze All the Type Tables
			if ($this->objTypeTableArray) foreach ($this->objTypeTableArray as $objTypeTable)
				$this->AnalyzeTypeTable($objTypeTable);

			// Analyze All the Regular Tables
			if ($this->objTableArray) foreach ($this->objTableArray as $objTable)
				$this->AnalyzeTable($objTable);

			// Analyze All the Association Tables
			if ($this->strAssociationTableNameArray) foreach ($this->strAssociationTableNameArray as $strAssociationTableName)
				$this->AnalyzeAssociationTable($strAssociationTableName);

			// Finally, for each Relationship in all Tables, Warn on Non Single Column PK based FK:
			if ($this->objTableArray) foreach ($this->objTableArray as $objTable)
				if ($objTable->ColumnArray) foreach ($objTable->ColumnArray as $objColumn)
					if ($objColumn->Reference && !$objColumn->Reference->IsType) {
						$objReference = $objColumn->Reference;
//						$objReferencedTable = $this->objTableArray[strtolower($objReference->Table)];
						$objReferencedTable = $this->GetTable($objReference->Table);
						$objReferencedColumn = $objReferencedTable->ColumnArray[strtolower($objReference->Column)];


						if (!$objReferencedColumn->PrimaryKey) {
							$this->strErrors .= sprintf("Warning: Invalid Relationship created in %s class (for foreign key \"%s\") -- column \"%s\" is not the single-column primary key for the referenced \"%s\" table\r\n",
								$objReferencedTable->ClassName, $objReference->KeyName, $objReferencedColumn->Name, $objReferencedTable->Name);
						} else if (count($objReferencedTable->PrimaryKeyColumnArray) != 1) {
							$this->strErrors .= sprintf("Warning: Invalid Relationship created in %s class (for foreign key \"%s\") -- column \"%s\" is not the single-column primary key for the referenced \"%s\" table\r\n",
								$objReferencedTable->ClassName, $objReference->KeyName, $objReferencedColumn->Name, $objReferencedTable->Name);
						}
					}
		}

		protected function ListOfColumnsFromTable(QTable $objTable) {
			$strArray = array();
			$objColumnArray = $objTable->ColumnArray;
			if ($objColumnArray) foreach ($objColumnArray as $objColumn)
				array_push($strArray, $objColumn->Name);
			return implode(', ', $strArray);
		}

		protected function GetColumnArray(QTable $objTable, $strColumnNameArray) {
			$objToReturn = array();

			if ($strColumnNameArray) foreach ($strColumnNameArray as $strColumnName) {
				array_push($objToReturn, $objTable->ColumnArray[strtolower($strColumnName)]);
			}

			return $objToReturn;
		}

		public function GenerateTable(QTable $objTable) {
			// Create Argument Array
			$mixArgumentArray = array('objTable' => $objTable);
			return $this->GenerateFiles('db_orm', $mixArgumentArray);
		}

		public function GenerateTypeTable(QTypeTable $objTypeTable) {
			// Create Argument Array
			$mixArgumentArray = array('objTypeTable' => $objTypeTable);
			return $this->GenerateFiles('db_type', $mixArgumentArray);
		}

		protected function AnalyzeAssociationTable($strTableName) {
			$objFieldArray = $this->objDb->GetFieldsForTable($strTableName);

			// Association tables must have 2 fields
			if (count($objFieldArray) != 2) {
				$this->strErrors .= sprintf("AssociationTable %s does not have exactly 2 columns.\n",
					$strTableName);
				return;
			}

			if ((!$objFieldArray[0]->NotNull) ||
				(!$objFieldArray[1]->NotNull)) {
				$this->strErrors .= sprintf("AssociationTable %s's two columns must both be not null",
					$strTableName);
				return;
			}

			if (((!$objFieldArray[0]->PrimaryKey) &&
				 ($objFieldArray[1]->PrimaryKey)) ||
				(($objFieldArray[0]->PrimaryKey) &&
				 (!$objFieldArray[1]->PrimaryKey))) {
				$this->strErrors .= sprintf("AssociationTable %s only support two-column composite Primary Keys.\n",
					$strTableName);
				return;
			}

			$objForeignKeyArray = $this->objDb->GetForeignKeysForTable($strTableName);

			// Add to it, the list of Foreign Keys from any Relationships Script
			$objForeignKeyArray = $this->GetForeignKeysFromRelationshipsScript($strTableName, $objForeignKeyArray);

			if (count($objForeignKeyArray) != 2) {
				$this->strErrors .= sprintf("AssociationTable %s does not have exactly 2 foreign keys.  Code Gen analysis found %s.\n",
					$strTableName, count($objForeignKeyArray));
				return;
			}

			// Setup two new ManyToManyReference objects
			$objManyToManyReferenceArray[0] = new QManyToManyReference();
			$objManyToManyReferenceArray[1] = new QManyToManyReference();

			// Ensure that the linked tables are both not excluded
			if (array_key_exists($objForeignKeyArray[0]->ReferenceTableName, $this->strExcludedTableArray) ||
				array_key_exists($objForeignKeyArray[1]->ReferenceTableName, $this->strExcludedTableArray))
				return;

			// Setup GraphPrefixArray (if applicable)
			if ($objForeignKeyArray[0]->ReferenceTableName == $objForeignKeyArray[1]->ReferenceTableName) {
				// We are analyzing a graph association
				$strGraphPrefixArray = $this->CalculateGraphPrefixArray($objForeignKeyArray);
			} else {
				$strGraphPrefixArray = array('', '');
			}

			// Go through each FK and setup each ManyToManyReference object
			for ($intIndex = 0; $intIndex < 2; $intIndex++) {
				$objManyToManyReference = $objManyToManyReferenceArray[$intIndex];

				$objForeignKey = $objForeignKeyArray[$intIndex];
				$objOppositeForeignKey = $objForeignKeyArray[($intIndex == 0) ? 1 : 0];

				// Make sure the FK is a single-column FK
				if (count($objForeignKey->ColumnNameArray) != 1) {
					$this->strErrors .= sprintf("AssoiationTable %s has multi-column foreign keys.\n",
						$strTableName);
					return;
				}

				$objManyToManyReference->KeyName = $objForeignKey->KeyName;
				$objManyToManyReference->Table = $strTableName;
				$objManyToManyReference->Column = $objForeignKey->ColumnNameArray[0];
				$objManyToManyReference->PropertyName = $this->ModelColumnPropertyName($objManyToManyReference->Column);
				$objManyToManyReference->OppositeColumn = $objOppositeForeignKey->ColumnNameArray[0];
				$objManyToManyReference->AssociatedTable = $objOppositeForeignKey->ReferenceTableName;

				// Calculate OppositeColumnVariableName
				// Do this by first making a fake column which is the PK column of the AssociatedTable,
				// but who's column name is ManyToManyReference->Column
//				$objOppositeColumn = clone($this->objTableArray[strtolower($objManyToManyReference->AssociatedTable)]->PrimaryKeyColumnArray[0]);

				$objTable = $this->GetTable($objManyToManyReference->AssociatedTable);
				$objOppositeColumn = clone($objTable->PrimaryKeyColumnArray[0]);
				$objOppositeColumn->Name = $objManyToManyReference->OppositeColumn;
				$objManyToManyReference->OppositeVariableName = $this->ModelColumnVariableName($objOppositeColumn);
				$objManyToManyReference->OppositePropertyName = $this->ModelColumnPropertyName($objOppositeColumn->Name);
				$objManyToManyReference->OppositeVariableType = $objOppositeColumn->VariableType;
				$objManyToManyReference->OppositeDbType = $objOppositeColumn->DbType;

				$objManyToManyReference->VariableName = $this->ModelReverseReferenceVariableName($objOppositeForeignKey->ReferenceTableName);
				$objManyToManyReference->VariableType = $this->ModelReverseReferenceVariableType($objOppositeForeignKey->ReferenceTableName);

				$objManyToManyReference->ObjectDescription = $strGraphPrefixArray[$intIndex] . $this->CalculateObjectDescriptionForAssociation($strTableName, $objForeignKey->ReferenceTableName, $objOppositeForeignKey->ReferenceTableName, false);
				$objManyToManyReference->ObjectDescriptionPlural = $strGraphPrefixArray[$intIndex] . $this->CalculateObjectDescriptionForAssociation($strTableName, $objForeignKey->ReferenceTableName, $objOppositeForeignKey->ReferenceTableName, true);

				$objManyToManyReference->OppositeObjectDescription = $strGraphPrefixArray[($intIndex == 0) ? 1 : 0] . $this->CalculateObjectDescriptionForAssociation($strTableName, $objOppositeForeignKey->ReferenceTableName, $objForeignKey->ReferenceTableName, false);
				$objManyToManyReference->IsTypeAssociation = ($objTable instanceof QTypeTable);
				$objManyToManyReference->Options = $this->objModelConnectorOptions->GetOptions($this->ModelClassName($objForeignKey->ReferenceTableName), $objManyToManyReference->ObjectDescription);

			}


			// Iterate through the list of Columns to create objColumnArray
			$objColumnArray = array();
			foreach ($objFieldArray as $objField) {
				if (($objField->Name != $objManyToManyReferenceArray[0]->Column) &&
					($objField->Name != $objManyToManyReferenceArray[1]->Column)) {
					$objColumn = $this->AnalyzeTableColumn($objField, null);
					if ($objColumn) {
						$objColumnArray[strtolower($objColumn->Name)] = $objColumn;
					}
				}
			}
			$objManyToManyReferenceArray[0]->ColumnArray = $objColumnArray;
			$objManyToManyReferenceArray[1]->ColumnArray = $objColumnArray;

			// Push the ManyToManyReference Objects to the tables
			for ($intIndex = 0; $intIndex < 2; $intIndex++) {
				$objManyToManyReference = $objManyToManyReferenceArray[$intIndex];
				$strTableWithReference = $objManyToManyReferenceArray[($intIndex == 0) ? 1 : 0]->AssociatedTable;

				$objTable = $this->GetTable($strTableWithReference);
				$objArray = $objTable->ManyToManyReferenceArray;
				array_push($objArray, $objManyToManyReference);
				$objTable->ManyToManyReferenceArray = $objArray;
			}

		}

		protected function AnalyzeTypeTable(QTypeTable $objTypeTable) {
			// Setup the Array of Reserved Words
			$strReservedWords = explode(',', QCodeGen::PhpReservedWords);
			for ($intIndex = 0; $intIndex < count($strReservedWords); $intIndex++)
				$strReservedWords[$intIndex] = strtolower(trim($strReservedWords[$intIndex]));

			// Setup the Type Table Object
			$strTableName = $objTypeTable->Name;
			$objTypeTable->ClassName = $this->ModelClassName($strTableName);

			// Ensure that there are only 2 fields, an integer PK field (can be named anything) and a unique varchar field
			$objFieldArray = $this->objDb->GetFieldsForTable($strTableName);

			if (($objFieldArray[0]->Type != QDatabaseFieldType::Integer) ||
				(!$objFieldArray[0]->PrimaryKey)) {
				$this->strErrors .= sprintf("TypeTable %s's first column is not a PK integer.\n",
					$strTableName);
				return;
			}

			if (($objFieldArray[1]->Type != QDatabaseFieldType::VarChar) ||
				(!$objFieldArray[1]->Unique)) {
				$this->strErrors .= sprintf("TypeTable %s's second column is not a unique VARCHAR.\n",
					$strTableName);
				return;
			}

			// Get the rows
			$objResult = $this->objDb->Query(sprintf('SELECT * FROM %s', $strTableName));
			$strNameArray = array();
			$strTokenArray = array();
			$strExtraPropertyArray = array();
			$strExtraFields = array();
			$intRowWidth = count($objFieldArray);
			while ($objDbRow = $objResult->GetNextRow()) {
				$strRowArray = $objDbRow->GetColumnNameArray();
				$id = $strRowArray[0];
				$name = $strRowArray[1];

				$strNameArray[$id] = str_replace("'", "\\'", str_replace('\\', '\\\\', $name));
				$strTokenArray[$id] = $this->TypeTokenFromTypeName($name);
				if ($intRowWidth > 2) { // there are extra columns to process
					$strExtraPropertyArray[$id] = array();
					for ($i = 2; $i < $intRowWidth; $i++) {
						$strFieldName = QCodeGen::TypeColumnPropertyName($objFieldArray[$i]->Name);
						$strExtraFields[$i - 2] = $strFieldName;

						// Get and resolve type based value
						$value = $objDbRow->GetColumn($objFieldArray[$i]->Name, $objFieldArray[$i]->Type);
						$strExtraPropertyArray[$id][$strFieldName] = $value;
					}
				}

				foreach ($strReservedWords as $strReservedWord)
					if (trim(strtolower($strTokenArray[$id])) == $strReservedWord) {
						$this->strErrors .= sprintf("Warning: TypeTable %s contains a type name which is a reserved word: %s.  Appended _ to the beginning of it.\r\n",
							$strTableName, $strReservedWord);
						$strTokenArray[$id] = '_' . $strTokenArray[$id];
					}
				if (strlen($strTokenArray[$id]) == 0) {
					$this->strErrors .= sprintf("Warning: TypeTable %s contains an invalid type name: %s\r\n",
						$strTableName, stripslashes($strNameArray[$id]));
					return;
				}
			}

			ksort($strNameArray);
			ksort($strTokenArray);

			$objTypeTable->NameArray = $strNameArray;
			$objTypeTable->TokenArray = $strTokenArray;
			$objTypeTable->ExtraFieldNamesArray = $strExtraFields;
			$objTypeTable->ExtraPropertyArray = $strExtraPropertyArray;
			$objTypeTable->KeyColumn = $this->AnalyzeTableColumn ($objFieldArray[0], $objTypeTable);
		}

		protected function AnalyzeTable(QTable $objTable) {
			// Setup the Table Object
			$objTable->OwnerDbIndex = $this->intDatabaseIndex;
			$strTableName = $objTable->Name;
			$objTable->ClassName = $this->ModelClassName($strTableName);
			$objTable->ClassNamePlural = $this->Pluralize($objTable->ClassName);

			$objTable->Options = $this->objModelConnectorOptions->GetOptions($objTable->ClassName, QModelConnectorOptions::TableOptionsFieldName);

			// Get the List of Columns
			$objFieldArray = $this->objDb->GetFieldsForTable($strTableName);

			// Iterate through the list of Columns to create objColumnArray
			$objColumnArray = array();
			if ($objFieldArray) foreach ($objFieldArray as $objField) {
				$objColumn = $this->AnalyzeTableColumn($objField, $objTable);
				if ($objColumn) {
					$objColumnArray[strtolower($objColumn->Name)] = $objColumn;
				}
			}
			$objTable->ColumnArray = $objColumnArray;




			// Get the List of Indexes
			$objTable->IndexArray = $this->objDb->GetIndexesForTable($objTable->Name);

			// Create an Index array
			$objIndexArray = array();
			// Create our Index for Primary Key (if applicable)
			$strPrimaryKeyArray = array();
			foreach ($objColumnArray as $objColumn)
				if ($objColumn->PrimaryKey) {
					$objPkColumn = $objColumn;
					array_push($strPrimaryKeyArray, $objColumn->Name);
				}
			if (count($strPrimaryKeyArray)) {
				$objIndex = new QIndex();
				$objIndex->KeyName = 'pk_' . $strTableName;
				$objIndex->PrimaryKey = true;
				$objIndex->Unique = true;
				$objIndex->ColumnNameArray = $strPrimaryKeyArray;
				array_push($objIndexArray, $objIndex);

				if (count($strPrimaryKeyArray) == 1) {
					$objPkColumn->Unique = true;
					$objPkColumn->Indexed = true;
				}
			}

			// Iterate though each Index that exists in this table, set any Columns's "Index" property
			// to TRUE if they are a single-column index
			if ($objTable->IndexArray) foreach ($objArray = $objTable->IndexArray as $objDatabaseIndex) {
				// Make sure the columns are defined
				if (count ($objDatabaseIndex->ColumnNameArray) == 0)
					$this->strErrors .= sprintf("Index %s in table %s indexes on no columns.\n",
						$objDatabaseIndex->KeyName, $strTableName);
				else {
					// Ensure every column exist in the DbIndex's ColumnNameArray
					$blnFailed = false;
					foreach ($objArray = $objDatabaseIndex->ColumnNameArray as $strColumnName) {
						if (array_key_exists(strtolower($strColumnName), $objTable->ColumnArray) &&
							($objTable->ColumnArray[strtolower($strColumnName)])) {
							// It exists -- do nothing
						} else {
							// Otherwise, add a warning
							$this->strErrors .= sprintf("Index %s in table %s indexes on the column %s, which does not appear to exist.\n",
								$objDatabaseIndex->KeyName, $strTableName, $strColumnName);
							$blnFailed = true;
						}
					}

					if (!$blnFailed) {
						// Let's make sure if this is a single-column index, we haven't already created a single-column index for this column
						$blnAlreadyCreated = false;
						foreach ($objIndexArray as $objIndex)
							if (count($objIndex->ColumnNameArray) == count($objDatabaseIndex->ColumnNameArray))
								if (implode(',', $objIndex->ColumnNameArray) == implode(',', $objDatabaseIndex->ColumnNameArray))
									$blnAlreadyCreated = true;

						if (!$blnAlreadyCreated) {
							// Create the Index Object
							$objIndex = new QIndex();
							$objIndex->KeyName = $objDatabaseIndex->KeyName;
							$objIndex->PrimaryKey = $objDatabaseIndex->PrimaryKey;
							$objIndex->Unique = $objDatabaseIndex->Unique;
							if ($objDatabaseIndex->PrimaryKey)
								$objIndex->Unique = true;
							$objIndex->ColumnNameArray = $objDatabaseIndex->ColumnNameArray;

							// Add the new index object to the index array
							array_push($objIndexArray, $objIndex);

							// Lastly, if it's a single-column index, update the Column in the table to reflect this
							if (count($objDatabaseIndex->ColumnNameArray) == 1) {
								$strColumnName = $objDatabaseIndex->ColumnNameArray[0];
								$objColumn = $objTable->ColumnArray[strtolower($strColumnName)];
								$objColumn->Indexed = true;

								if ($objIndex->Unique)
									$objColumn->Unique = true;
							}
						}
					}
				}
			}

			// Add the IndexArray to the table
			$objTable->IndexArray = $objIndexArray;




			// Get the List of Foreign Keys from the database
			$objForeignKeys = $this->objDb->GetForeignKeysForTable($objTable->Name);

			// Add to it, the list of Foreign Keys from any Relationships Script
			$objForeignKeys = $this->GetForeignKeysFromRelationshipsScript($strTableName, $objForeignKeys);

			// Iterate through each foreign key that exists in this table
			if ($objForeignKeys) foreach ($objForeignKeys as $objForeignKey) {

				// Make sure it's a single-column FK
				if (count($objForeignKey->ColumnNameArray) != 1)
					$this->strErrors .= sprintf("Foreign Key %s in table %s keys on multiple columns.  Multiple-columned FKs are not supported by the code generator.\n",
						$objForeignKey->KeyName, $strTableName);
				else {
					// Make sure the column in the FK definition actually exists in this table
					$strColumnName = $objForeignKey->ColumnNameArray[0];

					if (array_key_exists(strtolower($strColumnName), $objTable->ColumnArray) &&
						($objColumn = $objTable->ColumnArray[strtolower($strColumnName)])) {

						// Now, we make sure there is a single-column index for this FK that exists
						$blnFound = false;
						if ($objIndexArray = $objTable->IndexArray) foreach ($objIndexArray as $objIndex) {
							if ((count($objIndex->ColumnNameArray) == 1) &&
								(strtolower($objIndex->ColumnNameArray[0]) == strtolower($strColumnName)))
								$blnFound = true;
						}

						if (!$blnFound) {
							// Single Column Index for this FK does not exist.  Let's create a virtual one and warn
							$objIndex = new QIndex();
							$objIndex->KeyName = sprintf('virtualix_%s_%s', $objTable->Name, $objColumn->Name);
							$objIndex->Unique = $objColumn->Unique;
							$objIndex->ColumnNameArray = array($objColumn->Name);

							$objIndexArray = $objTable->IndexArray;
							$objIndexArray[] = $objIndex;
							$objTable->IndexArray = $objIndexArray;

							if ($objIndex->Unique)
								$this->strWarnings .= sprintf("Notice: It is recommended that you add a single-column UNIQUE index on \"%s.%s\" for the Foreign Key %s\r\n",
									$strTableName, $strColumnName, $objForeignKey->KeyName);
							else
								$this->strWarnings .= sprintf("Notice: It is recommended that you add a single-column index on \"%s.%s\" for the Foreign Key %s\r\n",
									$strTableName, $strColumnName, $objForeignKey->KeyName);
						}

						// Make sure the table being referenced actually exists
						if ((array_key_exists(strtolower($objForeignKey->ReferenceTableName), $this->objTableArray)) ||
							(array_key_exists(strtolower($objForeignKey->ReferenceTableName), $this->objTypeTableArray))) {

							// STEP 1: Create the New Reference
							$objReference = new QReference();

							// Retrieve the Column object
							$objColumn = $objTable->ColumnArray[strtolower($strColumnName)];

							// Setup Key Name
							$objReference->KeyName = $objForeignKey->KeyName;

							$strReferencedTableName = $objForeignKey->ReferenceTableName;

							// Setup IsType flag
							if (array_key_exists(strtolower($strReferencedTableName), $this->objTypeTableArray)) {
								$objReference->IsType = true;
							} else {
								$objReference->IsType = false;
							}

							// Setup Table and Column names
							$objReference->Table = $strReferencedTableName;
							$objReference->Column = $objForeignKey->ReferenceColumnNameArray[0];

							// Setup VariableType
							$objReference->VariableType = $this->ModelClassName($strReferencedTableName);

							// Setup PropertyName and VariableName
							$objReference->PropertyName = $this->ModelReferencePropertyName($objColumn->Name);
							$objReference->VariableName = $this->ModelReferenceVariableName($objColumn->Name);

							// Add this reference to the column
							$objColumn->Reference = $objReference;

							// References will not have been correctly read earlier, so try again with the reference name
							$objColumn->Options = $this->objModelConnectorOptions->GetOptions($objTable->ClassName, $objReference->PropertyName) + $objColumn->Options;



							// STEP 2: Setup the REVERSE Reference for Non Type-based References
							if (!$objReference->IsType) {
								// Retrieve the ReferencedTable object
//								$objReferencedTable = $this->objTableArray[strtolower($objReference->Table)];
								$objReferencedTable = $this->GetTable($objReference->Table);
								$objReverseReference = new QReverseReference();
								$objReverseReference->Reference = $objReference;
								$objReverseReference->KeyName = $objReference->KeyName;
								$objReverseReference->Table = $strTableName;
								$objReverseReference->Column = $strColumnName;
								$objReverseReference->NotNull = $objColumn->NotNull;
								$objReverseReference->Unique = $objColumn->Unique;
								$objReverseReference->PropertyName = $this->ModelColumnPropertyName($strColumnName);

								$objReverseReference->ObjectDescription = $this->CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, false);
								$objReverseReference->ObjectDescriptionPlural = $this->CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, true);
								$objReverseReference->VariableName = $this->ModelReverseReferenceVariableName($objTable->Name);
								$objReverseReference->VariableType = $this->ModelReverseReferenceVariableType($objTable->Name);

								// For Special Case ReverseReferences, calculate Associated MemberVariableName and PropertyName...

								// See if ReverseReference is due to an ORM-based Class Inheritence Chain
								if ((count($objTable->PrimaryKeyColumnArray) == 1) && ($objColumn->PrimaryKey)) {
									$objReverseReference->ObjectMemberVariable = QConvertNotation::PrefixFromType(QType::Object) . $objReverseReference->VariableType;
									$objReverseReference->ObjectPropertyName = $objReverseReference->VariableType;
									$objReverseReference->ObjectDescription = $objReverseReference->VariableType;
									$objReverseReference->ObjectDescriptionPlural = $this->Pluralize($objReverseReference->VariableType);

								// Otherwise, see if it's just plain ol' unique
								} else if ($objColumn->Unique) {
									$objReverseReference->ObjectMemberVariable = $this->CalculateObjectMemberVariable($strTableName, $strColumnName, $strReferencedTableName);
									$objReverseReference->ObjectPropertyName = $this->CalculateObjectPropertyName($strTableName, $strColumnName, $strReferencedTableName);
									// get override options for codegen
									$objReverseReference->Options = $this->objModelConnectorOptions->GetOptions($objReference->VariableType, $objReverseReference->ObjectDescription);
								}



								// Add this ReverseReference to the referenced table's ReverseReferenceArray
								$objArray = $objReferencedTable->ReverseReferenceArray;
								array_push($objArray, $objReverseReference);
								$objReferencedTable->ReverseReferenceArray = $objArray;
							}
						} else {
							$this->strErrors .= sprintf("Foreign Key %s in table %s references a table %s that does not appear to exist.\n",
								$objForeignKey->KeyName, $strTableName, $objForeignKey->ReferenceTableName);
						}
					} else {
						$this->strErrors .= sprintf("Foreign Key %s in table %s indexes on a column that does not appear to exist.\n",
							$objForeignKey->KeyName, $strTableName);
					}
				}
			}

			// Verify: Table Name is valid (alphanumeric + "_" characters only, must not start with a number)
			// and NOT a PHP Reserved Word
			$strMatches = array();
			preg_match('/' . $this->strPatternTableName . '/', $strTableName, $strMatches);
			if (count($strMatches) && ($strMatches[0] == $strTableName) && ($strTableName != '_')) {
				// Setup Reserved Words
				$strReservedWords = explode(',', QCodeGen::PhpReservedWords);
				for ($intIndex = 0; $intIndex < count($strReservedWords); $intIndex++)
					$strReservedWords[$intIndex] = strtolower(trim($strReservedWords[$intIndex]));

				$strTableNameToTest = trim(strtolower($strTableName));
				foreach ($strReservedWords as $strReservedWord)
					if ($strTableNameToTest == $strReservedWord) {
						$this->strErrors .= sprintf("Table '%s' has a table name which is a PHP reserved word.\r\n", $strTableName);
						unset($this->objTableArray[strtolower($strTableName)]);
						return;
					}
			} else {
				$this->strErrors .= sprintf("Table '%s' can only contain characters that are alphanumeric or _, and must not begin with a number.\r\n", $strTableName);
				unset($this->objTableArray[strtolower($strTableName)]);
				return;
			}

			// Verify: Column Names are all valid names
			$objColumnArray = $objTable->ColumnArray;
			foreach ($objColumnArray as $objColumn) {
				$strColumnName = $objColumn->Name;
				$strMatches = array();
				preg_match('/' . $this->strPatternColumnName . '/', $strColumnName, $strMatches);
				if (count($strMatches) && ($strMatches[0] == $strColumnName) && ($strColumnName != '_')) {
				} else {
					$this->strErrors .= sprintf("Table '%s' has an invalid column name: '%s'\r\n", $strTableName, $strColumnName);
					unset($this->objTableArray[strtolower($strTableName)]);
					return;
				}
			}

			// Verify: Table has at least one PK
			$blnFoundPk = false;
			$objColumnArray = $objTable->ColumnArray;
			foreach ($objColumnArray as $objColumn) {
				if ($objColumn->PrimaryKey)
					$blnFoundPk = true;
			}
			if (!$blnFoundPk) {
				$this->strErrors .= sprintf("Table %s does not have any defined primary keys.\n", $strTableName);
				unset($this->objTableArray[strtolower($strTableName)]);
				return;
			}
		}

		protected function AnalyzeTableColumn(QDatabaseFieldBase $objField, $objTable) {
			$objColumn = new QColumn();
			$objColumn->Name = $objField->Name;
			$objColumn->OwnerTable = $objTable;
			if (substr_count($objField->Name, "-")) {
				$tableName = $objTable ? " in table " . $objTable->Name : "";
				$this->strErrors .= "Invalid column name" . $tableName . ": " . $objField->Name . ". Dashes are not allowed.";
				return null;
			}

			$objColumn->DbType = $objField->Type;

			$objColumn->VariableType = $this->VariableTypeFromDbType($objColumn->DbType);
			$objColumn->VariableTypeAsConstant = QType::Constant($objColumn->VariableType);

			$objColumn->Length = $objField->MaxLength;
			$objColumn->Default = $objField->Default;

			$objColumn->PrimaryKey = $objField->PrimaryKey;
			$objColumn->NotNull = $objField->NotNull;
			$objColumn->Identity = $objField->Identity;
			$objColumn->Unique = $objField->Unique;
			if (($objField->PrimaryKey) && $objTable && (count($objTable->PrimaryKeyColumnArray) == 1))
				$objColumn->Unique = true;
			$objColumn->Timestamp = $objField->Timestamp;

			$objColumn->VariableName = $this->ModelColumnVariableName($objColumn);
			$objColumn->PropertyName = $this->ModelColumnPropertyName($objColumn->Name);

			// separate overrides embedded in the comment

			// extract options embedded in the comment field
			if (($strComment = $objField->Comment) &&
				($pos1 = strpos ($strComment, '{')) !== false &&
				($pos2 = strrpos ($strComment, '}', $pos1))) {

				$strJson = substr ($strComment, $pos1, $pos2 - $pos1 + 1);
				$a = json_decode($strJson, true);

				if ($a) {
					$objColumn->Options = $a;
					$objColumn->Comment = substr ($strComment, 0, $pos1) . substr ($strComment, $pos2 + 1); // return comment without options
					if (!empty ($a['Timestamp'])) {
						$objColumn->Timestamp = true;	// alternate way to specify that a column is a self-updating timestamp
					}
					if ($objColumn->Timestamp && !empty($a['AutoUpdate'])) {
						$objColumn->AutoUpdate = true;
					}
				} else {
					$objColumn->Comment = $strComment;
				}
			}

			// merge with options found in the design editor, letting editor take precedence
			$objColumn->Options = $this->objModelConnectorOptions->GetOptions($objTable->ClassName, $objColumn->PropertyName) + $objColumn->Options;

			return $objColumn;
		}

		protected function StripPrefixFromTable($strTableName) {
			// If applicable, strip any StripTablePrefix from the table name
			if ($this->intStripTablePrefixLength &&
				(strlen($strTableName) > $this->intStripTablePrefixLength) &&
				(substr($strTableName, 0, $this->intStripTablePrefixLength - strlen($strTableName)) == $this->strStripTablePrefix))
				return substr($strTableName, $this->intStripTablePrefixLength);

			return $strTableName;
		}

		protected function GetForeignKeyForQcubedRelationshipDefinition($strTableName, $strLine) {
			$strTokens = explode('=>', $strLine);
			if (count($strTokens) != 2) {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: %s (Incorrect Format)\r\n", $strLine);
				$this->strRelationshipLinesQcubed[$strLine] = null;
				return null;
			}

			$strSourceTokens = explode('.', $strTokens[0]);
			$strDestinationTokens = explode('.', $strTokens[1]);

			if ((count($strSourceTokens) != 2) ||
				(count($strDestinationTokens) != 2)) {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: %s (Incorrect Table.Column Format)\r\n", $strLine);
				$this->strRelationshipLinesQcubed[$strLine] = null;
				return null;
			}

			$strColumnName = trim($strSourceTokens[1]);
			$strReferenceTableName = trim($strDestinationTokens[0]);
			$strReferenceColumnName = trim($strDestinationTokens[1]);
			$strFkName = sprintf('virtualfk_%s_%s', $strTableName, $strColumnName);

			if (strtolower($strTableName) == trim($strSourceTokens[0])) {
				$this->strRelationshipLinesQcubed[$strLine] = null;
				return $this->GetForeignKeyHelper($strLine, $strFkName, $strTableName, $strColumnName, $strReferenceTableName, $strReferenceColumnName);
			}

			return null;
		}

		protected function GetForeignKeyForSqlRelationshipDefinition($strTableName, $strLine) {
			$strMatches = array();

			// Start
			$strPattern = '/alter[\s]+table[\s]+';
			// Table Name
			$strPattern .= '[\[\`\'\"]?(' . $this->strPatternTableName . ')[\]\`\'\"]?[\s]+';

			// Add Constraint
			$strPattern .= '(add[\s]+)?(constraint[\s]+';
			$strPattern .= '[\[\`\'\"]?(' . $this->strPatternKeyName . ')[\]\`\'\"]?[\s]+)?[\s]*';
			// Foreign Key
			$strPattern .= 'foreign[\s]+key[\s]*(' . $this->strPatternKeyName . ')[\s]*\(';
			$strPattern .= '([^)]+)\)[\s]*';
			// References
			$strPattern .= 'references[\s]+';
			$strPattern .= '[\[\`\'\"]?(' . $this->strPatternTableName . ')[\]\`\'\"]?[\s]*\(';
			$strPattern .= '([^)]+)\)[\s]*';
			// End
			$strPattern .= '/';

			// Perform the RegExp
			preg_match($strPattern, $strLine, $strMatches);

			if (count($strMatches) == 9) {
				$strColumnName = trim($strMatches[6]);
				$strReferenceTableName = trim($strMatches[7]);
				$strReferenceColumnName = trim($strMatches[8]);
				$strFkName = $strMatches[5];
				if (!$strFkName)
					$strFkName = sprintf('virtualfk_%s_%s', $strTableName, $strColumnName);

				if ((strpos($strColumnName, ',') !== false) ||
					(strpos($strReferenceColumnName, ',') !== false)) {
					$this->strErrors .= sprintf("Relationships Script has a foreign key definition with multiple columns: %s (Multiple-columned FKs are not supported by the code generator)\r\n", $strLine);
					$this->strRelationshipLinesSql[$strLine] = null;
					return null;
				}

				// Cleanup strColumnName nad strreferenceColumnName
				$strColumnName = str_replace("'", '', $strColumnName);
				$strColumnName = str_replace('"', '', $strColumnName);
				$strColumnName = str_replace('[', '', $strColumnName);
				$strColumnName = str_replace(']', '', $strColumnName);
				$strColumnName = str_replace('`', '', $strColumnName);
				$strColumnName = str_replace('	', '', $strColumnName);
				$strColumnName = str_replace(' ', '', $strColumnName);
				$strColumnName = str_replace("\r", '', $strColumnName);
				$strColumnName = str_replace("\n", '', $strColumnName);
				$strReferenceColumnName = str_replace("'", '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace('"', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace('[', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace(']', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace('`', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace('	', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace(' ', '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace("\r", '', $strReferenceColumnName);
				$strReferenceColumnName = str_replace("\n", '', $strReferenceColumnName);

				if (strtolower($strTableName) == trim($strMatches[1])) {
					$this->strRelationshipLinesSql[$strLine] = null;
					return $this->GetForeignKeyHelper($strLine, $strFkName, $strTableName, $strColumnName, $strReferenceTableName, $strReferenceColumnName);
				}

				return null;
			} else {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: %s (Not in ANSI SQL Format)\r\n", $strLine);
				$this->strRelationshipLinesSql[$strLine] = null;
				return null;
			}
		}

		protected function GetForeignKeyHelper($strLine, $strFkName, $strTableName, $strColumnName, $strReferencedTable, $strReferencedColumn) {
			// Make Sure Tables/Columns Exist, or display error otherwise
			if (!$this->ValidateTableColumn($strTableName, $strColumnName)) {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: \"%s\" (\"%s.%s\" does not exist)\r\n",
					$strLine, $strTableName, $strColumnName);
				return null;
			}

			if (!$this->ValidateTableColumn($strReferencedTable, $strReferencedColumn)) {
				$this->strErrors .= sprintf("Could not parse Relationships Script reference: \"%s\" (\"%s.%s\" does not exist)\r\n",
					$strLine, $strReferencedTable, $strReferencedColumn);
				return null;
			}

			return new QDatabaseForeignKey($strFkName, array($strColumnName), $strReferencedTable, array($strReferencedColumn));
		}

		/**
		 * This will go through the various Relationships Script lines (if applicable) as setup during
		 * the __constructor() through the <relationships> and <relationshipsScript> tags in the
		 * configuration settings.
		 *
		 * If no Relationships are defined, this method will simply exit making no changes.
		 *
		 * @param string $strTableName Name of the table to pull foreign keys for
		 * @param DatabaseForeignKeyBase[] Array of currently found DB FK objects which will be appended to
		 * @return DatabaseForeignKeyBase[] Array of DB FK objects that were parsed out
		 */
		protected function GetForeignKeysFromRelationshipsScript($strTableName, $objForeignKeyArray) {
			foreach ($this->strRelationshipLinesQcubed as $strLine) {
				if ($strLine) {
					$objForeignKey = $this->GetForeignKeyForQcubedRelationshipDefinition($strTableName, $strLine);

					if ($objForeignKey) {
						array_push($objForeignKeyArray, $objForeignKey);
						$this->strRelationshipLinesQcubed[$strLine] = null;
					}
				}
			}

			foreach ($this->strRelationshipLinesSql as $strLine) {
				if ($strLine) {
					$objForeignKey = $this->GetForeignKeyForSqlRelationshipDefinition($strTableName, $strLine);

					if ($objForeignKey) {
						array_push($objForeignKeyArray, $objForeignKey);
						$this->strRelationshipLinesSql[$strLine] = null;
					}
				}
			}

			return $objForeignKeyArray;
		}

		public function GenerateControlId($objTable, $objColumn) {
			$strControlId = null;
			if (isset($objColumn->Options['ControlId'])) {
				$strControlId = $objColumn->Options['ControlId'];
			} elseif ($this->blnGenerateControlId) {
				$strObjectName = $this->ModelVariableName($objTable->Name);
				$strClassName = $objTable->ClassName;
				$strControlVarName = $this->ModelConnectorVariableName($objColumn);
				$strLabelName = QCodeGen::ModelConnectorControlName($objColumn);

				$strControlId = $strControlVarName . $strClassName;

			}
			return $strControlId;
		}




		/**
		 * Returns a string that will cast a variable coming from the database into a php type.
		 * Doing this in the template saves significant amounts of time over using QType::Cast() or GetColumn.
		 * @param QColumn $objColumn
		 * @return string
		 * @throws Exception
		 */
		public function GetCastString (QColumn $objColumn) {
			switch ($objColumn->DbType) {
				case QDatabaseFieldType::Bit:
					return ('$mixVal = (bool)$mixVal;');

				case QDatabaseFieldType::Blob:
				case QDatabaseFieldType::Char:
				case QDatabaseFieldType::VarChar:
					return ''; // no need to cast, since its already a string or a null

				case QDatabaseFieldType::Date:
					return ('$mixVal = new QDateTime($mixVal, null, QDateTime::DateOnlyType);');

				case QDatabaseFieldType::DateTime:
					return ('$mixVal = new QDateTime($mixVal);');

				case QDatabaseFieldType::Time:
					return ('$mixVal = new QDateTime($mixVal, null, QDateTime::TimeOnlyType);');

				case QDatabaseFieldType::Float:
				case QDatabaseFieldType::Integer:
					return ('$mixVal = (' . $objColumn->VariableType . ')$mixVal;');

				default:
					throw new Exception ('Invalid database field type');
					exit;
			}
		}



		////////////////////
		// Public Overriders
		////////////////////

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'TableArray':
					return $this->objTableArray;
				case 'TypeTableArray':
					return $this->objTypeTableArray;
				case 'DatabaseIndex':
					return $this->intDatabaseIndex;
				case 'CommentConnectorLabelDelimiter':
					return $this->strCommentConnectorLabelDelimiter;
				case 'AutoInitialize':
					return $this->blnAutoInitialize;
				case 'objSettingsXml':
					throw new QCallerException('The field objSettingsXml is deprecated');
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
			try {
				switch($strName) {
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
			}
		}
	}

	function array_trim(&$strValue) {
		$strValue = trim($strValue);
	}
?>