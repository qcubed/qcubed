<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>Analyzing Reverse Relationships</h1>

	<p>Although it's a bit hard to understand at first, one of the unique and more powerful features of QCubed
		is its ability to generate code to handle reverse relationships as well.
		Given our previous example with the <strong>Project</strong> and <strong>ManagerPerson</strong>, we showed how
		QCubed generated code in the <strong>Project</strong> class to handle the relationship.  But QCubed will also geneate
		code in the <strong>Person</strong> class to handle the reverse aspects of this relationship.</p>

	<p>In this case, <strong>Person</strong> is on the "to Many" side of a "One to Many" relationship with <strong>Project</strong>.
		So QCubed will generate the following methods in <strong>Person</strong> to deal with this reverse
		relationship:</p>
	<ul>
		<li>GetProjectsAsManagerArray</li>
		<li>CountProjectsAsManager</li>
		<li>AssociateProjectAsManager</li>
		<li>UnassociateProjectAsManager</li>
		<li>UnassociateAllProjectsAsManager</li>
		<li>DeleteAssociatedProjectAsManager</li>
		<li>DeleteAllProjectsAsManager</li>
	</ul>

	<p>And in fact, QCubed will generate the same seven methods for any "One to Many" reverse relationship
		(get, count all, associate, unassociate, and unassociate all, delete associated, and delete all associated).
		Note that the "AsManager" token in all these methods are there because we named the column in the
		<strong>project</strong> table <strong>manager_person_id</strong>.  If we simply named it as <strong>person_id</strong>,
		the methods would be named without the "AsManager" token (e.g. "GetProjectsArray", "CountProjects",
		etc.)</p>

	<p>Also note that <strong>GetProjectsAsManagerArray</strong> utilizes the <strong>LoadArrayByManagerPersonId</strong>
		method in the <strong>Project</strong> object.  Of course, this was generated because <strong>manager_person_id</strong> is already
		an index (as well as a Foreign Key) in the <strong>project</strong> table.</p>

	<p>QCubed's Reverse Relationships functionality
		is dependent on the data model having indexes defined on all columns that are foreign keys.  For many
		database platforms (e.g. MySQL) this should not be a problem b/c the index is created implicitly by the engine.
		But for some (e.g. SQL Server) platforms, make sure that you have indexes defined on your Foreign Key columns,
		or else you forgo being able to use the Reverse Relationship functionality.</p>

	<h2>Unique Reverse Relationships (e.g. "One to One" Relationships)</h2>

	<p>QCubed will generate a different set of code if it knows the reverse relationship to be a "Zero
		to One" or "One to One" type of relationship.  This occurs in the relationship between
		our <strong>login</strong> and <strong>person</strong> tables.  Note that <strong>login</strong>.<strong>person_id</strong> is a unique
		column.  Therefore, QCubed recognizes this as a "Zero- or One-to-One" relationship.  So for the
		reverse relationship, QCubed will not generate the five methods (listed above) in the <strong>Person</strong>
		table for the <strong>Login</strong> relationship.  Instead, QCubed generates a <strong>Login</strong> property in
		<strong>Person</strong> object which can be set, modified, etc. just like the <strong>Person</strong> property in
		the <strong>Login</strong> object.</p>

	<h3>Self-Referential Tables</h3>

	<p>QCubed also has full support for self-referential tables (e.g. a <strong>category</strong> table that
		contains a <strong>parent_category_id</strong> column which would foreign key back to itself).
		In this case, the QCubed will generated the following seven methods to assist with the reverse
		relationship for this self-reference:</p>
	<ul>
		<li>GetChildCategoryArray</li>
		<li>CountChildCategories</li>
		<li>AssociateChildCategory</li>
		<li>UnassocaiteChildCategory</li>
		<li>UnassociateAllChildCategories</li>
		<li>DeleteChildCategory</li>
		<li>DeleteAllChildCategories</li>
	</ul>

	<p>(Note that even though this is being documented here, self-referential tables aren't actually
		defined in the <strong>Examples Site Database</strong>.)</p>
</div>

<div id="demoZone">

	<h2>Person's Reverse Relationships with Project (via project.manager_person_id) and Login (via login.person_id)</h2>
<?php
	// Let's load a Person object -- let's select the Person with ID #7
	$objPerson = Person::Load(7);
?>
	<ul class="person-list">
		<li>Person ID: <?php _p($objPerson->Id); ?></li>
		<li>First Name: <?php _p($objPerson->FirstName); ?></li>
		<li>Last Name: <?php _p($objPerson->LastName); ?></li>
	</ul>

	<h3>Listing of the Project(s) that This Person Manages</h3>
	<ul class="project-list">
<?php
		foreach ($objPerson->GetProjectAsManagerArray() as $objProject) {
			_p('<li>' . $objProject->Name . '</li>', false);
		}
?>
	</ul>
	<p>There are <?php _p($objPerson->CountProjectsAsManager()); ?> project(s) that this person manages.</p>

	<h3>This Person's Login Object</h3>
	<ul class="person-list">
		<li>Username: <?php _p($objPerson->Login->Username); ?></li>
		<li>Password: <?php _p($objPerson->Login->Password); ?></li>
	</ul>
</div>

<?php require('../includes/footer.inc.php'); ?>