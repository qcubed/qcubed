<?php
	// This example header.inc.php is intended to be modfied for your application.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php _p(QApplication::$EncodingType); ?>" />
<?php if (isset($strPageTitle)) { ?>
		<title><?php _p($strPageTitle); ?></title>
<?php } ?>
		<style type="text/css">@import url("<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__); ?>/styles.css");</style>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="headerLeft">
					<div id="codeVersion"><span class="headerSmall">QCubed Development Framework <?php _p(QCUBED_VERSION) ?></span></div>
					<div id="pageName"><?php if (isset($strPageTitle)) { _p($strPageTitle); } ?></div>
				</div>
				<div id="headerRight">
				</div>
			</div>
			<div id="content">