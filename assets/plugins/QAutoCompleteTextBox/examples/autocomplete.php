<?php
	require('../../../../includes/configuration/prepend.inc.php');

	class SampleForm extends QForm {
		protected $txtServerSide;
		protected $txtClientSide;

		protected function Form_Create() {
			$this->txtServerSide = new QAjaxAutoCompleteTextBox($this, 'txtServerSide_Change');
			$this->txtServerSide->Name = QApplication::Translate('Keyword');
			
			$arrAutoCompleteItems = array();
			$arrPersons = Person::LoadAll();
			foreach ($arrPersons as $person) {
				$arrAutoCompleteItems[] = $person->FirstName . " " . $person->LastName;
			}
			
			$this->txtClientSide = new QJavaScriptAutoCompleteTextBox($this, $arrAutoCompleteItems);
		}
		
		public function txtServerSide_Change($strParameter){
			$objMemberArray = Person::QueryArray(
				QQ::OrCondition(
					QQ::Like(QQN::Person()->FirstName, $strParameter . '%'),
					QQ::Like(QQN::Person()->LastName,  $strParameter . '%')
				),
					
				QQ::Clause(QQ::OrderBy(QQN::Person()->FirstName))
			);
			
			$result = array();
			foreach($objMemberArray as $objMember){
				$result[] = $objMember->FirstName. " " . $objMember->LastName;
			}
			
			return $result;
		}
	}

	SampleForm::Run('SampleForm');
?>