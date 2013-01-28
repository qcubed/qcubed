<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="full">
	<h1>QCubed Query Nodes</h1>
	
	<p><strong>QQ Nodes</strong> are any object table or association table (type tables are excluded), as well as any
	column within those tables.  <strong>QQ Node</strong> classes for your entire data model is generated for you
	during the code generation process.</p>

	<p>But in addition to this, <strong>QQ Nodes</strong> are completely interlinked together, matching the relationships
	that you have defined as foreign keys (or virtual foreign keys using a relationships script) in your
	database.</p>

	<p>To get at a specific <strong>QQ Node</strong>, you will need to call <strong>QQN::ClassName()</strong>, where "ClassName" is the name of the class
	for your table (e.g. "Person").  From there, you can use property getters to get at any column or relationship.</p>

	<p>Naming standards for the columns are the same as the naming standards for the public getter/setter properties on the object, itself.
	So just as <strong>$objPerson->FirstName</strong> will get you the "First Name" property of a Person object,
	<strong>QQN::Person()->FirstName</strong> will refer to the "person.first_name" column in the database.</p>

	<p>Naming standards for relationships are the same way.  The tokenization of the relationship reflected in a class's
	property and method names will also be reflected in the QQ Nodes.  So just as <strong>$objProject->ManagerPerson</strong> will
	get you a Person object which is the manager of a given project, <strong>QQN::Project()->ManagerPerson</strong> refers to the
	person table's row where person.id = project.manager_person_id.</p>

	<p>And of course, because <em>everything</em> that is linked together in the database is also linked together in your <strong>QQ Nodes</strong>,
	<strong>QQN::Project()->ManagerPerson->FirstName</strong> would of course refer to the person.first_name of the person who is the
	project manager of that particular row in the project table.</p>
</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>