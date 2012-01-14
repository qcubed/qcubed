<?php
	$strPageTitle = "Update Checker";
	require(__CONFIGURATION__ . '/header.inc.php');
?>
	<?php $this->RenderBegin() ?>
	<div id="page">
		<div id="header">
			<div id="headerLeft">
				<div id="codeVersion">QCubed Development Framework <?= QCUBED_VERSION ?></div>
				<div id="pageName">QCubed Update Checker</div>
			</div>
		</div>
		<div id="content">
			<h2>Available Updates</h2>
			<?php $this->dtgUpdates->Render() ?>
			<div id="lblNoUpdates">No updates - you are up to date!</div>

			<br />
			<h2>New Plugins Available for Download</h2>
			<?php $this->dtgNew->Render() ?>
			<div id="lblNoNew">No new plugins available for download.</div>
		</div>
	</div>
	<?php $this->RenderEnd() ?>

<?php require(__CONFIGURATION__ .'/footer.inc.php'); ?>
