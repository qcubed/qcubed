<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends QForm {
		// Declare the DataGrid
		protected $dtgPersons;

		protected function Form_Create() {
			// Define the DataGrid
			$this->dtgPersons = new QDataGrid($this);

			$this->dtgPersons->AddColumn(new QDataGridColumn('Full Name', '<?= $_FORM->renderFullName($_ITEM) ?>', 'HtmlEntities=false'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('Picture', '<?= $_FORM->renderImage($_ITEM->Id) ?>', 'HtmlEntities=false'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('', '<?= $_FORM->renderButton($_ITEM) ?>', 'HtmlEntities=false'));
			$this->dtgPersons->SetDataBinder('dtgPersons_Bind');
		}
		
		public function renderFullName(Person $objPerson) {
			return "<i>" . $objPerson->FirstName . "</i> " . $objPerson->LastName;
		}
		
		public function renderImage($intPersonId) {
			$objControlId = "personImage" . $intPersonId;
			
			if (!$objControl = $this->GetControl($objControlId)) {
				$objControl = new QImageControl($this, $objControlId);
				
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
				$objControl = new QButton($this, $objControlId);
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
