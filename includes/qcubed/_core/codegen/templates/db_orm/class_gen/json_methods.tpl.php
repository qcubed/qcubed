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
			return JavaScriptHelper::toJsObject(array('value' => $this->__toString(), 'id' => <?php if ( count($objTable->PrimaryKeyColumnArray) == 1 ) { ?> $this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName  ?> <?php } ?><?php if ( count($objTable->PrimaryKeyColumnArray) > 1 ) { ?> array(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?> $this-><?php echo $objColumn->VariableName  ?>, <?php } ?><?php GO_BACK(2); ?>) <?php } ?>));
		}

