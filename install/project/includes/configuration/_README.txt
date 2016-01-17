This is the central location for all included configuration files.  Feel free to include
any new classes or include files in this directory.



**** configuration.inc.php ****

This contains server-level configuration information (e.g. database connection
information, docroot paths (including subfoldering and virtual directories),
etc.  A sample file is provided for you, and is used by the QCubed startup wizard
to create an initial version. Feel free to make modifications to this file to have it reflect the
configuration of your application, including any global defines that are particular to your application.

See the inline documentation in configuration.sample.inc.php for more information.



**** prepend.inc.php ****

This is the top-level include file for any and all PHP scripts which use
Qcubed.  Global, application-wide loaders, settings, etc. are in this file.

Feel free to make modifications/changes/additions to this file as you wish.
Note that the QApplication class is defined in prepend.inc as well.  Feel free
to make changes, override methods, or add functionality to QApplication as
needed.

See the inline documentation in prepend.inc.php for more information.


**** codegen_settings.xml ****

This file controls overall settings for parts of the code generation. Feel free
to change these as needed.


**** codegen_options.json ****

This file is created and maintained by the ModelConnectorEditor. It has options for
the individual controls that correspond to fields in your database. There may be times
that you need to directly edit this file, and you should feel free to do so.

**** Codegen Notes ****

QCubed is set up to generate a default set of objects and forms to get you started with your application.
This is called “codegen”. The notes below will help you understand the process and how to customize it to your needs.
Ideally, you should customize the codegen process first before starting to write you application code,
but we know that development does not go always as planned, and the whole QCubed system is set up so that you can
separate out your hand written code from the generated code, and continue to tweak the codegen process and re-generate code at any time.

The codegen process starts at the QCubed start screen by clicking on the codegen link.
PHP is executed to generate the files. Therefore, the target directories for codegen will need to be writable by the web server process.

The codegen process works by instantiating a QCodeGen object. This object then looks in the template directories and begins
to include the php files there that start with an underscore (_). These templates then include other files, which in turn
may include other template files. This combination will eventually generate the forms, model connectors, and data table
interface classes that you will base your application on.


Model Connectors
Model Connectors are helper classes that have methods which connect form controls to columns in SQLn data tables. Each column
in the data table corresponds to a control that is generated in a model connector class. Your form object calls methods
in the model connector to get copies of the controls and then to place them in the form.

To customize the generated controls, you have the following choices:
- Use the ModelConnectorEditor (see the example on this), to set specific options on each control.
- Create your own code generating templates and place them in your project/includes/codegen/templates directory. Its best
  to do this by copying the corresponding file in the qcubed/framework/includes/codegen/templates directory and then
  editing the file and placing it in the corresponding location in the above project directory. The project directory
  files will override the files in the vendor directory.
- Override the generated code by editing the model connectors in your project/includes/connector directory.


Version 3

QCubed Version 3 introduces the concept of having the controls themselves create the code to interact with the database
for the ModelConnector, rather than the templates. Coupled with this is the ModelConnectorEditDlg dialog, which lets you
right click on a control and edit many of the controls options. These changes get embedded into the generated ModelConnector.
You can see a description of each option by hovering over the item in the dialog.

These new features give the developer the ability to do the following:
- Override the default control type to specify a particular control type
- Allow custom controls and plugins to generate their own model connector code and have that code automatically be used
  instead of the default code just by specifying that control in the comments of a column.
- Allow subclasses of standard controls to override the code generation methods to generate different code.
- Specify additional overrides to control many aspects of control creation in the generated model connector.

Notes for Upgrading from version 2

Many of the problems that caused programmers to create their own templates are now solvable through the new Options
feature available through Comments. However, you are still free to override the templates as needed. In fact, this new
feature is implemented entirely through the templates, so if you want to keep your old templates, simply replace the new
templates with the old ones from version 2.

QLabel no longer accepts a strFormat parameter at create time. You can always set it using the ->Format parameter after
creating the control, or specify this in an override in a Comment option.






