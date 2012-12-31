<?php
	$strPageTitle = "Update Checker";
	require(__CONFIGURATION__ . '/header.inc.php');
?>
<h1>Available Updates</h1>
<?php $this->RenderBegin() ?>

<?php $this->dtgUpdates->Render() ?>
<p id="lblNoUpdates">No updates - you are up to date!</p>

<h2>New Plugins Available for Download</h2>
<?php $this->dtgNew->Render() ?>
<div id="lblNoNew">No new plugins available for download.</div>

<?php $this->RenderEnd() ?>

<?php require(__CONFIGURATION__ .'/footer.inc.php'); ?>