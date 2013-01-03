<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="file">
	<h1>Unattended Installation for Plugins</h1>

	<p>When you're developing a plugin, it's often awkward to try to click-click-click
		through the Plugin Manager every time when you want to get the plugin going.
		Wouldn't it be great if you could just drop a plugin .zip file somewhere,
		and have it installed quickly?</p>

	<p>If you're configuring a brand new QCubed install, and you want to set up
		a few favorite plugins of yours, you also may want to save time and install a
		bunch of plugins at once.</p>

	<p>Unattended plugin installer to the rescue! Just place any of the plugin .zip files
		under includes/tmp/plugin.install, and launch the <strong><?= __DEVTOOLS__ ?>/plugin_unattended.php</strong>
		file in your browser. That utility will attempt to install every one of the plugins
		in that plugin.install folder, and give you detailed status on the results.</p>

	<p>Note that if you want to do run this utility remotely, you'll have to configure
		the <strong>AllowRemoteAdmin</strong> settings in configuration.inc.php.</p>
</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>