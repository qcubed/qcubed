<?php
/**
 * Tests for the ExpandAsArray functionality in QQuery
 * 
 * @package Tests
 */
// If the test is being run in php cli mode, the autoloader does not work.
// Check to see if the models you need exist and if not, include them here.
if(!class_exists('Person')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/Person.class.php';
}
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
if(!class_exists('TwoKey')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/TwoKey.class.php';
}
if(!class_exists('ProjectStatusType')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/ProjectStatusType.class.php';
}
if(!class_exists('Login')){
    require_once __DOCROOT__ . __SUBDIRECTORY__ .'/includes/model/Login.class.php';
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
		
		$objProjectArray = $targetPerson->_ProjectAsManagerArray;
		$this->assertEqual(sizeof($objProjectArray), 2, "2 projects found");
		
		foreach ($objProjectArray as $objProject) {
			$objMilestoneArray = $objProject->_MilestoneArray;
			
			switch ($objProject->Id) {
				case 1:
					$this->assertEqual(sizeof($objMilestoneArray), 3, "3 milestones found");
					break;
					
				case 4:
					$this->assertEqual(sizeof($objMilestoneArray), 4, "4 milestones found");
					break;
					
				default:
					$this->assertTrue(false, 'Unexpected project found, id: ' . $objProject->Id);
					break;
			}
		}
		
		// Now test a multilevel expansion where first level does not expand by array. Should get duplicate entries at that level.
		$clauses = QQ::Clause(
			QQ::ExpandAsArray(QQN::Person()->Address),
			QQ::Expand(QQN::Person()->ProjectAsManager),
			QQ::ExpandAsArray(QQN::Person()->ProjectAsManager->Milestone)
		);
		
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 7),
			$clauses
		);
						
		$objProjectArray = $targetPerson->_ProjectAsManagerArray;
		$this->assertNull($objProjectArray, "No project array found");

		$objProject = $targetPerson->_ProjectAsManager;
		$this->assertNotNull($objProject, "Project found");
		
		$objMilestoneArray = $objProject->_MilestoneArray;
		// since we didn't specify the order, not sure which one we will get, so check for either
		switch ($objProject->Id) {
			case 1:
				$this->assertEqual(sizeof($objMilestoneArray), 3, "3 milestones found");
				break;
				
			case 4:
				$this->assertEqual(sizeof($objMilestoneArray), 4, "4 milestones found");
				break;
				
			default:
				$this->assertTrue(false, 'Unexpected project found, id: ' . $objProject->Id);
				break;
		}
	}
	
	public function testQuerySingle() {
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 7),
			self::getTestClauses()
		);
		
		$this->helperVerifyKarenWolfe($targetPerson);
		
		$objTwoKey = TwoKey::QuerySingle(
			QQ::AndCondition (
				QQ::Equal(QQN::TwoKey()->Server, 'google.com'),
				QQ::Equal(QQN::TwoKey()->Directory, 'mail')
			),
			QQ::Clause(
				QQ::ExpandAsArray(QQN::TwoKey()->Project->PersonAsTeamMember)
			)
		);
		
		$this->assertEqual (count($objTwoKey->Project->_PersonAsTeamMemberArray), 6, '6 team members found.');
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
	
	public function testMultiLeafExpansion() {
		$objMilestone = Milestone::QuerySingle(
			QQ::Equal (QQN::Milestone()->Id, 1),
			QQ::Clause(
				QQ::ExpandAsArray(QQN::Milestone()->Project->ManagerPerson->ProjectAsTeamMember),
				QQ::ExpandAsArray(QQN::Milestone()->Project->PersonAsTeamMember)
			)
		);
		
		$objProjectArray = $objMilestone->Project->ManagerPerson->_ProjectAsTeamMemberArray;
		$objPeopleArray = $objMilestone->Project->_PersonAsTeamMemberArray;
		
		$this->assertTrue(is_array($objProjectArray), "_ProjectAsTeamMemberArray is an array");
		$this->assertEqual(count($objProjectArray), 2, "_ProjectAsTeamMemberArray has 2 Project objects");
		
		$this->assertTrue(is_array($objPeopleArray), "_PersonAsTeamMemberArray is an array");
		$this->assertEqual(count($objPeopleArray), 5, "_PersonAsTeamMemberArray has 5 People objects");
		
		// try through a unique relationship
		$objLogin = Login::QuerySingle(
			QQ::Equal (QQN::Login()->PersonId, 7),
			QQ::Clause(
				QQ::ExpandAsArray(QQN::Login()->Person->ProjectAsTeamMember),
				QQ::ExpandAsArray(QQN::Login()->Person->ProjectAsManager)
			)
		);
		
		$objProjectArray = $objLogin->Person->_ProjectAsTeamMemberArray;
		
		$this->assertTrue(is_array($objProjectArray), "_ProjectAsTeamMemberArray is an array");
		$this->assertEqual(count($objProjectArray), 2, "_ProjectAsTeamMemberArray has 2 Project objects");
		
		$objProjectArray = $objLogin->Person->_ProjectAsManagerArray;
		
		$this->assertTrue(is_array($objProjectArray), "_ProjectAsManagerArray is an array");
		$this->assertEqual(count($objProjectArray), 2, "_ProjectAsManagerArray has 2 Project objects");
				
	}
	
	public function testConditionalExpansion() {
		$clauses = QQ::Clause(
			QQ::ExpandAsArray(QQN::Person()->Address),
			QQ::Expand(QQN::Person()->ProjectAsManager, QQ::Equal (QQN::Person()->ProjectAsManager->ProjectStatusTypeId, ProjectStatusType::Open)),
			QQ::ExpandAsArray(QQN::Person()->ProjectAsManager->Milestone),
			QQ::OrderBy(QQN::Person()->Id)
		);
		
		$targetPersonArray = Person::LoadAll (
			$clauses
		);
		
		$targetPerson = reset($targetPersonArray);
		
		$this->assertEqual ($targetPerson->Id, 1, "Person 1 found.");
		$this->assertNotNull ($targetPerson->_ProjectAsManager, "Person 1 has a project.");

		$targetPerson = end($targetPersonArray);
		
		$this->assertEqual ($targetPerson->Id, 12, "Person 12 found.");
		$this->assertNull ($targetPerson->_ProjectAsManager, "Person 12 does not have a project.");
				
		//TODO: Conditional Array Expansion, requires API change
		
	}
	
}
?>