# Getting started with QCubed

## Installation

### File System

It begins with the extraction of the QCubed tar ball. If you have downloaded QCubed by cloning the Git Repository then it is not needed. We would recommend you to clone the Git repository (master branch) - it contains latest stable code. Copy the QCubed files to a directory within the webserver's DOCROOT (also known as DocumentRoot, webroot, wwwroot, etc., depending on which platform you are using. We will use the word 'DocumentRoot' in this document).

At a later point, you may choose to move folders around in your system, splitting them at different location etc.  QCubed offers the flexibility to have the framework files in any location. But that is a story you learn after using the framework (refer to the ```configuration.inc.php.sample``` file in ```includes/configuration``` directory).

But, since we're just getting started, we'll provide you with the instructions on how to finish the installation assuming that you're keeping the entire QCubed installation together as originally released. In the later part of the document, we will call the installation directory (the place where you have copied QCubed framework files) as *instdir*.

### Automated installation

Beginning Release 2.2, we have created an automated installer which will help you configure and install QCubed.

#### How to use the installer

  1. You should have copied the QCubed into its own directory under DocumentRoot. We assume that your DocumentRoot is ```/var/www``` and you copied QCubed under ```/var/www/qcubed```.
  2. Open ```http://localhost/qcubed``` from your browser. You should be provided with two options
    * To go to the start page
    * To launch the installer
  3. Since we have not installed the framework, we will launch the installer.
  4. Installer will come to its first step and will ask you for the location where you copied the file. It tries to make a guess and in most cases, the guess is right. However, you should verify the same and click on the **Next** button. Also, the instructions to install QCubed manually are shown on this (first) page of installer as well. Should you desire to do it manually, or if the installer fails, you can use the manual procedure.
  5. The installer will redirect to the second step and check for certain things to be true. These include:
    * The installation path was supplied.
    * The given directory ( *instdir* ) path must be inside the webserver's DocumentRoot.
    * The *instdir* directory must be existing.
    * Check for the availability of the ```includes```, ```assets``` and ```drafts``` directories inside *instdir* directory.
  6. If any of the conditions do not match, the installer will throw an error. Otherwise it will ask for the values of different fields such as ```__SUBDIRETORY__```, ```__DOCROOT__``` and ```__VIRTUAL_DIRETORY__``` along with the database settings (adapter, port, database name, database username and password). You should enter those and proceed to the 'Write Configuration' step.
  7. In the last step (Write Configuration step), the installer will read the ```configuration.inc.php.sample``` file in the ```includes/configuration``` directory and use the placeholders therein to replace the values with what you entered.It will then dump the contents to a new file called as ```configuration.inc.php```. If ```configuration.inc.php``` already exists, then it will not overwrite the file (to make sure you do not lose your current configuration) but it will show you the file contents so that you can use them later at your will. If the installer fails to create the file due to restricted permissions, it would still show you the contents. The feature of *not overwriting current configuration file* is in place to make sure that even if someone else (unwanted user) gains the access to the installer script, he should not be able to overwrite the configuration.

**NOTE**: After the installation has been finished, it is recommended to delete the installer files. They are located in ```assets/_core/php/_devtools/installer``` directory within *instdir*.

### Manual Installation

To install QCubed manually in face of failure of the installer due to any reason, follow the following steps

  1. Open the ```includes/configuration/configuration.inc.php.sample``` file within the *instdir* directory.
  2. Copy the contents of this file and paste it in a new file called ```configuration.inc.php``` in the same directory (if you want, you can rename the ```configuration.inc.php.sample``` file to ```configuration.inc.php``` as well, but we would not recommend that). Save the ```configuration.inc.php``` file.
  3. Edit the ```configuration.inc.php``` file and set the values for ```__DOCROOT__```, ```__VIRTUAL_DIRECTORY__``` and ```__SUBDIRECTORY__``` and set the correct values. You should also set the values for the definition of ```DB_CONNECTION_1``` correctly. All these variables have been explained well in the comments in the file.
  4. Save the ```configuration.inc.php``` file.

**IMPORTANT NOTE FOR WINDOWS USERS**: Please note that all paths should use standard "forward" slashes instead of "backslashes".  So windows paths would look like "c:/xampp/htdocs" instead of "c:\xampp\htdocs".

### Include prepend.inc.php or qcubed.inc.php

Calling ```require()``` on ```prepend.inc.php``` is necessary to include the framework in your PHP file.

Note that by default, this is already setup for you in most files (actually, almost every file that you get with QCubed, or the ones that QCubed generates).

To change this or for any new PHP scripts you want to write, simply make sure any PHP script that wants to utilize the QCubed Framework STARTS with:
	```require('includes/prepend.inc.php');```
on the very first line.

NOTE that the ```includes/configuration/prepend.inc.php``` may be different -- it depends on the relative path to the ```includes/prepend.inc.php``` file.  So if you have a docroot structure like:
```
	docroot/
	docroot/pages/foo/blah.php
	docroot/includes/configuration/prepend.inc.php
```
then in blah.php, the require line will be:
	```require('../../includes/configuration/prepend.inc.php');```

