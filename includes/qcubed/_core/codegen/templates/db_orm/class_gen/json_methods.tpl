////////////////////////////////////////
		// METHODS for JSON Object Translation
		////////////////////////////////////////

		// this function is required for objects that implement the
		// IteratorAggregate interface
		public function getIterator() {
			///////////////////
			// Member Variables
			///////////////////
<% foreach ($objTable->ColumnArray as $objColumn) { %>
			$iArray['<%= $objColumn->PropertyName %>'] = $this-><%= $objColumn->VariableName %>;
<% } %>
			return new ArrayIterator($iArray);
		}

		// this function returns a Json formatted string using the 
		// IteratorAggregate interface
		public function getJson() {
			return json_encode($this->getIterator());
		}
