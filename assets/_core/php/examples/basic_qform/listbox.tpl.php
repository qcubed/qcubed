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

	<p>In this example we create a <strong>QListBox</strong> control.  This single-select listbox will pull its data
		from the <strong>Person</strong> table in the database.  Also, if you select a person, we will update the
		<strong>lblMessage</strong> label to show what you have selected.</p>

	<p>If you do a <strong>View Source...</strong> in your browser to view the HTML,
		you'll note that the &lt;option&gt; values are arbitrary indexes (starting with 0).  This is
		done intentionally.  <strong>QListControl</strong> uses arbitrary listcontrol indexes to lookup the specific
		value that was assigned to that <strong>QListItem</strong>.  It allows you to do things like put in non-string
		based data into the value, or even to have multiple listitems point have the same exact value.</p>

	<p>And in fact, this is what we have done.  The actual value of each <strong>QListItem</strong> is <i>not</i> a
		<strong>Person</strong> Id, but it is in fact the <strong>Person</strong> object, itself.  Note that in our
		<strong>lstPersons_Change</strong>, we never need to re-lookup the <strong>Person</strong> via a <strong>Person::Load</strong>.  We
		simply display the <strong>Person's</strong> name directly from the object that is returned by the <strong>SelectedValue</strong>
		call on our <strong>QListBox</strong>.</p>
</div>

<div id="demoZone">
	<p><?php $this->lstPersons->Render(); ?></p>
	<p>Currently Selected: <?php $this->lblMessage->Render(); ?></p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>