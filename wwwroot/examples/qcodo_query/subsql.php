<?php require('../../includes/prepend.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

	<div class="instructions">
		<div class="instruction_title">SQL Subqueries for QQuery</div>
		QQuery provides excellent means to perform many common queries; however,
		once in a while, you'll find yourself needing to do some custom SQL. Don't
		jump into <a href="intro.php">full custom queries</a> just yet. You can
		write that little piece of SQL as a part of your QQuery in many cases using
		SQL subqueries.<br /><br />
		
		In the example below, we need to find the names of the project managers 
		whose projects are running over budget. While it's possible to write this
		query in a straight-up QQuery, for this example, we'll use a SubSql
        mechanism instead just for illustration purposes.<br /><br />
        
        Important gotcha: you have to define your subquery as a part of a
        <b>QQCondition</b>! To make it available in the returned array as a Virtual
        Attribute, you also have to put in a <b>QQ::Expand</b> clause to have the
        SELECT clause of the query include the subquery result.
		
		Note: the code below generates <a href="http://docs.hp.com/en/36216-90103/ch03s02.html">
		correlated (dependent) subqueries</a>. These are frequently not the
		fastest way to run queries against your SQL engine. If there is an
		opportunity to rewrite your subquery using simple joins, do it - this
		will improve the performance of your applications dramatically.<br /><br />

		In general, it's a good idea to use EXPLAIN statements to determine
		the query execution plan of the SQL statement that QQuery generates
		to determine what the SQL engine will actually do to run your queries.
		This is one of the best ways to improve the performance of your
		database-driven application.<br /><br />
	</div>

	<h3>Select names of project managers whose projects are over budget by at least $20</h3>
<?php

 QApplication::$Database[1]->EnableProfiling();
	$objPersonArray = Person::QueryArray(
		/* Only return the persons who have AT LEAST ONE overdue project */
		QQ::IsNotNull(
			QQ::Virtual(
				/* this will be the alias of our virtual attribute */
				'over_budget_projects',
				
				/* actual definition of the SQL subquery - note how
				  we will be using a {1} parameter to connect it with the rest
				  of the QQuery. Think of the sprintf-like syntax - that's
				  really what this is. 
				*/
				QQ::SubSql("SELECT COUNT(*)
							FROM project                            
							WHERE (spent - budget > 20)
								AND manager_person_id={1}
							GROUP BY manager_person_id",
							
							/* the value to be used for the {1} placeholder
							   at the time of query evaluation
							*/
							QQN::Person()->Id 
				)
			)
		),
		QQ::Clause(
			/* Sort by the number of over-budget projects -
              biggest offenders first.
			*/
			QQ::OrderBy(QQ::Virtual('over_budget_projects'), false),
			
			/* We want to return the actual number of the over-budget
			  projects - not just filter based on them. Thus, we have
			  to put in an expand statement.
			*/
			QQ::Expand(QQ::Virtual('over_budget_projects'))
		)
	);

	foreach ($objPersonArray as $objPerson){
		_p($objPerson->FirstName . ' ' . $objPerson->LastName . ': ' . 
			$objPerson->GetVirtualAttribute("over_budget_projects") . " project(s) over budget");
		_p('<br/>', false);
	}
	
QApplication::$Database[1]->OutputProfiling();    
?>

<?php require('../includes/footer.inc.php'); ?>