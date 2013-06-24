<?php
	require_once(dirname(__FILE__).'/../../../../qcubed.inc.php');
	require('includes/examples.inc.php');

	$intCategoryId = QApplication::PathInfo(0);
	$intExampleId = QApplication::PathInfo(1);
	$strScript = QApplication::PathInfo(2);

	$strReference = Examples::GetExampleScriptPath($intCategoryId, $intExampleId);
	$strName = Examples::GetExampleName($intCategoryId, $intExampleId);
	if (!$strScript)
		QApplication::Redirect(QApplication::$RequestUri . substr($strReference, strrpos($strReference, '/')));
?>
<html>
	<head>
		<meta charset="<?php _p(QApplication::$EncodingType); ?>" />
		<title>QCubed PHP 5 Development Framework - View Source</title>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . '/styles.css'); ?>"></link>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __EXAMPLES__ . '/includes/examples.css'); ?>"></link>
	</head>
	<body>
		<div id="closeWindow"><a href="javascript:window.close()" class="close-window">Close this Window</a></div>
		<header><nav class="page-links"><span class="headerSmall"><?php _p(Examples::CodeLinks($strReference, $strScript), false); ?></nav></header>
		<section id="content">
<?php
	// Filename Cleanup
	if (($strScript == 'header.inc.php') || ($strScript == 'footer.inc.php') || ($strScript == 'examples.css'))
		$strFilename = 'includes/' . $strScript;
	else if (($strScript == 'mysql_innodb.sql') || ($strScript == 'sql_server.sql')) {
		$strFilename = $strScript;
	} else if (substr($strScript, 0, 16) == '__CORE_CONTROL__') {
		$strFilename = __QCUBED__ . '/controls/' . str_replace('__CORE_CONTROL__', '', str_replace('/', '', $strScript));
	} else if (substr($strScript, 0, 18) == '__CORE_FRAMEWORK__') {
		$strFilename = __QCUBED_CORE__ . '/framework/' . str_replace('__CORE_FRAMEWORK__', '', str_replace('/', '', $strScript));
	} else {		
		$strFilename = substr($strReference, 1);
		$strFilename = __DOCROOT__ . '/' . substr($strFilename, strlen(__VIRTUAL_DIRECTORY__), strrpos($strReference, '/') - strlen(__VIRTUAL_DIRECTORY__)) . '/' . $strScript;
	}

	if (!file_exists($strFilename)) {
		throw new Exception("Example file does not exist: " . $strFilename);
	}
?>
			<h1>Source of: <?php _p(preg_replace('/__.*__/', '', $strScript)); ?></h1>
			<pre><?php highlight_file($strFilename); ?></pre>
		</section>
	</body>
</html>