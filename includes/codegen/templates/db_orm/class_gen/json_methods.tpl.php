<?php
	// NOTE for template develoepers: SQL and most other databases are either latin characters only, or Unicode for their
	// identifiers, so you don't need to worry about encoding issues for identifiers.
?>
////////////////////////////////////////
		// METHODS for JSON Object Translation
		////////////////////////////////////////

		// this function is required for objects that implement the
		// IteratorAggregate interface
		public function getIterator() {
			$iArray = array();

<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
            if (isset($this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD])) {
			    $iArray['<?= $objColumn->PropertyName ?>'] = $this-><?= $objColumn->VariableName ?>;
            }
<?php } ?>
			return new ArrayIterator($iArray);
		}

		/**
         *   @deprecated. Just call json_encode on the object. See the jsonSerialize function for the result.
		/*/
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
		 * @return string
		 */
		public function toJsObject () {
			return JavaScriptHelper::toJsObject(array('value' => $this->__toString(), 'id' => <?php if ( count($objTable->PrimaryKeyColumnArray) == 1 ) { ?> $this-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName ?> <?php } ?><?php if ( count($objTable->PrimaryKeyColumnArray) > 1 ) { ?> array(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?> $this-><?= $objColumn->VariableName ?>, <?php } ?><?php GO_BACK(2); ?>) <?php } ?>));
		}

		/**
		 * Default "jsonSerialize" handler
		 * Specifies how the object should be serialized using json_encode.
         * Control the values that are output by using QQ::Select to control which
		 * fields are valid, and QQ::Expand to control embedded objects.
		 * WARNING: If an object is found in short-term cache, it will be used instead of the queried object and may
		 * contain data fields that were fetched earlier. To really control what fields exist in this object, preceed
		 * any query calls (like Load or QueryArray), with a call to <?= $objTable->ClassName ?>::ClearCache()
		 *
		 * @return array An array that is json serializable
		 */
		public function jsonSerialize () {
			$a = [];
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php 	if (($objColumn->Reference) && (!$objColumn->Reference->IsType)) { ?>
			if (isset($this-><?= $objColumn->Reference->VariableName ?>)) {
				$a['<?= $objColumn->Reference->Name ?>'] = $this-><?= $objColumn->Reference->VariableName ?>;
			} elseif (isset($this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD])) {
				$a['<?= $objColumn->Name ?>'] = $this-><?= $objColumn->VariableName ?>;
			}
<?php 	} else { ?>
			if (isset($this->__blnValid[self::<?= strtoupper($objColumn->Name) ?>_FIELD])) {
<?php		if ($objColumn->DbType == QDatabaseFieldType::Blob) { // binary value ?>
                $a['<?= $objColumn->Name ?>'] = base64_encode($this-><?= $objColumn->VariableName ?>);
<?php       }
            elseif ($objColumn->VariableType == QType::String && QApplication::$EncodingType != 'UTF-8') { ?>
				$a['<?= $objColumn->Name ?>'] = JavsScriptHelper::MakeJsonEncodable($this-><?= $objColumn->VariableName ?>);
<?php 		}
            else {?>
				$a['<?= $objColumn->Name ?>'] = $this-><?= $objColumn->VariableName ?>;
<?php 		} ?>
			}
<?php 	} ?>
<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?>
<?php 	if ($objReverseReference->Unique) { ?>
			if (isset($this-><?= $objReverseReference->ObjectMemberVariable ?>)) {
				$a['<?= QConvertNotation::UnderscoreFromCamelCase($objReverseReference->ObjectDescription) ?>'] = $this-><?= $objReverseReference->ObjectMemberVariable ?>;
			}
<?php 	} else { ?>
			if (isset($this->_obj<?= $objReverseReference->ObjectDescription ?>)) {
				$a['<?= QConvertNotation::UnderscoreFromCamelCase($objReverseReference->ObjectDescription) ?>'] = $this->_obj<?= $objReverseReference->ObjectDescription ?>;
			} elseif (isset($this->_obj<?= $objReverseReference->ObjectDescription ?>Array)) {
				$a['<?= QConvertNotation::UnderscoreFromCamelCase($objReverseReference->ObjectDescription) ?>'] = $this->_obj<?= $objReverseReference->ObjectDescription ?>Array;
			}
<?php 	} ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
<?php
		$objAssociatedTable = $objCodeGen->GetTable($objReference->AssociatedTable);
		$varPrefix = (is_a($objAssociatedTable, 'QTypeTable') ? '_int' : '_obj');
?>
			if (isset($this-><?= $varPrefix . $objReference->ObjectDescription ?>)) {
				$a['<?= QConvertNotation::UnderscoreFromCamelCase($objReference->ObjectDescription) ?>'] = $this-><?= $varPrefix . $objReference->ObjectDescription ?>;
			} elseif (isset($this-><?= $varPrefix . $objReference->ObjectDescription ?>Array)) {
				$a['<?= QConvertNotation::UnderscoreFromCamelCase($objReference->ObjectDescription) ?>'] = $this-><?= $varPrefix . $objReference->ObjectDescription ?>Array;
			}
<?php } ?>
			return $a;
		}

