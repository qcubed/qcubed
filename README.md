# Getting started with QCubed

[![Build Status](https://travis-ci.org/qcubed/framework.png?branch=master)](https://travis-ci.org/qcubed/framework)

## Releases
**Newest stable release: [version 2.2.3, released on Nov. 26, 2013](https://github.com/qcubed/framework/archive/2.2.3.zip)**.

Older releases are available from the [downloads archive](https://github.com/qcubed/framework/downloads) and [releases archive](https://github.com/qcubed/framework/releases).

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

The installation procedure is described in a detail here: [Installation instructions](https://github.com/qcubed/framework/blob/master/INSTALL.md "Installation instructions").

* * *

## Latest commits

A list of the latest changes is available at https://github.com/qcubed/framework/commits/master

## Credits

QCubed was born out of QCodo, and uses jQuery UI libraries.

* * *

## Changelog

The full changelog can be examined here: [Changelog](https://github.com/qcubed/framework/blob/master/CHANGELOG.md "Changelog").



[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/qcubed/framework/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

