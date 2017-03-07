#System Requirements and Configuration
##Minimum requirements

* PHP 5.4 or greater. PHP7 and HHVM are both supported and are part of our unit test process.
* A SQL database. MySQL and Postrgres are actively supported, but we also have adapters for Oracle, Ingres, SqlServer and SqlLite.
* A web server capable of running PHP. 

##Dependencies
As of v3.0, QCubed depends on JQuery, and in its core it makes use of JQuery UI widgets and capabilities. We hope to remove that dependency in the future.

QCubed outputs HTML5 code and favors UTF-8 encoding, but can support any encoding. 

QCubed favors doing all styling in CSS style sheets, but also supports programmatically outputting inline styles for dynamic effects. Our CSS is currently very small, and is pure CSS at this point. Feel free to use LESS or SASS to develop your CSS, and override the small amount of CSS we provide.

There is a [Bootstrap](http://getbootstrap.com) plugin to support responsive design techniques.

##Configuring an OS
QCubed developers use a variety of platforms to do development, including Windows, Mac and Linux. There are a variety of tools available to set up a development environment. You can install a web server directly on your local machine, or install a virtualization product like VirtualBox to install a completely different operating system than your computer's operating system.

One popular virtualization product is [Docker](http://www.docker.com). The two files below can get you started installing an Apache2/PHP7/MySQL5.6 environment once you have installed Docker, and you can always tweak them as needed for yourself.

* [docker-compose.yml](docker-compose.yml)
* [Dockerfile](Dockerfile.txt)

In addition to the basic services required for QCubed, the Docker files above install some helpful development tools, including:

* [Xdebug](http://xdebug.org)
* [Composer](http://getcomposer.org)