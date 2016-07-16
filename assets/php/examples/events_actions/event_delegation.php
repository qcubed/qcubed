<?php
	require_once('../qcubed.inc.php');
	
	class ExampleForm extends QForm {
		// Declare the DataGrid
		protected $dtgPersons;
		protected $dtgPersonsDelegated;

		protected function Form_Create() {
			// Define the DataGrid
			$this->dtgPersons = new QDataGrid($this, 'dtgPersons');
			$this->dtgPersons->Height = "560px";
			
			
			// Define the DataGrid using event delegation
			$this->dtgPersonsDelegated = new QDataGrid($this, 'dtgPersonsDelegated');

			// Define Columns
			$this->dtgPersons->CreateNodeColumn('Person Id', QQN::Person()->Id);
			$this->dtgPersons->CreateNodeColumn('First Name', QQN::Person()->FirstName);
			$this->dtgPersons->CreateNodeColumn('Last Name', QQN::Person()->LastName);
			$col = $this->dtgPersons->CreateCallableColumn('', [$this, 'RenderDeleteButton']);
			$col->HtmlEntities = false;

			$this->dtgPersonsDelegated->CreateNodeColumn('Person Id', QQN::Person()->Id);
			$this->dtgPersonsDelegated->CreateNodeColumn('First Name', QQN::Person()->FirstName);
			$this->dtgPersonsDelegated->CreateNodeColumn('Last Name', QQN::Person()->LastName);
			$col = $this->dtgPersonsDelegated->CreateCallableColumn('', [$this, 'RenderDeleteButton2']);
			$col->HtmlEntities = false;

			// Create the delegated event action. We bind the event to the data grid, even though the event is
			// coming from buttons in the datagrid. These click events will bubble up to the table.
			$this->dtgPersonsDelegated->AddAction(
				// The 3rd parameter is the jQuery selector that controls which controls we are listening to. This is similar to a CSS selector.
				// In our example, we are listening to buttons that have a 'data-id' attribute.
				new QClickEvent(null, 0, 'button[data-id]'),
				// Respond to click events with an ajax action. The fourth parameter is a JavaScript fragment that controls what
				// the action paremeter will be. In this case, its the value of the data-id attribute. Note that the "event.target" member
				// of the event is the button that was clicked on. Also, we are sending in the record id as the action parameter, so we can
				// use the same dtgPersonsButton_Click for the delegated and non-delegated actions.
				new QAjaxAction('dtgPersonsButton_Click', null, null, '$j(event.target).data("id")')
			);

			// Specify the Datagrid's Data Binder method
			// Notice we are using the same binder for two datagrids
			$this->dtgPersons->SetDataBinder('dtgPersons_Bind');
			$this->dtgPersonsDelegated->SetDataBinder('dtgPersons_Bind');
		}


		/**
		 * Bind the data to the data source. Note that the first parameter is the control we are binding to. This allows
		 * us to use the same data binder for multiple controls.
		 */
		protected function dtgPersons_Bind($objControl) {
			// Use the control passed in to the data binder to know to which to send the data.
			$objControl->DataSource = Person::LoadAll();
		}

		/**
		 * Respond to the button click for the non-delegated events.
		 */
		public function dtgPersonsButton_Click($strFormId, $strControlId, $strParameter) {
			$intPersonId = intval($strParameter);
			
			$objPerson = Person::Load($intPersonId);
			QApplication::DisplayAlert("You clicked on a person with ID #{$intPersonId}: {$objPerson->FirstName} {$objPerson->LastName}");
		}

		/**
		 * A non-delegated event version. Create a new button for each control and attach an action to it.
		 *
		 * @param Person $objPerson
		 * @return String
		 */
		public function RenderDeleteButton($objPerson) {
			$strControlId = 'btn' . $objPerson->Id;
			$objControl = $this->GetControl($strControlId);
			if (!$objControl) {
				$objControl = new QButton($this);
				$objControl->Text = 'Edit';
				$objControl->ActionParameter = $objPerson->Id;
				$objControl->AddAction(new QClickEvent(), new QAjaxAction('dtgPersonsButton_Click')); // This will generate a javascript call for every button created.
			}
			return $objControl->Render(false);
		}

		/**
		 * The delegated button. We are directly creating the html for the button and assigning a data-id that corresponds to the action
		 * parameter we will eventually send in to the action handler.
		 * 
		 * @param $objPerson
		 * @return string
		 */
		public function RenderDeleteButton2($objPerson) {
			//create the delete button row, with a special naming scheme for the button ids: "delete_" . id (where id is a person id)
			return '<button data-id="' . $objPerson->Id . '">Edit</button>';
		}
	}
	

	ExampleForm::Run('ExampleForm');
?>
