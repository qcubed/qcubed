# About `model` directory

Model classes will be saved by the Code Generator here.

These files will be *created by the code generation process **only if they do not exist*** in this directory. You can modify these files, and they will not be replaced.

## About model classes

Model classes are sub-classes derived from the code-generated model classes. Hence, these classes contain all the functionality of the base classes and are here to allow you to modify/add methods which you would like to have in your model handling code.

#### Example:

So if you have a table called `user` in your database, then under normal codegen settings (no prefix or suffix added to the code-generated class), then:

  - You should have a `UserGen.class.php` file in the `model-base` directory and a `User.class.php` in this directory.

  - They should have `UserGen` and `User` classes defined in them, respectively. 

  - The `User` class will extend `UserGen` class.
  
  - When the code generator runs the first time, both `UserGen` (inside `UserGen.class.php`) and `User` (inside `User.class.php`) classes are created.
  
  - When the code generator runs next time (and any number of subsequent runs), the `UserGen.class.php` file is rewritten with updated code reflecting all schema changes (point to be noted: whether or not there were any schema changes, the file is overwritten) but code generator will see that the  `User.class.php` file already exists and will not alter it.
  
 Hence, you are free to make any changes in the `User.class.php` file without having the fear to lose it. 