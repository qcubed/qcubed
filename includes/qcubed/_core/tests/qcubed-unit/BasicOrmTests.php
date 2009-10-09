<?php

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
	
	public function testGroupBy() {
		$objItems = Project::QueryArray(
			QQ::All(),
			QQ::Clause(
				QQ::GroupBy(QQN::Project()->Id),
				QQ::Count(QQN::Project()->PersonAsTeamMember->PersonId, 'team_member_count')
			)
		);
		
		$this->assertEqual(sizeof($objItems), 4, "4 projects found");
		
		$this->assertEqual($objItems[0]->Name, "ACME Website Redesign", "Project " . $objItems[0]->Name . " found");
		$this->assertEqual($objItems[0]->GetVirtualAttribute('team_member_count'), 5, "5 team members found for project " . $objItems[0]->Name);

		$this->assertEqual($objItems[1]->Name, "State College HR System", "Project " . $objItems[1]->Name . " found");
		$this->assertEqual($objItems[1]->GetVirtualAttribute('team_member_count'), 6, "6 team members found for project " . $objItems[1]->Name);	

		$this->assertEqual($objItems[2]->Name, "Blueman Industrial Site Architecture", "Project " . $objItems[2]->Name . " found");
		$this->assertEqual($objItems[2]->GetVirtualAttribute('team_member_count'), 5, "5 team members found for project " . $objItems[2]->Name);	
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
	}
	
	public function testQuerySingleEmpty() {
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 1241243));
		
		$this->assertEqual($targetPerson, null, "QuerySingle should return null for a not-found record");		
	}
}
?>