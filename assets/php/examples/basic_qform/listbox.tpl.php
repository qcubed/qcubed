<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>The QListControl Family of Controls</h1>

	<p><strong>QListControl</strong> controls handle simple lists of objects which can be selected.  In its most
		basic form, we are basically talking about HTML listboxes (e.g. &lt;select&gt;) with name/value
		pairs (e.g. &lt;option&gt;).</p>

	<p>Of course, listboxes can be single- and multiple-select.  But note that sometimes,
		you may want to display this list as a list of labeled checkboxes (which basically acts
		like a multiple-select listbox) or a list of labeled radio buttons (which acts like a
		single-select listbox).  QCubed includes the <strong>QListBox</strong>, <strong>QCheckboxList</strong> and
		<strong>QRadioButtonList</strong> controls which all inherit from QListControl to allow you to
		present the data and functionality that you need to in the most user-friendly way possible.</p>

	<p>In this example we create a <strong>QListBox</strong> and <strong>QCheckbox</strong> control.  They pull their data
		from the <strong>Person</strong> table in the database.  Also, if you select a person, we will update the
		<strong>lblMessage</strong> label to show what you have selected.</p>

	<p>If you do a <strong>View Source...</strong> in your browser to view the HTML,
		you'll note that the <strong>value</strong> attributes in the &lt;option&gt; tags are indexes (starting with 0)
		and not the values assigned in the PHP code.  This is done intentionally as a security measure to prevent database
		indexes from being sent to the browser, and to allow for non-string based values, or even duplicate values.
		You can lookup specific values in the <strong>QListControl</strong> by using the <strong>SelectedValue</strong>
		attribute. You can also lookup selected Names, Ids, and get the whole <strong>QListItem</strong>.</p>
</div>

<div id="demoZone">

	<p><label>List 1</label><?php $this->lstPersons->Render(); ?></p>
	<p><label>List 2</label><?php $this->chkPersons->Render(); ?></p>

	<p>Recently Selected: <?php $this->lblMessage->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>