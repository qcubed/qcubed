<?php
	require_once('../qcubed.inc.php');
	
	class Person {
		public $Id;
		public $FirstName;
		public $LastName;
		
		public function __construct($id,$firstname,$lastname) {
			$this->Id = $id;
			$this->FirstName = $firstname;
			$this->LastName = $lastname;
		}
		
	}
	
	class ExampleForm extends QForm {
		// Declare the DataGrid
		protected $dtgPersons;
		protected $dtgPersonsDelegated;
		protected $pxyDelete;
		
		protected $arrPersons;
		protected $arrPersonsDelegated;
		
		protected $intNewPersonIdCounter = 101;
		
		protected $btnAddNewPerson;
		protected $btnAddNewPersonDelegated;

		protected function Form_Create() {
			// Define the DataGrid
			$this->dtgPersons = new QDataGrid($this, 'dtgPersons');
			$this->dtgPersons->CellPadding = 5;
			$this->dtgPersons->CellSpacing = 0;
			$this->dtgPersons->Height = "560px";
			
			
			// Define the DataGrid using event delegation
			$this->dtgPersonsDelegated = new QDataGrid($this, 'dtgPersonsDelegated');
			$this->dtgPersonsDelegated->CellPadding = 5;
			$this->dtgPersonsDelegated->CellSpacing = 0;
			
			// Define Columns
			// We will use $_ITEM, $_CONTROL and $_FORM to show how you can make calls to the Person object
			// being itereated ($_ITEM), the QDataGrid itself ($_CONTROL), and the QForm itself ($_FORM).
			$this->dtgPersons->AddColumn(new QDataGridColumn('Person Id', '<?= $_ITEM->Id ?>'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('First Name', '<?= $_ITEM->FirstName ?>'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('Last Name', '<?= $_ITEM->LastName ?>'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('Full Name', '<?= $_FORM->DisplayFullName($_ITEM) ?>'));
			$this->dtgPersons->AddColumn(new QDataGridColumn('', '<?= $_FORM->RenderDeleteButton($_ITEM) ?>','HtmlEntities=false'));
			
			$this->dtgPersonsDelegated->AddColumn(new QDataGridColumn('Person Id', '<?= $_ITEM->Id ?>'));
			$this->dtgPersonsDelegated->AddColumn(new QDataGridColumn('First Name', '<?= $_ITEM->FirstName ?>'));
			$this->dtgPersonsDelegated->AddColumn(new QDataGridColumn('Last Name', '<?= $_ITEM->LastName ?>'));
			$this->dtgPersonsDelegated->AddColumn(new QDataGridColumn('Full Name', '<?= $_FORM->DisplayFullName($_ITEM) ?>'));
			//create the delete button row, with a special naming scheme for the button ids: "remove_" . $_ITEM->Id (where $_ITEM is a person object)
			$this->dtgPersonsDelegated->AddColumn(new QDataGridColumn('', '<button id="delete_<?= $_ITEM->Id ?>">Delete</button>','HtmlEntities=false'));

			

			//WITHOUT event delegation:
			//--------------------------------------------------------------------------------------------------------
			//a proxy button for deleting table entries (without event delegation)
			$this->pxyDelete = new QControlProxy($this);
			$this->pxyDelete->AddAction(new QClickEvent(), new QAjaxAction("DeletePerson_Click"));
			
			//stop event bubbling
			$this->pxyDelete->AddAction(new QClickEvent(), new QTerminateAction());
			$this->pxyDelete->AddAction(new QClickEvent(), new QJavaScriptAction("event.stopPropagation()"));
			
			
			//Highlight the datagrid rows when mousing over them using AddRowAction
			// !!! this creates one line of JavaScript per row !!!
			$this->dtgPersons->AddRowAction(new QMouseOverEvent(), new QCssClassAction('selectedStyle'));
			$this->dtgPersons->AddRowAction(new QMouseOutEvent(), new QCssClassAction());
			
			// Add a click handler for the rows . 
			// We can use $_CONTROL->CurrentRowIndex to pass the row index to dtgPersonsRow_Click()
			// or $_ITEM->Id to pass the object's id, or any other data grid variable
			//!!! This generates javscript for every row in the table !!!
			$this->dtgPersons->RowActionParameterHtml = '<?= $_ITEM->Id ?>';
			$this->dtgPersons->AddRowAction(new QClickEvent(), new QAjaxAction('dtgPersonsRow_Click'));
			//--------------------------------------------------------------------------------------------------------
			
			
			//WITH event delegation: 
			//--------------------------------------------------------------------------------------------------------
			//Highlight the datagrid rows when mousing over them using QOnEvent.
			$this->dtgPersonsDelegated->AddAction(new QOnEvent("mouseover", "tr"), 
					new QJavaScriptAction('$j(event.currentTarget).toggleClass("selectedStyle")'));
			$this->dtgPersonsDelegated->AddAction(new QOnEvent("mouseout", "tr"), 
					new QJavaScriptAction('$j(event.currentTarget).toggleClass("selectedStyle")'));

				
			//Add a click handler to the rows using event delegation,
			//!!! This creates exactly ONE line of javascript !!!
			//this line adds a QAjaxAction to the datagrid that gets triggered if a
			//child tr elements receives a click
			//
			//the last parameter of the QAjaxAction ctor in this example defines the returned parameter
			//in this case the content of the first child of event.currentTarget is returned
			//where event.currentTarget is the tr element that was clicked.
			//As a result the person id stored in the first collumn is returned
			$this->dtgPersonsDelegated->AddAction(new QOnEvent("click","tr"), 
					new QAjaxAction('dtgPersonsRow_Click', 'default', null, '$j(event.currentTarget).children().first().text()'));
			
			//handle person removing with event delegation
			//filter for buttons with ids that begin with "delete_" and returns the id 
			//of the person to delete by splitting the button id on "_" and using the second string
			// (remember: the button id consists of "delete_" . $personId 
			$objOnEvent = new QOnEvent("click","button[id^='delete_']");
			$this->dtgPersonsDelegated->AddAction($objOnEvent,
					new QAjaxAction('DeletePerson_Click', 'default', null, '(event.currentTarget.id).split("_")[1]') );
			
			//do not submit
			$this->dtgPersonsDelegated->AddAction($objOnEvent, new QTerminateAction());
			//stop event bubbling
			$this->dtgPersonsDelegated->AddAction($objOnEvent, new QJavaScriptAction("event.stopPropagation()"));
			//-------------------------------------------------------------------------------------------------------
			
			
			//create the add person buttons
			$this->btnAddNewPerson = new QButton($this);
			$this->btnAddNewPerson->Text = "Add Person";
			
			$this->btnAddNewPersonDelegated = new QButton($this);
			$this->btnAddNewPersonDelegated->Text = "Add Person";
			
			$this->btnAddNewPerson->AddAction(new QClickEvent(), new QAjaxAction("AddPerson_Click"));
			$this->btnAddNewPersonDelegated->AddAction(new QClickEvent(), new QAjaxAction("AddPerson_Click"));
			
			
			
			// Specify the Datagrid's Data Binder method
			$this->dtgPersons->SetDataBinder('dtgPersons_Bind');
			$this->dtgPersonsDelegated->SetDataBinder('dtgPersonsDelegated_Bind');

			$objStyle = $this->dtgPersons->HeaderRowStyle;
			$objStyle->ForeColor = 'white';
			$objStyle->BackColor = '#000066';
			$objStyle = $this->dtgPersonsDelegated->HeaderRowStyle;
			$objStyle->ForeColor = 'white';
			$objStyle->BackColor = '#000066';
			$this->loadPersons();
		}
		
		// DisplayFullName will be called by the DataGrid on each row, whenever it tries to render
		// the Full Name column.  Note that we take in the $objPerson as a Person parameter.  Also
		// note that DisplayFullName is a PUBLIC function -- because it will be called by the QDataGrid class.
		public function DisplayFullName(Person $objPerson) {
			$strToReturn = sprintf('%s, %s', $objPerson->LastName, $objPerson->FirstName);
			return $strToReturn;
		}

		protected function dtgPersons_Bind() {
			// We must be sure to load the data source
			$this->dtgPersons->DataSource = $this->arrPersons;
		}
		
		protected function dtgPersonsDelegated_Bind() {
			// We must be sure to load the data source
			$this->dtgPersonsDelegated->DataSource = $this->arrPersonsDelegated;
		}

		public function dtgPersonsRow_Click($strFormId, $strControlId, $strParameter) {
			$intPersonId = intval($strParameter);
			
			$objPerson = $this->arrPersons[$intPersonId];
			
			QApplication::ExecuteJavascript("alert('You clicked on a person with ID #" . $intPersonId .
				": " . $objPerson->FirstName . " " . $objPerson->LastName . "');");
		}
		
		/**
		 * render the delete button for the $dtgPersons
		 * @param Person $objPerson
		 * @return String
		 */
		public function RenderDeleteButton($objPerson) {
			return '<button ' . $this->pxyDelete->RenderAsEvents($objPerson->Id,false,"delete_".$objPerson->Id) . '>Delete</button>';
		}
		
		/**
		 * a helper method for creating dummy persons
		 * In a real world application these would be loaded from
		 * the database
		 */
		public function loadPersons() {
			$this->arrPersons = Array();
			$this->arrPersonsDelegated;
			for($ii = 1; $ii <= 100; $ii++) {
				$this->arrPersons[$ii] = new Person($ii,"firstname" . $ii,"lastname" . $ii );
				$this->arrPersonsDelegated[$ii] = $this->arrPersons[$ii];
			}
			
		}
		
		public function DeletePerson_Click($strFormId, $strControlId, $strParameter) {
			$personId = intval($strParameter);
			if($strControlId == $this->pxyDelete->ControlId) {
				unset($this->arrPersons[$personId]);
				$strControlId = $this->dtgPersons->ControlId;
			}
			else {
				unset($this->arrPersonsDelegated[$personId]);
			}
			//find the delete button that ends with the person id, get its parent tr and remove it
			QApplication::ExecuteJavaScript('$j("#' . $strControlId . '").find("button[id=\"delete_' . $personId . '\"]").parents("tr").remove()'); 
		}
		
		public function AddPerson_Click($strFormId, $strControlId) {
			$targetGrid = NULL;
			$objNewPerson = new Person($this->intNewPersonIdCounter,
					"firstname" . $this->intNewPersonIdCounter, 
					"lastname" . $this->intNewPersonIdCounter);
			$this->intNewPersonIdCounter++;
			
			if($strControlId == $this->btnAddNewPerson->ControlId) {
				$targetGrid = $this->dtgPersons;
				$this->arrPersons[$objNewPerson->Id] = $objNewPerson;
			} 
			else {
				$targetGrid = $this->dtgPersonsDelegated;
				$this->arrPersonsDelegated[$objNewPerson->Id] = $objNewPerson;
			}
			
			//add a row to the target data grid and scroll to the bottom of the data grid
			QApplication::ExecuteJavaScript('$j("#' . $targetGrid->ControlId . '").prepend(\'<tr class="newperson"><td>' 
					. $objNewPerson->Id . '</td><td>' . $objNewPerson->FirstName . '</td><td>' . $objNewPerson->LastName . '</td><td>'
					. $this->DisplayFullName($objNewPerson) 
					. '</td><td><button id="delete_' . $objNewPerson->Id . '">Delete</button></td></tr>\')');
			
			
		}
		
		
	}
	

	ExampleForm::Run('ExampleForm');
?>
