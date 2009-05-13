<template OverwriteFlag="true" DocrootFlag="false" DirectorySuffix="" TargetDirectory="<%= __MODEL_GEN__ %>" TargetFileName="_type_class_paths.inc.php"/>
<?php 
<% foreach ($objTableArray as $objTable) { %>
	// ClassPaths for the <%= $objTable->ClassName %> type class
	<% if (__MODEL__) { %>
		QApplicationBase::$ClassFile['<%= strtolower($objTable->ClassName) %>'] = __MODEL__ . '/<%= $objTable->ClassName %>.class.php';
	<% } %>
<% } %>
?>