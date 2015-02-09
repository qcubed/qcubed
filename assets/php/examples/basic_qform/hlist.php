<?php
require_once('../qcubed.inc.php');

// Define the Qform with all our Qcontrols
class ExamplesForm extends QForm {

	protected $lstProjects;

	// Initialize our Controls during the Form Creation process
	protected function Form_Create() {
		// Define the ListBox, and create the first listitem as 'Select One'
		$this->lstProjects = new QHListControl($this);
		$this->lstProjects->SetDataBinder(array ($this, 'lstProjects_Bind'));
		$this->lstProjects->UnorderedListStyle = QUnorderedListStyle::Square;

	}

	/**
	 * Add the items to the project list.
	 */
	public function lstProjects_Bind() {
		$clauses[] = QQ::ExpandAsArray (QQN::Project()->PersonAsTeamMember);
		$objProjects = Project::QueryArray(QQ::All(), $clauses);

		foreach ($objProjects as $objProject) {
			$item = new QHListItem ($objProject->Name);
			$item->Tag = 'ol';
			$item->GetSubTagStyler()->OrderedListType = QOrderedListType::LowercaseRoman;
			foreach ($objProject->_PersonAsTeamMemberArray as $objPerson) {
				/****
				 * Here we add a sub-item to each item before adding the item to the main list.
				 */
				$item->AddItem ($objPerson->FirstName . ' ' . $objPerson->LastName);
			}
			$this->lstProjects->AddItem ($item);
		}
	}

}

// Run the Form we have defined
// The QForm engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>