#Database Design
This document describes how different aspects of the database design impact the generated files and the overall system. It is not a primer on SQL database design, but it does reveal certain aspects of QCubed's behavior that will help you decide what to do.

Much of this information can be gleaned from the Examples and Tutorials pages, but this is a more concise and in-depth discussion.

The code generator will read the schema structure of your database and generally will create a PHP object for each table and a member variable in that object for each column in your database table.

The examples database located in the qcubed/assets/php/examples directory is a good example of the application of the following design principles.

##Table and Column Naming Conventions
Generally you should name your tables and columns using only lower-case letters, and using underscores as word boundaries (e.g. 'first_name'). The code generator will convert these names to equivalent camelcase names in PHP when referring to the same object (e.g. 'FirstName').

Name your tables with the singular version of the kind of object you are representing ("person" instead of "people"). QCubed will create references for both the singular and plural version of the word depending on how it is used in PHP.

##Foreign Keys
When referring to a single primary key in another table, like when you are creating a foreign key column, append the name of the column with an underscore and the name of the column in the other table. For example, if the primary key in a table is named 'id', you should end the name of the column that references it with '_id' (e.g. person_id).

Specifying a foreign key in another table can create a one-to-many relationship or a one-to-one relationship. It depends on the type of index you create for the foreign key column. If a normal index, the relationship will be one-to-many. If a unique index, the relationship will be one-to-one.

For one-to-many relationships, the code generator creates the ability to get an array of related objects. It also creates user-interface elements to select multiple items from the perspective of the 'one' record, and a single item from the perspective of the 'many' record. 

For one-to-one relationships, the assumption is that the record being referred to is an extension of the base record. Thing of it like a subclass. In these situations, the code generator creates "As A" relationship references. For example, a manager could be a person in your database, and you can get the related Person record by asking for the ManagerAsPerson reference in the Manager record.

Many-to-many relationships can be created using an [Association Table](#association_tables).

If your database supports foreign keys (Postresql and MySQL Innodb for example), and you specify foreign key contstraints, the code generator will create the proper relationships automatically. However if your database does not support foreign keys (MySQL MyISAM for example), you can still specify the relationships through a relatinships.txt file to get the QCubed support for the relationships. See the Using a Relationships Script topic in the Tutorial and Examples website for more information.

##Type Tables
Type tables are a special kind of table that are used to create static enumerated types in the database. This is helpful when you want to present your user with a limited selection of options to choose from, and record that selection in the database.

For example, you might want to give your user the choice between "right, left, up or down".

MySQL has a similar functionality and uses SET and ENUM column types. QCubed Type tables provide a more powerful alternative to those, with the following features:

* **Portability**. SET and ENUM are MySQL only column types and are not part of the SQL standard.
* **PHP Support**. The code generator will generate special PHP code for a Type table that will let you refer to the values as constants in PHP, translate between values and their string equivalents, and automatically generate html lists to let the user select one, or a group of these options. 
* **Faster Queries**. Depending on what you are trying to do, QCubed's type tables in some instances can greatly improve SQL's ability to select a subset of objects based on type selections.
* **Code Reuse**. You can refer to the same Type table from multiple tables in your database. MySQL would require you to recreate the values in each reference, making it harder to maintain if you later add to these values.

One important limitation to remember is that Type tables are supposed to be static...they are only for creating a list that will not change while the program is running. If you change a type table, it will only effect the PHP after code generation. If you want to create a list that a user can edit, just use a regular SQL table for that.

###Defining the Type Table
A type table must have at a minimum two columns:

* An *id* column that is the primary key. This is the value that will be saved in the database and passed around in PHP. Most of the time, you should choose an INTEGER column type for this, but its not required.
* A *name* column that has a unique index. This is the string that will be used when a user is asked to choose the option, so likely you include capital letters and spaces here. QCubed also uses an algorith to convert this name to one that will be used in PHP when referring to the id value.

You can add other columns as well to specify additional information associated with each item. The code generator will create additional constants and functions to make it easy to associate a type with this additional information. For example, if you would like to separate out the name used in PHP from the string presented to the user, you can add an additional column to specify what the user will see.

End the name of your table with the suffix "_type". This indicates to QCubed to treat this table as a type table. Note that this is configurable in the codegen_settings.xml file if you want to use a different suffix.

After defining the table, insert values into the table to populate the table. During code generation, QCbued will read these values and turn them into constants. It might seem strange to create a table that will not change, but by doing it this way, you allow QCubed to use SQL to create queries that it couldn't otherwise if the types were only in PHP.

###Referring to a Type table
Type tables can be used to just create static types in your PHP, but can also be referred to from other tables in the database.

You can refer directly to a value in the type table by simply creating a foreign key reference from another table to the id column in the type table (this is similar to a MySQL ENUM). The code generator will then create code to let you set and get this value in either its id or string form, and also create a listbox in the form to allow the user to select this value.

You can also use an [association table](#association_tables) to create the ability to associate multiple type value with a record (this is similary to a MySQL SET). The code generator by default will create a list of checkboxes that will allow the user to select multiple values.

##Association Tables
Association tables are a QCubed mechanism to make many-to-many relationships easier to manage. The code generator creates PHP that allows you to easily query through the relationship to create arrays of results, and creates user interface elements to allow your user to select multiple items to link to. 

An association table has two, and only two columns in it. Both columns must be foreign keys to the respective tables you are linking together, and both together must be the primary key for the table. 

Unlike Type tables, you cannot add additional columns to an association table. If you have a need for something like that, (for example, to filter on this meta-data when querying), then just use a regular table. You will not get the association table user interface support, but you will still be able to make queries through the linked tables and you will likely be able to get the results you are looking for.

End the name of your association table with the suffix "_assn". Like Type tables, you can specify a different ending in the codegen_settings.xml file.

As mentioned earlier, one of the foreign keys can point to a Type table to create a multi-selection relationship to an enumerated type.

##Specifying Columns

QCubed will do some type checking that is consistent with the column types you specify in your SQL.

INTEGER(1) types are treated as booleans. They are represted as checkboxes by default in the generated user interface.

The other integer types (SMALLINT, INT, BIGINT, etc.) are all standard PHP integers. The user interface by default uses an integer-only textbox to edit these.

VARCHAR, TEXT and BLOBS are treated as strings in PHP. The VARCHAR length value will be used as a limit to the number of characters that a user can enter in the related textbox.

FLOAT and DECIMAL types are treated as floating point numbers in PHP. Floating-point only textboxes are generated to edit these fields. 

DATE, TIME, and DATETIME are treated as QDateTime types in PHP. 

TIMESTAMP types become PHP strings. If a TIMESTAMP is set up in the database to automatically update every time it changes, then no user interface code is created for that column. If your database does not support auto-updating TIMESTAMP columns, or if it limits the number of TIMESTAMP fields, you can have QCubed update the value of this field for you from PHP by entering the following in the comment field:

```{"timestamp":true,"autoupdate":true}```

If you specify that a column should not allowed to be NULL, the code generator will create code requiring the user to enter a value. 

If you specify a default value in the database, QCubed will use that default value to initialize the corresponding member variable in the consructor of the related PHP object.
