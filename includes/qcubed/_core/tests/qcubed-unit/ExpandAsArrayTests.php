<?php

class ExpandAsArrayTests extends QUnitTestCaseBase {    
	public function testMultiLevel() {
		$arrPeople = Person::LoadAll(
			self::getTestClauses()
		);
		
		$targetPerson = null;
		foreach ($arrPeople as $objPerson) {
			if ($objPerson->LastName == "Wolfe") {
				$targetPerson = $objPerson;
			}
		}
		
		$this->assertEqual(sizeof($arrPeople), 12);
		$this->helperVerifyKarenWolfe($targetPerson);
	}
	
	public function testQuerySingle() {
		$targetPerson = Person::QuerySingle(
			QQ::Equal(QQN::Person()->Id, 7),
			self::getTestClauses()
		);
		
		$this->helperVerifyKarenWolfe($targetPerson);
	}
	
	private static function getTestClauses() {
		return QQ::Clause(
				QQ::ExpandAsArray(QQN::Person()->Address),
				QQ::ExpandAsArray(QQN::Person()->ProjectAsManager),
				QQ::ExpandAsArray(QQN::Person()->ProjectAsManager->Milestone)
		);
	}
	
	private function helperVerifyKarenWolfe(Person $targetPerson) {
		$this->assertNotEqual($targetPerson, null, "Karen Wolfe found");
		
		$targetProject = null;
		foreach ($targetPerson->_ProjectAsManagerArray as $objProject) {
			if ($objProject->Name == "ACME Payment System") {
				$targetProject = $objProject;
			}
		}
		$this->assertEqual(sizeof($targetPerson->_ProjectAsManagerArray), 2, "2 projects found");
		$this->assertNotEqual($targetProject, null, "ACME Payment System project found");
		
		$targetMilestone = null;
		foreach ($targetProject->_MilestoneArray as $objMilestone) {
			if ($objMilestone->Name == "Milestone H") {
				$targetMilestone = $objMilestone;
			}
		}
		
		$this->assertEqual(sizeof($targetProject->_MilestoneArray), 4, "4 milestones found");
		$this->assertNotEqual($targetMilestone, null, "Milestone H found");				
	}
}
?>