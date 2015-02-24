<?php
/**
 * ModelConnector tests
 * @package Tests
 */

require_once (__INCLUDES__ .'/connector/TypeTestConnector.class.php');
require_once (__INCLUDES__ .'/connector/ProjectConnector.class.php');
require_once (__INCLUDES__ .'/connector/AddressConnector.class.php');
require_once (__INCLUDES__ .'/connector/PersonConnector.class.php');


class ModelConnectorTests extends QUnitTestCaseBase {
	protected $frmTest;

	public function __construct($objForm) {
		parent::__construct($objForm);
		$this->frmTest = $objForm;
	}

	public function testBasicControls() {
		$mctTypeTest = TypeTestConnector::Create($this->frmTest);

		$mctTypeTest->DateControl->DateTime = new QDateTime ('10/10/2010');
		$mctTypeTest->DateTimeControl->DateTime = new QDateTime ('11/11/2011');
		$mctTypeTest->TestIntControl->Value = 5;
		$mctTypeTest->TestFloatControl->Value = 3.5;
		$mctTypeTest->TestVarcharControl->Text = 'abcde';
		$mctTypeTest->TestTextControl->Text = 'ABCDE';
		$mctTypeTest->TestBitControl->Checked = true;

		$id = $mctTypeTest->SaveTypeTest();

		$mctTypeTest2 = TypeTestConnector::Create($this->frmTest, $id);
		$dt = $mctTypeTest2->DateControl->DateTime;
		$this->assertTrue ($dt->IsEqualTo (new QDateTime ('10/10/2010', null, QDateTime::DateOnlyType)), 'Date only type saved correctly through connector.');
		$dt = $mctTypeTest2->DateTimeControl->DateTime;
		$this->assertTrue ($dt->IsEqualTo (new QDateTime ('11/11/2011')), 'Date time type saved correctly through connector.');
		$this->assertEqual($mctTypeTest2->TestIntControl->Value, 5, 'Integer control saved correctly.');
		$this->assertEqual($mctTypeTest2->TestFloatControl->Value, 3.5, 'Float type saved correctly.');
		$this->assertEqual($mctTypeTest2->TestVarcharControl->Text, 'abcde', 'Varchar control type saved correctly through connector.');
		$this->assertEqual($mctTypeTest2->TestTextControl->Text, 'ABCDE', 'Text type saved correctly through connector.');
		$this->assertEqual($mctTypeTest2->TestBitControl->Checked, true, 'Bit saved correctly through connector.');

		$mctTypeTest2->DeleteTypeTest();
	}

	public function testReference() {
		// test through list control
		$mctProject = ProjectConnector::Create ($this->frmTest, 1);
		$lstControl = $mctProject->ManagerPersonIdControl;
		$this->assertTrue ($lstControl instanceof QListBox);
		$this->assertTrue ($lstControl->SelectedValue, 7, "Read manager as person value.");
		$lstControl->SelectedValue = 6;
		$mctProject->SaveProject();

		$mctProject2 = ProjectConnector::Create ($this->frmTest, 1);
		$objPerson = $mctProject2->Project->ManagerPerson;
		$this->assertEqual($objPerson->Id, 6, "Forward reference saved correctly through connector.");
		$mctProject2->Project->ManagerPersonId = 7;
		$mctProject2->Project->Save();	// restore value

		// test refresh
		$mctProject->Load (2);
		$this->assertEqual($mctProject->ManagerPersonIdControl->SelectedValue, 4, "Reloaded forward reference connector");


		// test through auto complete
		$mctAddress = AddressConnector::Create ($this->frmTest);
		$lstControl = $mctAddress->PersonIdControl;
		$this->assertTrue ($lstControl instanceof QAutocomplete);
		$lstControl->SelectedValue = 2;
		$mctAddress->StreetControl->Text = 'Test Street';
		$mctAddress->CityControl->Text = 'Test City';
		$id = $mctAddress->SaveAddress();

		$mctAddress2 = AddressConnector::Create ($this->frmTest, $id);
		$objPerson = $mctAddress2->Address->Person;
		$this->assertEqual($objPerson->FirstName, 'Kendall', "Forward reference saved correctly through connector.");
		$mctAddress->DeleteAddress();

		// test refresh
		$mctAddress->Load (3);
		$this->assertEqual($mctAddress->CityControl->Text, 'New York');
	}

