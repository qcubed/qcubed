<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>The Examples Site Database</h1>

	<p>Before learning about the Code Generator, it might be good to first get acquainted with the
		data model which the Code Generator will be generating from.</p>

	<p>Click on the "View Source" link in the upper righthand corner to view the
		<strong>mysql_innodb.sql</strong> to examine the data model in script form, or you can
		view an ER diagram of the data model below.</p>

	<p>If you have not installed this <strong>Examples Site Database</strong> on your MySQL server, you might want to
		do that now.  After installing the database, you must also remember to
		<strong><a href="<?php _p(__VIRTUAL_DIRECTORY__ . __DEVTOOLS_ASSETS__ . '/codegen.php'); ?>" class="bodyLink">code generate</a></strong>
		the corresponding objects <em>before</em> trying to any of the further code generation examples.</p>

	<p>Note that there is also a SQL Server version of this database script called <strong>sql_server.sql</strong>. And PostgreSql version called <strong>pgsql.sql</strong>.</p>

	<p>In the script, we have some tables defined.  The bulk of our examples will focus on the main three
		tables of the database:</p>
	<ul>
		<li><strong>login</strong></li>
		<li><strong>person</strong></li>
		<li><strong>project</strong></li>
	</ul>

	<p>The <strong>team_member_project_assn</strong> table handles the many-to-many relationship between
		<strong>person</strong> and <strong>project</strong>.
		The <strong>project_status_type</strong> table is a <strong>Type Table</strong> which will be discussed in
		the example for <a href="../more_codegen/type_tables.php">Type Tables</a>.
		The <strong>qc_watchers</strong> table is a special system table which will be discussed in
		the example for <a href="../events_actions/watcher.php">Automatic Refreshing of Controls</a>.
		Finally the <strong>person_with_lock</strong> table is specifically used by the example for
		<a href="../more_codegen/optimistic_locking.php">Optimistic Locking</a>.</p>
</div>

<div id="demoZone">
	<img src="../images/data_model.png" alt="&quot;Examples Site Database&quot; data model" style="max-width:100%" />
</div>

<?php require('../includes/footer.inc.php'); ?>