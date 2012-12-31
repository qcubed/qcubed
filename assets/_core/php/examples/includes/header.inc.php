<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/examples.inc.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php _p(QApplication::$EncodingType); ?>" />
		<title><?php _p(Examples::PageName(), false); ?> - QCubed PHP 5 Development Framework - Examples</title>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . '/styles.css'); ?>"></link>
	</head>
	<body>
		<header>
<?php	if(!isset($mainPage)) { ?>
			<span class="category-name"><?php _p((Examples::GetCategoryId() + 1) . '. ' . Examples::$Categories[Examples::GetCategoryId()]['name'], false); ?></span> / 
<?php	} ?>
			<span class="page-name"><?php _p(Examples::PageName(), false); ?></span>

<?php	if(!isset($mainPage)) { ?>
			<span class="page-links"><?php _p(Examples::PageLinks(), false); ?></span>
			<button id="viewSource" onclick="javascript:ViewSource(<?php _p(Examples::GetCategoryId() . ',' . Examples::GetExampleId()); ?>);">View Source</button>
<?php	} ?>
			
		</header>
		<section id="content">