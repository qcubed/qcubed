# Welcome to QCubed!

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
