<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Using the QDataGrid_CheckBoxColumn</h1>

	<p>In this example we will take our Paginated <strong>QDataGrid</strong>, and add a column which has a
	"Select" checkbox.  Checkbox columns that are part of a paginated datagrid can be tricky to manage.
	Remember that a paginated control only shows a portion of the overall data. Since the data could be
	10,0000 records or more, we want to avoid having to query the entire database every time a checkbox changes. However,
		we might have to do that if the user checks the "Check All" box.
		Further complicating the situation is that more than one person may be changing the data at the same time.
	</p>

	<p>In our examples here, we address two situations:</p>
	<ul>
		<li>The checkboxes represent a selection of rows which you would like to act upon later. In this situation, the
		state of the selection is unique to each user, and a private copy of the selection is maintained in the _SESSION
		variable.</li>
		<li>The checkboxes represent a boolean value that is in the database itself. Changing the checkbox immediately changes the
			corresponding value in the database.</li>
	</ul>

	<p>A situation we don't address here is if the checkboxes represent boolean values that are part of the data, but changing
	a checkbox will not change the corresponding data in the database immediately, but only after a Save button is pressed.
		This is certainly doable, but the
	problem with this scenario is that in a multi-user environment, the last person who presses the save button might
	unknowingly replace values recently changed by another user. Its better to have real-time awareness of the values when two
	people are editing the same data.</p>

	<p>The <strong>QDataGrid_CheckBoxColumn</strong> is designed to help manage a column of checkboxes.
		By default, it acts as a selection list, and maintains its own record of what is checked, 
		managing the display of checked items
		as the user pages through the data. To get the current list of what is checked, call <strong>GetCheckedItemIds()</strong>.<p>
	

	<h2>Check All</h2>
	<p>
		The <strong>QDataGrid_CheckBoxColumn</strong> can display a checkbox in the header that will check all, or check none,
		by setting the <strong>ShowCheckAll</strong> property to true.
		In order to implement this, <strong>QDataGrid_CheckBoxColumn</strong> must be able to get an id
		for each possible row shown, so that when the user pages through data, they can see what is checked. In order to do this,
		you must subclass <strong>QDataGrid_CheckBoxColumn</strong> and implement the <strong>GetAllIds()</strong> method.
	</p>

	<hr>

	<p>
		In the first example, we are using the default functionality of the <strong>QDataGrid_CheckBoxColumn</strong> class
		to create a selection list that will be acted on later. The <strong>QDataGrid_CheckBoxColumn</strong> keeps a record
		of what is checked in the _SESSION variable. It uses a subclass of <strong>QDataGrid_CheckBoxColumn</strong> to get
		all the ids if the user clicks the Check All button.
	</p>

	<p>The second example is a simulation of changing the data in real-time. In this case, we're displaying a
	many-to-many relationship and allowing the user to select a Project that should be associated with 
	the current one (in this case, ACME Website Redesign). Checking a box will immediately associate the selected project
	with the ACME project. To support this type of interaction, we subclass <strong>QDataGrid_CheckBoxColumn</strong> and
	implement the <strong>GetItemCheckedState</strong> and <strong>SetItemCheckedState</strong> methods.</p>

</div>

<div id="demoZone">
	<p><?php $this->lblResponse->Render(); ?></p>
	<?php $this->dtgPersons->Render(); ?>

	<hr />

	<h2>Child Projects of ACME Website Redesign</h2>
	<?php $this->dtgProjects->Render(); ?>
	<?php //$this->btnGo->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>