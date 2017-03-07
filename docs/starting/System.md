#System Requirements and Configuration
Minimum requirements:

* PHP 5.4 or greater. PHP7 and HHVM are both supported and are part of our unit test process.
* A SQL database. MySQL and Postrgres are actively supported, but we also have adapters for Oracle, Ingres, SqlServer and SqlLite.
* A web server capable of running PHP. 

QCubed developers use a variety of platforms to do development, including Windows, Mac and Linux. There are a variety of tools available to set up a development environment. You can install a web server directly on your local machine, or install a virtualization product like VirtualBox to install a completely different operating system to run in.

One popular virtualization product is Docker. These two files can get you started installing an Apache2/PHP7/MySQL5.6 environment, and you can always tweak them as needed for yourself.

* [docker-compose.yml](docker-compose.yml)
* [Dockerfile](Dockerfile.txt)

In addition to the basic services required for QCubed, the Docker files above install some helpful development tools, including:

* [Xdebug](http://xdebug.org)
* [Composer](http://getcomposer.org)