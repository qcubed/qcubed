// General Variables
		/**
		 * @var <%= $objTable->ClassName; %> <%= $objCodeGen->VariableNameFromTable($objTable->Name); %>
		 */
		protected $<%= $objCodeGen->VariableNameFromTable($objTable->Name); %>;
		protected $objParentObject;
		/**
		 * @var string TitleVerb
		 */
		protected $strTitleVerb;
		/**
		 * @var boolean EditMode
		 */
		protected $blnEditMode;

		// Controls that allow the editing of <%= $objTable->ClassName %>'s individual data fields
<% foreach ($objTable->ColumnArray as $objColumn) { %>
		/**
		 * @var <%= $objCodeGen->FormControlClassForColumn($objColumn); %> <%= $objColumn->VariableName; %>
		 */
		protected $<%= $objCodeGen->FormControlVariableNameForColumn($objColumn); %>;
<% } %>

		// Controls that allow the viewing of <%= $objTable->ClassName %>'s individual data fields
<% foreach ($objTable->ColumnArray as $objColumn) { %>
<% if (!$objColumn->Identity && !$objColumn->Timestamp) { %>
		protected $<%= $objCodeGen->FormLabelVariableNameForColumn($objColumn); %>;
<% } %>
<% } %>

		// QListBox Controls (if applicable) to edit Unique ReverseReferences and ManyToMany References
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		protected $<%= $objCodeGen->FormControlVariableNameForUniqueReverseReference($objReverseReference); %>;
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
		protected $<%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>;
		protected $str<%= $objManyToManyReference->ObjectDescription; %>Glue;
<% } %>

		// QLabel Controls (if applicable) to view Unique ReverseReferences and ManyToMany References
<% foreach ($objTable->ReverseReferenceArray as $objReverseReference) { %>
	<% if ($objReverseReference->Unique) { %>
		protected $<%= $objCodeGen->FormLabelVariableNameForUniqueReverseReference($objReverseReference); %>;
	<% } %>
<% } %>
<% foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { %>
		protected $<%= $objCodeGen->FormLabelVariableNameForManyToManyReference($objManyToManyReference); %>;
<% } %>