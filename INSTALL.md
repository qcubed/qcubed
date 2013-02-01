# Getting started with QCubed

## Installation

### File System

It begins with the extraction of the QCubed tarball. If you have downloaded QCubed by cloning the Git Repositiory then it is not needed. We would recommend you to clone the Git repository - it contains latest stable code. Copy the QCubed files to a directory within the webserver's DOCROOT (also known as DocumentRoot, webroot, wwwroot, etc., depending on which platform you are using).

At a later point, you may choose to move folders around in your system, splitting them at different location etc.  QCubed offers the flexibility to have the framework files in any location. But that is a story you learn after using the framework (shhh.... just refer to the ```configuration.inc.php.sample``` file in ```includes/configuration``` directory).

But, since we're just getting started, we'll provide you with the instructions on how to finish the installation assuming that you're keeping the entire QCubed installation together as originally released. In the later part of the document, we will call the installation directory (the place where you have copied QCubed framework files) as *wwwroot*.

### Automated installation

Beginning Release 2.2, we have created an automated installer which will help you configure and install QCubed.

#### How to use the installer

  1. You should have copied the QCubed into its own directory under DocumentRoot. We assume that your DocumentRoot is```/var/www``` and you copied QCubed under ```/var/www/qcubed```.
  2. Open ```http://localhost/qcubed``` from your browser. You should be provided with two options
    * To go to the start page
    * To launch the installer
  3. Since we have not installed the framework, we will launch the installer.
  4. Installer will come to its first step and will ask you for the location where you copied the file. It tries to make a guess and in most cases, the guess is right. However, you should verify the same and click on the **Next** button. Also, the instructions to install QCubed manually are shown on this (first) page of installer as well. Should you desire to do it manually, or if the installer fails, you can use the manual procedure.
  5. The installer will redirect to the second step and check for certain things to be true. These include:
    * The installation path was supplied.
    * The given directory (*wwwroot*) path must be inside the webserver's DocumentRoot.
    * The *wwwroot* directory must be existing.
    * Check for the availability of the ```includes```, ```assets``` and ```drafts``` directories inside *wwwroot* directory.
  6. If any of the conditions do not match, the installer will throw an error. Otherwise it will ask for the values of different fields such as ```__SUBDIRETORY__```, ```__DOCROOT__``` and ```__VIRTUAL_DIRETORY__``` along with the database settings (adapter, port, database name, databse username and password). You should enter those and proceed to the 'Write Configuration' step.
  7. In the last step (Write Configuration step), the installer will read the ```configuration.inc.php.sample``` file in the ```includes/configuration``` directory and replace the values with with you entered and dump them to a new file called as ```configuration.inc.php```. If ```configuration.inc.php``` exists already then it will not overwrite the file (to save your current configuration) but it will show you the file contents so that you can use them later at your will. If the installer fails to create the file due to restricted permissions, it would still show you the contents. The feature of *not overwriting current configuration file* is in place to make sure that even if someone else gains the access to the installer script, he should not be able to overwrite the configuration.

**NOTE**: After the installation has been finished, it is recommended to delete the installer files. They are located in ```assets/_core/php/_devtools/installer``` directory within *wwwroot*.

### Manual Installation

Inside of wwwroot/configuration/includes you'll find the configuration.inc.php file.  You'll need
to open it to specify the actual location of your __DOCROOT__.

IMPORTANT NOTE FOR WINDOWS USERS:
Please note that all paths should use standard "forward" slashes instead of
"backslashes".  So windows paths would look like "c:/wwwroot" instead of
"c:\wwwroot".

Also, if you are putting QCubed into a SUBDIRECTORY of DOCROOT, then be sure
to set the __SUBDIRECTORY__ constant to whatever the subdirectory is
within DOCROOT.

If you are using QCubed inside of a Virtual Directory (also known as a Directory
Alias), be sure to specify the __VIRTUAL_DIRECTORY__ constant, too.

Next, specify a location to put your "_devtools_cli" directory (this could be either
inside or outside of docroot), and update the __DEVTOOLS_CLI__ constant accordingly.

Finally, be sure to update the DB_CONNECTION_1 serialized array constant with the
correct database connection information for your database.

(Information on all these constants are in configuration.inc.php, itself.)

> We are working on a guided installation to ease this step.

### Include prepend.inc.php

Calling require() on prepend.inc.php is necessary to include the framework in your PHP file.

Note that by default, this is already setup for you in:
* /index.php
* /sample.php
* /_devtools/codegen.php
* /form_drafts/index.php
* All the /examples/
* Any code generated form_draft page

To change this or for any new PHP scripts you want to write, simply make sure any PHP
script that wants to utilize the QCubed Framework STARTS with:
	require('includes/prepend.inc.php');
on the very first line.

