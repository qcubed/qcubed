# Welcome to QCubed!

[![Build Status](https://travis-ci.org/qcubed/framework.png?branch=f-travis)](https://travis-ci.org/jamescmunro/framework)

## Releases
**Newest stable release: [version 2.1.1, released on Dec 1, 2012](https://github.com/qcubed/framework/archive/2.1.1.zip)**. See the [ChangeLog](#Changes) for what's new in this release.

Older releases are available from the [downloads archive](https://github.com/qcubed/framework/downloads). 

## What is QCubed?

QCubed (pronounced 'Q' - cubed) is a PHP5 Model-View-Controller framework. The goal of the framework is to save the time for developers around mundane, repeatable tasks - allowing them to concentrate on things that are useful AND fun.

How many times have you written that SQL query, and then parsed out the results? How about that time when you had to create a form with validation logic? How about a situation where you had to move your database back-end from MySQL to PostgreSQL or another database?

All of these situations, and many more, can be simplified with QCubed. There are two key elements to the framework: the Code Generator, and the event-driven, stateful user interface framework (QForms). 

### The Code Generator
The Code Generator creates PHP classes based on your database schema. It uses the concept of ORM, [object-relational mapping](http://en.wikipedia.org/wiki/Object-relational_mapping), to map your DB tables to PHP classes, to allow you to manipulate objects, instead of constantly issuing SQL queries. One-to-many relationship? No problem. Association tables? No problem. Ease of transitioning between RDBMS systems? That's the whole point. Object-oriented querying? We got it.

### User Interface Library
QForms provide a framework for a true model-view-controller infrastructure in your application. Using standard HTML, create a layout of your page (view). Insert a few controls into that HTML to make it a template that will display the form data. Define those controls and their logic in a PHP class that derives from QForm (controller). Use the code-generated ORM classes to read and write from the database (model).

Customize and extend any component of the system: override properties of a QForm; create your own custom control; use a combination of controls to define a reusable QPanel that can be used as a building block across multiple pages. Abstract out the complex database logic into customizable ORM classes. 

Interested? Check out [QCubed video screencasts](http://qcu.be/content/video-screencasts) or [text-based QCubed tutorials](http://trac.qcu.be/projects/qcubed/wiki/Tutorials). 

## Changes
### Release 2.1.1
[845](http://trac.qcu.be/projects/qcubed/ticket/845) QDialog rendering events problems when modified

[861](http://trac.qcu.be/projects/qcubed/ticket/861) AJAX is too async in qcubed

[813](http://trac.qcu.be/projects/qcubed/ticket/813) Immediate patch for release 2.1

[816](http://trac.qcu.be/projects/qcubed/ticket/816) QDialog re-parenting causes problems for stacked dialogs

[818](http://trac.qcu.be/projects/qcubed/ticket/818) HTMLPurifier supported XSS protection not working on 2.1

[835](http://trac.qcu.be/projects/qcubed/ticket/835) Utilizing comments on table columns (in database definitions) for meta control {Create()} methods

[838](http://trac.qcu.be/projects/qcubed/ticket/838) QDbBackedSessionHandler functionality was broken in 2.1 release, fixed again.

[839](http://trac.qcu.be/projects/qcubed/ticket/839) codegen template overrides not working

[840](http://trac.qcu.be/projects/qcubed/ticket/840) Incorrect draft filenames

[850](http://trac.qcu.be/projects/qcubed/ticket/850) FormStates Garbage Collection made public

[853](http://trac.qcu.be/projects/qcubed/ticket/853) QAutocomplete remote JSON DataSource Problem after unsuccessful validation

[870](http://trac.qcu.be/projects/qcubed/ticket/870) Applying CheckRemoteAdmin for the start page

[821](http://trac.qcu.be/projects/qcubed/ticket/821) fix command line codegen

[823](http://trac.qcu.be/projects/qcubed/ticket/823) QDraggable not recording results correctly

[824](http://trac.qcu.be/projects/qcubed/ticket/824) Wrapper-less controls: change in QControl.class.php got lost in 2.1

[825](http://trac.qcu.be/projects/qcubed/ticket/825) QDraggable Handle error

[826](http://trac.qcu.be/projects/qcubed/ticket/826) QDialog not receiving events

[827](http://trac.qcu.be/projects/qcubed/ticket/827) Error when using QDialog with no properties

[828](http://trac.qcu.be/projects/qcubed/ticket/828) QDateTime returns with TimeNull = true when passed a date in IsoCompressed (20120402082830)

[832](http://trac.qcu.be/projects/qcubed/ticket/832) QResizable not accurate in its dimensions, and also should update object width and height

[842](http://trac.qcu.be/projects/qcubed/ticket/842) problem in collabsable QAccordion with no active panels

[844](http://trac.qcu.be/projects/qcubed/ticket/844) handle IP ranges in remote admin check

[846](http://trac.qcu.be/projects/qcubed/ticket/846) Over-optimization in codegen for cache key creation

[847](http://trac.qcu.be/projects/qcubed/ticket/847) "QDatepicker does not supports ""-1d"" or ""+1m"" syntax for minDate/maxDate"

[849](http://trac.qcu.be/projects/qcubed/ticket/849) QDataGrid top (header) row height fixed

[851](http://trac.qcu.be/projects/qcubed/ticket/851) Upgrading HTMLPurifier from 4.3.0 to 4.4.0

[855](http://trac.qcu.be/projects/qcubed/ticket/855) The codegen fails to handle tables with only one id column

[856](http://trac.qcu.be/projects/qcubed/ticket/856) New methods in codegened classes to extract info about the class itself.

[857](http://trac.qcu.be/projects/qcubed/ticket/857) Config Checker failing when includes directory is out of the web directory

[858](http://trac.qcu.be/projects/qcubed/ticket/858) Generate PHPDoc Comments on QPanel variables

[859](http://trac.qcu.be/projects/qcubed/ticket/859) QControl.class.php and QcontrolBase.class.php is problematic...

[871](http://trac.qcu.be/projects/qcubed/ticket/871) PHPDocs added

[811](http://trac.qcu.be/projects/qcubed/ticket/811) Autoloading Custom files, making use of the existing directories

[814](http://trac.qcu.be/projects/qcubed/ticket/814) Defining the External Libraries directory in configuration.inc.php

[817](http://trac.qcu.be/projects/qcubed/ticket/817) QDbBackedFormStateHandler additional comments for MySQL

[830](http://trac.qcu.be/projects/qcubed/ticket/830) Adding Licensing info for HTMLPurifier Library

[833](http://trac.qcu.be/projects/qcubed/ticket/833) QDraggable cannot take jQuery selector as Handle

[837](http://trac.qcu.be/projects/qcubed/ticket/837) Delete QAutocompleteListItem and move support to QListItem

[854](http://trac.qcu.be/projects/qcubed/ticket/854) Updating Simple HTML Parser in the jquery_ui_gen directory

[863](http://trac.qcu.be/projects/qcubed/ticket/863) Blank Attributes in QLabels

[864](http://trac.qcu.be/projects/qcubed/ticket/864) Improvement for QFolder class

[868](http://trac.qcu.be/projects/qcubed/ticket/868) QDbBackedSessionHandler can't use database where qc_session is first table in database

[819](http://trac.qcu.be/projects/qcubed/ticket/819) Missing PHPDOC comment on method QApplication::QueryString
