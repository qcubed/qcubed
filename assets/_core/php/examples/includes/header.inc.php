<?php require(__DOCROOT__ . __EXAMPLES__ . '/includes/examples.inc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php _p(QApplication::$EncodingType); ?>" />
		<title><?php _p(Examples::PageName(), false); ?> - QCubed PHP 5 Development Framework - Examples</title>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . '/styles.css'); ?>"></link>
		<script type="text/javascript">
			function ViewSource(intCategoryId, intExampleId, strFilename) {
				var fileNameSection = "";
				if (arguments.length == 3) {
					fileNameSection = "/" + strFilename;
				}
				var objWindow = window.open("<?php echo __VIRTUAL_DIRECTORY__ . __EXAMPLES__ ?>/view_source.php/" + intCategoryId + "/" + intExampleId + fileNameSection, "ViewSource", "menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=750,left=50,top=50");
				objWindow.focus();
			}
		</script>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="headerLeft">
					<?php if(isset($mainPage)) { ?>
					<div id="codeVersion"><span class="headerSmall">QCubed Examples - <?php _p(QCUBED_VERSION); ?></span></div>
					<?php } ?>
					<?php if(!isset($mainPage)) { ?>
					<div id="categoryName"><span class="headerSmall"><?php _p((Examples::GetCategoryId() + 1) . '. ' . Examples::$Categories[Examples::GetCategoryId()]['name'], false); ?></span></div>
					<?php } ?>
					<div id="pageName"><?php _p(Examples::PageName(), false); ?></div>
					
					<div id="pageLinks"><span class="headerSmall">
					<?php if(!isset($mainPage)) { ?>
						<?php _p(Examples::PageLinks(), false); ?>
					<?php } else { ?>
							<strong><a class="headerLink" href="http://qcu.be">QCubed website</a></strong>
					<?php } ?>
					</span></div>
				</div>
				<div id="headerRight">
					<?php if(!isset($mainPage)) { ?>
						<div id="viewSource"><a href="javascript:ViewSource(<?php _p(Examples::GetCategoryId() . ',' . Examples::GetExampleId()); ?>);">View Source</a></div>
		<!--				<a href="#" onclick="window.open('http://localhost/validator/htdocs/check?uri=<?php _p(urlencode('http://qcodo/' . QApplication::$RequestUri)); ?>'); return false;" style="color: #ffffff;">Validate</a>-->
						<div id="willOpen"><span class="headerSmall">will open in a new window</span></div>
					<?php } ?>
				</div>
			</div>
			<div id="content">