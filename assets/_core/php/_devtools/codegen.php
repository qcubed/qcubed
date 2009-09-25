<?php
	require_once('../qcubed.inc.php');

	// Load in the QCodeGen Class
	require(__QCUBED__ . '/codegen/QCodeGen.class.php');

	// Security check for ALLOW_REMOTE_ADMIN
	// To allow access REGARDLESS of ALLOW_REMOTE_ADMIN, simply remove the line below
	QApplication::CheckRemoteAdmin();

	/////////////////////////////////////////////////////
	// Run CodeGen, using the ./codegen_settings.xml file
	/////////////////////////////////////////////////////
	QCodeGen::Run(__CONFIGURATION__ . '/codegen_settings.xml');

	function DisplayMonospacedText($strText) {
		$strText = QApplication::HtmlEntities($strText);
		$strText = str_replace('	', '    ', $strText);
		$strText = str_replace(' ', '&nbsp;', $strText);
		$strText = str_replace("\r", '', $strText);
		$strText = str_replace("\n", '<br/>', $strText);

		_p($strText, false);
	}

	//////////////////
	// Output the Page
	//////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php _p(QApplication::$EncodingType); ?>" />
		<title>QCubed Development Framework - Code Generator</title>
		<link rel="stylesheet" type="text/css" href="<?php _p(__VIRTUAL_DIRECTORY__ . __CSS_ASSETS__ . '/styles.css'); ?>"></link>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="headerLeft">
					<div id="codeVersion"><span class="headerSmall">QCubed Development Framework <?php _p(QCUBED_VERSION); ?></span></div>
					<div id="pageName">Code Generator</div>
				</div>
				<div id="headerRight">
					<div class="headerLine"><span class="headerSmall"><strong>PHP Version:</strong> <?php _p(PHP_VERSION); ?>;&nbsp;&nbsp;<strong>Zend Engine Version:</strong> <?php _p(zend_version()); ?>;&nbsp;&nbsp;<strong>QCubed Version:</strong> <?php _p(QCUBED_VERSION); ?></span></div>

					<div class="headerLine"><span class="headerSmall"><?php if (array_key_exists('OS', $_SERVER)) printf('<strong>Operating System:</strong> %s;&nbsp;&nbsp;', $_SERVER['OS']); ?><strong>Application:</strong> <?php _p($_SERVER['SERVER_SOFTWARE']); ?>;&nbsp;&nbsp;<strong>Server Name:</strong> <?php _p($_SERVER['SERVER_NAME']); ?></span></div>

					<div class="headerLine"><span class="headerSmall"><strong>Code Generated:</strong> <?php _p(date('l, F j Y, g:i:s A')); ?></span></div>
				</div>
			</div>
		
			<div id="content">
				<?php if ($strErrors = QCodeGen::$RootErrors) { ?>
					<p><strong>The following root errors were reported:</strong></p>
					<div class="code"><?php DisplayMonospacedText($strErrors); ?></div>
				<?php } else { ?>
					<p><strong>CodeGen Settings (as evaluated from <?php _p(QCodeGen::$SettingsFilePath); ?>):</strong></p>
					<div class="code"><?php DisplayMonospacedText(QCodeGen::GetSettingsXml()); ?></div>
				<?php } ?>

				<?php foreach (QCodeGen::$CodeGenArray as $objCodeGen) { ?>
					<p><strong><?php _p($objCodeGen->GetTitle()); ?></strong></p>
					<div class="code"><p class="code_title"><?php _p($objCodeGen->GetReportLabel()); ?></p>
						<?php DisplayMonospacedText($objCodeGen->GenerateAll()); ?>
						<?php if ($strErrors = $objCodeGen->Errors) { ?>
							<p class="code_title">The following errors were reported:</p>
							<?php DisplayMonospacedText($objCodeGen->Errors); ?>
						<?php } ?>
					</div>
				<?php } ?>
				
				<?php foreach (QCodeGen::GenerateAggregate() as $strMessage) { ?>
					<p><strong><?php _p($strMessage); ?></strong></p>
				<?php } ?>
			</div>

			<div id="footer">
				<div id="footerLeft"><a href="http://qcu.be/"><img src="<?php _p(__VIRTUAL_DIRECTORY__ . __EXAMPLES__ . '/images/qcubed_logo_footer.png'); ?>" alt="QCubed - A Rapid Prototyping PHP5 Framework" /></a></div>
				<div id="footerRight">
					<div><span class="footerSmall">For more information, please visit the QCubed website at <a href="http://www.qcu.be/" class="footerLink">http://www.qcu.be/</a></span></div>
					<div><span class="footerSmall">Questions, comments, or issues can be discussed at the <a href="http://qcu.be/forum" class="footerLink">Examples Site Forum</a></span></div>
				</div>
			</div>
		</div>	
	</body>
</html>