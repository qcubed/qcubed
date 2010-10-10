<?php

/**
 * Tests for the ExpandAsArray functionality in QQuery
 * 
 * @package Tests
 */
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
}
?>