<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div id="instructions">
		<h1>SQL Queries in QCubed</h1>
		Although the QCubed can generate the SQL query code for most of your application, you will undoubtedly
		need to write your own custom queries, to either perform more refined Load methods, execute searches,
		generate reports, etc.<br/><br/>
		
		The framework offers multiple ways for performing your own custom SQL queries, from completely free
		form queries using just the database adapter, itself, to completely structured object-oriented queries
		using <strong>QCubed Query</strong>, or <strong>QQ</strong> for short.

		In general, there are three main ways to perform queries, with pros and cons for each:
		<ul><li><p><strong>Ad Hoc Queries</strong>: Completely custom, ad hoc queries can be executed by accessing the
		database adapter, itself.  The advantage of this is that you have complete, total free form control
		over how you want the query to run.  Moreover, you can also run "NonQuery" commands like UPDATE and DELETE.
		The disadvantage is that because the queries are completely free form, there is no structure, and the Qcubed
		generated ORM cannot take advantage or use your query results at all.</p></li>

		<li><p><strong>Custom Load Queries</strong>: These custom SQL SELECT statements do require a bit more structure, but by
		adhering to a structure/form that QCubed expects, you can utilize code-generated <strong>InstantiateDbRow</strong> and
		<strong>InstantiateDbResult</strong> methods to convert your query results into instantiated data objects.  You still get
		the benefit of writing, more or less, completely custom SQL SELECT statements, but you now have the added benefit
		of taking advantage of your code generated ORM.  The drawback is that if/when you make changes to your data model,
		you <i>may</i> need to go back and revisit your custom-written SQL code to ensure that the appropriate fields
		are being selected to match what the QCubed ORM is expecting.</p></li>
		
		<li><p><strong>QCubed Query</strong>: This is a fully structure, object-oriented approach to performing SQL SELECT queries,
		without needing to write a single line of SQL code.  Utilizing code generated code and per-table-specific QCubed Query
		nodes, the <strong>QQ</strong> API offers almost the full set of functionality that free form <strong>Custom Load Queries</strong> provide,
		but with the added advantage that whenever you make changes to your data model and re-code generate, you do not have
		to worry about updating any hard-coded SQL statements in your code.  Of course, the drawback is that <strong>QQ</strong> is
		a new methodology for performing queries, so there will be a learning curve.</p></li>
		</ul>
		
		The examples below provide a quick sample of each of these three query types.  And then the following examples will
		illustrate <strong>QCubed Query</strong> in much greater detail.
		<br/><br/>
		
		As a final note, all the examples here are coded below on the page, itself.  However, it is always a good practice
		to have query code like this written within the classes, themselves.  Especially for any Load-related methods,
		QCubed tries to be consistent in following the Singleton design pattern with static "LoadBy" and "LoadArrayBy" methods,
		so the SELECT queries for any table can reside in that table's ORM class, itself.  For more on this, be sure to
		view the code generated commented out sample code in your custom ORM subclasses in <strong>/includes/data_classes</strong>.  See
		<a href="../more_codegen/custom_load.php">Customized Load Methods</a> in Section 2 for more information.
	</div>

<div id="demoZone">
	<h2>Ad Hoc Query: Selecting the Projects, their managers and team member count</h2>
<?php
	// To perform an ad hoc query, simply write out the SQL you want to perform.
	$strQuery = 
		"SELECT
			project.name AS project_name,
			manager.first_name AS manager_first,
			manager.last_name AS manager_last,
			(
				SELECT
					COUNT(*)
				FROM
					team_member_project_assn
				WHERE
					project_id = project.id
			) AS team_member_count
		FROM
			project AS project,
			person AS manager
		WHERE
			project.manager_person_id = manager.id
		ORDER BY
			project.name";

	// Call on QApplication to get to the instantiated/active Database Adapter that you want to query
	// Be sure to specify the database index (as you defined in configuration.inc.php)
	// For purposes of this example, we're assuming that the "Examples" database connection string is defined
	// in the DB_CONNECTION_1 constant.
	$objDatabase = QApplication::$Database[1];

	// Perform the Query
	$objDbResult = $objDatabase->Query($strQuery);

	// Iterate through the Database Result using ->FetchRow() or ->FetchArray(), as you would if
	// you used the a database connector, directly.
	while ($mixRow = $objDbResult->FetchArray()) {
		_p(sprintf('%s, managed by %s %s (with %s team members)',
			$mixRow['project_name'], $mixRow['manager_first'], $mixRow['manager_last'], $mixRow['team_member_count']));
		_p('<br/>', false);
	}
?>
	<h2>Ad Hoc NonQuery: Updating Project #3's budget to 2500</h2>
<?php
	// Performing nonqueries like UPDATE, INSERT and DELETE statements can be done in a very similar way
	$strQuery = 'UPDATE project SET budget=2500 WHERE id=3';

	// Use that same database connection to perform a "NonQuery"
	$objDatabase->NonQuery($strQuery);
?>
	<p>Updated.  (Use <strong>View Source</strong> above to see the code for this)</p>

	<h3>Custom Load Query: Select all Projects with Budgets over $5000, ordered by Descending Budget</h3>
<?php
	// Custom Load Queries must have a resultset that returns all the fields of the
	// table which corresponds to the ORM class you want to instantiate
	// Note that because the InstantiateDb* methods in your ORM classes are code generated
	// it is actually safe to use "SELECT *" for your Custom Load Queries.
	$strQuery = 'SELECT project.* FROM project WHERE budget > 5000 ORDER BY budget DESC';
	
	// perform the query
	$objDbResult = $objDatabase->Query($strQuery);
	
	// Use the Project::InstantiateDbResult on the $objDbResult to get an array of Project objects
	$objProjectArray = Project::InstantiateDbResult($objDbResult);

	// Iterate through the Project Array as you would any other ORM object
	foreach ($objProjectArray as $objProject) {
		_p($objProject->Name . ' has a budget of $' . $objProject->Budget);
		_p('<br/>', false);
	}
?>
	<h2>QCubed Query: Select all Projects which have a Budget over $5000 and under $10000, ordered by Descending Budget</h2>
<?php
	// Perform the Query using Project::QueryArray, which will return an array of Project objects
	// given a QQ Condition, and any optional QQ Clauses.
	$objProjectArray = Project::QueryArray(
		QQ::AndCondition(
			QQ::GreaterThan(QQN::Project()->Budget, 5000),
			QQ::LessThan(QQN::Project()->Budget, 10000)
		),
		QQ::Clause(
			QQ::OrderBy(QQN::Project()->Budget, false)
		)
	);

	// Iterate through the Project Array like last time
	foreach ($objProjectArray as $objProject) {
		_p($objProject->Name . ' has a budget of $' . $objProject->Budget);
		_p('<br/>', false);
	}
?>
</div>

<?php require('../includes/footer.inc.php'); ?>