<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Using Association Tables</h1>

	<p>QCubed also supports handling many-to-many relationships.  Typically, many-to-many relationships are
		mapped in the database using an <strong>Association Table</strong> (sometimes also called a <strong>Mapping</strong>
		or a <strong>Join Table</strong>).  It is basically a two-column table where both
		columns are Foreign Keys to two different tables.</p>

	<p>QCubed allows you to define a set suffix for all <strong>Association Tables</strong> (the default is "_assn").
		Whenever the code generator sees any table that ends in "_assn", it will mark it as a special
		table to be used/analyzed as an <strong>Association Table</strong>, associating two tables together in a many-to-many
		relationship.</p>

	<p>With the <strong>Association Table</strong> in place, QCubed will generate five methods each for the two classes
		involved in this many-to-many relationship.  In our example, we created a <strong>team_member_project_assn</strong>
		table to represent a many-to-many relationship between <strong>Person</strong> and <strong>Project</strong>.</p>

	<p>QCubed will generate the following five methods in <strong>Person</strong> to deal with this many-to-many
		relationship:</p>
	<ul>
		<li>GetProjectAsTeamMemberArray</li>
		<li>CountProjectsAsTeamMember</li>
		<li>AssociateProjectAsTeamMember</li>
		<li>UnassociateProjectAsTeamMember</li>
		<li>UnassociateAllProjectsAsTeamMember</li>
	</ul>

	<p>QCubed will also generate the following five methods in <strong>Project</strong> to deal with this many-to-many
		relationship:</p>
	<ul>
		<li>GetPersonAsTeamMemberArray</li>
		<li>CountPeopleAsTeamMember</li>
		<li>AssociatePersonAsTeamMember</li>
		<li>UnassociatePersonAsTeamMember</li>
		<li>UnassociateAllPeopleAsTeamMember</li>
	</ul>

	<p>Note that the structure of these five methods are very similar for both objects (get, count,
		associate, unassociate, and unassociate all).  In fact, you will also notice that this is
		the same structure as the reverse one-to-many relationship in our previous example.  This especially
		makes sense considering that for all three examples, the object is dealing with the "-to-many" side
		of the relationship.  Regardless if it is a one-"to-many" or a many-"to-many", the five methods
		dealing with "-to-many" is consistent.</p>

	<p>Also, similar to our previous example, note that the "AsTeamMember" token in all these methods are
		there because we named the <strong>Association Table</strong> in the database <strong>team_member_project_assn</strong>.
		If we had use that actuale names of the two tables, as in <strong>person_project_assn</strong>, then
		the methods would be named without the "AsTeamMember" token (e.g. "GetProjectArray", "AssociatePerson",
		etc.).</p>
		
	<p>When associating two tables together that also use hyphens in their table names, the association table name can get
	   confusing with all those hyphens. In this case, when naming the association table, remove the hyphens for the table
	   names. For example, if you want to associate the table "project_location" with "customer_sites", you can name the
	   association table "projectlocation_customersites_assn".</p>
		
</div>

<div id="demoZone">
	<h2>Person's Many-to-Many Relationship with Project (via team_member_project_assn)</h2>
<?php
	// Let's load a Person object -- let's select the Person with ID #2
	$objPerson = Person::Load(2);
?>
	<ul class="project-list">
		<li>Person ID: <?php _p($objPerson->Id); ?></li>
		<li>First Name: <?php _p($objPerson->FirstName); ?></li>
		<li>Last Name: <?php _p($objPerson->LastName); ?></li>
	</ul>


	<h2>Listing of the Project(s) that This Person is a Team Member of</h2>
	<ul>
<?php
		foreach ($objPerson->GetProjectAsTeamMemberArray() as $objProject) {
			_p('<li>' . $objProject->Name . '</li>', false);
		}
?>
	</ul>
	<p>There are <?php _p($objPerson->CountProjectsAsTeamMember()); ?> project(s) that this person is a team member of.</p>

	<h2>Project's Many-to-Many Relationship with Person (via team_member_project_assn)</h2>
<?php
	// Let's load a Project object -- let's select the Project with ID #1
	$objProject = Project::Load(1);
?>
	<ul class="project-list">
		<li>Project ID: <?php _p($objProject->Id); ?></li>
		<li>Project Name: <?php _p($objProject->Name); ?></li>
	</ul>

	<h2>Listing of the Person(s) that This Project has as Team Members</h2>
	<ul>
<?php
	foreach ($objProject->GetPersonAsTeamMemberArray() as $objPerson){
		_p('<li>' . $objPerson->FirstName . ' ' . $objPerson->LastName . '</li>', false);
	}
?>
	</ul>
	<p>There are <?php _p($objProject->CountPeopleAsTeamMember()); ?> person(s) that this project has as team members.</p>
</div>

<?php require('../includes/footer.inc.php'); ?>