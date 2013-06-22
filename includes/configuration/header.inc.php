<?php
	// This example header.inc.php is intended to be modfied for your application.
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php _p(QApplication::$EncodingType); ?>" />
<?php if (isset($strPageTitle)) { ?>
		<title><?php _p($strPageTitle); ?></title>
<?php } ?>
		<style type="text/css">@import url("<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__); ?>/styles.css");</style>
<?php
		require 'lessc.inc.php';

		try {
			lessc::ccompile(__DOCROOT__ . __APP_CSS_ASSETS__ .'/styles.less', __DOCROOT__ .__APP_CSS_ASSETS__ .'/styles.css');
		} catch (exception $ex) {
			exit($ex->getMessage());
		}
?>
		<style type="text/css">@import url("<?php _p(__VIRTUAL_DIRECTORY__ .__APP_CSS_ASSETS__ ); ?>/styles.css");</style>
		<script src="<?php echo __APP_JS_ASSETS__ .'/application.js'; ?>" type="text/javascript"></script>
	</head>
	<body>
		<section id="content">