NOTE that the "includes/configuration/prepend.inc.php" may be different -- it depends on the relative
path to the includes/prepend.inc.php file.  So if you have a docroot structure like:
	docroot/
	docroot/pages/foo/blah.php
	docroot/includes/configuration/prepend.inc.php
then in blah.php, the require line will be:
	require('../../includes/configuration/prepend.inc.php');

Note that if you move your .php script to another directory level, you may need to update
the relative path to prepend.inc

If you specified the includes/ in your includes_path in your php.ini file (see optional
STEP FIVE below), then all you need to do is have
	require('prepend.inc.php');
at the top of each file (no need to specify a relative path).

### File Permissions

Because the code generator generates files in multiple locations, you want to be sure that the
webserver process has permissions to write to the docroot.

The simplest way to do this is just to allow full access to the docroot for everyone.  While this
is obviously not recommended for production environments, if you are reading this, I think it is
safe to assume you are working in a development environment. =P

On Unix/Linux, simply run "chmod -R ugo+w" on your docroot directory.

On Windows, you will want to right-click on the docroot folder and select "Properties",
go to the "Security" tab, Add a "Everyone" user, and specify that "Everyone" has "Full Control".
Also, on the "general" tab, make sure that "Read-Only" is unchecked.  If asked, be sure to
apply changes to this folder and all subfolders.

If this doesn't work, an additional task would be to use Start - Control Panel - Administrative Tools
- Computer Management - Local Users and Groups - Users.  Look for a user with a name like
IUSR_ComputerName (where ComputerName is your computer name).  Right-click on this user then
Properties - Member of.  If it just shows Guests, make sure it's selected.  And then finally
right-click on your QCubed folder, select Properties, and add the group Guests with Full Control.



### (Optional) Set up the include path

NOTE THAT THIS STEP IS OPTIONAL!  While this adds a VERY slight benefit from a
convenience standpoint, note that doing this will also have a slight performance cost,
and also may cause complications if trying to integrate with other PHP frameworks.

Starting with Qcodo 0.2.13, you no longer need to update the PHP include_path
to run Qcodo.  However, you may still want to update the include_path for any
of the following reasons:
* All PHP scripts will only need to have "require('prepend.inc.php')" without needing
  to specify a relative path.  This makes file management slightly easier; whenever
  you want to move your files in and out of directories/subdirectories, you can do
  so without needing to worry to update the relative paths in your "require"
  statement (see STEP THREE for more information)
* With the include_path in place, you can also easily place other include files
  (like headers, footers, other libraries, etc.) in the includes/ directory, and
  then you can include them, too, without worrying about relative paths

Again, NOTE THAT THIS STEP IS OPTIONAL.

If you wish to do this, then the PREFERRED way of doing this is simply edit your
PHP.INI file, and set the include path to:
	.;c:\path\to\DOCROOT\includes\configuration (for windows)
		or
	.:/path/to/DOCROOT/includes/configuration (for unix)
(If you put QCubed into a subdirectory, then you want to make sure to specify it
in include_path by specifying /path/to/DOCROOT/subdir/includes/configuration)

NOTE: the "current directory" marker must be present (e.g. the ".;" or the ".:" at
the beginning of the path)

Now, depending on your server configuration, ISP, webhost, etc., you may
not necessarily have access to the php.ini file on the server.  SOME web servers
(e.g. Apache) will allow you to make folder-level or virtualhost directives
to the php.ini file.  See the PHP documentation for more information.


ALTERNATIVELY, if you like the idea of being able to simply have
"require('prepend.inc.php')" with no relative path inforamtion at the top of your
pages, but if you are unable for whatever reason to set the include_path, then you
could use one of the following "set_include_path" lines at the top of each
web-accessed *.php file/script in your web application.

IMPORTANT NOTE: Because the Code Generator can also generate some of your
web-accessed *.php files, you will need to ALSO update the codegen template files
	DOCROOT/includes/qcodo/_core/codegen/templates/db_orm_edit_form_draft.tpl
	DOCROOT/includes/qcodo/_core/codegen/templates/db_orm_list_form_draft.tpl
to have the same "set_include_path" line at the top.

The line to choose depends on whether you're running the PHP engine as a Plug-In/Module
or a CGI (and of course, keep in mind that if you threw QCubed within a subdirectory of
DOCROOT, be sure to specify that in the line you select).

Use this if running PHP as a Apache/IIS/Etc. Plug-in or Module
set_include_path(sprintf('.%s%s/includes', PATH_SEPARATOR, $_SERVER['DOCUMENT_ROOT']));

Use this if running PHP as a CGI executable
set_include_path(sprintf('.%s%s/includes', PATH_SEPARATOR, substr($_SERVER['SCRIPT_FILENAME'], 0, strlen($_SERVER['SCRIPT_FILENAME']) - strlen($_SERVER['SCRIPT_NAME']))));