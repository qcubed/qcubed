<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Picking database columns for QQuery</h1>
	
	<p>By default <strong>QQuery</strong> selects all the columns from the table and thus populates all the properties of
	the resulting objects.
	Normally, this is the right thing to do - the most expensive part of a typical query is hitting the database and
	performing the query;
	once the query is performed, fetching as much data as possible is the most efficient behaviour.</p>
	
	<p>However, when some tables have a large amount of columns, or some <em>LOB</em> columns, this may become expensive,
	both in terms of the traffic generated between application and database, and in terms of the memory footprint of the
	application.</p>
	
	<p><strong>QQ::Select</strong> solves this problem by allowing to pick only the desired subset of columns to fetch from
	the database.</p>
	
	<p>QQ::Select can be passed as a clause to any query method.
	As shown in the second example below, it can also be passed as an argument to QQ::Expand()
	to pick specific columns to fetch for the object to be expanded.</p>
	
	<p>Note, that when QQ::Select is used, the primary keys are always automatically added to the select list.
	This is illustrated by the first example below, where QQN::Person()->Id is not part of the QQ::Select list,
	but $objPerson->Id is populated and used afterwards.</p>
	
	<p>One QQ::Select() can be used to select multiple columns, as shown in the third example below:</p>
	<pre><code>QQ::Select(QQN::Person()->Address->Street, QQN::Person()->Address->City)</code></pre>

	<p>The same example also shows the use of QQ::Select in QQ::ExpandAsArray.</p>

	<p>The examples also have some <code>assert(is_null(...))</code> calls, to show that the data for not selected columns is indeed not loaded.
	You can also verify this by examining the performed queries in the profiling details.</p>

	<p>You may also notice, that many times the QQ::Select clause is passed as the last argument to the query method.
	Even though this is not ideal (since in SQL the select clause is the first in a statement),
	it was necessary for backward compatibility reasons with older versions of QCubed.</p>
</div>

<div id="demoZone">
	<h2>Get <em>the first names</em> of all the people</h2>
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

	<h2>Get the last names of all the people, and the amount spent on the project they manage (if any), for Projects that
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
	<h3>Projects and Addresses for each Person</h3>
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