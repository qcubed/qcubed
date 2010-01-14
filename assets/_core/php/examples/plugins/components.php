<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div class="instructions" style="max-height: none">
		<h1 class="instruction_title">Make Your Own Plugin, Part 1: Plugin Components</h1>
		If you've written a component that you think may be broadly usable for
		the QCubed community, it would be amazing if you took a few minutes to
		wrap it up nicely as a plugin - this would help everyone install your
		stuff easily, and who doesn't love it when their code is used and
		appreciated? <br /><br />
		
		Writing a plugin is pretty simple. The first thing I'd do to learn about
		it is browse through the <a target="_blank"
		href="http://trac.qcu.be/projects/qcubed/browser/plugins">source code of
		one of the existing plugins</a> to learn how it's been put together.<br /><br />
		
		If you're ready to get started with your own plugin, create a
		directory somewhere on your computer; we'll refer to that directory as
		the "root" of your plugin. Add one file to the root: <b><?php echo
		QPluginInstaller::PLUGIN_CONFIG_GENERATION_FILE ?></b>. That
		configuration file will describe the steps that QCubed needs to take
		while installing, as well as uninstalling your plugin.<br /><br />
		
		Then, create a few folders under the root, and place the files that you
		want to be distributed with the plugin there - the structure is entirely
		up to you. You may want to put all your included PHP files under the includes
		directory, or you may not; you can put all images in a separate folder, or
		just keep them as siblings of the configuration file. <br /><br />
		
		A plugin is described through a <b>QPlugin</b> object - you can see all the
		properties of that object by inspecting the
		<b><?php echo substr(__QCUBED_CORE__, strlen(__DOCROOT__)) ?>/framework/QPluginInterface.class.php</b>
		file. <a href="javascript:ViewSource(<?php _p(Examples::GetCategoryId() . ',' . Examples::GetExampleId() . ',"__CORE_FRAMEWORK__QPluginInterface.class.php"'); ?>);">Take a look </a>at it now.<br /><br />
		
		To define the QPlugin object, we'll first set simple metadata on it:
		
		<div style="padding-left: 50px;">
			<code>
				$objPlugin = new QPlugin();<br />
				$objPlugin->strName = "MyCoolPlugin"; // no spaces allowed<br />
				$objPlugin->strDescription = 'A great little plugin that does this and that';<br />
				$objPlugin->strVersion = "0.1";<br />
				$objPlugin->strPlatformVersion = "1.1"; // version of QCubed that this plugin works well with<br />
				$objPlugin->strAuthorName = "Alex Weinstein, a.k.a. alex94040";<br />
				$objPlugin->strAuthorEmail ="alex94040 [at] yahoo [dot] com";				
			</code>
		</div><br />
		
		Then, let's add <b>QPluginFile</b>'s to the plugin. Each of the files that you
		added to the root folder will need to be mentioned, along with some relevant
		metadata for it, in order for that file to be deployed. Some of the plugin
		components you need to be aware of are:
		<ul>
			<li><b>QPluginControlFile</b>: a class that extends QControl.</li>
			<li><b>QPluginMiscIncludedFile</b>: miscellaneous include file (non-web accessible).</li>
			<li><b>QPluginCssFile</b>, <b>QPluginJsFile</b>, <b>QPluginImageFile</b>: CSS, JavaScript, and image resources.</li>
			<li><b>QPluginExampleFile</b>: an example file for the plugin. Note that images, .tpl files, and other resources that
			are only used as a part of the example should all be declared as QPluginExampleFile's.</li>
		</ul>
		
		Let's now register several <b>QPluginFiles</b> with your <b>QPlugin</b>. Note that
		all paths are relative to the root of your plugin:
		<div style="padding-left: 50px;">
			<code>
				$files = array(); <br />
				$files[] = new QPluginControlFile("includes/QPhoneTextBox.class.php");<br />
				$files[] = new QPluginJsFile("js/phonetextbox.js");<br />
				$files[] = new QPluginExampleFile("example/phonetextbox.php");<br />
				$files[] = new QPluginExampleFile("example/phonetextbox.tpl.php");<br />
				$objPlugin->addComponents($files);
			</code>
		</div><br />
		
		After you've added all the files, it's time to declare any classfiles that need
		to be included when QCubed attempts to instantiate your plugin. You can do this
		by adding a <b>QPluginIncludedClass</b> component to your <b>QPlugin</b>:
		<div style="padding-left: 50px;">
			<code>
				$components = array(); <br />
				// First parameter is the name of the class, second - path to the file, <br />
				// relative to the root of your plugin. Note that the QFile for this included<br />
				// class should already be declared above! <br />
				$components[] = new QPluginIncludedClass("QPhoneTextBox", "includes/QPhoneTextBox.class.php");<br />
				$objPlugin->addComponents($components);<br />
			</code>
		</div><br />
		
		It's always a good idea to provide a few examples with your plugin. To do so,
		we will create <b>QPluginExample</b> components, and add them to our <b>QPlugin</b>:
		<div style="padding-left: 50px;">
			<code>
				$components = array(); <br />
				// First parameter is the path to the file, relative to the root of your plugin.<br />
				// Second parameter is the description of the example. <br />
				$components[] = new QPluginExample("example/phonetextbox.php", "Validate and format phone numbers");<br />
				$objPlugin->addComponents($components);<br />
			</code>
		</div><br />
		
		Now, add a magical line to the end of the configuration file...		
		<div style="padding-left: 50px;">
			<code>
				$objPlugin->install();
			</code>
		</div><br />
		
		..and you're done! <a href="packaging.php">Read the next chapter</a> to
		learn about ways to package and distribute your plugin. <br /><br />
	</div>

<?php require('../includes/footer.inc.php'); ?>
