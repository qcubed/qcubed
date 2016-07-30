<?php 

$configPath = "project/includes/configuration";

if (!defined ('__PREPEND_INCLUDED__')) {	// not already included some other way (like with .htaccess file)
	if (isset($__CONFIG_ONLY__) && $__CONFIG_ONLY__ == true) {
		require_once($configPath . '/configuration.inc.php');
	} else {
		require_once($configPath . '/prepend.inc.php');
	}
}
