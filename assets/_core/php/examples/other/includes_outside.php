<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div class="instructions" style="max-height: none">
		<h1 class="instruction_title">Moving your /includes outside of the __DOCROOT__</h1>

		There is a school of security thought that suggests that files that aren't meant to 
		be web-accessible aren't even supposed to be in a web-accessible directory. An approach 
		like this may seem to be over-zealous for some, but for others - especially those who
		had their sites hacked in the past - will immediately understand the benefits.<br /><br />
		
		All QCubed non-web-accessible files - including those of the plugins - are under the /includes
		folder. QCubed has been architected with security in mind; you can easily move the ENTIRE 
		/includes directory anywhere else on your server, and the system will keep working - you'll 
		need to update just a couple of files:
		<ul>
			<li>Update the $configPath variable in qcubed.inc.php in the root of your web application. 
				That variable can contain an absolute or a relative path (relative to the root of your 
				web application)</li>
			<li>Update the __INCLUDES__ variable in your includes/configuration/prepend.inc.php file.</li>
		</ul>
		
		There's another reason why you may want to move your /includes folder outside of the 
		web application: if you want to share a QCubed installation between two web applications.
		If that's the case, just follow the instructions above; you'll need to carefully juggle the 
		database configuration settings and such, but if you're sharing the install for a few apps, you
		probably know what you're doing :-). If you have any questions along the way, 
		<a href="http://qcu.be/forums/qcubed-framework/help">QCubed Forums</a> are there to help.
	</div>
<?php require('../includes/footer.inc.php'); ?>
