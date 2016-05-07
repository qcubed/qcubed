<?php require_once('../qcubed.inc.php'); ?>
<?php require('../includes/header.inc.php'); ?>

<div id="instructions">
	<h1>SQL functions and math operations for QQuery</h1>

	<p><a href="subsql.php">QQuery SubSql</a> is a very powerfull technique, but sometimes
	you don't need so much control. In this case you can use QQuery Math Operations or SQL Functions</p>

	<p>Math operations are: 
	<strong>QQ::Add</strong> for addition (+), 
	<strong>QQ::Sub</strong> for subtraction (-), 
	<strong>QQ::Mul</strong> for multiplication (*), 
	<strong>QQ::Div</strong> for division (/), 
	and a <strong>QQ::MathOp</strong> to apply an arbitrary math operation to 2 or more operands</p>

	<p>Exactly in the same way can be used SQL functions like: 
	<strong>QQ::Abs</strong> for the absolute value (ABS), 
	<strong>QQ::Ceil</strong> for the smallest integer value not less than the argument (CEIL), 
	<strong>QQ::Floor</strong> for largest integer value not greater than the argument (FLOOR), 
	<strong>QQ::Mod</strong> for the remainder (MOD), 
	<strong>QQ::Power</strong> for the argument raised to the specified power (POWER), 
	<strong>QQ::Sqrt</strong> for the square root of the argument (SQRT), 
	and a <strong>QQ::Func</strong> to apply an arbitrary scalar function using the given parameters</p>

</div>

<div id="demoZone">
	<h2>Select names of project managers whose projects are over budget by at least $20</h2>
<?php

	QApplication::$Database[1]->EnableProfiling();
	$objPersonArray = Person::QueryArray(
		/* Only return the persons who have AT LEAST ONE overdue project */
		QQ::GreaterThan(QQ::Sub(QQN::Person()->ProjectAsManager->Spent, QQN::Person()->ProjectAsManager->Budget), 20)
	);

	foreach ($objPersonArray as $objPerson) {
		_p($objPerson->FirstName . ' ' . $objPerson->LastName);
		_p('<br/>', false);
	}
?>
	<p><?php QApplication::$Database[1]->OutputProfiling(); ?></p>

	<h2>The same as above, but add calculated virtual field and do order by with it</h2>
<?php

	QApplication::$Database[1]->EnableProfiling();
	$objPersonArray = Person::QueryArray(
		/* Only return the persons who have AT LEAST ONE overdue project */
		QQ::GreaterThan(
			QQ::Virtual('diff', QQ::Sub(
				QQN::Person()->ProjectAsManager->Spent
				, QQN::Person()->ProjectAsManager->Budget
			))
			, 20
		),
		QQ::Clause(
			/* The most overdue first */
			QQ::OrderBy(QQ::Virtual('diff'), 'DESC')
			/* Required to access this field with GetVirtualAttribute */
			, QQ::Expand(QQ::Virtual('diff'))
		)
	);

	foreach ($objPersonArray as $objPerson) {
		_p($objPerson->FirstName . ' ' . $objPerson->LastName) . ' : ' . $objPerson->GetVirtualAttribute('diff');
		_p('<br/>', false);
	}
?>
	<p><?php QApplication::$Database[1]->OutputProfiling(); ?></p>

	<h2>The same as above, but filter out all other fields with Select clause</h2>
	<p>It also demonstrates the use of QQ::MathOp function</p>
<?php

	QApplication::$Database[1]->EnableProfiling();
	$objPersonArray = Person::QueryArray(
		/* Only return the persons who have AT LEAST ONE overdue project */
		QQ::GreaterThan(
			QQ::Virtual('diff', QQ::MathOp(
				'-', // Note the minus operation sign here
				QQN::Person()->ProjectAsManager->Spent
				, QQN::Person()->ProjectAsManager->Budget
			))
			, 20
		),
		QQ::Clause(
			/* The most overdue first */
			QQ::OrderBy(QQ::Virtual('diff'), 'DESC')
			/* Required to access this field with GetVirtualAttribute */
			, QQ::Expand(QQ::Virtual('diff'))
			, QQ::Select(array(
				QQ::Virtual('diff')
				, QQN::Person()->FirstName
				, QQN::Person()->LastName
			))
		)
	);

	foreach ($objPersonArray as $objPerson) {
		_p($objPerson->FirstName . ' ' . $objPerson->LastName) . ' : ' . $objPerson->GetVirtualAttribute('diff');
		_p('<br/>', false);
	}
?>
	<p><?php QApplication::$Database[1]->OutputProfiling(); ?></p>

	<h2>SQL Function usage</h2>
	<p>It uses QQ::Abs function to retrieve not only overdue, but also, an opposite to an overdue projects</p>
<?php

	QApplication::$Database[1]->EnableProfiling();
	$objPersonArray = Person::QueryArray(
		/* Only return the persons who have AT LEAST ONE overdue project */
		QQ::GreaterThan(
			QQ::Virtual('absdiff', QQ::Abs(
				QQ::Sub(
					QQN::Person()->ProjectAsManager->Spent
					, QQN::Person()->ProjectAsManager->Budget
				)
			))
			, 20
		),
		QQ::Clause(
			/* The most overdue first */
			QQ::OrderBy(QQ::Virtual('absdiff'), 'DESC')
			/* Required to access this field with GetVirtualAttribute */
			, QQ::Expand(QQ::Virtual('absdiff'))
			, QQ::Select(array(
				QQ::Virtual('absdiff')
				, QQN::Person()->FirstName
				, QQN::Person()->LastName
			))
		)
	);

	foreach ($objPersonArray as $objPerson) {
		_p($objPerson->FirstName . ' ' . $objPerson->LastName) . ' : ' . $objPerson->GetVirtualAttribute('diff');
		_p('<br/>', false);
	}
?>
	<p><?php QApplication::$Database[1]->OutputProfiling(); ?></p>
</div>

<?php require('../includes/footer.inc.php'); ?>