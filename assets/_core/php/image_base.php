<?php
	require('../../../includes/configuration/prepend.inc.php');
	$strClassName = QApplication::PathInfo(0);
	call_user_func(array($strClassName, 'Run'));
?>