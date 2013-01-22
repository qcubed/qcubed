<?php 

$configPath = "includes/configuration";

if (isset($__CONFIG_ONLY__) && $__CONFIG_ONLY__ == true) {
	require_once($configPath . '/configuration.inc.php');
} else {
	require_once($configPath . '/prepend.inc.php');
}
?>