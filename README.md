# Getting started with QCubed

[![Build Status](https://travis-ci.org/qcubed/framework.png?branch=master)](https://travis-ci.org/qcubed/framework)

## Releases
**Newest stable release: [version 2.2.2, released on May 28, 2013](https://github.com/qcubed/framework/archive/2.2.1.zip)**.

**alpha-3.0 is available via composer and github**

Older releases are available from the [downloads archive](https://github.com/qcubed/framework/downloads).

## What is QCubed?

QCubed (pronounced 'Q' - cubed) is a PHP5 Model-View-Controller framework. The goal of the framework is to save development time around mundane, repetitive tasks - allowing you to concentrate on things that are useful AND fun. QCubed excels in situations where you have a large database structure that you quickly want to make available to users.

## Stateful architecture

With QCubed, you don't have to deal with POSTs and GETs coming from the browser. QCubed automatically handles that for you and packages the information into object oriented forms and controls. Programming with QCubed feels very much like programming a desktop application. If you are familiar with ASP, it is similar.

## The Code Generator

The Code Generator automatically creates object classes with matching forms and controls based on your database schema. It uses the concept of ORM, [object-relational mapping](http://en.wikipedia.org/wiki/Object-relational_mapping), to practically create your whole model layer for you.

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

The easiest way to learn QCubed is to see the examples tutorial at <http://examples.qcu.be/>

### Plugins

Through its plugin system, QCubed makes it easy to package and deliver enhancements and additions to the core codebase. The plugin project is located at <https://github.com/qcubed/plugins> and contains an exhaustive list of contributed plugins.

## Learn more
Interested? Check out [QCubed video screencasts](http://qcu.be/content/video-screencasts) or [text-based QCubed tutorials](http://trac.qcu.be/projects/qcubed/wiki/Tutorials).

The [github wiki](https://github.com/qcubed/framework/wiki) will eventually supersede these.

* * *

## System Requirements
* A development computer that you can set up so that the browser can write to a directory in your file system.
* As of v3.0.0 alpha, PHP 5.0 and above are supported. However, v3.0 eventually will require PHP 5.3 or above. To do a composer install, you will need PHP 5.3.2.
* As of v3.0.0 alpha, QCubed will generally produce XHTML 1.0 compliant code. However, v3.0 will eventually produce XHTML5 compliant code only.
* A SQL database engine. MySQL, SqlServer, Postgres, Oracle, PDO, SqlLite, Informix adapters are included. Creating another adapter is not hard if you have a different SQL.

## Installation

The installation procedure is described in a detail here: [Installation instructions](https://github.com/qcubed/framework/blob/alpha-3.0/INSTALL.md "Installation instructions").

* * *

## Latest commits

A list of the latest changes is available at https://github.com/qcubed/framework/commits/master

## Credits

QCubed was born out of QCodo, and uses jQuery UI libraries.

* * *

## Changelog

The full changelog can be examined here: [Changelog](https://github.com/qcubed/framework/blob/master/CHANGELOG.md "Changelog").