Note that if you move your .php script to another directory level, you may need to update the relative path to ```prepend.inc.php```

If you specified the includes/ in your includes_path in your php.ini file (discussed later in this document), then all you need to do is have
	```require('prepend.inc.php');```
at the top of each file (no need to specify a relative path).

#### qcubed.inc.php
Throughout the QCubed installation, you will find a number of ```qcubed.inc.php``` files at a number of places and these files have been used at multiple places as well. While some of these files are pretty important, others are not. The one you should be concerned with is the one located inside the *instdir* (no subdirectories). This file helps in the particular case when you want to move the ```includes``` directory outside the DocumentRoot on webserver. It is a good security practice.

```qcubed.inc.php``` (the file we talked about) calls (includes) the ```prepend.inc.php``` file automatically. So you can also include this file instead of ```prepend.inc.php```. While you are free to choose the way you want, QCubed uses this file to ease the pain of making sure that even the files you create can be moved wherever you want.

If you go to the ```assets``` directory inside *instdir*, then you would find a file named as ```qcubed.inc.php``` too. This file does nothing but include the one in the *instdir*. Another ```qcubed.inc.php``` is located in ```assets/_core``` directory as well, with the same contents. So basically, you can copy and paste these files in any directory you manually create and *include* the file in other php files in the same directory. This will make sure that if you later choose to move the files around, they will not have to undergo path changes in the first ```prepend.inc.php``` include (i.e. you would not have to change a line like ```require_once '../../../includes/configuration/prepend.inc.php``` to ```../../includes/configuration/prepend.inc.php```.

**Moving the includes directory outside DocumentRoot**: We have already said that moving the includes directory out of the DocumentRoot is a good security practice. If you want to do the same, you have 4 steps to follow:

  1. Change the ```__INCLUDES__``` directive in ```includes/configuration/configuration.inc.php``` file.
  2. Move the includes directory to another location.
  3. Alther the ```qcubed.inc.php``` file (inside the *instdir* ) to contain the updated location of the includes directory.
  4. Make sure that at all the php files you have written includes the ```qcubed.inc.php``` file instead of ```prepend.inc.php```.

These steps makes sure that you would be able to move around the includes directory elsewhere. Also, it signifies the importance and usage of ```qcubed.inc.php``` located in the *instdir*.

### File Permissions

Because the code generator generates files in multiple locations, you need to be sure that the webserver process has permissions to write to the docroot. The simplest way to do this is just to allow full access to the docroot for everyone.  While this is obviously not recommended for production environments, if you are reading this, we believe it is safe to assume you are working in a development environment. =P

On Unix/Linux, simply run "chmod -R ugo+w" on your docroot directory (or better still, your *instdir* only).

On Windows, you will want to right-click on the docroot folder and select "Properties", go to the "Security" tab, Add a "Everyone" user, and specify that "Everyone" has "Full Control". Also, on the "general" tab, make sure that "Read-Only" is unchecked.  If asked, be sure to apply changes to this folder and all subfolders.

If this doesn't work, an additional task would be to use Start - Control Panel - Administrative Tools - Computer Management - Local Users and Groups - Users.  Look for a user with a name like IUSR_ComputerName (where ComputerName is your computer name).  Right-click on this user then Properties - Member of.  If it just shows Guests, make sure it's selected.  And then finally right-click on your QCubed folder, select Properties, and add the group Guests with Full Control.


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
to the php.ini file - however those capabilities are not availed to the user by all hosts.
See the PHP documentation for more information about this and contact your hosting service provider to learn about the possibilities.

ALTERNATIVELY, if you like the idea of being able to simply have ```require('prepend.inc.php')``` (or ```require 'qcubed.inc.php'``` ) with no relative path information at the top of your
pages, but if you are unable for whatever reason to set the include_path, then you could use one of the following "set_include_path" lines at the top of each web-accessed *.php file/script in your web application.

IMPORTANT NOTE: Because the Code Generator can also generate some of your
web-accessed *.php files, you will need to ALSO update the codegen template files
	DOCROOT/includes/qcodo/_core/codegen/templates/db_orm_edit_form_draft.tpl
	DOCROOT/includes/qcodo/_core/codegen/templates/db_orm_list_form_draft.tpl
to have the same "set_include_path" line at the top.

The line to choose depends on whether you're running the PHP engine as a Plug-In/Module
or a CGI (and of course, keep in mind that if you threw QCubed within a subdirectory of
DOCROOT, be sure to specify that in the line you select).

Use this if running PHP as a Apache/IIS/Etc. Plug-in or Module
```set_include_path(sprintf('.%s%s/includes', PATH_SEPARATOR, $_SERVER['DOCUMENT_ROOT']));```

Use this if running PHP as a CGI executable
```set_include_path(sprintf('.%s%s/includes', PATH_SEPARATOR, substr($_SERVER['SCRIPT_FILENAME'], 0, strlen($_SERVER['SCRIPT_FILENAME']) - strlen($_SERVER['SCRIPT_NAME']))));```
