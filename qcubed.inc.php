<?php 

$includesPath = "includes/configuration";

if (isset($__CONFIG_ONLY__) && $__CONFIG_ONLY__ == true) {
	require_once($includesPath . '/configuration.inc.php');
} else {
	require_once($includesPath . '/prepend.inc.php');
}
?>