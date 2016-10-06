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
				
		$this->assertEquals(1, sizeof($items), "Saved the Person object");
			
		$objPerson2 = $items[0];
		$this->assertEquals("Test1", $objPerson2->FirstName, "The first name is correct");
		$this->assertEquals("Last1", $objPerson2->LastName, "The last name is correct");
		
		$objPerson2->Delete();

		$items = Person::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Person()->FirstName, "Test1"),
				QQ::Equal(QQN::Person()->LastName, "Last1")
			)						  
		);
		
		$this->assertEquals(0, sizeof($items), "Deleting the Person object");
	}

	public function testQueryArray() {
		$someDate = new QDateTime();
		$someDate->setDate(2006, 1, 1);
		
		$objItems = Milestone::QueryArray(
			QQ::GreaterThan(QQN::Milestone()->Project->StartDate, $someDate),
			QQ::OrderBy(QQN::Milestone()->Project->Name)
		);
		
		$this->assertEquals(3, sizeof($objItems));

		$this->assertEquals("Milestone F", $objItems[0]->Name);
		$this->assertEquals("Blueman Industrial Site Architecture", $objItems[0]->Project->Name);

		$this->assertEquals("Milestone D", $objItems[1]->Name);
		$this->assertEquals("State College HR System", $objItems[1]->Project->Name);

		$this->assertEquals("Milestone E", $objItems[2]->Name);
		$this->assertEquals("State College HR System", $objItems[2]->Project->Name);
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
		
		$this->assertEquals(3, $intItemCount);

		$intItemCount2 = Milestone::QueryCount(
			QQ::GreaterThan(QQN::Milestone()->Project->StartDate, $someDate),
			// test for an array of QQClause objects
			QQ::Clause(
				// The QQ::Distinct is used because of the https://github.com/qcubed/framework/issues/231 issue #231
				QQ::Distinct()
				, QQ::Distinct()
			)
		);
		
		$this->assertEquals(3, $intItemCount2);
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

		$this->assertEquals("Alex Smith", $objItems[0]->FirstName . " " . $objItems[0]->LastName);
		$this->assertEquals("Jennifer Smith", $objItems[1]->FirstName . " " . $objItems[1]->LastName);
		$this->assertEquals("Wendy Smith", $objItems[2]->FirstName . " " . $objItems[2]->LastName);
		$this->assertEquals("Ben Robinson", $objItems[3]->FirstName . " " . $objItems[3]->LastName);
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
		
		$this->assertEquals(4, sizeof($objItems), "4 projects found");
		
		$this->assertEquals(5, $objItems[0]->GetVirtualAttribute('team_member_count'), "5 team members found for the first project");
		$this->assertEquals(6, $objItems[1]->GetVirtualAttribute('team_member_count'), "6 team members found for the second project");
		$this->assertEquals(5, $objItems[2]->GetVirtualAttribute('team_member_count'), "5 team members found for the third project");
		$this->assertEquals(7, $objItems[3]->GetVirtualAttribute('team_member_count'), "7 team members found for the forth project");
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
		
		$this->assertEquals(array(
			"Brett Carlisle",
			"John Doe",
			"Samantha Jones",
			"Jacob Pratt",
			"Kendall Public",
			"Ben Robinson",
			"Alex Smith",
			"Wendy Smith",
			"Karen Wolfe"),
			$arrNamesOnly,
			"List managed persons is correct");
		
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
		
		$this->assertEquals(array(
			"Linda Brady",
			"John Doe",
			"Ben Robinson")
			, $arrNamesOnly
			, "Person-PersonType assn is correct");
		
	}
	
	public function testQuerySingleEmpty() {
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 1241243));
		
		$this->assertEquals(null, $targetPerson, "QuerySingle should return null for a not-found record");
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
		$this->assertEquals(12, count($objPersonArray), "12 people found.");
		
		$objTwoKeyArray = TwoKey::LoadAll();
		$this->assertEquals(6, count($objTwoKeyArray), "6 TwoKey items found.");
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
		$this->assertEquals("Milestone A", $objMilestone->Name, "Milestone 1 has name of Milestone A");
		$this->assertTrue(!is_null($objMilestone->Project->Name), "Project 1 has a name");
		$this->assertEquals("ACME Website Redesign", $objMilestone->Project->Name, "Project 1 has name of ACME Website Redesign");
		$this->assertTrue(!is_null($objMilestone->Project->ManagerPerson->FirstName), "Person 7 has a name");
		$this->assertEquals("Karen", $objMilestone->Project->ManagerPerson->FirstName, "Person 7 has first name of Karen");
		
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
		
		$this->assertEquals(array(
			"Samantha Jones",
			"Kendall Public",
			"Alex Smith",
			"Wendy Smith",
			"Karen Wolfe")
			, $arrNamesOnly
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
		
		$this->assertEquals(array(
			"Samantha Jones",
			"Kendall Public",
			"Alex Smith",
			"Wendy Smith",
			"Karen Wolfe"
			)
			, $arrNamesOnly
			, "Long reach Milestone to Project Team Member expansion is correct");
	}

	/**
	 * Make sure that expansions looking backwards are pointing to the same object looking forwards
	 */
	public function testExpandReverseReferences() {

		// Test virtual binding of reverse relationships
		$clauses = [QQ::Expand(QQN::Person()->ProjectAsManager)];
		$objPerson = Person::QuerySingle(QQ::All(), $clauses);
		$objPerson->FirstName = 'test';
		$objProject = $objPerson->ProjectAsManager;
		$objPerson2 = $objProject->ManagerPerson;

		$this->assertEquals('test', $objPerson2->FirstName);

		
		// Test forward reference looking back
		$clauses = [QQ::Expand(QQN::Project()->ManagerPerson)];
		$objProject = Project::QuerySingle(QQ::All(), $clauses);
		$objProject->Name = 'test';
		$objPerson = $objProject->ManagerPerson;
		$objProject2 = $objPerson->ProjectAsManager;

		$this->assertEquals('test', $objProject2->Name);

		// test unique reverse reference
		$clauses = [QQ::Expand(QQN::Person()->Login)];
		$objPerson = Person::QuerySingle(QQ::All(), $clauses);
		$objPerson->FirstName = 'test';
		$objLogin = $objPerson->Login;
		$objPerson2 = $objLogin->Person;

		$this->assertEquals('test', $objPerson2->FirstName);

		// test many-to-many expansion
		$clauses = [QQ::ExpandAsArray(QQN::Project()->PersonAsTeamMember)];
		$objProject = Project::QuerySingle(QQ::All(), $clauses);
		$objProject->Name = 'test';
		$objPersonArray = $objProject->_PersonAsTeamMemberArray;
		$objProject2 = $objPersonArray[0]->_ProjectAsTeamMember;

		$this->assertEquals('test', $objProject2->Name);

	}
	
	public function testHaving() {
		$objItems = Project::QueryArray(
			QQ::All(),
			QQ::Clause(
				QQ::Select(QQN::Project()->Id, QQN::Project()->Name),	// Some databases require selecting specific fields when aggregating
				QQ::GroupBy(QQN::Project()->Id),
				QQ::Count(QQN::Project()->PersonAsTeamMember->PersonId, 'team_member_count'),
				QQ::Having(QQ::SubSql('COUNT({1}) > 5', QQN::Project()->PersonAsTeamMember->PersonId)),
				QQ::OrderBy(QQN::Project()->Id)
			)
		);
		
		$this->assertEquals(2, sizeof($objItems), "2 projects found");
		
		$this->assertEquals("State College HR System", $objItems[0]->Name, "Project " . $objItems[0]->Name . " found");
		$this->assertEquals(6, $objItems[0]->GetVirtualAttribute('team_member_count'), "6 team members found for project " . $objItems[0]->Name);
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
		$this->assertEquals(7, $objPerson->Id, "Manager of first project found.");

	}

	public function testOrderByExpansion() {
		$objPersonArray = Person::QueryArray(
			QQ::All(),
			QQ::OrderBy(
				QQ::IsNotNull(QQN::Person()->ProjectAsManager->Description), false, QQN::Person()->ProjectAsManager->Id
			)
		);

		$this->assertEquals(7, $objPersonArray[0]->Id, "Found first project with manager");
	}

	public function testVirtualAttributeAliases() {
		$clauses = [
			QQ::GroupBy(QQN::Project()->ProjectStatusTypeId),
			QQ::Sum(QQN::Project()->Budget, 'Budget Amount'),
			QQ::Expand(QQ::Virtual('Balance', QQ::Func('SUM', QQ::Sub(QQN::Project()->Budget, QQN::Project()->Spent))))
		];
		$cond = QQ::Equal(QQN::Project()->ProjectStatusTypeId, ProjectStatusType::Open);

		$objProject = Project::QuerySingle($cond, $clauses);

		$amount1 = $objProject->GetVirtualAttribute('Budget Amount');
		$this->assertEquals(83000, $amount1);
		$amount2 = $objProject->GetVirtualAttribute('Balance');
		$this->assertEquals(5599.50, $amount2);
	}

	public function testSubSql() {
		$objProject = Project::QuerySingle(
			QQ::All(),
			QQ::Clause(
				QQ::Count(QQ::SubSql('DISTINCT {1}', QQN::Project()->ManagerPersonId), "manager_count")
			)
		);

		$this->assertEquals(3, $objProject->GetVirtualAttribute("manager_count"), "Project manager count is 3");
	}
}

