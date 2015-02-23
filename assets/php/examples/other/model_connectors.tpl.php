<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Generated ModelConnector Objects</h1>
	As you build out more and more database-driven <strong>QForms</strong> and <strong>QPanels</strong>, you'll notice
	that you may spend quite a bit of time coding the same type of Control
	definition, setup and data binding procedures over and over again. This becomes
	especially tedious when you are talking about modifying objects with a large
	number of fields.</p>

<p>An important part of the "Controller" functionality of the MVC architecture of QCubed is the <strong>ModelConnector</strong>.
	It connects specific screen controls to the fields in a database table,
	and includes the code to create the controls, populate them with data from the table, and save the user's changes
	back to the database.
	<strong>ModelConnectors</strong> include a code-generated base, and also a stub sub-class to allow you to override parts of the
	<strong>ModelConnector</strong> for your own customizations.

<p>For each field in a class, you can have the <strong>ModelConnector</strong> return for you a data bound
	and setup <strong>QControl</strong> for editing, or a <strong>QLabel</strong> just for viewing.  But because these ModelConnectors
	are simply returning standard QControls, you can then modify them (stylizing, adding events, etc.) as you normally would
	any other control.</p>

<p>As you request controls from the <strong>ModelConnector</strong>, it keeps track of which controls you have request, so that you can call
		<strong>SavePerson()</strong> on the <strong>ModelConnector</strong>, and it will go through any controls
		created thus far and bind the data for those controls back to the Person object. If your application needs to
		scroll through a group of objects, you can use the <strong>Load</strong> method to load new data for a database record
		into all of your requested controls automatically. </p>

<h2>The ModelConnector Designer</h2>

<p>The code-generated controls includes some basic options which QCubed reads from the database. For example, if your database field
	is not allowed to be null, the code-generated control will automatically have the <strong>Required</strong>
	attribute set to true. To further customize what will be generated in the base version of the <strong>ModelConnector</strong>,
	you can use the <strong>ModelConnector Designer</strong>. To use the designer, do the following:</p>

	<ol>
		<li>Define the <strong>__DESIGN_MODE__</strong> constant in your configuration file.</li>
		<li>Right click on the <strong>QControl</strong> you want to modify in your browser.</li>
		<li>Set your options, click the Save button, and the code-generate again.</li>
	</ol>

<p>You can set a large variety of options from this dialog (try it now by right clicking on a field in the example to the right), including
	the ability to change the type of control generated for a database field.
	Hover over any option in the designer to popup a description of that option.</p>

<p>The options for the <strong>ModelConnector Designer</strong> are saved in the "codegen_options.json" file in your configuration directory.
	If you make a change that you cannot fix from the <strong>ModelConnector Designer</strong> (like hiding a control that you want to show),
	you can always directly edit that file to recover from your mistake.</p>

<h2>The Example</h2>

<p>The example shows some basic controls so that you can try out the <strong>ModelConnector Designer</strong>. Right click
	on any of the fields or the checkboxes to bring up a dialog that will let you specify the various options for
	the codegen process.</p>

<p>Finally, note that since the <strong>ModelConnectors</strong> encapsulate all the functionality for a given
	instance of a given object, and since it is able to keep track of and maintain its own
	set of controls, you can easily have multiple <strong>ModelConnectors</strong> on any <strong>QForm</strong> or <strong>QPanel</strong>,
	view or edit multiple objects of any class at the same time.</p>
</div>

<div id="demoZone">
	<p>Right-click on any label to edit:</p>
	<?php $this->txtFirstName->RenderWithName(); ?>
	<?php $this->txtLastName->RenderWithName(); ?>
	<?php $this->lstPersonTypes->RenderWithName(); ?>

	<p>
		<?php $this->btnSave->Render(); ?>
		<?php $this->btnCancel->Render(); ?>
	</p>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>