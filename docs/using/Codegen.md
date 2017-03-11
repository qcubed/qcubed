#Code Generation

##Overview
The code generator (codegen for short) will read the schema structure of your database and generate a number of different kinds of files based on what it finds.

The entire process is controlled by the QCodeGenBase class, which is overridden by the QCodeGen class in such a way that you can customize the process by changing code in the QCodeGen class if you need to. 

The QCodeGen class uses template files to generate the output files, and all the output files will eventually end up in the *generated* folder in your *project* folder. You can customize the templates if needed.

Controls have a form of template too. During part of the user interface generation process, the templates will call specific functions inside QControl generators, allowing the QControls themselves to control how they are generated. This mechanism enables the ability for developers to associate custom controls and plugins with particular data types, allowing the codegenerator then to generate code that will move data between the user-interface for that control and the database field its associated with.

The settings in your *project/includes/configuration/codgen_settings.xml* file change the codegen process too.

 As you can see, there are many places where you can customize the codegen process. Below you will find more detail on each piece to better understand where you should focus your customizations.
 
##Codegen Settings
##Templates

 
