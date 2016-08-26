<?php
require_once('./qcubed.inc.php');

session_cache_limiter('must-revalidate'); 
header("Pragma: hack"); // IE chokes on "no cache", so set to something, anything, else.
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time()) . " GMT";
header($ExpStr);

QImageControl::Run();
