<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The QDataGrid2 Class</h1>

	<p><strong>QSimpleTable</strong>, is great for drawing basic HTML tables, but can overload a web page
	when trying to display a large amount of information.</p>

	<p>The <strong>QDataGrid2</strong> class is a subclass of QSimpleTable that has features that allow the table
		to be a view into a larger set of data that is stored in a database.

		<h2>Sorting</h2>
		<p><strong>QDataGrid2</strong> allows you to click on column headers to resort the data in the table. Sorting is provided
		by SQL, and each click causes the database to be queried with a sort clause corresponding to the column that was clicked.
		</p>
		<h2>Pagination</h2>
	<p>A <strong>QDataGrid2</strong> allows you to click on column headers to resort the data in the table. Sorting is provided
		by SQL, and each click causes the database to be queried with a sort clause corresponding to the column that was clicked.
	</p>


	, , is great for drawing basic HTML tables, but can overload a web page
		when trying to display a large amount of information.</p>

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

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>