<?php
/**
 * Tests for the ExpandAsArray functionality in QQuery
 * 
 * @package Tests
 */
// If the test is being run in php cli mode, the autoloader does not work.
// Check to see if the models you need exist and if not, include them here.
if(!class_exists('Person')){
    require_once __INCLUDES__ .'/model/Person.class.php';
}
if(!class_exists('Project')){
    require_once __INCLUDES__ .'/model/Project.class.php';
}
if(!class_exists('Login')){
    require_once __INCLUDES__ .'/model/Login.class.php';
}
if(!class_exists('Milestone')){
    require_once __INCLUDES__ .'/model/Milestone.class.php';
}
if(!class_exists('Address')){
    require_once __INCLUDES__ .'/model/Address.class.php';
}
if(!class_exists('PersonType')){
    require_once __INCLUDES__ .'/model/PersonType.class.php';
}
if(!class_exists('TwoKey')){
    require_once __INCLUDES__ .'/model/TwoKey.class.php';
}
if(!class_exists('ProjectStatusType')){
    require_once __INCLUDES__ .'/model/ProjectStatusType.class.php';
}
if(!class_exists('Login')){
    require_once __INCLUDES__ .'/model/Login.class.php';
}

class CacheTests extends QUnitTestCaseBase {

	public function setUp() {
		QApplication::$blnLocalCache = true;
	}

	public function tearDown() {
		QApplication::$blnLocalCache = false;
	}


