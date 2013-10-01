<?php
// If the test is being run in php cli mode, the autoloader does not work.
// Check to see if the models you need exist and if not, include them here.
if(!class_exists('Person')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/Person.class.php';
}

/**
 * Tests for the ExpandAsArray functionality in QQuery
 * 
 * @package Tests
 */
if(!class_exists('Person')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/Person.class.php';
    
}
if(!class_exists('Project')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/Project.class.php';
}
if(!class_exists('Login')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/Login.class.php';
}
if(!class_exists('Milestone')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/Milestone.class.php';
}
if(!class_exists('Address')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/Address.class.php';
}
if(!class_exists('PersonType')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/PersonType.class.php';
}

class ExpandAsArrayTests extends QUnitTestCaseBase {    
	public function testMultiLevel() {
		$arrPeople = Person::LoadAll(
			self::getTestClauses()
		);
				
		$this->assertEqual(sizeof($arrPeople), 12, "12 Person objects found");
		$targetPerson = $this->verifyObjectPropertyHelper($arrPeople, 'LastName', 'Wolfe');
		
		$this->helperVerifyKarenWolfe($targetPerson);
	}
	
	public function testQuerySingle() {
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 7),
			self::getTestClauses()
		);
		
		$this->helperVerifyKarenWolfe($targetPerson);
	}
	
	public function testEmptyArray() {
		$arrPeople = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 2),
			self::getTestClauses()
			);
			
		$this->assertTrue(is_array($arrPeople->_ProjectAsManagerArray), "_ProjectAsManagerArray is an array");
		$this->assertEqual(count($arrPeople->_ProjectAsManagerArray), 0, "_ProjectAsManagerArray has no Project objects");
	}

	public function testNullArray() {
		$arrPeople = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 2)
			);
		
		$this->assertTrue(is_null($arrPeople->_ProjectAsManagerArray), "_ProjectAsManagerArray is null");
	}
	
	public function testTypeExpansion() {		
		$clauses = QQ::Clause(
			QQ::ExpandAsArray (QQN::Person()->PersonType)
		);
		
		$objPerson = 
			Person::QuerySingle(
				QQ::Equal (QQN::Person()->Id, 7),
				$clauses
			);
		
		$intPersonTypeArray = $objPerson->_PersonTypeArray;
		$this->assertEqual($intPersonTypeArray, array(
			PersonType::Manager,
			PersonType::CompanyCar)
		, "PersonType expansion is correct");
	}

	private static function getTestClauses() {
		return QQ::Clause(
			QQ::ExpandAsArray(QQN::Person()->Address),
			QQ::ExpandAsArray(QQN::Person()->ProjectAsManager),
			QQ::ExpandAsArray(QQN::Person()->ProjectAsManager->Milestone)
		);
	}
	
	private function helperVerifyKarenWolfe(Person $targetPerson) {		
		$this->assertEqual(sizeof($targetPerson->_ProjectAsManagerArray), 2, "2 projects found");
		$targetProject = $this->verifyObjectPropertyHelper($targetPerson->_ProjectAsManagerArray, 'Name', 'ACME Payment System');
		
		$this->assertEqual(sizeof($targetProject->_MilestoneArray), 4, "4 milestones found");
		$this->verifyObjectPropertyHelper($targetProject->_MilestoneArray, 'Name', 'Milestone H');
	}

	public function testSelectSubsetInExpand() {
		$objPersonArray = Person::QueryArray(
			QQ::OrCondition(
				QQ::Like(QQN::Person()->ProjectAsManager->Name, '%ACME%'),
				QQ::Like(QQN::Person()->ProjectAsManager->Name, '%HR%')
			),
			// Let's expand on the Project, itself
			QQ::Clause(
				QQ::Select(QQN::Person()->LastName),
				QQ::Expand(QQN::Person()->ProjectAsManager, null, QQ::Select(QQN::Person()->ProjectAsManager->Spent)),
				QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)
			)
		);

		foreach ($objPersonArray as $objPerson) {
			$this->assertNull($objPerson->FirstName, "FirstName should be null, since it was not selected");
			$this->assertNotNull($objPerson->Id, "Id should not be null since it's always added to the select list");
			$this->assertNotNull($objPerson->_ProjectAsManager->Id, "ProjectAsManager->Id should not be null since id's are always added to the select list");
			$this->assertNull($objPerson->_ProjectAsManager->Name, "ProjectAsManager->Name should be null since it was not selected");
		}
	}

	public function testSelectSubsetInExpandAsArray() {
		$objPersonArray = Person::LoadAll(
			QQ::Clause(
				QQ::Select(QQN::Person()->FirstName),
				QQ::ExpandAsArray(QQN::Person()->Address, QQ::Select(QQN::Person()->Address->Street, QQN::Person()->Address->City)),
				QQ::ExpandAsArray(QQN::Person()->ProjectAsManager, QQ::Select(QQN::Person()->ProjectAsManager->StartDate)),
				QQ::ExpandAsArray(QQN::Person()->ProjectAsManager->Milestone, QQ::Select(QQN::Person()->ProjectAsManager->Milestone->Name))
			)
		);

		foreach ($objPersonArray as $objPerson) {
			$this->assertNull($objPerson->LastName, "LastName should be null, since it was not selected");
			$this->assertNotNull($objPerson->Id, "Id should not be null since it's always added to the select list");
			if (sizeof($objPerson->_AddressArray) > 0) {
				foreach ($objPerson->_AddressArray as $objAddress) {
					$this->assertNotNull($objAddress->Id, "Address->Id should not be null since it's always added to the select list");
					$this->assertNull($objAddress->PersonId, "Address->PersonId should be null, since it was not selected");
				}
			}
			if (sizeof($objPerson->_ProjectAsManagerArray) > 0) {
				foreach($objPerson->_ProjectAsManagerArray as $objProject) {
					$this->assertNotNull($objProject->Id, "Project->Id should not be null since it's always added to the select list");
					$this->assertNull($objProject->Name, "Project->Name should be null, since it was not selected");
					if (sizeof($objProject->_MilestoneArray) > 0) {
						foreach ($objProject->_MilestoneArray as $objMilestone) {
							$this->assertNotNull($objMilestone->Id, "Milestone->Id should not be null since it's always added to the select list");
							$this->assertNull($objMilestone->ProjectId, "Milestone->ProjectId should be null, since it was not selected");
						}
					}
				}
			}
		}
	}
}
?>