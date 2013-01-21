# Getting started with QCubed

[![Build Status](https://travis-ci.org/qcubed/framework.png?branch=f-travis)](https://travis-ci.org/jamescmunro/framework)

## Releases
**Newest stable release: [version 2.1.1, released on Dec 1, 2012](https://github.com/qcubed/framework/archive/2.1.1.zip)**.

Older releases are available from the [downloads archive](https://github.com/qcubed/framework/downloads).

## What is QCubed?

QCubed (pronounced 'Q' - cubed) is a PHP5 Model-View-Controller framework. The goal of the framework is to save development time around mundane, repetitive tasks - allowing you to concentrate on things that are useful AND fun.

## The Code Generator

The Code Generator creates PHP classes based on your database schema. It uses the concept of ORM, [object-relational mapping](http://en.wikipedia.org/wiki/Object-relational_mapping), to practically create your whole model layer for you.
Codegen can take advantage of foreign key relationships and field constraints to generate ready-to-use data models complete with validation routines and powerful CRUD methods, allowing you to manipulate objects instead of constantly issuing SQL queries.

More info as well as examples are available online at <http://examples.qcu.be/>

### Object-oriented querying

Using QQueries allows for simple yet powerful loading of models, all generated ORM classes have Query methods and QQNodes. By using these methods, getting a complex subset of data is pretty straightforward - and can be used on almost any relational database.

## User Interface Library

QCubed uses the concept of a QForm to keep form state between POST transactions. A QForm serves as the controller and can contain QControls which are UI components.

All QControls (including QForm itself) can use a template which is the view layer, completing the MVC structure.

QControls can take advantage of the QForm's FormState to update themselves through Ajax callbacks as easily as synchronous server POSTs. All jQuery UI core widgets are available as QControls.

Some QControls include:
- QDialog
- QTextBox
- QListBox
- QTabs
- QAccordion

A full list and examples are available online at <http://examples.qcu.be/>

### Plugins

Through its plugin system, QCubed makes it easy to package and deliver enhancements and additions to the core codebase. The plugin project is located at <https://github.com/qcubed/plugins> and contains an exhaustive list of contributed plugins.

## Learn more
Interested? Check out [QCubed video screencasts](http://qcu.be/content/video-screencasts) or [text-based QCubed tutorials](http://trac.qcu.be/projects/qcubed/wiki/Tutorials).

The [github wiki](https://github.com/qcubed/framework/wiki) will eventually supersede these.

* * *

## Installation

### File System

Copy the contents of wwwroot/* to the ROOT level of your web site's DOCROOT
(also known as DocumentRoot, webroot, wwwroot, etc., depending on which platform
you are using).

At a later point, you may choose to move folders around in your system,
putting them in subdirectories, etc.  QCubed offers the flexibility to have
these framework files in any location.

But for now, since we're getting started, we'll provide you with the instructions
on how to finish the installation assuming that you're keeping the entire
QCubed installation together as originally released.


### Modify Configuration

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

<<<<<<< HEAD
[819](http://trac.qcu.be/projects/qcubed/ticket/819) Missing PHPDOC comment on method QApplication::QueryString

# jQCubed

jQCubed is derived from the [QCubed](http:qcu.be) framework with the goal of providing better scaffolding for usability, customizability and look-and-feel.

Here are the features a jQCubed auto-generated application adds on top of a regular QCubed application:

*   _Nested forms:_ One should be able to insert/edit any entity and it's relationships from one screen without jumping through tons of pages.
*   _Better Object Selectors:_ when selecting objects for relationships, search as you type must be available in object selectors.
*   _Generic searches:_ we should provide generic searches that work out of box for all the entities.
*   _Easily customizable searches_
*   _Better data grids with better filters:_ we should auto-generate better data grids with search-as-you-type filters, multi-column sorting, row clicking, etc.
* _Use [jQuery UI theme](http://jqueryui.com/themeroller/) styles_ everywhere that's reasonable, allowing users to plugin other themes.
* _Use [less](http://lesscss.org/ "less css") instead of css_.

To make the aut-generated application easy to customize, jQCubed is guided by the principle that it's easier to remove/disable things than to add them. For searches, data grids, or forms, we should auto-generate controls where all the columns are already setup. It's much easier to customize them by removing the ones that are not needed, than manually adding them.

It turns out, thanks to QCubed's powerfull code generator, we can accomplish all this (and more) with a few plugins and with QCubed's code generator.
Here are some new classes I created that are central for the implementation of all the features above

* ```QCallback``` - this class tries to provide a consistent interface to the many ways PHP (and Qcubed) does callbacks, closures, user function calls, etc.
* ```QGenericSearchOptions```, ```SearchPanel``` - provide the generic search capabilities mentioned above

The following new classes are auto-generated in jQCubed to provide all the functionality described above

* ```[Model]ObjectSelector``` - provide the object selection feature
* ```[Model]DataTable``` - provide the new data grids
* ```[Model]SearchPanel``` - provide the generic search feature
* ```[Model]Popup``` - factory classes for various popup dialogs for creating, editing, searching etc.
* ```[Model]Toolbar``` - a container for various controls and buttons to load, create, edit, search, delete, etc.
* ```[Model]ViewPanel``` - panel for showing the entity
* ```[Model]ViewWithRelationships``` - view panel also showing the relationships of the entity
* ```[Model]ViewWithToolbar``` - view panel combined with the toolbar control above
* ```[Model]UpdatePanel``` - panel for updating the entity
* ```[Model]ListDetailView``` - provide the classic List-Detail, where the list panel contains a search and a data table, and the Detail panel contains a view panel with the toolbar (and relationships).

In addition all the controls are generated to use jQuery UI theme classes. The default theme included in jQCubed is the sunny theme.

### QCubed Plugins required by jQCubed
The following plugins are included as part of jQCubed

* [QDataTables](https://github.com/qcubed/plugins/tree/master/QDataTables)
* [QSelect2ListBox](https://github.com/qcubed/plugins/tree/master/QSelect2ListBox)
* [QJqDateTimePicker](https://github.com/qcubed/plugins/tree/master/QJqDateTimePicker)
* [QDateRangePicker](https://github.com/qcubed/plugins/tree/master/QDateRangePicker)

## Installation
Since jQCubed is derived from QCubed, the installation procedure is exactly the same as for QCubed.
After downloading the jQCubed package, please follow the installation instructions for QCubed.

## Customizing your jQCubed application
Even though jQCubed tries hard to auto-generate a good looking and usable application out of the box, you will almost certainly need to customize it to your needs. Fortunately jQCubed makes the process very easy.
The principle is the same as it always was in QCubed: customize the auto-generated subclasses and templates.
Below are more details for the most commonly needed customizations

* UI and layout:
 1. In the auto-generated subclass of the corresponding panel class (e.g. PersonViewPanel), create a constructor and point the view template to your own:
```$this->strTemplate = __APP_INCLUDES__ . '/templates/MyPersonView.tpl.php'```
 1. copy the auto-generated template into ```app_includes/templates/```
 1. modify the new template as desired

* DataTables:

   In the auto-generated subclass (e.g. PersonDataTable), create a constructor and remove or add new columns, or change sorting preferences
  * To remove columns use any of the "RemoveColumn" methods in QSimpleTable
  * To reposition columns use any of the "MoveColumn" method in QSimpleTable
  * To add new columns use any of the "AddColumn" or "CreateColumn" methods in QSimpleTable. Note that this allows adding columns that are not necessarily database fields, but could be any calculated expressions. See the QSimpleTable examples for more details.
  * To rename a column use QSimpleTable::RenameColumn
  * To post process the value that appears in the data table cells set the PostCallback property of the column object. For example:
```
$this->GetColumnByName("Street")->PostCallback = 'ReformatAddress';
```
  * To hide columns, use the ColumnDefs property of QDataTable
  * To pre-sort the table, use the Sorting property of QDataTable.
* Searches
 * To modify the search behaviour of the data tables' built-in search, set the ```$objSearchOptions``` member variable of the data table class to a new ```QGenericSearchOptions``` and set its properties as desired. For example, in the PersonDataTable constructor:
```
$this->objSearchOptions = new QGenericSearchOptions();
$this->objSearchOptions->ExcludeProperties = array("Id");
$this->objSearchOptions->StringComparisonMode = array("FirstName", QStringComparisonMode::startsWith);
```
 * Similarly, to modify the search behaviour of the ObjectSelector's, create a constructor in the auto-generated subclass (e.g. PersonObjectSelector), and set its ```$objSearchOptions``` member variable with appropriate options.

### Note about the "jQCubed" name
Using a different name for this project is in no way an indication of any intentation to fork the QCubed project or its user base. In fact, I have made every effort (and succeeded) to not modify any QCubed core files. Thus you can get jQCubed by just adding to the stock QCubed package.

The name "jQCubed" is an homage to both jQuery and QCubed. If and when this projects merges back into QCubed core, this name will disappear and no longer be used.

## Screenshoots

Here is how the standard QCubed dashboard looks with jQCubed (and the sunny jQuery theme):

![jQCubed dashboard](http://i.troll.ws/f24eafce.png)
