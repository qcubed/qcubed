<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div class="instructions">
		<div class="instruction_title">Rich Text Editing with QFCKEditor</div>

		QCubed comes with a built-in, integrated open-source rich-text WYSIWYG
		editor <a href="http://www.fckeditor.net">FCKEditor</a>. The idea here
		is "what you see is what you get": the user can do a little bit of
		text processing right inside of your application: make text bold,
		introduce bullets, etc - all with an intuitive point-and-click interface,
		a-la Microsoft Word, and without security risk for your server.<br/><br/>
		
		To use it, just instantiate a <b>QFCKEditor</b> control the same way you
		would a <b>QTextBox</b>. Set its width and height, and you're good to go.
		One important gotcha: the QForm that's hosting <b>QFCKEditor</b> must use
		<b>QServerAction</b> - QAjaxAction will not work weill with the rich-text
		editor. <br /><br />
		
		If you want to make customizations to how the <b>QFCKEditor</b> looks (for
		example, change the color scheme of the toolbar) or what the users can
		do with it (for example, allow them to add tables), you should know about
		the <b>QFCKEditor</b> configuration file: <i>/assets/js/fckeditor_config.js</i>.
		To learn more about the options you can specify in this file, refer to
		<a href="http://docs.fckeditor.net/FCKeditor_2.x/Developers_Guide/Configuration/Configuration_Options">
		FCKEditor configuration documentation</a>.
	</div>

	<p><?php $this->txtInput->Render() ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
	<p><?php $this->lblMessage->Render(); ?></p>

	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>