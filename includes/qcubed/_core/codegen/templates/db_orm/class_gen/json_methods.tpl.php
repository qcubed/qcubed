////////////////////////////////////////
		// METHODS for JSON Object Translation
		////////////////////////////////////////

		// this function is required for objects that implement the
		// IteratorAggregate interface
		public function getIterator() {
			///////////////////
			// Member Variables
			///////////////////
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
			$iArray['<?php echo $objColumn->PropertyName  ?>'] = $this-><?php echo $objColumn->VariableName  ?>;
<?php } ?>
			return new ArrayIterator($iArray);
		}

		// this function returns a Json formatted string using the
		// IteratorAggregate interface
		public function getJson() {
			return json_encode($this->getIterator());
		}
