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
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
		<style type="text/css">@import url("<?php _p(__CSS_ASSETS__ . '/' . __JQUERY_CSS__); ?>");</style>
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
	<body id="application">
