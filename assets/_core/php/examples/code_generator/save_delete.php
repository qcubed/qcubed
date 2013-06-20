<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Saving and Deleting Objects</h1>
	<p>The C, U and D in CRUD is handled by the code generated <strong>Save</strong> and <strong>Delete</strong> methods in
		every object.</p>

	<p><strong>Delete</strong> should hopefully be self-explanatory.  <strong>Save</strong> will either call a SQL INSERT
		or a SQL UPDATE, depending on whether the object was created brand new or if it was restored via
		a Load method of some kind.</p>

	<p>Note that you can also call <strong>Save</strong> passing in true for the optional <strong>$blnForceInsert</strong>
		parameter.  If you pass in true, then it will force the <strong>Save</strong> method to call SQL INSERT.
		Note that depending on how your table is set up (e.g. if you have certain columns marked as
		UNIQUE), forcing the INSERT <em>may</em> throw an exception.</p>
</div>

<div id="demoZone">
	<h2>Load a Person Object, Modify It, and Save</h2>
<?php
	// Let's load a Person object -- let's select the Person with ID #3
	$objPerson = Person::Load(3);
?>
	<h3><em>Before the Save</em></h3>
	<ul>
		<li>Person ID: <?php _p($objPerson->Id); ?></li>
		<li>First Name: <?php _p($objPerson->FirstName); ?></li>
		<li>Last Name: <?php _p($objPerson->LastName); ?></li>
	</ul>
<?php
	// Update the field and save
	$objPerson->FirstName = 'FooBar';
	$objPerson->Save();

	// Restore the same person object just to make sure we
	// have a clean object from the database
	$objPerson = Person::Load(3);
?>
	<h3><em>After the Save</em></h3>
	<ul>
		<li>Person ID: <?php _p($objPerson->Id); ?></li>
		<li>First Name: <?php _p($objPerson->FirstName); ?></li>
		<li>Last Name: <?php _p($objPerson->LastName); ?></li>
	</ul>

<?php
	// Let's clean up -- once again update the field and save
	$objPerson->FirstName = 'Ben';
	$objPerson->Save();
?>
	<h3><em>Cleaning Up</em></h3>
	<ul>
		<li>Person ID: <?php _p($objPerson->Id); ?></li>
		<li>First Name: <?php _p($objPerson->FirstName); ?></li>
		<li>Last Name: <?php _p($objPerson->LastName); ?></li>
	</ul>
</div>

<?php require('../includes/footer.inc.php'); ?>