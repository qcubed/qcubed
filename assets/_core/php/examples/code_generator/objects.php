<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Your Tables as PHP Objects</h1>

	<p>The Code Generator will more or less create a PHP object for each table in your database.
		For our three main tables (<strong>login</strong>, <strong>person</strong> and <strong>project</strong>), the Code Generator
		created the following PHP classes:</p>

	<ul>
		<li>Login</li>
		<li>LoginGen</li>
		<li>Person</li>
		<li>PersonGen</li>
		<li>Project</li>
		<li>ProjectGen</li>
	</ul>

	<p><strong>LoginGen</strong>, <strong>PersonGen</strong> and <strong>ProjectGen</strong> are the generated classes
		which contain all the code to handle your database CRUD (create, restore, update
		and delete) functionality.  The <strong>Login</strong>, <strong>Person</strong> and <strong>Project</strong>
		classes inherit from the generated classes, and they are known as custom
		subclasses.</p>

	<p>Note that on any subsequent code generation, while the generated classes will be overwritten,
		the custom subclasses will not be touched.  So you should feel free to make changes
		to these custom subclasses, override methods, introduce additional functionality, etc.
		as well as re-execute the code generator at any time.  Your changes and class customizations
		will remain intact.</p>

	<p>For every object, the Code Generator will generate the getter and setter properties for each
		of the attributes in the table.  It will also generate the following basic <abbr title="Create, Restore, Update, Delete">CRUD</abbr> methods:</p>

	<ul>
		<li>Load</li>
		<li>LoadAll</li>
		<li>CountAll</li>
		<li>Save</li>
		<li>Delete</li>
	</ul>

	<p>The example below shows how we can use the <strong>Load</strong> and <strong>LoadAll</strong> methods and the
		properties to view some the data.  Feel free to <strong>View Source</strong> to view the PHP code
		for <strong>objects.php</strong> which makes these calls.</p>
</div>

<div id="demoZone">
	<h3>Displaying the Properties of a Project</h3>
<?php
	// Let's load a project object -- let's select the
	// project with ID #2
	$objProject = Project::Load(2);
?>
	<ul class="project-list">
		<li>Project ID: <?php _p($objProject->Id); ?></li>
		<li>Project Name: <?php _p($objProject->Name); ?></li>
		<li>Project Decsription: <?php _p($objProject->Description); ?></li>
		<li>Project Start Date: <?php _p($objProject->StartDate); ?></li>
		<li>Project End Date: <?php _p($objProject->EndDate); ?></li>
		<li>Project Budget: <?php _p($objProject->Budget); ?></li>
	</ul>

	<h3>Using LoadAll to get an Array of Person Objects</h3>
	<ul class="person-list">
<?php
		// We'll load all the persons into an array
		$objPersonArray = Person::LoadAll();

		// Use foreach to iterate through that array and output the first and last
		// name of each person
		foreach ($objPersonArray as $objPerson) {
			printf('<li>' . $objPerson->FirstName . ' ' . $objPerson->LastName . '</li>');
		}
?>
	</ul>

	<h3>Using CountAll to get a Count of All Persons in the Database</h3>
	<p>There are <?php _p(Person::CountAll()); ?> person(s) in the system.</p>
</div>

<?php require('../includes/footer.inc.php'); ?>