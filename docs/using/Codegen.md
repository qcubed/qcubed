#Code Generation

##Overview
The code generator (codegen for short) will read the schema structure of your database and generate a number of different kinds of files based on what it finds.

The entire process is controlled by the QCodeGenBase class, which is overridden by the QCodeGen class in such a way that you can customize the process by changing code in the QCodeGen class if you need to. 

The QCodeGen class uses template files to generate the output files, and all the output files will eventually end up in the *generated* folder in your *project* folder. You can customize the templates if needed.

Controls have a form of template too. During part of the user interface generation process, the templates will call specific functions inside QControl generators, allowing the QControls themselves to control how they are generated. This mechanism enables the ability for developers to associate custom controls and plugins with particular data types, allowing the codegenerator then to generate code that will move data between the user-interface for that control and the database field its associated with.

The settings in your *project/includes/configuration/codgen_settings.xml* file change the codegen process too.

 As you can see, there are many places where you can customize the codegen process. Below you will find more detail on each piece to better understand where you should focus your customizations.
 
##Codegen Settings
The installation process will give you a default *codegen_settings.xml* file in your *project/includes/configuration* directory. You can edit this file to specify various options you can set to customize the code generation process. The file is well documented, so see the comments there for more information.

##Templates
Most of the codegen process is controlled by the templates found in the *vendor/qcubed/qcubed/codegen/templates* directory. There you will find the following four top-level directories:

* **aggregate\_db_orm**. One-time code generation for creating files that are related to all the ORM (Object-relational model) classes.
* **aggregate\_db_type**. One-time code generation for creating files related to the [Type](Database.md#TypeTables) tables.
* **db_orm**. Templates for creating files for each general-purpose table in the database.
* **db_type**. Templates for creating files related to Type tables.

Templates are just PHP files that are "Eval'd" by the code generator, there is no need to learn a separate template language (after all, PHP was originally designed as a template language). 

File names that begin with an underscore (_) are entry point template files. The code generator will look for these files and eval them. Many of these files include other files in the directory that do not start with an underscore. These are sub-templates, and are broken out for code management and also to provide the ability to override parts of the codegenneration process.

The use of *gen* in the name of a template is a convention that indicates the template creates a file in the *generated* directory every time the codegen process runs. The use of *sub_class* in the name is a convention that indicates the template is for creating a stub file that overrides a Gen class and which you can later edit to modify the behavior of the class. These stub files will only be generated if they do not exist, so that any changes you make will not be over-written.

Within many of the template files, you will see a preamble which specifies some settings for that particular file. These settings specify the location of the oiutput file, whether its a one-time creation file or not, and other settings.

You can create your own templates, or override a particular template. To override a template file, name the file the same as the file you are overriding, and place it in a directory inside the project/includes/codegen/templates directory that matches the directory structure that the file is contained in. For example, to override the *vendor/qcubed/qcubed/includes/codegen/templates/db\_orm/class\_gen/custom\_funcs.tpl.php* file, you would create the *project/includes/codegen/templates/db\_orm/class\_gen/custom\_funcs.tpl.php* file. Also, any additional files you create in these directories that begin with an underscore in the name will be eval'd as well.

Template override files can be placed in other locations. For example, you might develop a library of templates that you would like to use in multiple projects, or share with other users. You can specify a chain of directories to search for templates by editing the QCodeGen.class.php file and adding to the static *TemplatePaths* variable there.

##Control Code Generators
Some of the templates call into specific functions in code generator classes to generate the code that initializes a specific control, and links it to the user interface. 

The base code generator classes are located in the vendor/*qcubed/qcubed/includes/codegen/classes* directory. Sub-class files that you can edit are in the *project/includes/codegen/controls* directory. However, you will not likely need to edit these files. The default functionality will work for the vast majority of cases.

##Associating a Database type with a QControl
The code generator will generate a specific kind of control for each kind of column it finds in a table. For example, VARCHAR and TEXT columns will result in a QTextBox control in the generated user interface.

You can change the generated control for individual columns by using the Model Connector Editor. You can change the default control for a particular database type for the entire user interface by creating a ModelConnectorControlClass function and putting it in your QCodeGen.class.php file. Here is an example:

```
		public function ModelConnectorControlClass($objColumn) {

			$ret = parent::ModelConnectorControlClass($objColumn);

			if ($ret == 'QLabel') return $ret;

			switch ($objColumn->VariableType) {
				case QType::Boolean:
					return 'QJqCheckBox';

				case QType::DateTime:
					if ($objColumn->DbType == 'Date') {
						return 'QDatepickerBox';
					}
					elseif ($objColumn->DbType == 'Time') {
						return 'QTimepickerBox';
					}
					else {
						return 'QCubed\Plugin\QDateTimePickerBox';
					}
			}

			return $ret;
		}
```

##Generated Objects
As of QCubed version 3.1, the following types of objects get generated by the default templates:

* **ORM Models**. These are PHP classes that reflect the state of records in the database, and include code to load these objects from the database, change them, and delete them. Codegen creates base classes to do most of the general purpose data work, and sub-classes that you can edit to customize how an individual model works, and to help you decouple your application from the SQL needed to move data in and out of your application.
* **Forms, Dialogs and Panels**. These are user interface objects that let you list the contents of a SQL table, and create, update and delete (CrUD) records in the table. Like models, you will also get generated classes for default functionality, and editable sub-classes.
* **Model Connectors**. These are helper classes that move data back and forth between the controls in the Panels, and the Model objects.

The end result of codegen is a default working website that provides a user the ability to create, update and delete records in the database. You can see this default by clicking on the Form Drafts link from the QCubed Start page. From this default, you add your business logic and styling to make a website that solves your specific needs.