<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Dynamically Adding QControls to QHtmlTable</h1>

	<p>This example illustrates a technique for creating QControls and placing them in
		a simple table dynamically, during the time each row is drawn. </p>

	<p>The scenario is that you want to build a simple Employee Directory,
		showing a grid of People. For each person, you want to show their picture
		and an Edit button - with the edit button leading to the employee editing
		page.</p>

	<p>Take a look at the example. You'll see exactly this type of
		functionality. Then glance through the code; you'll be able to learn
		something from each column, let's examine them one-by-one.</p>

	<p><strong>Full Name</strong>: We are using a callback to generate the html of the column.
		Since we are generating HTML, we must turn of HtmlEntities for the column, or we will see
		the HTML code appear in the column, and not the text formatted by the HTML.</p>


	<p><strong>Image</strong>: Inside the renderImage() method, we are creating <strong>QImageControl</strong> controls.
		However, since we may be drawing the same row many times, we need to be careful to only create the control
		once. To do this, we generate a unique control id using the data object's id and a constant string,
		and then we check whether the QControl with the pre-formed ID  has already
		been created. If so, we don't try to re-create it &mdash; instead, we just ask it to render again.
		This is good for situations when you have to re-render the datagrid (because of an Ajax
		refresh, for example). If that control doesn't exist yet &mdash; and it won't the first time the datagrid is
		rendered &mdash; we create the QImageControl, and give it the right Control ID.</p>

	<p><strong>The Edit Column</strong>: i.e. the column with a custom Edit QButton &mdash; is quite
		similar in its structure to the Image column. Note that we are using an
		ActionParameter to help the click handler determine which row the user clicked on.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>