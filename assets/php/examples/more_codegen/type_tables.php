<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Using Type Tables</h1>

	<p><strong>Type Tables</strong> are essentially built-in enumerated types for QCubed.  So 
	while only some database vendors (e.g. MySQL) offer support for	a formal ENUM column type, 
	QCubed provides support for the enumerated column types for <em>all</em>
	database vendors through the use of <strong>Type Tables</strong>.</p>

	<p>Similar to <strong>Association Tables</strong>, the code generator will look for a user-defined suffix
	(the default is "_type") to mark certain tables as <strong>Type Tables</strong>. <strong>Type Tables</strong> must 
	have at least 2 columns, a primary key ID and a unique name (named "id" and "name"). </p>

	<p>A <strong>Type</strong> object will be generated from the table, but note that this <strong>Type</strong> object will
	<em>not</em> have the CRUD functionality generated for it.  Instead, constants will be defined,
	one for each row in the <strong>Type Table</strong>.</p>

	<p>Because this is supposed to be an enumerated data type of some kind, the idea is that rows
	should <em>not</em> be added by the application, but instead, added by developers.  So whenever
	a new enumerated value needs to be added to this <strong>Type</strong> object, you should manually do the SQL INSERT
	into this <strong>Type Table</strong>, and then re-code generate.</p>

	<p>In our example below, we show the contents of <strong>ProjectStatusType</strong>.  Note how the <strong>Project</strong>
	class has a relationship with <strong>ProjectStatusType</strong>, and how we can display a <strong>Project</strong>
	object's status using the static methods of <strong>ProjectStatusType</strong>.</p>

	<p>You can, if you want, have more than two columns in a type table; QCubed will auto-generate methods
	based on the names of the columns you defined. In the example below, the Project Status Types table
	has the following columns: "id", "name" (unique), "description", and "guidelines". QCubed code 
	generator will create methods such as <strong>ProjectStatusType::ToDescription()</strong> and <strong>ProjectStatusType::ToGuidelines()</strong>
	for you.</p>		

	<p>You can also use an association table with a type table to create a many-to-many relationship with a type.
	This is similar to the SET type in MySQL, but is database independent.</p>

</div>

<div id="demoZone">
	<h2>List All the Project Status Types (Names and Descriptions)</h2>
<?php
	// All Enumerated Types should go from 1 to "MaxId"
	for ($intIndex = 1; $intIndex <= ProjectStatusType::MaxId; $intIndex++) {
		// We use the Code Generated ToString and ToDescription to output a constant's value
		_p(ProjectStatusType::ToString($intIndex) . ' - ' . ProjectStatusType::ToDescription($intIndex));

		// We can even use the Enums as PHP constants
		if ($intIndex == ProjectStatusType::Cancelled)
			_p(' (sad!)');

		_p('<br/>', false);
	}
?>
	<h2>Load a Project Object and View Its Project Status</h2>
<?php
	// Let's load a Project object -- let's select the Project with ID #3
	$objProject = Project::Load(3);
?>
	Project ID: <?php _p($objProject->Id); ?><br/>
	Project Name: <?php _p($objProject->Name); ?><br/>
	Project Status: <?php _p(ProjectStatusType::ToString($objProject->ProjectStatusTypeId)); ?>

	<h2>List the employees and their options.</h2>
<?php
	// Load all the people and expand the type array associated with the person table
	$objClauses[] = QQ::ExpandAsArray (QQN::Person()->PersonType);
	$objPeople = Person::LoadAll($objClauses);
	
	foreach ($objPeople as $objPerson) {
		_p ($objPerson->FirstName . ' ' . $objPerson->LastName . ': ');
		$intTypeArray = $objPerson->_PersonTypeArray;
		$strTypeArray = array();
		foreach ($intTypeArray as $intType) {
			$strTypeArray[] = PersonType::ToString ($intType);
		}
		_p (implode(', ', $strTypeArray));	
		_p('<br/>', false);
	}
?>
</div>


<?php require('../includes/footer.inc.php'); ?>
