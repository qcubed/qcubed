<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="full">
	<h1>Make Your Own Plugin, Part 1: Plugin Components</h1>

	<p>If you've written a component that you think may be broadly usable for
		the QCubed community, it would be amazing if you took a few minutes to
		wrap it up nicely as a plugin - this would help everyone install your
		stuff easily, and who doesn't love it when their code is used and
		appreciated?</p>

	<p>Writing a plugin is pretty simple. The first thing I'd do to learn about
		it is browse through the <a target="_blank"
									href="http://trac.qcu.be/projects/qcubed/browser/plugins">source code of
			one of the existing plugins</a> to learn how it's been put together.</p>

	<p>If you're ready to get started with your own plugin, create a
		directory somewhere on your computer; we'll refer to that directory as
		the "root" of your plugin. Add one file to the root: <strong><?=
QPluginInstaller::PLUGIN_CONFIG_GENERATION_FILE
?></strong>. That
		configuration file will describe the steps that QCubed needs to take
		while installing, as well as uninstalling your plugin.</p>

	<p>Then, create a few folders under the root, and place the files that you
		want to be distributed with the plugin there - the structure is entirely
		up to you. You may want to put all your included PHP files under the includes
		directory, or you may not; you can put all images in a separate folder, or
		just keep them as siblings of the configuration file.</p>

	<p>A plugin is described through a <strong>QPlugin</strong> object - you can see all the
		properties of that object by inspecting the
		<strong><?= substr(__QCUBED_CORE__, strlen(__DOCROOT__)) ?>/framework/QPluginInterface.class.php</strong>
		file. <a href="javascript:ViewSource(<?php _p(Examples::GetCategoryId() . ',' . Examples::GetExampleId() . ',"__CORE_FRAMEWORK__QPluginInterface.class.php"'); ?>);">Take a look </a>at it now.</p>

	<p>To define the QPlugin object, we'll first set simple metadata on it:</p>

	<pre><code>	$objPlugin = new QPlugin();<br />
	$objPlugin->strName = "MyCoolPlugin"; // no spaces allowed<br />
	$objPlugin->strDescription = 'A great little plugin that does this and that';<br />
	$objPlugin->strVersion = "0.1";<br />
	$objPlugin->strPlatformVersion = "1.1"; // version of QCubed that this plugin works well with<br />
	$objPlugin->strAuthorName = "Alex Weinstein, a.k.a. alex94040";<br />
	$objPlugin->strAuthorEmail ="alex94040 [at] yahoo [dot] com";</code></pre>

	<p>Then, let's add <strong>QPluginFile</strong>'s to the plugin. Each of the files that you
		added to the root folder will need to be mentioned, along with some relevant
		metadata for it, in order for that file to be deployed. Some of the plugin
		components you need to be aware of are:</p>
	<ul>
		<li><strong>QPluginControlFile</strong>: a class that extends QControl.</li>
		<li><strong>QPluginMiscIncludedFile</strong>: miscellaneous include file (non-web accessible).</li>
		<li><strong>QPluginCssFile</strong>, <strong>QPluginJsFile</strong>, <strong>QPluginImageFile</strong>: CSS, JavaScript, and image resources.</li>
		<li><strong>QPluginExampleFile</strong>: an example file for the plugin. Note that images, .tpl files, and other resources that
			are only used as a part of the example should all be declared as QPluginExampleFile's.</li>
	</ul>

	<p>Let's now register several <strong>QPluginFiles</strong> with your <strong>QPlugin</strong>. Note that
		all paths are relative to the root of your plugin:</p>
	<pre><code>
		$files = array(); <br />
		$files[] = new QPluginControlFile("includes/QPhoneTextBox.class.php");<br />
		$files[] = new QPluginJsFile("js/phonetextbox.js");<br />
		$files[] = new QPluginExampleFile("example/phonetextbox.php");<br />
		$files[] = new QPluginExampleFile("example/phonetextbox.tpl.php");<br />
		$objPlugin->addComponents($files);
	</code></pre>

	<p>After you've added all the files, it's time to declare any classfiles that need
		to be included when QCubed attempts to instantiate your plugin. You can do this
		by adding a <strong>QPluginIncludedClass</strong> component to your <strong>QPlugin</strong>:</p>
	<pre><code>
			$components = array(); <br />
			// First parameter is the name of the class, second - path to the file, <br />
			// relative to the root of your plugin. Note that the QFile for this included<br />
			// class should already be declared above! <br />
			$components[] = new QPluginIncludedClass("QPhoneTextBox", "includes/QPhoneTextBox.class.php");<br />
			$objPlugin->addComponents($components);<br />
		</code></pre>

	<p>It's always a good idea to provide a few examples with your plugin. To do so,
		we will create <strong>QPluginExample</strong> components, and add them to our <strong>QPlugin</strong>:</p>
	<pre><code>
		$components = array(); <br />
		// First parameter is the path to the file, relative to the root of your plugin.<br />
		// Second parameter is the description of the example. <br />
		$components[] = new QPluginExample("example/phonetextbox.php", "Validate and format phone numbers");<br />
		$objPlugin->addComponents($components);<br />
	</code></pre>

	<p>Now, add a magical line to the end of the configuration file...</p>	
	<pre><code>
		$objPlugin->install();
	</code></pre>

	<p>..and you're done! <a href="packaging.php">Read the next chapter</a> to
		learn about ways to package and distribute your plugin.</p>
</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>