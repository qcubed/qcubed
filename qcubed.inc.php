<?php 

$configPath = "includes/configuration";

if (!defined ('__PREPEND_INCLUDED__')) {	// not already included some other way (like with .htaccess file)
	// expect this file to be in the vendor/qcubed/framework directory
	include(dirname(__FILE__).'/../../../qcubed.inc.php');
}
