This directory is here to serve as a place for custom made or modified templates.
If you want to modify the default templates, you can copy the ones in the default
templates directory. The templates path is defined in configuration.inc.php[.sample]
file as the CODEGEN_TEMPLATE_PATH constant. The (very) basic intro to templates
and their organisation is given below.

If you are copying template files here, you would also have to update the definition
of CODEGEN_TEMPLATE_PATH in the configuration.inc.php file. You can also create another
directory somewhere else and use that as CODEGEN_TEMPLATE_PATH. Read configuration.inc.php
for more information.

===============================================
The naming structure for CodeGen template files
(assuming they are placed in the same directory as this file):

The templates directory would be:
	includes/qcubed/codegen/templates/[TYPE]/[MODULE]/[FILE]

Where [TYPE] is the object being generated, for example:
	* db_orm
	* db_type

And [MODULE] is the category of file being generated, for example:
	* class_gen - templates and subtemplates for the Data Class Gen file
	* class_subclass - templates and subtemplates for the Data Class customizable subclass
	* drafts - templates and subtemplates for all things with regards to draft forms/panels
	* meta_control - templates and subtemplates for the metacontrol
	* meta_datagrid - templates and subtemplates for the metadatagrid

And [FILE] is the filename of the  template or subtemplate, itself.
Note that any file with a "_" prefix is considered a template and will
be processed by the code generator.  All other files are considered
subtemplates, and are only processed if envoked by a template.
===============================================