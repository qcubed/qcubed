<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Early Binding: Using Object Expansion</h1>

	<p>(Note: for more information about "QQ::"-related classes (a.k.a. <strong>QCubed Query</strong>), please refer to section 3 of the
	Examples Site.)</p>

	<p>When you need to perform LoadAll or LoadArray calls, and would like to include related objects
	in order to limit round tripping, you can use QCubed's <strong>Object Expansion</strong> functionality to 
	specify which Foreign Key columns that you want to expand immediately.</p>

	<p>The <strong>Object Expansion</strong> function, which is generated into each object in the ORM,
	will bind these related objects when the objects are initially created, thus the term
	"early binding".</p>

	<p>In our example here, we will perform the <i>exact same task</i> as the previous example, pulling
	all the <strong>Project</strong> objects and displaying each object's <strong>ManagerPerson</strong>.  Note
	that the <i>only difference</i> in our code is that we've added a <strong>QQ::Expand()</strong> clause.
	There is <i>no other difference</i> with the way we access the restored objects and their related
	objects.</p>

	<p>The end result is that instead of displaying the data using 5 queries, we have now cut this down
	to just 1 query.  This is accomplished because of the LEFT JOIN which is executed
	by the code generated ORM and the passed in <strong>QQ::Expand()</strong> clause.</p>

	<p>But more importantly, because the way we access the objects is the exact same, this
	kind of round trip optimization can be done <i>after</i> the page is functional and complete.  This
	follows the general philosophy of QCubed, which is to first focus on making your application
	functional, then focus on making your application more optimized.  The value of doing this is
	because often engineers can get bogged down on making an application as optimized as possible,
	and in doing so they can unnecessarily overengineer some pieces of functionality.
	If the focus is on getting the application functional, first, then after the application is in
	a usable state, you can profile the functionality that tends to get used more often and simply
	focus on optimizing this smaller subset of heavily-used functionality.</p>

	<p>For information about Expanding through Association Tables, please refer to the
	<a href="../qcubed_query/association.php">Handling Association Tables example</a>.</p>
</div>

<div id="demoZone">
	<h2>List All Projects and its Manager</h2>
<?php
	// Enable Profiling (we're assuming the Examples Site Database is at index 1)
	// NOTE: Profiling should only be enabled when you are actively wanting to profile a specific PHP script.
	// Because of SIGNIFICANT performance degradation, it should otherwise always be off.
	QApplication::$Database[1]->EnableProfiling();

	// Load the Project array
	// The following line of code is the ONLY line of code we will modify
	$objProjectArray = Project::LoadAll(  QQ::Clause(QQ::Expand(QQN::Project()->ManagerPerson))  );
	foreach ($objProjectArray as $objProject) {
		_p(QApplication::HtmlEntities($objProject->Name) . ' is managed by ' . $objProject->ManagerPerson->FirstName . ' ' . 
			$objProject->ManagerPerson->LastName);
		_p('<br/>', false);
	}
	_p('<br/>', false);

	// Output Profiling Data
	QApplication::$Database[1]->OutputProfiling();
?>
</div>

<?php require('../includes/footer.inc.php'); ?>