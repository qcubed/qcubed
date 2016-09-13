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
	protected static $frmTest;

	/**
	 * @beforeClass
	 */
	public static function setUpClass()
	{
		global $_FORM;
		self::$frmTest = $_FORM;
	}

	public function testBasicControls() {
		$mctTypeTest = TypeTestConnector::Create(self::$frmTest);

		$mctTypeTest->DateControl->DateTime = new QDateTime ('10/10/2010');
		$mctTypeTest->DateTimeControl->DateTime = new QDateTime ('11/11/2011');
		$mctTypeTest->TestIntControl->Value = 5;
		$mctTypeTest->TestFloatControl->Value = 3.5;
		$mctTypeTest->TestVarcharControl->Text = 'abcde';
		$mctTypeTest->TestTextControl->Text = 'ABCDE';
		$mctTypeTest->TestBitControl->Checked = true;

		$id = $mctTypeTest->SaveTypeTest();

		$mctTypeTest2 = TypeTestConnector::Create(self::$frmTest, $id);
		$dt = $mctTypeTest2->DateControl->DateTime;
		$this->assertTrue ($dt->IsEqualTo (new QDateTime ('10/10/2010', null, QDateTime::DateOnlyType)), 'Date only type saved correctly through connector.');
		$dt = $mctTypeTest2->DateTimeControl->DateTime;
		$this->assertTrue ($dt->IsEqualTo (new QDateTime ('11/11/2011')), 'Date time type saved correctly through connector.');
		$this->assertEquals(5, $mctTypeTest2->TestIntControl->Value, 'Integer control saved correctly.');
		$this->assertEquals(3.5, $mctTypeTest2->TestFloatControl->Value, 'Float type saved correctly.');
		$this->assertEquals('abcde', $mctTypeTest2->TestVarcharControl->Text, 'Varchar control type saved correctly through connector.');
		$this->assertEquals('ABCDE', $mctTypeTest2->TestTextControl->Text, 'Text type saved correctly through connector.');
		$this->assertEquals(true, $mctTypeTest2->TestBitControl->Checked, 'Bit saved correctly through connector.');

		$mctTypeTest2->DeleteTypeTest();
	}

	public function testReference() {
		// test through list control
		$mctProject = ProjectConnector::Create (self::$frmTest, 1);
		$lstControl = $mctProject->ManagerPersonIdControl;
		$this->assertTrue ($lstControl instanceof QListBox);
		$this->assertEquals ($lstControl->SelectedValue, 7, "Read manager as person value.");
		$lstControl->SelectedValue = 6;
		$mctProject->SaveProject();

		$mctProject2 = ProjectConnector::Create (self::$frmTest, 1);
		$objPerson = $mctProject2->Project->ManagerPerson;
		$this->assertEquals(6, $objPerson->Id, "Forward reference saved correctly through connector.");
		$mctProject2->Project->ManagerPersonId = 7;
		$mctProject2->Project->Save();	// restore value

		// test refresh
		$mctProject->Load (2);
		$this->assertEquals(4, $mctProject->ManagerPersonIdControl->SelectedValue, "Reloaded forward reference connector");


		// test through auto complete
		$mctAddress = AddressConnector::Create (self::$frmTest);
		$lstControl = $mctAddress->PersonIdControl;
		$this->assertTrue ($lstControl instanceof QAutocomplete);
		$lstControl->SelectedValue = 2;
		$mctAddress->StreetControl->Text = 'Test Street';
		$mctAddress->CityControl->Text = 'Test City';
		$id = $mctAddress->SaveAddress();

		$mctAddress2 = AddressConnector::Create (self::$frmTest, $id);
		$objPerson = $mctAddress2->Address->Person;
		$this->assertEquals('Kendall', $objPerson->FirstName, "Forward reference saved correctly through connector.");
		$mctAddress->DeleteAddress();

		// test refresh
		$mctAddress->Load (3);
		$this->assertEquals('New York', $mctAddress->CityControl->Text);
	}

	public function testReverseReference() {
		$mctPerson = PersonConnector::Create (self::$frmTest, 7);
		$lstControl = $mctPerson->LoginControl;
		$this->assertTrue ($lstControl instanceof QListControl);
		$this->assertEquals ($lstControl->SelectedValue, 4);
		$this->assertEquals ($mctPerson->Person->Login->Username, 'kwolfe');

		// test save
		$lstControl->SelectedValue = 5;
		$mctPerson->SavePerson();
		$this->assertEquals ($mctPerson->Person->Login->Id, 5);
		// restore
		$lstControl->SelectedValue = 4;
		$mctPerson->SavePerson();
		$this->assertEquals ($mctPerson->Person->Login->Id, 4);

		// test refresh
		$mctPerson->Load(3);
		$this->assertEquals ($lstControl->SelectedValue, 2);
		$this->assertEquals ($mctPerson->Person->Login->Username, 'brobinson');
	}

	public function testManyToMany() {
		$clauses = array(QQ::ExpandAsArray(QQN::Person()->ProjectAsTeamMember));
		$objPerson = Person::Load (2, $clauses);
		$mctPerson = new PersonConnector (self::$frmTest, $objPerson);
		$lstControl = $mctPerson->ProjectAsTeamMemberControl;
		$this->assertTrue ($lstControl instanceof QListControl);
		$values = $lstControl->SelectedValues;
		sort ($values);
		$this->assertEquals ($values[0], 1);
		$this->assertEquals ($values[1], 2);
		$this->assertEquals ($values[2], 4);

		// test refresh
		$mctPerson->Load (3, $clauses);
		$values = $lstControl->SelectedValues;
		sort ($values);
		$this->assertEquals ($values[0], 4);
		$this->assertEquals (count($values), 1);

		// Test save
		$lstControl->SelectedValues = [2,4];
		$mctPerson->SavePerson();
		$a = Project::LoadArrayByPersonAsTeamMember(3);
		$this->assertEquals(2, $a[0]->Id);
		$this->assertEquals(4, $a[1]->Id);

		$lstControl->SelectedValues = [4];
		$mctPerson->SavePerson();
		$a = Project::LoadArrayByPersonAsTeamMember(3);
		$this->assertEquals(4, $a[0]->Id);

	}

	public function testType1() {
		$mctProject = ProjectConnector::Create(self::$frmTest,3);
		$this->assertEquals ($mctProject->ProjectStatusTypeIdControl->SelectedValue, 1);

		$mctProject->ProjectStatusTypeIdControl->SelectedValue = ProjectStatusType::Cancelled;
		$mctProject->SaveProject();
		$this->assertEquals(ProjectStatusType::Cancelled, $mctProject->Project->ProjectStatusTypeId);

		// restore
		$mctProject->ProjectStatusTypeIdControl->SelectedValue = ProjectStatusType::Open;
		$mctProject->SaveProject();
		$this->assertEquals(ProjectStatusType::Open, $mctProject->Project->ProjectStatusTypeId);

		$mctProject->Load (1);
		$this->assertEquals ($mctProject->ProjectStatusTypeIdControl->SelectedValue, 3);

	}

	public function testTypeMulti() {
		$mctPerson = PersonConnector::Create (self::$frmTest, 3);
		$values = $mctPerson->PersonTypeControl->SelectedValues;
		$this->assertEquals(3, count ($values));

		$values2 = $values;
		$values2[] = 5;

		$mctPerson->PersonTypeControl->SelectedValues = $values2;
		$mctPerson->SavePerson();
		$values3 = $mctPerson->Person->GetPersonTypeArray();
		$this->assertEquals(4, count ($values3));
		$mctPerson->PersonTypeControl->SelectedValues = $values;
		$mctPerson->SavePerson();
		$values3 = $mctPerson->Person->GetPersonTypeArray();
		$this->assertEquals(3, count ($values3));
	}

	/**
	 * These tests check to see that the codegen_options.json file is being used during code generation.
	 */
	public function testOverrides() {

		$mctAddress = AddressConnector::Create (self::$frmTest);

		$blnError = false;
		try {
			$mctAddress->StreetLabel;
		}
		catch (QUndefinedPropertyException $e) {
			$blnError = true;
		}
		$this->assertTrue($blnError, 'Street Label was removed by override.');

		$this->assertEquals('100px', $mctAddress->CityControl->Width);

		// Many-to-Many settings
		$mctProject = ProjectConnector::Create (self::$frmTest);
		$this->assertEquals(3, $mctProject->PersonAsTeamMemberControl->RepeatColumns);
		$this->assertEquals('Team Members', $mctProject->PersonAsTeamMemberControl->Name);

		// Unique Reverse Reference
		$mctPerson = PersonConnector::Create (self::$frmTest);
		$this->assertTrue ($mctPerson->LoginControl->Required, 'Reverse reference was marked required by override file.');

		$objItem = $mctPerson->LoginControl->GetItem(0);
		$this->assertEquals ($objItem->Name, '- Select One -', 'Required value was detected by list control.');

	}

}