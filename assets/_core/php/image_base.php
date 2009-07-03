<?php
	require_once(dirname(__FILE__).'/../../../qcubed.inc.php');
	$strClassName = QApplication::PathInfo(0);
	call_user_func(array($strClassName, 'Run'));
?>