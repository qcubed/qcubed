<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div id="instructions">
		<h1>Introduction to QQuery</h1>
		
		<p>The querying logic behind all the Load methods in your ORM classes is powered by <strong>QQuery</strong>,
		or <strong>QQ</strong> for short.  Put simply, <strong>QQ</strong> is a completely object oriented API to perform any SELECT-based
		query on your database to return any result or hierarchy of your ORM objects.</p>

		<p>While the ORM classes utilize basic, straightforward SELECT statements in its Load methods,
		<strong>QQ</strong> is capable of much more complex queries. </p>

		<p>At its core, any <strong>QQ</strong> query will return a collection of objects of the same type (e.g. a collection of
		Person objects).  But the power of <strong>QQ</strong> is that we can branch beyond this core collection by bringing in
		any related objects, performing any SQL-based clause (including WHERE, ORDER BY, JOIN, aggregations, etc.) on both
		the core set of Person rows <i>and</i> any of these related objects rows.</p>

		<p>Every code generated class in your ORM will have the three following static <strong>QQuery</strong> methods:</p>
		<ul>
			<li><strong>QuerySingle</strong>: to perform a QCubed Query to return just a single object (typically for queries where you expect only one row)</li>
			<li><strong>QueryArray</strong>: to perform a QCubed Query to return just an array of objects</li>
			<li><strong>QueryCount</strong>: to perform a QCubed Query to return an integer of the count of rows (e.g. "COUNT (*)")</li>
		</ul>

		<p>All three QCubed Query methods expect two parameters, a <strong>QQ Condition</strong> and an optional set of <strong>QQ Clauses</strong>.
		<strong>QQ Conditions</strong> are typically conditions that you would expect to find in a SQL WHERE clause, including <strong>Equal</strong>,
		<strong>GreaterThan</strong>, <strong>IsNotNull</strong>, etc.  <strong>QQ Clauses</strong> are additional clauses that you could add to alter
		your SQL statement, including methods to perform SQL equivalents of JOIN, DISTINCT, GROUP BY, ORDER BY and LIMIT.</p>

		<p>And finally, both <strong>QQ Condition</strong> and <strong>QQ Clause</strong> objects will expect <strong>QQ Node</strong> parameters.  <strong>QQ Nodes</strong> can
		either be tables, individual columns within the tables, or even association tables.  <strong>QQ Node</strong> classes for your
		entire ORM is code generated for you.</p>
		
		<p>The next few examples will examine all three major constructs (<strong>QQ Node</strong>, <strong>QQ Condition</strong> and <strong>QQ Clause</strong>) in greater
		detail.</p>
		
		<p>And as a final note, notice that <strong>QCubed Query</strong> doesn't have any construct to describe what would normally be your SELECT clause.
		This is because we take advantage of the code generation process to allow <strong>QQuery</strong> to automagically "know" which
		fields that should be SELECT-ed based on the query, conditions and clauses you are performing.  This will allow a lot
		greater flexbility in your data model.  Because the framework is now taking care of column names, etc., instead of the
		developer needing to manually hard code it, you can make changes to columns in your tables without needing to rewrite
		your <strong>QQuery</strong> calls.</p>
	</div>

<div id="demoZone">
	<h2>QuerySingle Example</h2>
	<p>
<?php
	$objPerson = Person::QuerySingle(
		QQ::Equal(QQN::Person()->Id, 1)
	);

	// Notice that QuerySingle returned just a single Person object
	_p($objPerson->FirstName . ' ' . $objPerson->LastName);
?>
	</p>
	<h2>QueryArray Example</h2>
	<ul>
<?php
	$objPersonArray = Person::QueryArray(
		QQ::In(QQN::Person()->Id, array(5, 6, 8))
	);

	// Notice that QueryArray returns an array of Person objects... this will
	// be true even if the result set only yields 1 row.=
	foreach ($objPersonArray as $objPerson) {
		_p("<li>".$objPerson->FirstName . ' ' . $objPerson->LastName."</li>", false);
	}
?>
	</ul>
	<h2>QueryCount Example</h2>
	<p>
<?php
	$intCount = Person::QueryCount(
		QQ::In(QQN::Person()->Id, array(5, 6, 8))
	);

	// Notice that QueryCount returns an integer
	_p($intCount . ' rows.');
?>
	</p>
</div>

<?php require('../includes/footer.inc.php'); ?>