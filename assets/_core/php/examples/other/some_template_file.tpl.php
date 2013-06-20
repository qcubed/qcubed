<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Hello World, Revisited... Again...</h1>

	<p>By default, the <strong>QForm</strong> engine will insert <strong>.tpl</strong> to the PHP script's file path to use as the
		template file path.  For example, for the very first example, the script with the form defintion
		was named <strong>intro.php</strong>. Therefore, by default, QCubed used <strong>intro.tpl.php</strong> as the HTML template
		include file (the "tpl" signifying that it's an HTML template).</p>

	<p>For many reasons you may want to use a different filename or even
		specify a different filepath altogether. In fact, the QCubed Code Generator does this when it generates the
		form_draft template files into a separate directory than the form_drafts, themselves.</p>

	<p>The <strong>QForm::Run</strong> method takes in an optional second parameter where you can specify the exact
		filepath of the template file you wish to use, overriding the default "script_name.tpl.php".</p>
</div>

<div id="demoZone">
	<?php
	// We will override some visual attributes of the controls here -
	// the ForeColor, FontBold and the FontSize.
	?>
	<p><?php $this->lblMessage->Render('ForeColor=red', 'FontBold=true'); ?></p>
	<p><?php $this->btnButton->Render('FontSize=20'); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>