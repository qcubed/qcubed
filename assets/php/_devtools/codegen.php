<?php
	require_once('../qcubed.inc.php');

	// Load in the QCodeGen Class
	require(__QCUBED__ . '/codegen/QCodeGen.class.php');
	// code generators
	include (__QCUBED_CORE__ . '/codegen/controls/_class_paths.inc.php');


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
	
	$strPageTitle = "QCubed Development Framework - Code Generator";
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<h1>Code Generator</h1>
	<div class="headerLine"><span><strong>PHP Version:</strong> <?php _p(PHP_VERSION); ?>;&nbsp;&nbsp;<strong>Zend Engine Version:</strong> <?php _p(zend_version()); ?>;&nbsp;&nbsp;<strong>QCubed Version:</strong> <?php _p(QCUBED_VERSION); ?></span></div>

	<div class="headerLine"><span><?php if (array_key_exists('OS', $_SERVER)) printf('<strong>Operating System:</strong> %s;&nbsp;&nbsp;', $_SERVER['OS']); ?><strong>Application:</strong> <?php _p($_SERVER['SERVER_SOFTWARE']); ?>;&nbsp;&nbsp;<strong>Server Name:</strong> <?php _p($_SERVER['SERVER_NAME']); ?></span></div>

	<div class="headerLine"><span><strong>Code Generated:</strong> <?php _p(date('l, F j Y, g:i:s A')); ?></span></div>

<?php if (QCodeGen::$TemplatePaths) { ?>
	<div>
		<p><strong>Template Paths</strong></p>
		<pre><code><?php DisplayMonospacedText(implode("\r\n", QCodeGen::$TemplatePaths)); ?></code></pre>
	</div>
<?php } ?>

	<div>
		<?php if ($strErrors = QCodeGen::$RootErrors) { ?>
			<p><strong>The following root errors were reported:</strong></p>
			<pre><code><?php DisplayMonospacedText($strErrors); ?></code></pre>
		<?php } else { ?>
			<p><strong>CodeGen Settings (as evaluated from <?php _p(QCodeGen::$SettingsFilePath); ?>):</strong></p>
			<pre><code><?php DisplayMonospacedText(QCodeGen::GetSettingsXml()); ?></code></pre>
		<?php } ?>

		<?php foreach (QCodeGen::$CodeGenArray as $objCodeGen) { ?>
			<p><strong><?php _p($objCodeGen->GetTitle()); ?></strong></p>
			<pre><code><p class="code_title"><?php _p($objCodeGen->GetReportLabel()); ?></p><?php
					if (QCodeGen::DebugMode) {
						DisplayMonospacedText($objCodeGen->GenerateAll());
					} else {
						@DisplayMonospacedText($objCodeGen->GenerateAll());
					}
?>
<?php if ($strErrors = $objCodeGen->Errors) { ?>
					<p class="code_title">The following errors were reported:</p>
<?php DisplayMonospacedText($objCodeGen->Errors); ?>
<?php } ?>
<?php if ($strWarnings = $objCodeGen->Warnings) { ?>
					<p class="code_title">The following warnings were reported:</p>
<?php DisplayMonospacedText($objCodeGen->Warnings); ?>
<?php } ?></code></pre>
		<?php } ?>

		<?php
			if (!$strErrors) {
				foreach (QCodeGen::GenerateAggregate() as $strMessage) { ?>
					<p><strong><?php _p($strMessage); ?></strong></p>
				<?php }
			} ?>
	</div>

<?php require(__CONFIGURATION__ . '/footer.inc.php'); ?>