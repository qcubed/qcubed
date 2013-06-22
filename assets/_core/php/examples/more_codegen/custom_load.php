<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div id="instructions">
		<h1>Implementing a Customized LoadBy or LoadArrayBy</h1>
		
		<p>(Note: for more information about creating custom queries, please refer to Section 3 of the Examples Site.)</p>

		<p>With the <strong>InstantiateDbResult</strong> method that is code generated for you in each
		generated class, it is very simple to create your own custom <strong>LoadBy</strong> or <strong>LoadArrayBy</strong>
		method using your own custom SQL. Simply specify a custom Load query by using <strong>QCubed Query</strong> (or by writing your
		own SQL statement and passing the results into <strong>InstantiateDbResult</strong>).  The code generated logic will take care
		of the rest, transforming your DB result into an array of that object.</p>

		<p>In our example below, we have a custom load function to get an array of all 
		<strong>Project</strong> objects where the budget is over a given amount.  We pass this amount
		as a parameter to <strong>LoadArrayByBudgetMinimum</strong>.</p>
	</div>

<div id="demoZone">
<?php
	// Let's define our Project SubClass

	// Note: Typically, this code would be in includes/data_objects/Project.class.php
	// but the Project.class.php code has been pulled out and put here for demonstration
	// purposes.
	require(__MODEL_GEN__ . '/ProjectGen.class.php');
	class Project extends ProjectGen {
		// Create our Custom Load Method
		// Note that this custom load method is based on the sample LoadArrayBySample that is generated
		// in the Project custom subclass.  Because it utilizes the QCubed Query mechanism,
		// we can easily take full advantage of any QQ Clauses by taking it in as an optional parameter.
		public static function LoadArrayByBudgetMinimum($fltBudgetMinimum, $objOptionalClauses = null) {
			return Project::QueryArray(
				QQ::GreaterOrEqual(QQN::Project()->Budget, $fltBudgetMinimum),
				$objOptionalClauses
			);
		}
	}
?>
	<h2>Load an Array of Projects Where the Budget >= $8,000</h2>
	<ul>
<?php
	// Let's load all Projects > $10,000 in budget
	$objProjectArray = Project::LoadArrayByBudgetMinimum(8000);
	foreach ($objProjectArray as $objProject)
		_p('<li>' . QApplication::HtmlEntities($objProject->Name) . ' (Budget: $' . QApplication::HtmlEntities($objProject->Budget) . ')</li>', false);
?>
	</ul>
</div>

<?php require('../includes/footer.inc.php'); ?>