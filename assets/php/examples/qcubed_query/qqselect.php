<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Picking database columns for QQuery</h1>
	
	<p>Most of the time, <strong>QQuery</strong> selects all the columns from the table and thus populates all the properties of
	the resulting objects.
	Normally, this is the right thing to do - the most expensive part of a typical query is hitting the database and
	performing the query;</p>
	
	<p>However, when some tables have a large number of columns, or some columns that contain large objects (BLOB, TEXT, etc.), this may become expensive,
	both in terms of the traffic generated between application and database, and in terms of the memory footprint of the
	application.</p>

	<p>Also, more and more databases are preventing you from creating SQL queries that might produce ambiguous results when
		using aggregate clauses. For example, if you create a query that groups employees by last name, and counts how many
		employees have each last name, but then also tries to select a first name, if there are mulitple employees with the same
		last name, the database will be confused and won't know which first name to show. Most databases will error in this
		situation. However, it would be perfectly fine to select a last name, because each group has the same last name.
		You need a way to specify particular database fields to select.</p>
	
	<p><strong>QQ::Select</strong> solves this problem by allowing you to pick particular columns to fetch from
	the database.</p>
	
	<p>QQ::Select can be passed as a clause to any query method.
	As shown in the second example below, it can also be passed as an argument to QQ::Expand()
	to pick specific columns to fetch for the object to be expanded.</p>
	
	<p>Note, that when QQ::Select is used, by default the primary keys are automatically added to the select list.
	This is illustrated by the first example below, where QQN::Person()->Id is not part of the QQ::Select list,
	but $objPerson->Id is populated and used afterwards. This behaviour can be changed by using using the <strong>SetSkipPrimaryKey()</strong>
	method of <strong>QQSelect</strong>, as shown in the second example. This is typically useful for simple queries with the <em>distict</em>
	clause, where the presence of the primary keys would prevent <em>distinct</em> from having the desired effect</p>
	
	<p>One QQ::Select() can be used to select multiple columns, as shown in the fourth example below:</p>
	<pre><code>QQ::Select(QQN::Person()->Address->Street, QQN::Person()->Address->City)</code></pre>

	<p>The same example also shows the use of QQ::Select in QQ::ExpandAsArray.</p>

	<p>The examples also have some <code>assert(is_null(...))</code> calls, to show that the data for not selected columns is indeed not loaded.
	You can also verify this by examining the performed queries in the profiling details.</p>

	<p>You may also notice, that many times the QQ::Select clause is passed as the last argument to the query method.
	Even though this is not ideal (since in SQL the select clause is the first in a statement),
	it was necessary for backward compatibility reasons with older versions of QCubed.</p>
</div>

<div id="demoZone">
	<h2>1. Get <em>the first names</em> of all the people</h2>
	<ul>
<?php
QApplication::$Database[1]->EnableProfiling();
	$objPersonArray = Person::LoadAll(QQ::Select(QQN::Person()->FirstName));

	foreach ($objPersonArray as $objPerson) {
		printf('<li>%s %s</li>',
			   QApplication::HtmlEntities($objPerson->Id),
			   QApplication::HtmlEntities($objPerson->FirstName));
		assert(is_null($objPerson->LastName));
	}
?>
	</ul>

	<h2>2. Get all the distinct <em>first names</em> of all the people</h2>
	<ul>
<?php
QApplication::$Database[1]->EnableProfiling();
	$objSelect = QQ::Select(QQN::Person()->FirstName);
	$objSelect->SetSkipPrimaryKey(true);
	$objPersonArray = Person::LoadAll(QQ::Clause($objSelect, QQ::Distinct()));

	foreach ($objPersonArray as $objPerson) {
		printf('<li>%s</li>',
			   QApplication::HtmlEntities($objPerson->FirstName));
		assert(is_null($objPerson->Id));
		assert(is_null($objPerson->LastName));
	}
?>
	</ul>

	<h2>3. Get the last names of all the people, and the amount spent on the project they manage (if any), for Projects that
	have 'ACME' or 'HR' in it. Sort the result by Last Name, then First Name</h2>
	<p><i>Notice how some people may be listed twice, if they manage more than one project.</i></p>
	<ul>
<?php
	$objPersonArray = Person::QueryArray(
		QQ::OrCondition(
			QQ::Like(QQN::Person()->ProjectAsManager->Name, '%ACME%'),
			QQ::Like(QQN::Person()->ProjectAsManager->Name, '%HR%')
		),
		// Let's expand on the Project, itself
		QQ::Clause(
			QQ::Select(QQN::Person()->LastName),
			QQ::Expand(QQN::Person()->ProjectAsManager, null, QQ::Select(QQN::Person()->ProjectAsManager->Spent)),
			QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)
		)
	);

	foreach ($objPersonArray as $objPerson) {
		printf("<li>%s's project spent \$%0.2f</li>",
			   QApplication::HtmlEntities($objPerson->LastName),
			   QApplication::HtmlEntities($objPerson->_ProjectAsManager->Spent));
	}
?>
	</ul>
	<h2>4. Projects and Addresses for each Person</h2>
	<ul>
<?php
	$people = Person::LoadAll(
		QQ::Clause(
			QQ::Select(QQN::Person()->FirstName),
			QQ::ExpandAsArray(QQN::Person()->Address, QQ::Select(QQN::Person()->Address->Street, QQN::Person()->Address->City)),
			QQ::ExpandAsArray(QQN::Person()->ProjectAsManager, QQ::Select(QQN::Person()->ProjectAsManager->StartDate)),
			QQ::ExpandAsArray(QQN::Person()->ProjectAsManager->Milestone, QQ::Select(QQN::Person()->ProjectAsManager->Milestone->Name))
		)
	);

	foreach ($people as $person) {
		echo "<li><b>" . $person->FirstName . "</b><br />";
		assert(is_null($person->LastName));
		echo "Addresses: ";
		if (sizeof($person->_AddressArray) == 0) {
			echo "none";
		} else {
			foreach ($person->_AddressArray as $address) {
				echo $address->Street . ', ' . $address->City . "; ";
				assert(is_null($address->PersonId));
			}
		}
		echo "<br />";

		echo "Projects where this person is a project manager: ";
		if (sizeof($person->_ProjectAsManagerArray) == 0) {
			echo "none<br />";
		} else {
			foreach($person->_ProjectAsManagerArray as $project) {
				assert(is_null($project->Name));
				echo "started on " . $project->StartDate . " (milestones: ";

				if (sizeof($project->_MilestoneArray) == 0) {
					echo "none";
				} else {
					foreach ($project->_MilestoneArray as $milestone) {
						echo $milestone->Name . "; ";
					}
				}
				echo ")<br />";
			}
		}
		echo "</li>";
	}
?>
	</ul>
	<p><?php QApplication::$Database[1]->OutputProfiling(); ?></p>
</div>

<?php require('../includes/footer.inc.php'); ?>