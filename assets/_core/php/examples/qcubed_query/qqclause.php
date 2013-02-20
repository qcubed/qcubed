<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>QCubed Query Clauses</h1>

	<p>All <strong>QCubed Query</strong> method calls take in an optional set of <strong>QQ Clauses</strong>. <strong>QQ Clauses</strong> allow you
	alter the result set by performing the equivalents of most of your major SQL clauses, including JOIN, ORDER BY,
	GROUP BY and DISTINCT.</p>

	<p>The following is the list of QQ Clause classes and what parameters they take:</p>
	<ul>
		<li>QQ::OrderBy(array/list of QQNodes or QQConditions)</li>
		<li>QQ::GroupBy(array/list of QQNodes)</li>
		<li>QQ::Having(QQSubSqlNode)</li>
		<li>QQ::Count(QQNode, string)</li>
		<li>QQ::Minimum(QQNode, string)</li>
		<li>QQ::Maximum(QQNode, string)</li>
		<li>QQ::Average(QQNode, string)</li>
		<li>QQ::Sum(QQNode, string)</li>
		<li>QQ::Expand(QQNode)</li>
		<li>QQ::ExpandAsArray(QQNode for an Association Table)</li>
		<li>QQ::LimitInfo(integer[, integer = 0])</li>
		<li>QQ::Distinct()</li>
	</ul>

	<p><strong>OrderBy</strong> and <strong>GroupBy</strong> follow the conventions of SQL ORDER BY and GROUP BY.  It takes in a
	list of one or more <strong>QQ Column Nodes</strong>. This list could be a parameterized list and/or an array.</p>

	<p>Specifically for <strong>OrderBy</strong>, to specify a <strong>QQ Node</strong> that you wish to order by in descending
	order, add a "false" after the QQ Node.  So for example, <strong>QQ::OrderBy(QQN::Person()->LastName, false,
	QQN::Person()->FirstName)</strong> will do the SQL equivalent of "ORDER BY last_name DESC, first_name ASC".</p>

	<p><strong>Count</strong>, <strong>Minimum</strong>, <strong>Maximum </strong>, <strong>Average</strong> and <strong>Sum</strong> are aggregation-related clauses, and
	only work when <strong>GroupBy</strong> is specified.  These methods take in an attribute name, which
	can then be restored using <strong>GetVirtualAttribute()</strong> on the object.</p>

	<p><strong>Having</strong> adds a SQL Having clause, which allows you to filter the results of your query based
	on the results of the aggregation-related functions. <strong>Having</strong> requires a Subquery, which is a SQL code
	snippet you create to specify the criteria to filter on. (See the Subquery section
	later in this tutorial for more information on those).</p>

	<p><strong>Expand</strong> and <strong>ExapandAsArray</strong> deals with Object Expansion / Early Binding.  More on this
	can be seen in the <a href="../more_codegen/early_bind.php">Early Binding of Related Objects example</a>.</p>

	<p><strong>LimitInfo</strong> will limit the result set.  The first integer is the maximum number of rows
	you wish to limit the query to.  The <em>optional</em> second integer is the offset (if any).</p>

	<p>And finally, <strong>Distinct</strong> will cause the query to be called with SELECT DISTINCT.</p>

	<p>All clauses must be wrapped around a single <strong>QQ::Clause()</strong> call, which takes in any
	number of clause classes for your query.</p>
</div>

<div id="demoZone">
	<h2>Select all People, Ordered by Last Name then First Name</h2>
	<p><em>Note now QQ::OrderBy gets two parameters here</em></p>
	<ul>
<?php
	$objPersonArray = Person::QueryArray(
		QQ::All(),
		QQ::Clause(
			QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)
		)
	);

	foreach ($objPersonArray as $objPerson) {
		_p('<li>'.$objPerson->FirstName . ' ' . $objPerson->LastName.'</li>', false);
	}
?>
	</ul>
	<h2>Select all People, Ordered by Last Name then First Name, Limited to the first 4 results</h2>
	<p><em>Combining QQ::OrderBy and QQ::LimitInfo</em></p>
	<ul>
<?php
	$objPersonArray = Person::QueryArray(
		QQ::All(),
		QQ::Clause(
			QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName),
			QQ::LimitInfo(4)
		)
	);

	foreach ($objPersonArray as $objPerson) {
		_p('<li>'.$objPerson->FirstName . ' ' . $objPerson->LastName.'</li>', false);
	}
?>
	</ul>
	<h2>Select all People, those with last name Smith first, then ordered by First Name</h2>
	<p><em>Using a QQ::Condition as an ORDER BY clause</em></p>
	<ul>
<?php
	$objPersonArray = Person::QueryArray(
		QQ::All(),
		QQ::Clause(
			QQ::OrderBy(QQ::NotEqual(QQN::Person()->LastName, 'Smith'), QQN::Person()->FirstName)
		)
	);

	foreach ($objPersonArray as $objPerson) {
		_p('<li>'.$objPerson->FirstName . ' ' . $objPerson->LastName.'</li>', false);
	}
?>
	</ul>
	<h2>Select all Projects and the Count of Team Members (if applicable)</h2>
	<p><em>GROUP BY in action</em></p>
	<ul>
<?php
	$objProjectArray = Project::QueryArray(
		QQ::All(),
		QQ::Clause(
			QQ::GroupBy(QQN::Project()->Id),
			QQ::Count(QQN::Project()->PersonAsTeamMember->PersonId, 'team_member_count')
		)
	);

	foreach ($objProjectArray as $objProject) {
		_p('<li>'.$objProject->Name . ' (' . $objProject->GetVirtualAttribute('team_member_count') . ' team members)'.'</li>', false);
	}
?>
	</ul>

	<h2>Select all Projects with more than 5 team members. </h2>
	<p><em>Using a Having clause to further limit group functions</em></p>
	<ul>
<?php
	$objProjectArray = Project::QueryArray(
		QQ::All(),
		QQ::Clause(
			QQ::GroupBy(QQN::Project()->Id),
			QQ::Count(QQN::Project()->PersonAsTeamMember->PersonId, 'team_member_count'),
			QQ::Having (QQ::SubSql('COUNT({1}) > 5', QQN::Project()->PersonAsTeamMember->PersonId))
		)
	);

	foreach ($objProjectArray as $objProject) {
		_p($objProject->Name . ' (' . $objProject->GetVirtualAttribute('team_member_count') . ' team members)');
		_p('<br/>', false);
	}
?>
	</ul>
</div>


<?php require('../includes/footer.inc.php'); ?>
