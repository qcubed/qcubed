<?php
    require_once('../qcubed.inc.php');
    
	class SpeedForm extends QForm {
		protected $pnlTiny;
		protected $pnlBig;

		protected $btnGo;

		protected function Form_Create() {

			$count = 10000;
			Project::ClearCache();
			Person::ClearCache();

			// Create test persons in database.

			// Tiny objects
			if (Person::CountAll() < $count) {
				for ($i = 0; $i < $count; $i++) {
					$objPerson = new Person();
					$objPerson->FirstName = 'FirstName' . $i;
					$objPerson->LastName = 'LastName' . $i;
					$objPerson->Save();
				}
			}

			// Bigger objects with expansion
			if (Project::CountAll() < $count) {
				for ($i = 0; $i < $count; $i++) {
					$objProject = new Project();
					$objProject->Name = 'Project' . $i;
					$objProject->ProjectStatusTypeId = ProjectStatusType::Open;
					$objProject->ManagerPersonId = $i % 1000 + 1000;
					$objProject->Description = 'Description' . $i;
					$objProject->StartDate = QDateTime::Now();
					$objProject->EndDate = QDateTime::Now();
					$objProject->Budget = $i;
					$objProject->Spent = 1;
					$objProject->Save();
				}
			}

			$this->pnlTiny = new QPanel($this);
			$this->pnlTiny->Name = '10,000 Person Objects';

			$this->pnlBig = new QPanel($this);
			$this->pnlBig->Name = '10,000 Project Objects With Expansion';

			$this->btnGo = new QButton($this);
			$this->btnGo->Text = 'Go';
			$this->btnGo->AddAction (new QClickEvent(), new QAjaxAction('Go_Click'));

		}

		protected function Go_Click() {
			QApplication::$blnLocalCache = false;

			$timeNoCache = -microtime(true);
			$a = Person::LoadAll(); // noncached loads
			$timeNoCache += microtime(true);

			QApplication::$blnLocalCache = true;

			$timeLoad1Cached = -microtime(true);
			$a = Person::LoadAll(); // noncached loads
			$timeLoad1Cached += microtime(true);
			$timeLoad2Cached = -microtime(true);
			$a = Person::LoadAll(); // cached loads
			$timeLoad2Cached += microtime(true);

			$this->pnlTiny->Text = sprintf ("Load No Cache: %f \n", $timeNoCache) .
				sprintf ("Populate Cache: %f \n", $timeLoad1Cached) .
				sprintf ("Load With Cache: %f \n", $timeLoad2Cached);

			$cond = QQ::Equal (QQN::Project()->ProjectStatusTypeId, ProjectStatusType::Open);
			$clauses[] = QQ::Expand (QQN::Project()->ManagerPerson);

			Project::ClearCache();
			Person::ClearCache();

			QApplication::$blnLocalCache = false;

			$timeNoCache = -microtime(true);
			$a = Project::QueryArray($cond, $clauses); // noncached loads
			$timeNoCache += microtime(true);

			QApplication::$blnLocalCache = true;

			$timeLoad1Cached = -microtime(true);
			$a = Project::QueryArray($cond, $clauses); // noncached loads
			$timeLoad1Cached += microtime(true);
			$timeLoad2Cached = -microtime(true);
			$a = Project::QueryArray($cond, $clauses); // cached loads
			$timeLoad2Cached += microtime(true);

			$this->pnlBig->Text = sprintf ("Load No Cache: %f \n", $timeNoCache) .
				sprintf ("Populate Cache: %f \n", $timeLoad1Cached) .
				sprintf ("Load With Cache: %f \n", $timeLoad2Cached);
		}

	}
	SpeedForm::Run('SpeedForm');
?>