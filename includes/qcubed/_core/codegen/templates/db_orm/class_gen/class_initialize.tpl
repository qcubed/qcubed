/**
		 * Initialize each property with default values from database definition
		 */
		public function Initialize()
		{
		<% foreach ($objTable->ColumnArray as $objColumn) { %>
			$this-><%= $objColumn->VariableName %> = <%
			$defaultVarName = $objTable->ClassName . '::' . $objColumn->PropertyName . 'Default';
			if ($objColumn->VariableType != QType::DateTime)
				return ($defaultVarName);
			return "(" . $defaultVarName . " === null)?null:new QDateTime(" . $defaultVarName . ")";
			%>;
		<% } %>
		}
