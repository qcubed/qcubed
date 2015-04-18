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
		<link href="<?= __VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ ?>/styles.css" rel="stylesheet">
		<?php if (isset($this)) $this->RenderStyles(); ?>
	</head>
	<body>
		<section id="content">