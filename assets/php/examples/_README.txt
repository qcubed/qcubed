This directory contains the code for the examples web site, which you can run
locally. In order to run all the examples, and pass the QCubed unit tests locally,
you will need to:

1. Set up a database. Use one of the SQL files 'mysql_innodb.sql', 'pgsql.sql' and
'sql_server.sql' to set up the examples database, depending on the type of database
you are using.

2. Edit your configuration.inc.php file to point to the examples database.

3. Copy the codegen_options.json file to your configuration directory.

The codgen_options.json file contains options that the unit tests will look for when
running the tests. Delete this file from your configuration directory when you are
ready to build your own site.
