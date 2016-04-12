<?php

/**
 * 
 * @package Tests
 */
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
if(!class_exists('TwoKey')){
    require_once __INCLUDES__ .'/model/TwoKey.class.php';
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
				
		$this->assertEquals(sizeof($items), 1, "Saved the Person object");
			
		$objPerson2 = $items[0];
		$this->assertEquals($objPerson2->FirstName, "Test1", "The first name is correct");
		$this->assertEquals($objPerson2->LastName,  "Last1", "The last name is correct");
		
		$objPerson2->Delete();

		$items = Person::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Person()->FirstName, "Test1"),
				QQ::Equal(QQN::Person()->LastName, "Last1")
			)						  
		);
		
		$this->assertEquals(sizeof($items), 0, "Deleting the Person object");
	}

	public function testQueryArray() {
		$someDate = new QDateTime();
		$someDate->setDate(2006, 1, 1);
		
		$objItems = Milestone::QueryArray(
			QQ::GreaterThan(QQN::Milestone()->Project->StartDate, $someDate),
			QQ::OrderBy(QQN::Milestone()->Project->Name)
		);
		
		$this->assertEquals(sizeof($objItems), 3);

		$this->assertEquals($objItems[0]->Name, "Milestone F");
		$this->assertEquals($objItems[0]->Project->Name, "Blueman Industrial Site Architecture");

		$this->assertEquals($objItems[1]->Name, "Milestone D");
		$this->assertEquals($objItems[1]->Project->Name, "State College HR System");

		$this->assertEquals($objItems[2]->Name, "Milestone E");
		$this->assertEquals($objItems[2]->Project->Name, "State College HR System");
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
		
		$this->assertEquals($intItemCount, 3);

		$intItemCount2 = Milestone::QueryCount(
			QQ::GreaterThan(QQN::Milestone()->Project->StartDate, $someDate),
			// test for an array of QQClause objects
			QQ::Clause(
				// The QQ::Distinct is used because of the https://github.com/qcubed/framework/issues/231 issue #231
				QQ::Distinct()
				, QQ::Distinct()
			)
		);
		
		$this->assertEquals($intItemCount2, 3);
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

		$this->assertEquals($objItems[0]->FirstName . " " . $objItems[0]->LastName, "Alex Smith");
		$this->assertEquals($objItems[1]->FirstName . " " . $objItems[1]->LastName, "Jennifer Smith");
		$this->assertEquals($objItems[2]->FirstName . " " . $objItems[2]->LastName, "Wendy Smith");
		$this->assertEquals($objItems[3]->FirstName . " " . $objItems[3]->LastName, "Ben Robinson");
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
		
		$this->assertEquals(sizeof($objItems), 4, "4 projects found");
		
		$this->assertEquals($objItems[0]->GetVirtualAttribute('team_member_count'), 5, "5 team members found for the first project");
		$this->assertEquals($objItems[1]->GetVirtualAttribute('team_member_count'), 6, "6 team members found for the second project");
		$this->assertEquals($objItems[2]->GetVirtualAttribute('team_member_count'), 5, "5 team members found for the third project");
		$this->assertEquals($objItems[3]->GetVirtualAttribute('team_member_count'), 7, "7 team members found for the forth project");
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
		
		$this->assertEquals($arrNamesOnly, array(
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
		
		$this->assertEquals($arrNamesOnly, array(
			"Linda Brady",
			"John Doe",
			"Ben Robinson")
		, "Person-PersonType assn is correct");
		
	}
	
	public function testQuerySingleEmpty() {
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 1241243));
		
		$this->assertEquals($targetPerson, null, "QuerySingle should return null for a not-found record");		
	}

	public function testQuerySelectSubset() {
		$objPersonArray = Person::LoadAll(QQ::Select(QQN::Person()->FirstName));
		foreach ($objPersonArray as $objPerson) {
			$this->assertNull($objPerson->LastName, "LastName should be null, since it was not selected");
			$this->assertNotNull($objPerson->Id, "Id should not be null since it's always added to the select list");
		}
	}
	
	public function testLoadAll() {
		$objPersonArray = Person::LoadAll ();
		$this->assertEquals(count($objPersonArray), 12, "12 people found.");
		
		$objTwoKeyArray = TwoKey::LoadAll();
		$this->assertEquals(count($objTwoKeyArray), 6, "6 TwoKey items found.");
	}
	
	public function testQuerySelectSubsetSkipPK() {
		$objSelect = QQ::Select(QQN::Person()->FirstName);
		$objSelect->SetSkipPrimaryKey(true);
		$objPersonArray = Person::LoadAll($objSelect);
		foreach ($objPersonArray as $objPerson) {
			$this->assertNull($objPerson->LastName, "LastName should be null, since it was not selected");
			$this->assertNull($objPerson->Id, "Id should be null since SkipPrimaryKey is set on the Select object");
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
		$this->assertEquals($objMilestone->Name, "Milestone A", "Milestone 1 has name of Milestone A");
		$this->assertTrue(!is_null($objMilestone->Project->Name), "Project 1 has a name");
		$this->assertEquals($objMilestone->Project->Name, "ACME Website Redesign", "Project 1 has name of ACME Website Redesign");
		$this->assertTrue(!is_null($objMilestone->Project->ManagerPerson->FirstName), "Person 7 has a name");
		$this->assertEquals($objMilestone->Project->ManagerPerson->FirstName, "Karen", "Person 7 has first name of Karen");
		
		 $clauses = QQ::Clause(
			QQ::ExpandAsArray (QQN::Project()->PersonAsTeamMember),
			QQ::OrderBy (QQN::Project()->PersonAsTeamMember->Person->LastName, QQN::Project()->PersonAsTeamMember->Person->FirstName)
		);
		
		// short reach
		$objProject = 
			Project::QuerySingle(
				QQ::Equal (QQN::Project()->Id, 1),
				$clauses
			);
			
		$objPersonArray = $objProject->_PersonAsTeamMemberArray;
		$arrNamesOnly = array();
		foreach ($objPersonArray as $item) {
			$arrNamesOnly[] = $item->FirstName . " " . $item->LastName;
		}
		
		$this->assertEquals($arrNamesOnly, array(
			"Samantha Jones",
			"Kendall Public",
			"Alex Smith",
			"Wendy Smith",
			"Karen Wolfe")
				, "Project Team Member expansion is correct");
		
		// long reach
		$clauses = QQ::Clause(
			QQ::ExpandAsArray (QQN::Milestone()->Project->PersonAsTeamMember),
			QQ::OrderBy (QQN::Milestone()->Project->PersonAsTeamMember->Person->LastName, QQN::Milestone()->Project->PersonAsTeamMember->Person->FirstName)
		);
		
		
		$objMilestone = 
			Milestone::QuerySingle(
				QQ::Equal (QQN::Milestone()->Id, 1),
				$clauses
			);
			
		$objPersonArray = $objMilestone->Project->_PersonAsTeamMemberArray;
		$arrNamesOnly = array();
		foreach ($objPersonArray as $item) {
			$arrNamesOnly[] = $item->FirstName . " " . $item->LastName;
		}
		
		$this->assertEquals($arrNamesOnly, array(
			"Samantha Jones",
			"Kendall Public",
			"Alex Smith",
			"Wendy Smith",
			"Karen Wolfe"
			)
		, "Long reach Milestone to Project Team Member expansion is correct");
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
		
		$this->assertEquals(sizeof($objItems), 2, "2 projects found");
		
		$this->assertEquals($objItems[0]->Name, "State College HR System", "Project " . $objItems[0]->Name . " found");
		$this->assertEquals($objItems[0]->GetVirtualAttribute('team_member_count'), 6, "6 team members found for project " . $objItems[0]->Name);	
	}
	
	public function testEmptyColumns() {
		$objItem = Login::QuerySingle(
			QQ::Equal(QQN::Login()->Id, 1)
		);
		
		$var = $objItem->IsEnabled;

		$this->assertNotNull($var, "Zero column does not return null. ");
		$this->assertTrue($var == 0, "Zero boolean column is false or zero. ");

		$objItem = Project::QuerySingle(
			QQ::Equal(QQN::Project()->Id, 2)
		);
		$this->assertTrue($objItem->EndDate === null, "Null date column returns a null.");

		// Testing unique reverse reference on null
		$objPerson = new Person();
		$objLogin = $objPerson->Login;

		$this->assertNull($objLogin, "New record should not be associated with null PK.");
	}

	public function testOrderByReverseReference() {
		// orders by the private key of the reverse reference node.
		$objPerson = Person::QuerySingle(
			QQ::IsNotNull(QQN::Person()->ProjectAsManager->Id),
			[QQ::OrderBy(QQN::Person()->ProjectAsManager)]
		);
		$this->assertEquals($objPerson->Id, 7, "Manager of first project found.");

	}

	public function testOrderByExpansion() {
		$objPersonArray = Person::QueryArray(
			QQ::All(),
			QQ::OrderBy(
				QQ::IsNotNull(QQN::Person()->ProjectAsManager->Description), false, QQN::Person()->ProjectAsManager->Id
			)
		);

		$this->assertEquals($objPersonArray[0]->Id, 7, "Found first project with manager");
	}

}
?>
