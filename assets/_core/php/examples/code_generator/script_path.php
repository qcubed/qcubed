<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions" class="full">
	<h1>Using a Relationships Script</h1>

	<p>Our <strong>Examples Site Database</strong> uses the InnoDB storage engine in MySQL, which has
		full support for Foreign Keys to help define relationships between tables.</p>

	<p>However, sometimes you maybe using a platform which does not offer Foreign Key support
		(e.g. MySQL with MyISAM tables), or alternatively, you may want to have relationships
		defined in your objects but you do not want to incur the performance and/or restriction
		of using a programmatic foreign key constraint.</p>

	<p>The code generator supports this by allowing you to define a <strong>Relationships Script</strong> to
		a relationships script file.  This is just a plain textfile that you write to
		define any "foreign keys" you have in your database (without explicitly defining
		a real foreign key).  This file can be formatted in one of two ways.  The standard "QCubed"
		format is basically:</p>

	<pre><code>table1.column1 => table2.column2</code></pre>

	<p>where <strong>table1.column1</strong> is meant to be a Foreign Key to <strong>table2.column2</strong>.  The other
		option is to use standard ANSI "sql" format:</p>

	<pre><code>ALTER TABLE table1 ADD CONSTRAINT foo_bar FOREIGN KEY column1 ON table2(column2);</code></pre>

	<p>This format is more compatible with ER Diagramming applications which can generate SQL scripts for use
		with the database.  You can simply point the code generator to use the generated SQL script to help
		with your "virtual" foreign keys.</p>

	<p>Once you have your relationships script defined, you can specify the location of this script
		file in the <strong>RelationshipsScript</strong> directive of your codegen settings XML file.</p>

	<p>Please <strong>View Source</strong> to view the <strong>Examples Site Database</strong> SQL script using MyISAM tables, as
		well as its corresponding <strong>relationships.txt</strong> file.  The combination of this MyISAM script
		and the <strong>relationships.txt</strong> file should functionally give you the same, equivalent
		database as the InnoDB version of our <strong>Examples Site Database</strong>.</p>
</div>

<style>#viewSource { display: none; }</style>

<?php require('../includes/footer.inc.php'); ?>