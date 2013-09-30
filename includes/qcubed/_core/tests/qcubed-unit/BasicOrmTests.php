<?php

/**
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
class BasicOrmTests extends QUnitTestCaseBase {    
	public function testSaveAndDelete() {
		$objPerson1 = new Person();
		$objPerson1->FirstName = "Test1";
		$objPerson1->LastName = "Last1";
		$objPerson1->Save();
		
		$items = Person::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Person()->FirstName, "Test1"),
				QQ::Equal(QQN::Person()->LastName, "Last1")
			)
		);
				
		$this->assertEqual(sizeof($items), 1, "Saved the Person object");
			
		$objPerson2 = $items[0];
		$this->assertEqual($objPerson2->FirstName, "Test1", "The first name is correct");
		$this->assertEqual($objPerson2->LastName,  "Last1", "The last name is correct");
		
		$objPerson2->Delete();

		$items = Person::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Person()->FirstName, "Test1"),
				QQ::Equal(QQN::Person()->LastName, "Last1")
			)						  
		);
		
		$this->assertEqual(sizeof($items), 0, "Deleting the Person object");
	}

	public function testQueryArray() {
		$someDate = new QDateTime();
		$someDate->setDate(2006, 1, 1);
		
		$objItems = Milestone::QueryArray(
			QQ::GreaterThan(QQN::Milestone()->Project->StartDate, $someDate),
			QQ::OrderBy(QQN::Milestone()->Project->Name)
		);
		
		$this->assertEqual(sizeof($objItems), 3);

		$this->assertEqual($objItems[0]->Name, "Milestone F");
		$this->assertEqual($objItems[0]->Project->Name, "Blueman Industrial Site Architecture");

		$this->assertEqual($objItems[1]->Name, "Milestone D");
		$this->assertEqual($objItems[1]->Project->Name, "State College HR System");

		$this->assertEqual($objItems[2]->Name, "Milestone E");
		$this->assertEqual($objItems[2]->Project->Name, "State College HR System");
	}
	
	public function testQueryCount() {
		$someDate = new QDateTime();
		$someDate->setDate(2006, 1, 1);
		
		$intItemCount = Milestone::QueryCount(
			QQ::GreaterThan(QQN::Milestone()->Project->StartDate, $someDate),
			// test for single QQClause object
			// the subject of the https://github.com/qcubed/framework/issues/100 issue #100
			QQ::Distinct()
		);
		
		$this->assertEqual($intItemCount, 3);

		$intItemCount2 = Milestone::QueryCount(
			QQ::GreaterThan(QQN::Milestone()->Project->StartDate, $someDate),
			// test for an array of QQClause objects
			QQ::Clause(
				// The QQ::Distinct is used because of the https://github.com/qcubed/framework/issues/231 issue #231
				QQ::Distinct()
				, QQ::Distinct()
			)
		);
		
		$this->assertEqual($intItemCount2, 3);
	}
	
	public function testOrderByCondition() {
		$objItems = Person::QueryArray(
			QQ::All(),
			QQ::Clause(
				QQ::OrderBy(
					QQ::NotEqual(QQN::Person()->LastName, 'Smith'), 
					QQN::Person()->FirstName)
				)
			);

		$this->assertEqual($objItems[0]->FirstName . " " . $objItems[0]->LastName, "Alex Smith");
		$this->assertEqual($objItems[1]->FirstName . " " . $objItems[1]->LastName, "Jennifer Smith");
		$this->assertEqual($objItems[2]->FirstName . " " . $objItems[2]->LastName, "Wendy Smith");
		$this->assertEqual($objItems[3]->FirstName . " " . $objItems[3]->LastName, "Ben Robinson");
	}
	
	public function testGroupBy() {
		$objItems = Project::QueryArray(
			QQ::All(),
			QQ::Clause(
				QQ::GroupBy(QQN::Project()->Id),
				QQ::Count(QQN::Project()->PersonAsTeamMember->PersonId, 'team_member_count'),
				QQ::OrderBy(QQN::Project()->Id)
			)
		);
		
		$this->assertEqual(sizeof($objItems), 4, "4 projects found");
		
		$this->assertEqual($objItems[0]->GetVirtualAttribute('team_member_count'), 5, "5 team members found for the first project");
		$this->assertEqual($objItems[1]->GetVirtualAttribute('team_member_count'), 6, "6 team members found for the second project");
		$this->assertEqual($objItems[2]->GetVirtualAttribute('team_member_count'), 5, "5 team members found for the third project");
		$this->assertEqual($objItems[3]->GetVirtualAttribute('team_member_count'), 7, "7 team members found for the forth project");
	}
	
	public function testAssociationTables() {
		// All People Who Are on a Project Managed by Karen Wolfe (Person ID #7)		
		$objPersonArray = Person::QueryArray(
			QQ::Equal(QQN::Person()->ProjectAsTeamMember->Project->ManagerPersonId, 7),
			QQ::Clause(
				QQ::Distinct(),
				QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)
			)
		);
		
		$arrNamesOnly = array();
		foreach ($objPersonArray as $item) {
			$arrNamesOnly[] = $item->FirstName . " " . $item->LastName;
		 }
		
		$this->assertEqual($arrNamesOnly, array(
			"Brett Carlisle",
			"John Doe",
			"Samantha Jones",
			"Jacob Pratt",
			"Kendall Public",
			"Ben Robinson",
			"Alex Smith",
			"Wendy Smith",
			"Karen Wolfe")
		, "List managed persons is correct");
		
		$objPersonArray = Person::QueryArray(
			QQ::Equal(QQN::Person()->PersonType->PersonTypeId, PersonType::Inactive),
			QQ::Clause(
				QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)
			)
		);
		
		$arrNamesOnly = array();
		foreach ($objPersonArray as $item) {
			$arrNamesOnly[] = $item->FirstName . " " . $item->LastName;
		}
		
		$this->assertEqual($arrNamesOnly, array(
			"Linda Brady",
			"John Doe",
			"Ben Robinson")
		, "Person-PersonType assn is correct");
		
	}
	
	public function testQuerySingleEmpty() {
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 1241243));
		
		$this->assertEqual($targetPerson, null, "QuerySingle should return null for a not-found record");		
	}

	public function testQuerySelectSubset() {
		$objPersonArray = Person::LoadAll(QQ::Select(QQN::Person()->FirstName));
		foreach ($objPersonArray as $objPerson) {
			$this->assertNull($objPerson->LastName, "LastName should be null, since it was not selected");
			$this->assertNotNull($objPerson->Id, "Id should not be null since it's always added to the select list");
		}
	}
	
	public function testExpand() {
		// Test intermediate nodes on expansion
		 $clauses = QQ::Clause(
			QQ::Expand(QQN::Milestone()->Project->ManagerPerson)
		);
		
		$objMilestone = 
			Milestone::QuerySingle(
				QQ::Equal (QQN::Milestone()->Id, 1),
				$clauses
			);
		
		$this->assertTrue(!is_null($objMilestone->Name), "Milestone 1 has a name");
		$this->assertEqual($objMilestone->Name, "Milestone A", "Milestone 1 has name of Milestone A");
		$this->assertTrue(!is_null($objMilestone->Project->Name), "Project 1 has a name");
		$this->assertEqual($objMilestone->Project->Name, "ACME Website Redesign", "Project 1 has name of ACME Website Redesign");
		$this->assertTrue(!is_null($objMilestone->Project->ManagerPerson->FirstName), "Person 7 has a name");
		$this->assertEqual($objMilestone->Project->ManagerPerson->FirstName, "Karen", "Person 7 has first name of Karen");
		
	}
	
	public function testHaving() {
		$objItems = Project::QueryArray(
			QQ::All(),
			QQ::Clause(
				QQ::GroupBy(QQN::Project()->Id),
				QQ::Count(QQN::Project()->PersonAsTeamMember->PersonId, 'team_member_count'),
				QQ::Having(QQ::SubSql('COUNT({1}) > 5', QQN::Project()->PersonAsTeamMember->PersonId)),
				QQ::OrderBy(QQN::Project()->Id)
			)
		);
		
		$this->assertEqual(sizeof($objItems), 2, "2 projects found");
		
		$this->assertEqual($objItems[0]->GetVirtualAttribute('team_member_count'), 6, "6 team members found for the first project");
		$this->assertEqual($objItems[1]->GetVirtualAttribute('team_member_count'), 7, "7 team members found for the second project");
	}
}
?>
