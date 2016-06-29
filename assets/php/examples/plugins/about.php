<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="full">
	<h1>Introduction to the Plugin Ecosystem</h1>

	<p>QCubed was built on a principle that the core distribution should be
		lightweight and extensible. A part of that vision is an easy-to-use
		plugin infrastructure. The plugin system has gone through a number of
		radical changes in attempt to make a system that is flexible, but also
		compatible with future changes of the core.</p>

	<p>The current plugin architecture relies on <a href="http://getcomposer.org">Composer</a> to install plugins.
		Once you have Composer installed and working, installing plugins is a simple
		matter of editing the composer.json file, and running Composer to do the
		installation for you. Once a plugin is installed, you can use Composer to
		monitor for updates to the plugins, and automatically install those updates.
		See the <a href="https://www.github.com/qcubed/">QCubed Github</a> page for a
		list of composer installable plugins. There you will find specific instructions
		on how to edit your composer.json file to include the plugin.
		These files currently include interfaces for
		various custom user-interface widgets, but eventually may include interfaces
		to whole javascript libraries, and template files that will give your site a
		particular structure.</p>

	<p>Composer installs all files in a <strong>/vendor</strong> directory. It includes an autoloader,
		so you can immediately use plugin classes once they are installed, without the
		need to use <strong>include</strong> statements to include them. </p>

	<p>QCubed includes a <a
			href="../../_devtools/plugin_manager.php">Plugin Manager</a>
		component that lists out the plugins you have installed, and lets you
		access the example code included with these plugins.</p>

</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>