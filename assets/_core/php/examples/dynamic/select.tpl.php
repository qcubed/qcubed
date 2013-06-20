<?php require('../includes/header.inc.php'); ?>
	<?php $this->RenderBegin(); ?>

	<div id="instructions">
		<h1>Dynamically Creating QControls in a QDataGrid</h1>
		
		<p>In this example we will take our Paginated <strong>QDataGrid</strong>, and add a column which has a
		"Select" checkbox.  When clicking the checkbox, a server action will update the response label
		to say who has been selected or deselected.</p>

		<p>To get this to work, we added a fourth column to put the checkbox.  In the HTML
		of that column, we make a call to a new <strong>chkSelected_Render</strong> method which
		we define.  This method checks to see if a checkbox for that <strong>Person</strong> has
		already been created (and if not, it will then create one).  The method
		then returns the rendered string for that checkbox.</p>
		
		<p>Also, on the column object, itself, we need to make sure to set the <strong>HtmlEntities</strong>
		to <strong>false</strong>, so that the HTML of the checkbox doesn't get escaped.</p>

		<p>And finally, we define a <strong>QClickEvent</strong> server action for the checkboxes which 
		will call the <strong>chkSelected_Click</strong> method to actually perform the action.  In order
		to let the <strong>chkSelected_Click</strong> method know <i>which</i> Person we just selected or
		deselected, we set the <strong>ActionParameter</strong> of each checkbox to the ID of the <strong>Person</strong>.</p>
	</div>

<div id="demoZone">
	<p><?php $this->lblResponse->Render(); ?></p>
	<?php $this->dtgPersons->Render(); ?>
</div>
	<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>