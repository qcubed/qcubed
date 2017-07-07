# Features
## MVC Architecture

QCubed is an [MVC (Model View Controller)](http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) style PHP Development Framework. QCubed accomplishes this through some unique concepts, as described below.

## Database (Model)

* Support for multiple databases - QCubed supports MySQL, PostgreSQL, Oracle, Microsoft SQL Server and more! You may also use multiple databases in a single QCubed-based application.

* [[Code Generation|Codegen]] - QCubed can generate ORM classes for all of your databases (and tables).

* [[ORM Classes|ORM]] - QCubed turns your database structure into an ORM model in PHP helping you focus on things that matter more while keeping the redundant jobs easy.

* ORM Relationships - QCubed's ORM classes take into account the foreign keys and reverse relationships to make it extremely easy to work with databases and avoid errors in code.

* Query Caching - QCubed supports caching query results to reduce database load for very expensive queries.

* Object Caching - QCubed currently supports caching of single ORM objects using memcached for faster loads on single objects.

* Flexible Querying - With QCubed, you can do most of the SQL job using the Code-Generated classes, but writing your SQL is not prohibited by any means!

* [[Automatic Form Creation|Form Drafts]] - When QCubed runs the 'Code Generation', it also creates 'drafts' which are a starting point for forms which can be used to input data straightaway. No need to write code for CRUD operations in your webapp, simply extend the drafts to suit your business logic.

* Dedicated Control Creation - You get a dedicated control for every single column of every single table of every database you added in your configuration file and ran codegen on! We call them Meta Controls. They are what make up the Drafts, actually!

### Presentation (View)

* [[QForm]] - QForms are used to create a new webpage/webapp using QCubed. They encompass both the view and controller parts of the MVC model.

* [[HTML Templates|Templating]] - QCubed helps you keep the HTML code separate from the PHP code and saves you from confusion that the combination can create. With QForms, you write a class with PHP code determining what to do and leave the presentation on the template file.

* Full support for jQueryUI - QCubed comes along with the latest version of jQueryUI available at the release time. The best thing is - you can manage your jQueryUI widgets using PHP code since every jQueryUI widget has been made into a QControl.

* Elements are individual Controls - All UI elements such as buttons, textboxes, radio buttons, checkboxes etc are treated as QControls and you can control the behavior and looks any way you want. You might even [[create your own control|Building a custom control]] by combining them and optionally putting them in a QPanel.

* DataGrids - QCubed has a special control called QDataGrid which takes displaying and filtering data in tabular format to a whole new level!

#### Actions and Events (Controller)

* Event Driven Programming - QCubed is fully event driven. You can define each control (like buttons, textboxes, checkboxes etc) with its own set of events and actions.

* Actions - QCubed supports single as well as multiple actions on every control. Actions are taken when an event is fired. Some frequently used actions are QServerAction (does the job by reloading a page), QAjaxAction (does the job using Ajax), and QJavascriptAction (executes client side javascript).

* Events - QForms, the main presentation class of QCubed supports events (e.g. Escape Key press, Enter key press, click, hover, mouse over, focus, blur and more). You can define an Event-Action pair on any QControl of your wish!

* Full support for jQuery - QCubed comes with latest version of jQuery at the release time. So you can write jQuery code in your javascript files seamlessly.

### Freedom to the developer

* Code Customization - QCubed is meant to be built upon and modified. For instance, you could choose to go with a [[front-controller pattern|Front Controller Pattern]].

* Extensible - QCubed is fully based on OOP. You are free to derive and extend every single class and customize it your needs, and as long as you place the code in a separate folder than the rest of the QCubed core code, they will not be overwritten on upgrades! This is the recommended way to stretch your arms.

* Plugins - Think you made something good which can be shared with the world? Well, you can create plugins and help those who are in need without having to modify the core! It also helps you package a large web app based on QCubed into smaller modules and make them 'installable' really easily! Impressive, isn't it? Plugins are available to download from https://github.com/qcubed/plugins

* License - QCubed is released under MIT license. So forget your worries about using it in proprietary projects, we are not coming after you with a lawyer. :)

### Security

* XSS Protection - QCubed now comes with [HTMLPurifier](http://htmlpurifier.org/), one of the best HTML filtering libraries available to prevent Cross Site Scripting attacks. Just add a line to your code. This feature can be enabled, disabled and customized as you wish.

* [[Protection from SQL injection|SQL Injection]] - Your site is protected from SQL injection attacks by default (as long as you stick with QQueries; for custom queries, you must take care of input). Work is underway to support parameterized queries.

* Moving Server-side includes - QCubed allows you to move your includes directory to anywhere on the filesystem hence protecting your most valuable assets.

* Protection for important scripts - You can control the CLI runtime access to sensitive parts of your application based on IP address ranges.

### Scalability

* FormState handling on databases - QCubed saves the 'state' of every page your users view. QCubed allows you to handle them (through periodic garbage collection and storage) in a database, thus enabling you to deploy two or more web servers serving the same application if the load is high.

* Session handling on databases - PHP stores user sessions on local disk. This does not work well when you have more than one web server serving the same site. QCubed allows you to handle all the sessions on a central database of your choice which you are already using in the application. It's as simple as defining a table and enabling it in the QCubed configuration.

### Coding Experience and Support

* Low Learning Curve - Code written in QCubed is easy to write, read and understand. Also, the way everything is structured takes full advantage of code completion features in all the popular IDEs (such as Eclipse, Netbeans, KDevelop, PHPStorm, Visual Studio, etc).

* [Community Support ](https://github.com/organizations/qcubed/teams/235645)- The QCubed community is there to help you with problems, anytime!

* [Documentation](http://api.qcu.be/) - The framework API documentation is available online and can be generated locally, and is constantly improving. Even generated code functions come complete with PHPDoc comment blocks.
