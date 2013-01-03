<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The QFileAsset Control</h1>

	<p>The <strong>QFileAsset</strong> control is a great example of a complex control that combines many simpler controls to provide
	a single, cohesive control that is very simple/straightfoward in terms of end-user usability.</p>

	<p>Combining <strong>QPanels</strong>, <strong>QButtons</strong>, <strong>QDialogBox</strong>, <strong>QImageControl</strong> and of course <strong>QFileControl</strong>, the QFileAsset is an alternative to the standard
	<strong>&lt;input type="file"&gt;</strong> approach to handling file uploads on forms.</p>

	<p>Moreover, while <b>&lt;input type="file"&gt;</b> and <strong>QFileControl</strong> requires a form to be submitted via a
	<strong>QServerAction</strong> (as opposed to a <strong>QAjaxAction</strong>),
	because the actual file upload mechanism is in a separate dialog box, this does allow you to have an entire form submitted via Ajax, while at the same time
	the file upload, itself, is specifically performed using its own QServerControlAction within the <strong>QFileAsset</strong> dialog box.</p>

	<p>And finally, as this example shows, <strong>QFileAsset</strong> allows you to process through the standard <strong>QForm</strong> validation 
	process (e.g. checking and reporting on failed "Required" constraints) in a way that makes sense,
	and in a way that does not necessarily force the user to re-upload on every form submit.</p>
</div>

<div id="demoZone">
	<style type="text/css">
		.image_canvas {border-width: 4px; border-style: solid; border-color: #a9f;}
	</style>
	<p><?php $this->flaSample->RenderWithError(); ?></p>
	<p><?php $this->lblMessage->Render(); ?></p>
	<p><?php $this->btnButton->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>