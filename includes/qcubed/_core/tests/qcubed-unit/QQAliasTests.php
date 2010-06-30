<?php

/**
 * Validation tests for the SQL Aliasing logic provided in QQ::Alias().
 * 
 * @package Tests
 */
class QQAliasTests extends QUnitTestCaseBase {	
	public function testAlias1() {
		$objPersonArray = Person::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQ::Alias(QQN::Person()->ProjectAsTeamMember, 'pm1')->ProjectId, 1),
				QQ::Equal(QQ::Alias(QQN::Person()->ProjectAsTeamMember, 'pm2')->ProjectId, 2)
			)
		);
		
		$this->assertEqual(sizeof($objPersonArray), 3);
		$this->verifyObjectPropertyHelper($objPersonArray, 'FirstName', 'Kendall');
		$this->verifyObjectPropertyHelper($objPersonArray, 'LastName', 'Wolfe');
		$this->verifyObjectPropertyHelper($objPersonArray, 'LastName', 'Smith');
	}
	
	public function testAlias2() {
		$objProjectArray = Project::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQ::Alias(QQN::Project()->ProjectAsRelated, 'related1')->Project->Name, 'Blueman Industrial Site Architecture'),
				QQ::Equal(QQ::Alias(QQN::Project()->ProjectAsRelated, 'related2')->Project->Name, 'ACME Payment System')
			)
		);

		$this->assertEqual(sizeof($objProjectArray), 1);
		$this->verifyObjectPropertyHelper($objProjectArray, 'Name', 'ACME Website Redesign');

	}	
}
?>
