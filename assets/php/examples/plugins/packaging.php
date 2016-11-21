<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="full">
	<h1>Make Your Own Plugin, Part 2: Packaging and Distributing</h1>

	<p>Now that you <a href="components.php">know</a> how to create a plugin
		control, all that's left before you can share your plugin is to
		package it nicely, test it, and you're good to go!</p>
	<p>See other plugins for examples of the directory structure required. The directory structure can be very flexible, but generally you will want the following directories in your plugin root directory:</p>
	<ul>
	  <li><strong>js</strong> - The location of your javascript files, including the javascript widget and any additional javascript files you need. Add these files to your output by using the <strong>AddPluginJavascriptFile</strong> method.</li>
	  <li><strong>includes</strong> - The PHP files you want to make available to your project. Composer will use its autoloader to make these files available upon request, if you correctly set up the composer.json file (see below).</li>
	  <li><strong>css</strong>- Any css files that go with your plugin. Include these by calling AddPluginCssFile from your constructor.</li>
		<li><strong>examples</strong> - Example files that demonstrate the use of the plugin. This is also a great way to document  your plugin.</li>
		<li><strong>install</strong> - Stub files that will get moved to the <strong>/projects</strong> directory after installation. These files are user
			modifiable, and do not get overwritten when the user does a composer update to get the latest version of your control.</li>
    </ul>
	<p>If your control edits a basic data type, be sure to include a <strong>control_registry.inc.php</strong> file as described in the previous example page.</p>
	<p>Include a <strong>composer.json</strong> file in the root directory of your control. Its probably easiest to copy one from a current plugin and edit it.</p>
	<p>Create a new repository in your own GitHub account, and upload your directory to the repository. Point to your repository from your main qcubed <strong>composer.json</strong> file, and try a Composer Install command to see if your control will install. It should appear in the <strong>vendor/qcubed/plugin</strong> directory if all goes well. Try it out in your project and see if you can use it.</p>
	<p>Once you are ready to give it to the community, post an issue in the <a href="https://github.com/qcubed/qcubed">QCubed Github website</a>. One of the core developers will take a look, and add it as a qcubed repository if everything looks good.</p>
</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>