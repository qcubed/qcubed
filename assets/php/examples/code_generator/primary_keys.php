<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="full">
	<h1>Primary Keys in Your Tables</h1>

	<p>In order for any ORM architecture to work, there must be at least some kind of Primary Key defined
		on any table for which you want an object generated.  But what is unique about Qcubed's ORM is that it does
		<em>not</em> impose any requirements on <em>how</em> to define your Primary Keys.  (Note that you can also
		still use the framework against any database that contains tables that do <em>not</em> have primary keys,
		it is just that those specific tables will not be generated as objects.)</p>

	<p>Your Primary Key column or columns can be named however you wish.  Moreover, Qcubed supports Primary Key columns
		that are both "automatically incremented" and <em>not</em> "automatically incremented".  ("Automatically
		incremented" columns are known as auto_incremement, identity, or using a sequence,
		depending on which database platform you are using).</p>

	<p>QCubed also offers <em>some</em> support for tables that have multiple-column Primary Keys defined on it.
		For tables that have multi-column Primary Keys, QCubed will fully generate the object
		itself.  But note that you will <em>not</em> be able to use this generated object as a related object for
		another table (in other words, QCubed does not support multi-column <em>Foreign</em> Keys).  However,
		with all the generated <strong>Load</strong> methods in these objects, it is still possible to fully develop
		an application with tables that use multi-column Foreign Keys.  Basically, whenever you want to access
		a related object via a multi-column Foreign Key, you can simply call that object's <strong>Load</strong> method
		directly to retrieve that object.</p>

	<p>If you are code generating against a legacy application or database that has tables with multiple-column
		Primary Keys, then this level of support should hopefully suffice.  But if you are creating a new application
		or database, then it is recommended that all tables have a single-column Primary Key (with one that
		preferably is sequenced, auto_increment, or identity, depending on which DB platform you are using).</p>
</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>