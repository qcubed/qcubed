<template OverwriteFlag="false" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __MODEL__ %>" TargetFileName="<%= $objTable->ClassName %>.class.php"/>
<?php
	require(__MODEL_GEN__ . '/<%= $objTable->ClassName %>Gen.class.php');

	/**
	 * The <%= $objTable->ClassName %> class defined here contains any
	 * customized code for the <%= $objTable->ClassName %> class in the
	 * Object Relational Model.  It represents the "<%= $objTable->Name %>" table
	 * in the database, and extends from the code generated abstract <%= $objTable->ClassName %>Gen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * @package <%= QCodeGen::$ApplicationName; %>
	 * @subpackage DataObjects
	 *
	 */
	class <%= $objTable->ClassName %> extends <%= $objTable->ClassName %>Gen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $obj<%= $objTable->ClassName %>->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('<%= $objTable->ClassName %> Object <% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %>%s - <% } %><%---%>', <% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %> $this-><%= $objColumn->VariableName %>, <% } %><%--%>);
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
			return JavaScriptHelper::toJsObject(array('value' => $this->__toString(), 'id' => <% if ( count($objTable->PrimaryKeyColumnArray) == 1 ) { %> $this-><%= $objTable->PrimaryKeyColumnArray[0]->VariableName %> <% } %><% if ( count($objTable->PrimaryKeyColumnArray) > 1 ) { %> array(<% foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { %> $this-><%= $objColumn->VariableName %>, <% } %><%--%>) <% } %>));
		}


		<%@ example_load_methods('objTable'); %>



		<%@ example_properties('objTable'); %>



		<%@ example_initialization('objTable'); %>
	}
?>