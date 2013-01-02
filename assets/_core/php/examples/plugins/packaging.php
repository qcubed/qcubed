<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" style="max-height: none">
	<h1>Make Your Own Plugin, Part 2: Packaging and Distributing</h1>

	<p>Now that you <a href="components.php">know</a> how to create a plugin
		configuration file, all that's left before you can share your plugin is to
		package it nicely, test it, and you're good to go!</p>

	<p>You should now have a plugin configuration file, as all the relevant PHP,
		JavaScript, CSS, images, and other files under the root of your plugin. Now,
		package your plugin as a regular ZIP archive - the only restriction here is
		that the configuration file <strong><?php echo QPluginInstaller::PLUGIN_CONFIG_GENERATION_FILE ?></strong> should be at the root
		of the ZIP archive.</p>

	<p>If you're like me, and you make subtle little mistakes that make you
		re-generate that ZIP archive a bunch of times - and you're developing under
		Windows, download and use the following
		<a href="http://trac.qcu.be/projects/qcubed/browser/plugins/QAutoCompleteTextBox/tools/make_package.bat">DOS batch script</a>
		that takes care of cleaning up any SVN relics from the
		distribution, and packages stuff up as a ZIP archive. For this script
		to work, you need to have the following folder hierarchy (<a
			href="http://trac.qcu.be/projects/qcubed/browser/plugins/QAutoCompleteTextBox">sample</a>):</p>
	<ul>
		<li>releases <- this is where the resulting ZIP will be placed</li>
		<li>source <- this is the root of your plugin, with <?php echo QPluginInstaller::PLUGIN_CONFIG_GENERATION_FILE; ?> underneath it</li>
		<li>tools <- this is where the batch script goes
	</ul>
	<p>Note that you don't have to use this script at all - you can use your own.
		If you happen to write a script that does the same thing under Linux / MacOS,
		please <a href="http://qcu.be">share it with the community</a>!</p>

	<p>After you're done, test the plugin on your own installation - navigate to
		the <a href="../../_devtools/plugin_manager.php">Plugin Manager</a> and
		try uploading your resulting ZIP archive. You should see all the metadata
		you've put in when you defined your plugin; if all is well, press the
		<strong>Install</strong> button and carefully inspect the installation log. Were all
		the files copied appropriately, as you'd expect them to? Are all the class
		files included? Are all the examples hooked up? If so, press <strong>Continue</strong>
		and put your plugin to a real test - run one of the examples that you've
		thoughtfully included for the community. Do they work? Cool!</p>

	<p>Now, the last test: try uninstalling the plugin through the Plugin Manager.
		Were all the files deleted appropriately? Great. Now install it again, for
		the last time, as the last precaution. Still works? You're ready to share
		your plugin with the community!</p>

	<p>Put your plugin ZIP file - and, ideally, the source code - somewhere
		where the community can download it. A great place is the <a
			href="http://trac.qcu.be/projects/qcubed/wiki/SvnRepository">QCubed
			Subversion</a> /plugins directory. Just create a folder for your own
		plugin, and you're good to go.</p>

	<p>Now, go to the <a target="_blank"
						 href="<?php echo QPluginInstaller::ONLINE_PLUGIN_REPOSITORY ?>">
		QCubed online plugin repository</a> and edit it to add the info on your plugin. You can link
		to your plugin ZIP directly at the QCubed SVN if you're using it! You
		may also want to post an announcement to the <a
		href="http://qcu.be/forum">QCubed forums</a> and let the community know
		about your contribution.</p>
</div>

<?php require('../includes/footer.inc.php'); ?>