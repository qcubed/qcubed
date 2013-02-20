<?php require('../includes/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div id="instructions">
	<h1>Sorting a QDataGrid by Columns</h1>

	<p>In this example we show how to make the datagrid sortable by individual columns.</p>

	<p>For each column, we add the properties <strong>OrderByClause</strong> and <strong>ReverseOrderByClause</strong> (it is possible
		to also just define <strong>OrderByClause</strong>, and to leave <strong>ReverseOrderByClause</strong> undefined).  The <strong>QQ Clause</strong>
		you specify is given back to you when you call the <strong>OrderByClause</strong> property on the <strong>QDataGrid</strong>
		itself.</p>

	<p>So what you do is you specify the <strong>QQ OrderBy Clause</strong> that you would want run
		for each column.  Then you pass the this clause to your class's <strong>LoadAll</strong> or <strong>LoadArrayBy...</strong> 
		method as one of the optional <strong>QQ Clause</strong> parameters.  Note that all QCubed code generated <strong>LoadAll</strong> and <strong>LoadArrayBy...</strong>
		methods take in an optional <strong>$objOptionalClauses</strong> parameter which conveniently uses the clause returned by the <strong>QDataGrid</strong>'s
		<strong>OrderByClause</strong> method.</p>

	<p>Convenient how they end up working together, isn't it? =)</p>
</div>

<div id="demoZone">
	<?php $this->dtgPersons->Render(); ?>
</div>

<?php $this->RenderEnd(); ?>
<?php require('../includes/footer.inc.php'); ?>