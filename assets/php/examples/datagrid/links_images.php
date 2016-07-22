<?php
require_once('../qcubed.inc.php');

class ExampleForm extends QForm {

	// Declare the DataGrid
	protected $dtgPersons;

	protected function Form_Create() {
		// Define the DataGrid
		$this->dtgPersons = new QHtmlTable($this);

		$col = $this->dtgPersons->CreateCallableColumn('Full Name', [$this, 'renderFullName']);
		$col->HtmlEntities = false;
		$col = $this->dtgPersons->CreateCallableColumn('Picture', [$this, 'renderImage']);
		$col->HtmlEntities = false;
		$col = $this->dtgPersons->CreateCallableColumn('', [$this, 'renderButton']);
		$col->HtmlEntities = false;
		$this->dtgPersons->SetDataBinder('dtgPersons_Bind');
	}

	public function renderFullName(Person $objPerson) {
		return "<em>" . $objPerson->FirstName . "</em> " . $objPerson->LastName;
	}

	public function renderImage(Person $objPerson) {
		$intPersonId = $objPerson->Id;
		$objControlId = "personImage" . $intPersonId;

		if (!$objControl = $this->GetControl($objControlId)) {
			$objControl = new QImageControl($this->dtgPersons, $objControlId);
			
			// And finally, let's specify a CacheFolder so that the images are cached
			// Notice that this CacheFolder path is a complete web-accessible relative-to-docroot path
			$objControl->CacheFolder = __IMAGE_CACHE_ASSETS__;

			$imagePath = "../images/emoticons/" . $intPersonId . ".png";

			if (file_exists($imagePath)) {
				// Beautiful images are courtesy of Yellow Icon at http://yellowicon.com/downloads/page/4
				$objControl->ImagePath = $imagePath;
			} else {
				$objControl->ImagePath = "../images/emoticons/1.png"; // fail-over case: default image
			}
		}

		// We pass the parameter of "false" to make sure the control doesn't render
		// itself RIGHT HERE - that it instead returns its string rendering result.
		return $objControl->Render(false);
	}

	public function renderButton(Person $objPerson) {
		$objControlId = "editButton" . $objPerson->Id;

		if (!$objControl = $this->GetControl($objControlId)) {
			$objControl = new QButton($this->dtgPersons, $objControlId);
			$objControl->Text = "Edit Person #" . $objPerson->Id;

			$objControl->AddAction(new QClickEvent(), new QAjaxAction("renderButton_Click"));
			$objControl->ActionParameter = $objPerson->Id;
		}

		// We pass the parameter of "false" to make sure the control doesn't render
		// itself RIGHT HERE - that it instead returns its string rendering result.
		return $objControl->Render(false);
	}

	public function renderButton_Click($strFormId, $strControlId, $strParameter) {
		$intPersonId = intval($strParameter);

		QApplication::DisplayAlert("In a real application, you'd be redirected to the page that edits person #" . $intPersonId);

		// You'd do something like this in a real application:
		// QApplication::Redirect("person_edit.php?intPersonId=" . $intPersonId);
	}

	protected function dtgPersons_Bind() {
		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->dtgPersons->DataSource = Person::LoadAll();
	}
}

ExampleForm::Run('ExampleForm');
?>
