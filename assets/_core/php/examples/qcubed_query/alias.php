<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div class="instructions">
		<h1 class="instruction_title">SQL Aliases for QQuery</h1>
		<p>Up until now, we've only described doing simple joins with QQuery, where each
		table is only needed for a single related record. But what about when you need 
		to check on multiple entries in the same joined table. In standard SQL, you 
		would join the table a second time, giving it a new name. And that's exactly 
		how you do it in QQuery as well, using <b>QQ::Alias</b>.</p>
		
		<p>In the example 1 below, we need to find who belongs to both the ACME Website 
		Redesign and the State College HR System projects. We do this simply by joining
		the membership association table twice, each time with a different alias. This
		allows us to create a condition that says that one associated project has an
		ID of 1, while a <i>different</i> associated project also has an ID of 2.</p>
		
		<p>In a slightly more complex example 2 below, we are looking for all projects that
		are associated with two other projects (each is specified by name). We use the
		same technique with <b>QQ::Alias()</b> as in example 1, except that we now
		mix it in with relationships expanded to other tables. 
	</div>

	<h3>Example 1: Project members whose are in both project 1 and 2</h3>
<?php

QApplication::$Database[1]->EnableProfiling();

$objPersonArray = Person::QueryArray(
	QQ::AndCondition(
			QQ::Equal(QQ::Alias(QQN::Person()->ProjectAsTeamMember, 'pm1')->ProjectId, 1),
			QQ::Equal(QQ::Alias(QQN::Person()->ProjectAsTeamMember, 'pm2')->ProjectId, 2)
		)
	);

foreach ($objPersonArray as $objPerson){
	_p($objPerson->FirstName . ' ' . $objPerson->LastName);
	_p('<br/>', false);
}
?>

<br />
<h3>Example 2: Projects that are related to both 'Blueman Industrial Site Architecture' and 'ACME Payment System' projects</h3>
<?php 
$objProjectArray = Project::QueryArray(
	QQ::AndCondition(
			QQ::Equal(QQ::Alias(QQN::Project()->ProjectAsRelated, 'related1')->Project->Name, 'Blueman Industrial Site Architecture'),
			QQ::Equal(QQ::Alias(QQN::Project()->ProjectAsRelated, 'related2')->Project->Name, 'ACME Payment System')
		)
	);

foreach ($objProjectArray as $objProject){
	_p($objProject->Name . " (" . $objProject->Description . ")");
	_p('<br/>', false);
}
echo "<br/><br/>";
QApplication::$Database[1]->OutputProfiling();    

?>

<?php require('../includes/footer.inc.php'); ?>
