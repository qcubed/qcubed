<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Creating a QDataGrid with Inline Editing</h1>
	
	<p>Using the techniques for dynamically creating controls and utilizing the AJAX
	features in QCubed, we update our <strong>Person</strong> datagrid to include functionality for
	inline editing.</p>

	<p>We must first add a <strong>$intEditPersonId</strong> in the QForm to keep track of which
	<strong>Person</strong> (if any) we are currently editing.  We then must define the First
	and Last Name <strong>QTextBoxes</strong>, as well as Save and Cancel <strong>QButtons</strong>.
	Note that we only need to define one of each, because only one Person can be edited
	at a time.  The textboxes have <strong>QEscapeKeyEvents</strong> defined on them to
	perform a "Cancel", and the "Save" button is set to be a <strong>PrimaryButton</strong>.  This
	allows the textboxes to be sensitive to the <strong>Enter</strong> and <strong>Escape</strong> keys for
	saving and cancelling, respectively.</p>

	<p>We also define render methods for each of the columns
	to properly display either the name or the <strong>QTextBox</strong>, depending on the row we are
	rendering and which <strong>Person</strong> we are editing.</p>

	<p>And finally, we add a <strong>btnNew</strong> at the bottom to allow the user to create new
	<strong>Person</strong> objects.  If they want to create a new person, the <strong>$intEditPersonId</strong>
	is set to -1, and we get the datagrid to basically act as if it's editing a blank person.</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
	<div style="text-align: center; width: 670px; margin-top: 16px;">
		<?php $this->btnNew->Render(); ?>
	</div>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>