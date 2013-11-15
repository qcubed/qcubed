<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Manipulating LoadAll and LoadArrayBy Results</h1>

	<p>(Note: for more information about "QQ::"-related classes (a.k.a. <strong>QCubed Query</strong>), please refer to section 3 of the
	Examples Site.)</p>

	<p>All Code Generated <strong>LoadAll</strong> and <strong>LoadArrayBy...</strong> methods take in an optional
	<strong>QCubed Query Clauses</strong> parameter, where you can specify an unlimited number of <strong>QQClause</strong>
	objects, including (but not limited) functionality that handles <strong>ORDER BY</strong>, <strong>LIMIT</strong>
	and <strong>Object Expansion</strong>.  We will
	discuss <strong>Object Expansion</strong> in the examples that deal with <strong>Late Binding</strong>
	and <strong>Early Binding</strong>.  But for this example, we'll focus on using
	using <strong>QQ::OrderBy()</strong> and <strong>QQ::LimitInfo()</strong> to manipulate how the results come out of the database.</p>

	<p><strong>OrderBy</strong> and <strong>LimitInfo</strong> are actually really straightforward to use.  Order By takes
	in any number of QCubed Query Node columns, followed by an optional boolean (to specify ascending/descending),
	which will be used in a SQL ORDER BY clause in the SELECT statement.  So you can simply say
	<strong>QQ::OrderBy(QQN::Person()->LastName)</strong> to sort all the Person objects by last name.</p>

	<p><strong>LimitInfo</strong> takes in a Maximum Row Count, followed by an optional offset.
	So if you specified "10, 4", the result set would contain at most 10 rows, starting with row #5
	(the offset is based on a 0 index).
	Depending on which database platform you are on, the database adapter will appropriately handle
	how to deal with this Limit information.</p>

	<p>As a final reminder, note that you can use either, both, more or none of these optional <strong>QQClause</strong>
	parameters whenever you make your <strong>LoadAll</strong> or <strong>LoadArrayBy</strong> calls.</p>
</div>

<div id="demoZone">
	<h2>List All Persons, Ordered by Last Name then First Name</h2>

<?php
	// Load the Person array, sorted
	$objPersonArray = Person::LoadAll(QQ::Clause(
		QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)
	));
	foreach ($objPersonArray as $objPerson) {
		_p($objPerson->LastName . ', ' . $objPerson->FirstName . ' (ID #' . $objPerson->Id . ')');
		_p('<br/>', false);
	}
?>


	<h2>List Five People, Start with the Third from the Top, Ordered by Last Name then First Name</h2>
<?php
	// Load the Person array, sorted and limited
	// Note that because we want to start with row #3, we need to define "2" as the offset
	$objPersonArray = Person::LoadAll(QQ::Clause(
		QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName),
		QQ::LimitInfo(5, 2)
	));
	foreach ($objPersonArray as $objPerson) {
		_p($objPerson->LastName . ', ' . $objPerson->FirstName . ' (ID #' . $objPerson->Id . ')');
		_p('<br/>', false);
	}
?>
</div>

<?php require('../includes/footer.inc.php'); ?>