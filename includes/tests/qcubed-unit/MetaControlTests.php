<?php
/**
 * 
 * @package Tests
 */
class MetaControlTests extends QUnitTestCaseBase {
	protected $frmTest;

	public function __construct($objForm) {
		parent::__construct($objForm);
		$this->frmTest = $objForm;
	}

	public function testBasicControls() {
		$mctTypeTest = TypeTestMetaControl::Create($this->frmTest);

		$mctTypeTest->DateControl->DateTime = new QDateTime ('10/10/2010');
		$mctTypeTest->DateTimeControl->DateTime = new QDateTime ('11/11/2011');
		$mctTypeTest->TestIntControl->Value = 5;
		$mctTypeTest->TestFloatControl->Value = 3.5;
		$mctTypeTest->TestVarcharControl->Text = 'abcde';
		$mctTypeTest->TestTextControl->Text = 'ABCDE';
		$mctTypeTest->TestBitControl->Checked = true;

		$id = $mctTypeTest->SaveTypeTest();

		$mctTypeTest2 = TypeTestMetaControl::Create($this->frmTest, $id);
		$dt = $mctTypeTest2->DateControl->DateTime;
		$this->assertTrue ($dt->IsEqualTo (new QDateTime ('10/10/2010', null, QDateTime::DateOnlyType)), 'Date only type saved correctly through meta control.');
		$dt = $mctTypeTest2->DateTimeControl->DateTime;
		$this->assertTrue ($dt->IsEqualTo (new QDateTime ('11/11/2011')), 'Date time type saved correctly through meta control.');
		$this->assertEqual($mctTypeTest2->TestIntControl->Value, 5, 'Integer control saved correctly.');
		$this->assertEqual($mctTypeTest2->TestFloatControl->Value, 3.5, 'Float type saved correctly.');
		$this->assertEqual($mctTypeTest2->TestVarcharControl->Text, 'abcde', 'Varchar control type saved correctly through meta control.');
		$this->assertEqual($mctTypeTest2->TestTextControl->Text, 'ABCDE', 'Text type saved correctly through meta control.');
		$this->assertEqual($mctTypeTest2->TestBitControl->Checked, true, 'Bit saved correctly through meta control.');

		$mctTypeTest2->DeleteTypeTest();
	}

	public function testReference() {
		$mctAddress = AddressMetaControl::Create ($this->frmTest);
		$lstControl = $mctAddress->PersonIdControl;
		$this->assertTrue ($lstControl instanceof QListControl);
		$lstControl->SelectedValue = 2;
		$mctAddress->StreetControl->Text = 'Test Street';
		$mctAddress->CityControl->Text = 'Test City';
		$id = $mctAddress->SaveAddress();

		$mctAddress2 = AddressMetaControl::Create ($this->frmTest, $id);
		$objPerson = $mctAddress2->Address->Person;
		$this->assertEqual($objPerson->FirstName, 'Kendall', "Forward reference saved correctly through meta control.");
		$mctAddress->DeleteAddress();
	}

	public function testReverseReference() {
		$mctPerson = PersonMetaControl::Create ($this->frmTest, 7);
		$lstControl = $mctPerson->LoginControl;
		$this->assertTrue ($lstControl instanceof QListControl);
		$this->assertEqual ($lstControl->SelectedValue, 4);
		$this->assertEqual ($mctPerson->Person->Login->Username, 'kwolfe');

		// test refresh
		$mctPerson->Load(3);
		$this->assertEqual ($lstControl->SelectedValue, 2);
		$this->assertEqual ($mctPerson->Person->Login->Username, 'brobinson');
	}

	public function testManyToMany() {
		$clauses = array(QQ::ExpandAsArray(QQN::Person()->ProjectAsTeamMember));
		$objPerson = Person::Load (2, $clauses);
		$mctPerson = new PersonMetaControl ($this->frmTest, $objPerson);
		$lstControl = $mctPerson->ProjectAsTeamMemberControl;
		$this->assertTrue ($lstControl instanceof QListControl);
		$values = $lstControl->SelectedValues;
		$this->assertEqual ($values[0], 1);
		$this->assertEqual ($values[1], 2);
		$this->assertEqual ($values[2], 4);

		// test refresh
		$mctPerson->Load (3, $clauses);
		$values = $lstControl->SelectedValues;
		$this->assertEqual ($values[0], 4);
		$this->assertEqual (count($values), 1);
	}

}
?>