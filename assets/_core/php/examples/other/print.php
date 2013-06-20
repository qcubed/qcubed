<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Print Command Shortcuts</h1>

	<p>Developers will tend to use the following PHP <b>Print</b> methods fairly often
		in the template include files:</p>
	<ul>
		<li>print($strSomeString)</li>
		<li>print(htmlentities($strSomeString))</li>
		<li>print(QApplication::Translate($strSomeString))</li>
		<li>print(QApplication::LocalizeInteger($intSomething)) (not yet implemented)</li>
		<li>print(QApplication::LocalizeFloat($fltSomething)) (not yet implemented)</li>
		<li>print(QApplication::LocalizeCurrency($fltSomething)) (not yet implemented)</li>
	</ul>

	<p>Because of this, QCubed has defined several global PHP functions which act as shortcuts
		to these specific commands:</p>
	<ul>
		<li>_p($strSomeString, $blnHtmlEntities = true) - will print the passed in string.  By default, it will also perform <b>QApplication::HtmlEntities</b> first.  You can override this by setting $blnHtmlEntities = false.</li>
		<li>_t($strSomeString) -- will print a translated string via <b>QApplication::Translate</b></li>
		<li>_i($intSomething)</li>
		<li>_f($fltSomething)</li>
		<li>_c($fltSomething)</li>
	</ul>

	<p>Please note: these are simply meant to be shortcuts to actual QCubed functional
		calls to make your templates a little easier to read.  By no means do you have to
		use them.  Your templates can just as easily make the fully-named method/function calls.</p>
</div>

<div id="demoZone">
	<p>Examples: <?php _p('Hello, world'); ?></p>
</div>

<?php require('../includes/footer.inc.php'); ?>
