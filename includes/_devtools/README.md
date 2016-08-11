# About `_devtools` directory

This directory contains various tools that help with the development phase of a project
only. These include command line tools, and helpers for the installation process of qcubed
and plugins. You should not include this directory in your deployment.

* `codegen.cli` - for Unix/Linux/Mac OS X command lines
* `codegen.phpexe` - for Windows command line

Both use the QCodeGen and related QCubed codegen libraries to do the bulk
  of the work. They simply instantiate a QCodeGen object, execute various
  public methods on it to do the code generation, and create a text-based
  report of its activities, outputting it to STDOUT.


## PATH_TO_PREPEND.TXT

**VERY IMPORTANT**: Before running ANY command line tools, you need to be sure
to update the path_to_prepend.txt file with the absolute path to the
prepend.inc.php file in your includes directory.


## OTHER IMPORTANT NOTES

For the .cli version, you may need to update the top line of the file to
match the path of the PHP bin executable on your system, too.

For the .phpexe version, you need to remember to run it as a PARAMETER to
the php.exe executable (usually installed in `c:\php\php.exe`).

