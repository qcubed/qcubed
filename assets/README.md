# About `assets` directory

This directory contains web-accessible resources necessary for proper functioning of a QCubed web-app. You should not directly alter contents here. Any changes you make will be overwritten when you upgrade the framework (unless you tell us what you changed and why, using pull requests :relaxed:).
 
The various assets directory are for assets and helpers for the various
 QControls, including images, javascript files and popups.
 
Of course, these files can technically be anywhere in the docroot,
 but the current directory location of /assets/* is meant to serve
 as a centrally-available assets location for these QControl helpers.
 
If you want to move them (either individually or entirely),
 be sure to update your `configuration.inc.php` to reflect the new location(s) of the assets. [**HINT**: Look for definition and usages of `__QCUBED_ASSETS__` in the configuration file]
 
Remember that any additional QControl classes that you create or download (manually, not by installing plugins) which may have their own assets should **NOT** have their assets installed in one of these subdirectories because they will be over-written on framework upgrade.
 
If you need to create your own web-assets (HTML/CSS/JS/Images/Fonts), please use the assets directory in your `project` folder.

