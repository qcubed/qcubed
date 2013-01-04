<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Late Binding: The Default Load Method</h1>

	<p>By default, any object with related objects (e.g. in our <strong>Examples Site Database</strong>, an example
	of this is how the <strong>Project</strong> object has a related <strong>ManagerPerson</strong> object) will perform
	"late binding" on that related object.</p>

	<p>So given our example, when you load a given <strong>Project</strong> object, the <strong>$objManagerPerson</strong>
	member variable is initially NULL.  But when you ask for the <strong>ManagerPerson</strong> property,
	the object first checks to see of <strong>$objManagerPerson</strong> is null, and if it is, it will
	call the appropriate <strong>Load</strong> method to then query the database to pull that <strong>Person</strong>
	object into memory, and then bind it to this <strong>Project</strong> object.  Note that any <i>subsequent</i>
	calls to the <strong>ManagerPerson</strong> property will simply return the already bound <strong>Person</strong>
	object (no additional query to the database is needed).  This <strong>Person</strong> is
	essentially bound, as late as possible, to the <strong>Project</strong>, thus the term "late binding".</p>


	<p>The advantages of "late binding" is that the data going between the database and the application
	is as minimal as possible.  You only get the minimal amount data that you need, when you need it,
	and nothing else.  And fortunately, because the QCubed generated code does the binding for you
	behind the scenes, there is nothing that you would need to manually code to check, enforce or
	execute this binding functionality.</p>

	<p>The disadvantage, however, is that for some functionalities where you are performing <strong>LoadAll</strong>
	or <strong>LoadArrayBy</strong>, and you need to use related objects within those arrays, you end up with
	"N+1 round tripping".  This means that if you had 100 objects, you are essentially doing 101 round trips
	to the database: 1 queries to get the list of 100 objects, and 100 additional queries (one for
	each object to get its related object).</p>

	<p>In this example, we <strong>LoadAll</strong> all the <strong>Project</strong> objects, and view each object's
	<strong>ManagerPerson</strong>.  Using the built in QCubed Database Profiler, you can see that
	five database calls are made: One call to get all the projects (four rows in all), and then four calls
	to <strong>Person::Load</strong> (one for each of those projects).</p>
</div>

<div id="demoZone">
	<h2>List All Projects and its Manager</h2>
	<ul>
<?php
	// Enable Profiling (we're assuming the Examples Site Database is at index 1)
	// NOTE: Profiling should only be enabled when you are actively wanting to profile a specific PHP script.
	// Because of SIGNIFICANT performance degradation, it should otherwise always be off.
	QApplication::$Database[1]->EnableProfiling();

	// Load the Project array
	// Note how even though we make two calls to ManagerPerson PER project, only ONE call to
	// Person::Load is made per project -- this is because ManagerPerson is bound to the
	// Project during the first call.  So the second call is using the ManagerPerson that's
	// already bound to that project object.
	$objProjectArray = Project::LoadAll();
	foreach ($objProjectArray as $objProject) {
		_p('<li>'.$objProject->Name . ' is managed by ' . $objProject->ManagerPerson->FirstName . ' ' . 
			$objProject->ManagerPerson->LastName.'</li>', false);
	}
	_p('</ul>', false);
	// Output Profiling Data
	QApplication::$Database[1]->OutputProfiling();
?>
</div>

<?php require('../includes/footer.inc.php'); ?>