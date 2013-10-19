<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Custom Renderers and Control Properties</h1>

	<p>In our final Calculator example, we show how you can use custom renderers to affect layout,
		as well as use control properties to change the appearance of your QControls.</p>

	<p>The QCubed distribution includes a sample custom renderer, <strong>RenderWithName</strong>, which is
		defined in your QControl custom class (which is at /includes/qform/QControl.inc).
		We'll use this <strong>RenderWithName</strong> for our calculator's textboxes and listbox.  We've also
		made sure to assign <strong>Name</strong> properties to these QControls.</p>

	<p>Note how "Value 1" and "Value 2" are in all caps and boldfaced, while "Operation" is not.  This is
		because the textboxes are set to <strong>Required</strong> while the listbox is not.  And the sample
		<strong>RenderWithName</strong> method has code which will boldface/allcaps the names of any required controls.</p>

	<p>We've also made some changes to the styling and such to the various controls.  Note that you can
		programmatically make these changes in our form definition (in <strong>Form_Create</strong>), and you can
		also make these changes as "Attribute Overrides" in the HTML template itself (see the "Other Tidbits"
		section for more information on <strong>Attribute Overriding</strong>).</p>

	<p>And finally, in our HTML template, we are now using the <strong>RenderWithName</strong> calls.  Because of that,
		we no longer need to hard code the "Value 1" and "Value 2" HTML in the template.</p>
</div>

<div id="demoZone">
	<p><?php $this->txtValue1->RenderWithName(); ?></p>

	<p><?php $this->txtValue2->RenderWithName(); ?></p>

	<p><?php $this->lstOperation->RenderWithName(); ?></p>

	<?php $this->btnCalculate->Render('Width=200px', 'Height=100px', 'FontNames=Courier'); ?>
	<hr/>
	<?php $this->lblResult->Render('FontSize=20px', 'FontItalic=true'); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>