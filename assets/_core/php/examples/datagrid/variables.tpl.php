<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The QDataGrid Variables -- $_ITEM, $_COLUMN, $_CONTROL and $_FORM</h1>

	<p>As you may have noticed in the first example, we make use of the $_ITEM variable when we render
		each row's column.  There are in fact three special variables used by the QDataGrid:
		<strong>$_ITEM</strong>, <strong>$_COLUMN</strong>, <strong>$_CONTROL</strong> and <strong>$_FORM</strong>.</p>

	<p><strong>$_ITEM</strong> represents a specific row's instance of the array of items you are iterating through.
		So in our example, the <strong>DataSource</strong> is an array of <strong>Person</strong> objects.  Therefore, <strong>$_ITEM</strong>
		is the  specific <strong>Person</strong> object for the row that we are rendering.</p>

	<p><strong>$_COLUMN</strong> is the QDataGridColumn, <strong>$_CONTROL</strong> is the QDataGrid itself and <strong>$_FORM</strong> is the QForm itself.</p>

	<p>So in our example, the first column shows the "Row Number", which is basically just the
		<strong>CurrentRowIndex</strong> property of the <strong>QDataGrid</strong> (e.g. <strong>$_CONTROL</strong>).  And the last column's
		"Full Name" is rendered by the <strong>DisplayFullName</strong> method we have defined in our <strong>ExampleForm</strong>
		(e.g. <strong>$_FORM</strong>).  Note that the <strong>DisplayFullName</strong> takes in a <strong>Person</strong> object.
		Subsequently, in our HTML defintion, we make the call to <strong>$_FORM->DisplayFullName</strong> passing in
		<strong>$_ITEM</strong>.</p>

	<p>Finally, note that <strong>DisplayFullName</strong> is declared as a <strong>Public</strong> method.  This is because
		<strong>DisplayFullName</strong> is actually called by the <strong>QDataGrid</strong>, which only has the rights to call
		<strong>Public</strong> methods in your <strong>ExampleForm</strong> class.</p>
</div>

<div class="demo-zone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>