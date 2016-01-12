<?php
// If the test is being run in php cli mode, the autoloader does not work.
// Check to see if the models you need exist and if not, include them here.
if(!class_exists('Person')){
    require_once __INCLUDES__ .'/model/Person.class.php';
    
}
if(!class_exists('Project')){
    require_once __INCLUDES__ . '/model/Project.class.php';
}
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

	public function testAlias3() {
		$emptySelect = QQ::Select();
		$emptySelect->SetSkipPrimaryKey(true);
		$nVoyel = QQ::Alias(QQN::Person()->ProjectAsManager->Milestone, 'voyel');
		$nConson = QQ::Alias(QQN::Person()->ProjectAsManager->Milestone, 'conson');
		$objPersonArray = Person::QueryArray(
			QQ::IsNotNull($nConson->Id),
			QQ::Clause(
				QQ::Expand($nVoyel, QQ::In($nVoyel->Name, array('Milestone A', 'Milestone E', 'Milestone I')), $emptySelect),
				QQ::Expand($nConson, QQ::NotIn($nConson->Name, array('Milestone A', 'Milestone E', 'Milestone I')), $emptySelect),
				QQ::GroupBy(QQN::Person()->Id),
				QQ::Minimum($nVoyel->Name, 'min_voyel'),
				QQ::Minimum($nConson->Name, 'min_conson'),
				//*** just to avoid build error with pg.
				// Even with an empty select, id is selected;
				// Happily, PG doesn't complain if both id and MIN(id) are selected
				QQ::Expand(QQN::Person()->ProjectAsManager, null, $emptySelect),
				QQ::Minimum(QQN::Person()->ProjectAsManager->Id, 'dummy'),
				//***
				QQ::Select(
					QQN::Person()->FirstName,
					QQN::Person()->LastName
				)
			)
		);
		$this->assertEqual(sizeof($objPersonArray), 3);
		$obj = $this->verifyObjectPropertyHelper($objPersonArray, 'LastName', 'Doe');
		$this->assertNull($obj->GetVirtualAttribute('min_voyel'));
		$this->assertEqual($obj->GetVirtualAttribute('min_conson'), 'Milestone F');

		$obj = $this->verifyObjectPropertyHelper($objPersonArray, 'LastName', 'Ho');
		$this->assertEqual($obj->GetVirtualAttribute('min_voyel'), 'Milestone E');
		$this->assertEqual($obj->GetVirtualAttribute('min_conson'), 'Milestone D');

		$obj = $this->verifyObjectPropertyHelper($objPersonArray, 'LastName', 'Wolfe');
		$this->assertEqual($obj->GetVirtualAttribute('min_voyel'), 'Milestone A');
		$this->assertEqual($obj->GetVirtualAttribute('min_conson'), 'Milestone B');
	}
}
?>