	public function testMultiLevel() {
		$arrPeople = Person::LoadAll(
			self::getTestClauses()
		);
				
		$this->assertEquals(sizeof($arrPeople), 12, "12 Person objects found");
		$targetPerson = $this->verifyObjectPropertyHelper($arrPeople, 'LastName', 'Wolfe');
		
		$this->helperVerifyKarenWolfe($targetPerson);
		
		$objProjectArray = $targetPerson->_ProjectAsManagerArray;
		$this->assertEquals(sizeof($objProjectArray), 2, "2 projects found");
		
		foreach ($objProjectArray as $objProject) {
			$objMilestoneArray = $objProject->_MilestoneArray;
			
			switch ($objProject->Id) {
				case 1:
					$this->assertEquals(sizeof($objMilestoneArray), 3, "3 milestones found");
					break;
					
				case 4:
					$this->assertEquals(sizeof($objMilestoneArray), 4, "4 milestones found");
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

		$arrPeople = Person::LoadAll(
			$clauses
		);

		// Karen Wolfe should duplicate, since she is managing two projects
		$this->assertEquals(sizeof($arrPeople), 13, "13 Person objects found");
		$targetPerson = $this->verifyObjectPropertyHelper($arrPeople, 'LastName', 'Wolfe');

		$objProjectArray = $targetPerson->_ProjectAsManagerArray;
		$this->assertNull($objProjectArray, "No project array found");

		$objProject = $targetPerson->_ProjectAsManager;
		$this->assertNotNull($objProject, "Project found");
		
		$objMilestoneArray = $objProject->_MilestoneArray;
		// since we didn't specify the order, not sure which one we will get, so check for either
		switch ($objProject->Id) {
			case 1:
				$this->assertEquals(sizeof($objMilestoneArray), 3, "3 milestones found");
				break;
				
			case 4:
				$this->assertEquals(sizeof($objMilestoneArray), 4, "4 milestones found");
				break;
				
			default:
				$this->assertTrue(false, 'Unexpected project found, id: ' . $objProject->Id);
				break;
		}

		// test that querying for expanded objects will return the cached version

		$objProject2 = Project::Load ($objProject->Id, array (QQ::Select (QQN::Project()->Name)));
		// even though we only selected a name, we still get the other items in the cached object
		$this->assertNotNull($objProject2->ManagerPersonId, "ManagerPersonId found");

	}
	
	public function testQuerySingle() {
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 7),
			self::getTestClauses()
		);
		
		$this->helperVerifyKarenWolfe($targetPerson);

		$targetPerson2 = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 7),
			array (QQ::Select(QQN::Person()->FirstName))
		);

		$this->assertNotNull($targetPerson2->LastName, "Used a cached object");

		$targetPerson2->Save();

		$targetPerson2 = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 7),
			array (QQ::Select(QQN::Person()->FirstName))
		);
		$this->assertNull($targetPerson2->LastName, "Saving an object deleted it from the cache");

		$objTwoKey = TwoKey::QuerySingle(
			QQ::AndCondition (
				QQ::Equal(QQN::TwoKey()->Server, 'google.com'),
				QQ::Equal(QQN::TwoKey()->Directory, 'mail')
			),
			QQ::Clause(
				QQ::ExpandAsArray(QQN::TwoKey()->Project->PersonAsTeamMember)
			)
		);
		
		$this->assertEquals (count($objTwoKey->Project->_PersonAsTeamMemberArray), 6, '6 team members found.');
	}
	
	public function testEmptyArray() {
		$arrPeople = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 2),
			self::getTestClauses()
			);
			
		$this->assertTrue(is_array($arrPeople->_ProjectAsManagerArray), "_ProjectAsManagerArray is an array");
		$this->assertEquals(count($arrPeople->_ProjectAsManagerArray), 0, "_ProjectAsManagerArray has no Project objects");
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
		$this->assertEquals($intPersonTypeArray, array(
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
		$this->assertEquals(sizeof($targetPerson->_ProjectAsManagerArray), 2, "2 projects found");
		$targetProject = $this->verifyObjectPropertyHelper($targetPerson->_ProjectAsManagerArray, 'Name', 'ACME Payment System');
		
		$this->assertEquals(sizeof($targetProject->_MilestoneArray), 4, "4 milestones found");
		$this->verifyObjectPropertyHelper($targetProject->_MilestoneArray, 'Name', 'Milestone H');
	}

	public function testSelectSubsetInExpand() {
		Project::ClearCache();
		Person::ClearCache();
		Milestone::ClearCache();

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

		// generate full objects to load into cache
		$objPersonArray = Person::QueryArray(
			QQ::OrCondition(
				QQ::Like(QQN::Person()->ProjectAsManager->Name, '%ACME%'),
				QQ::Like(QQN::Person()->ProjectAsManager->Name, '%HR%')
			),
			// Let's expand on the Project, itself
			QQ::Clause(
				QQ::Expand(QQN::Person()->ProjectAsManager),
				QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)
			)
		);

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
			$this->assertNotNull($objPerson->FirstName, "FirstName should not be null, because it has been cached");
			$this->assertNotNull($objPerson->Id, "Id should not be null since it's always added to the select list");
			$this->assertNotNull($objPerson->_ProjectAsManager->Id, "ProjectAsManager->Id should not be null since id's are always added to the select list");
			$this->assertNotNull($objPerson->_ProjectAsManager->Name, "ProjectAsManager->Name should not be null since it was cached");
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
		$this->assertEquals(count($objProjectArray), 2, "_ProjectAsTeamMemberArray has 2 Project objects");
		
		$this->assertTrue(is_array($objPeopleArray), "_PersonAsTeamMemberArray is an array");
		$this->assertEquals(count($objPeopleArray), 5, "_PersonAsTeamMemberArray has 5 People objects");
		
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
		$this->assertEquals(count($objProjectArray), 2, "_ProjectAsTeamMemberArray has 2 Project objects");
		
		$objProjectArray = $objLogin->Person->_ProjectAsManagerArray;
		
		$this->assertTrue(is_array($objProjectArray), "_ProjectAsManagerArray is an array");
		$this->assertEquals(count($objProjectArray), 2, "_ProjectAsManagerArray has 2 Project objects");
				
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
		
		$this->assertEquals ($targetPerson->Id, 1, "Person 1 found.");
		$this->assertNotNull ($targetPerson->_ProjectAsManager, "Person 1 has a project.");

		$targetPerson = end($targetPersonArray);
		
		$this->assertEquals ($targetPerson->Id, 12, "Person 12 found.");
		$this->assertNull ($targetPerson->_ProjectAsManager, "Person 12 does not have a project.");
				
		//TODO: Conditional Array Expansion, requires API change
		
	}

}
?>