<?php require('../../includes/prepend.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div class="instructions">
		<div class="instruction_title">Conditional Joins with QQ::Expand()</div>
	
		Sometimes, you find yourself in a situation when you want to issue a 
		query for ALL items in a given table, and only some information in
		another table.<br /><br />
		
		For example, let's say you have a list of persons, and a related list of
		logins. Only some of the persons have logins; some of the logins are
		disabled. Your task is to show the name of every person, and next to it,
		show their login information, but only if their login is actually enabled.
		<br /><br />
		
		Before you found out about conditional joins, you had several options:
		<ol>
		<li>Do a LEFT JOIN on the <b>login</b> table; write a database-specific,
			somewhat convoluted IF statement that might look like
			<b>IF(login.is_enabled = 1, login.username, "")</b>. But what if you
			want to show more than just that one column? Write an IF statement for
			every single output column... Ehh. Plus, not portable across databases.
		</li>
		<li>Get a list of all <b>persons</b>, then also get a list of all
			<b>logins</b>, then merge the two using PHP. Works with QQuery, but
			incurs an overhead of extra processing. 
		</ol>
		
		As you'd expect, there's a better way. Introducing conditional joins: when
		you use <b>QQ::Expand</b>, you can specify conditions on the table with
		which you want to join, and get only those values that you care about.
		Remember that a <b>QQ::Expand</b> is always a
		<a href="http://en.wikipedia.org/wiki/Join_(SQL)#Left_outer_join">left
		join</a> - so if a row of a table with which you are joining does not
		have a matching record, the left side of your join will still be there,
		and the right side will contain nulls. 
	</div>

	<h3>Names of every person, plus usernames for each person if their Login is active</h3>
<?php

 QApplication::$Database[1]->EnableProfiling();
	$objPersonArray = Person::QueryArray(
		// We want *every single person*
		QQ::All(),
		
		
		QQ::Expand(
			// We also want the login information for each person
			QQN::Person()->Login,
		
			// But only the login information for folks that have
			// their logins ON; for everyone else, just the Person metadata
			QQ::Equal(QQN::Person()->Login->IsEnabled, 1)
		)
	);

	foreach ($objPersonArray as $objPerson){
		_p($objPerson->FirstName . ' ' . $objPerson->LastName . ': ');
		if ($objPerson->Login) {
			_p("<b>" . $objPerson->Login->Username . "</b>", false);
		} else {
			_p("none");
		}
		_p('<br/>', false);
	}
	
_p('<br/>', false);

QApplication::$Database[1]->OutputProfiling();    
?>

<?php require('../includes/footer.inc.php'); ?>