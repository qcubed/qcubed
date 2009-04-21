///////////////////////////////
		// CLASS-WIDE LOAD AND COUNT METHODS
		///////////////////////////////

		/**
		 * Static method to retrieve the Database object that owns this class.
		 * @return QDatabaseBase reference to the Database object that can query this class
		 */
		public static function GetDatabase() {
			return QApplication::$Database[<%= $objCodeGen->DatabaseIndex; %>];
		}

		/**
		 * Load a <%= $objTable->ClassName %> from PK Info
<% foreach ($objTable->ColumnArray as $objColumn) { %>
	<% if ($objColumn->PrimaryKey) { %>
		 * @param <%= $objColumn->VariableType %> $<%= $objColumn->VariableName %>
	<% } %>
<% } %>
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <%= $objTable->ClassName %>
		 */
		public static function Load(<%= $objCodeGen->ParameterListFromColumnArray($objTable->PrimaryKeyColumnArray); %>, $objOptionalClauses = null) {
			// Use QuerySingle to Perform the Query
			return <%= $objTable->ClassName %>::QuerySingle(
				QQ::AndCondition(
<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>
					QQ::Equal(QQN::<%= $objTable->ClassName %>()-><%= $objColumn->PropertyName %>, $<%= $objColumn->VariableName %>),
<% } %><%--%>
				),
				$objOptionalClauses
			);
		}

		/**
		 * Load all <%= $objTable->ClassNamePlural %>
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return <%= $objTable->ClassName %>[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			if (func_num_args() > 1) {
				throw new QCallerException("LoadAll must be called with an array of optional clauses as a single argument");
			}
			// Call <%= $objTable->ClassName %>::QueryArray to perform the LoadAll query
			try {
				return <%= $objTable->ClassName; %>::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all <%= $objTable->ClassNamePlural %>
		 * @return int
		 */
		public static function CountAll() {
			// Call <%= $objTable->ClassName %>::QueryCount to perform the CountAll query
			return <%= $objTable->ClassName %>::QueryCount(QQ::All());
		}