	public function testReverseReference() {
		$mctPerson = PersonConnector::Create ($this->frmTest, 7);
		$lstControl = $mctPerson->LoginControl;
		$this->assertTrue ($lstControl instanceof QListControl);
		$this->assertEqual ($lstControl->SelectedValue, 4);
		$this->assertEqual ($mctPerson->Person->Login->Username, 'kwolfe');

		// test save
		$lstControl->SelectedValue = 5;
		$mctPerson->SavePerson();
		$this->assertEqual ($mctPerson->Person->Login->Id, 5);
		// restore
		$lstControl->SelectedValue = 4;
		$mctPerson->SavePerson();
		$this->assertEqual ($mctPerson->Person->Login->Id, 4);

		// test refresh
		$mctPerson->Load(3);
		$this->assertEqual ($lstControl->SelectedValue, 2);
		$this->assertEqual ($mctPerson->Person->Login->Username, 'brobinson');
	}

	public function testManyToMany() {
		$clauses = array(QQ::ExpandAsArray(QQN::Person()->ProjectAsTeamMember));
		$objPerson = Person::Load (2, $clauses);
		$mctPerson = new PersonConnector ($this->frmTest, $objPerson);
		$lstControl = $mctPerson->ProjectAsTeamMemberControl;
		$this->assertTrue ($lstControl instanceof QListControl);
		$values = $lstControl->SelectedValues;
		sort ($values);
		$this->assertEqual ($values[0], 1);
		$this->assertEqual ($values[1], 2);
		$this->assertEqual ($values[2], 4);

		// test refresh
		$mctPerson->Load (3, $clauses);
		$values = $lstControl->SelectedValues;
		sort ($values);
		$this->assertEqual ($values[0], 4);
		$this->assertEqual (count($values), 1);

		// Test save
		$lstControl->SelectedValues = [2,4];
		$mctPerson->SavePerson();
		$a = Project::LoadArrayByPersonAsTeamMember(3);
		$this->assertEqual($a[0]->Id, 2);
		$this->assertEqual($a[1]->Id, 4);

		$lstControl->SelectedValues = [4];
		$mctPerson->SavePerson();
		$a = Project::LoadArrayByPersonAsTeamMember(3);
		$this->assertEqual($a[0]->Id, 4);

	}

	public function testType1() {
		$mctProject = ProjectConnector::Create($this->frmTest,3);
		$this->assertEqual ($mctProject->ProjectStatusTypeIdControl->SelectedValue, 1);

		$mctProject->ProjectStatusTypeIdControl->SelectedValue = ProjectStatusType::Cancelled;
		$mctProject->SaveProject();
		$this->assertEqual($mctProject->Project->ProjectStatusTypeId, ProjectStatusType::Cancelled);

		// restore
		$mctProject->ProjectStatusTypeIdControl->SelectedValue = ProjectStatusType::Open;
		$mctProject->SaveProject();
		$this->assertEqual($mctProject->Project->ProjectStatusTypeId, ProjectStatusType::Open);

		$mctProject->Load (1);
		$this->assertEqual ($mctProject->ProjectStatusTypeIdControl->SelectedValue, 3);

	}

	public function testTypeMulti() {
		$mctPerson = PersonConnector::Create ($this->frmTest, 3);
		$values = $mctPerson->PersonTypeControl->SelectedValues;
		$this->assertEqual(count ($values), 3);

		$values2 = $values;
		$values2[] = 5;

		$mctPerson->PersonTypeControl->SelectedValues = $values2;
		$mctPerson->SavePerson();
		$values3 = $mctPerson->Person->GetPersonTypeArray();
		$this->assertEqual(count ($values3), 4);
		$mctPerson->PersonTypeControl->SelectedValues = $values;
		$mctPerson->SavePerson();
		$values3 = $mctPerson->Person->GetPersonTypeArray();
		$this->assertEqual(count ($values3), 3);
	}

	/**
	 * These tests check to see that the codegen_options.json file is being used during code generation.
	 */
	public function testOverrides() {

		$mctAddress = AddressConnector::Create ($this->frmTest);

		$blnError = false;
		try {
			$mctAddress->StreetLabel;
		}
		catch (QUndefinedPropertyException $e) {
			$blnError = true;
		}
		$this->assertTrue($blnError, 'Street Label was removed by override.');

		$this->assertEqual($mctAddress->CityControl->Width, 100);

		// Many-to-Many settings
		$mctProject = ProjectConnector::Create ($this->frmTest);
		$this->assertEqual($mctProject->PersonAsTeamMemberControl->RepeatColumns, 3);
		$this->assertEqual($mctProject->PersonAsTeamMemberControl->Name, 'Team Members');

		// Unique Reverse Reference
		$mctPerson = PersonConnector::Create ($this->frmTest);
		$this->assertTrue ($mctPerson->LoginControl->Required, 'Reverse reference was marked required by override file.');

		$objItem = $mctPerson->LoginControl->GetItem(0);
		$this->assertEqual ($objItem->Name, '- Select One -', 'Required value was detected by list control.');

	}

}
?>