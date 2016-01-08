<?php
	/**
	 * This is the abstract Panel class for the List All functionality
	 * of the Address class.  This code-generated class
	 * contains a datagrid to display an HTML page that can
	 * list a collection of Address objects.  It includes
	 * functionality to perform pagination and sorting on columns.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QPanel which extends this AddressListPanelBase
	 * class.
	 *
	 * Any and all changes to this file will be overwritten with any subsequent re-
	 * code generation.
	 *
	 *
	 */
     
	class AddressListPanel extends QPanel {
		// Local instance of the DataGrid connector to list Addresses
		/**
		 * @var AddressDataGrid
		 */
		public $dtgAddresses;

		// Protected Objects
		protected $objPerson;

		protected $mctAddress;
		protected $lblId;
		protected $lblPerson;
		protected $txtStreet;
		protected $txtCity;

		protected $btnAdd;
		protected $btnSave;
		protected $btnCancel;

		// Add New By Default
		// This value is either a Address->Id, "null" (if nothing is being edited), or "-1" (if creating a new Address)
		protected $intEditAddressId = null;
		protected $Id = -1;

		protected $tempedit = 0;
		protected $tempdelete = 0;

		public function __construct($objParentObject, Person $objPerson, $strControlId = null) {
			// Call the Parent
			try {
				parent::__construct($objParentObject, $strControlId);

			// Setup the Template
			$this->Template = 'AddressListPanel.tpl.php';

			// Setting local the Master QDataGrid to refresh on
			// Saves on the Child DataGrid..
			$this->objPerson = $objPerson;
			
			// Instantiate the Meta DataGrid
			$this->dtgAddresses = new QDataGrid($this);

			// Bind him, the normal...
			$this->dtgAddresses->SetDataBinder('dtgAddresses_Bind', $this);

			$this->dtgAddresses->AddColumn(
				new QDataGridColumn('Address',
				'<?= $_CONTROL->ParentControl->render_StreetColumn($_CONTROL, $_ITEM ) ?>' ,
				'HtmlEntities=false',
				'Name=Street'));
			$this->dtgAddresses->AddColumn(
				new QDataGridColumn('City',
				'<?= $_CONTROL->ParentControl->render_CityColumn($_CONTROL, $_ITEM ) ?>' ,
				'HtmlEntities=false',
				'Name=City'));
			$this->dtgAddresses->AddColumn(
				new QDataGridColumn('PersonId',
				'<?= $_CONTROL->ParentControl->render_PersonIdColumn($_CONTROL, $_ITEM ) ?>',
				'HtmlEntities=false',
				'Name=person_id'));

			// edit button 
			$this->dtgAddresses->AddColumn(
				new QDataGridColumn('',
				'<?= $_CONTROL->ParentControl->render_btnRecordEdit($_CONTROL, $_ITEM) ?>',
				'HtmlEntities=false',
				'Width=1px','Wrap=false'));

			// delete button
			$this->dtgAddresses->AddColumn(
				new QDataGridColumn('',
				'<?= $_CONTROL->ParentControl->render_btnRecordDelete($_CONTROL, $_ITEM) ?>',
				'HtmlEntities=false','Width=1px'));


			$this->mctAddress = AddressConnector::CreateFromPathInfo($this->dtgAddresses);

//			$this->lblId = $this->mctAddress->lblId_Create();
//			$this->lblId->AddAction(new QEscapeKeyEvent(), new QAjaxControlAction($this,'btnCancel_Click',$this->dtgAddresses->WaitIcon));
//			$this->lblId->AddAction(new QEscapeKeyEvent(), new QTerminateAction());

			$this->lblPerson = $this->mctAddress->lblPerson_Create();
			$this->lblPerson->AddAction(new QEscapeKeyEvent(), new QAjaxControlAction($this,'btnCancel_Click',$this->dtgAddresses->WaitIcon));
			$this->lblPerson->AddAction(new QEscapeKeyEvent(), new QTerminateAction());

			$this->txtStreet = $this->mctAddress->txtStreet_Create();
			$this->txtStreet->AddAction(new QEscapeKeyEvent(), new QAjaxControlAction($this,'btnCancel_Click',$this->dtgAddresses->WaitIcon));
			$this->txtStreet->AddAction(new QEscapeKeyEvent(), new QTerminateAction());

			$this->txtCity = $this->mctAddress->txtCity_Create();
			$this->txtCity->AddAction(new QEscapeKeyEvent(), new QAjaxControlAction($this,'btnCancel_Click',$this->dtgAddresses->WaitIcon));
			$this->txtCity->AddAction(new QEscapeKeyEvent(), new QTerminateAction());

			$this->btnSave = new QButton($this->dtgAddresses);
			$this->btnSave->Text = QApplication::Translate('Save');
			$this->btnSave->CssClass = 'inputbutton';
			$this->btnSave->PrimaryButton = true;
			$this->btnSave->CausesValidation = QCausesValidation::SiblingsOnly;
			$this->btnSave->AddAction(new QClickEvent(), 
				new QAjaxControlAction($this,
				'btnSave_Click',
				$this->dtgAddresses->WaitIcon));

			$this->btnCancel = new QButton($this->dtgAddresses);
			$this->btnCancel->Text = QApplication::Translate('Cancel');
			$this->btnCancel->CssClass = 'inputbutton';
			$this->btnCancel->AddAction(new QClickEvent(), 
				new QAjaxControlAction($this,'btnCancel_Click',
				$this->dtgAddresses->WaitIcon));

			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function dtgAddresses_Bind() {
			$this->dtgAddresses->DataSource = Address::QueryArray(
					QQ::Equal(QQN::Address()->PersonId, $this->objPerson->Id));
		}

		// If the address for the row we are rendering is currently being edited,
		// show the textbox.  Otherwise, display the contents as is. 		 
//		 public function render_AddressIdColumn($parControl, Address $objRecord) {
//			if (($objRecord->Id == $this->intEditAddressId) ||
//				(($this->intEditAddressId == -1) && (!$objRecord->Id)))
//				return $this->lblId->RenderWithError(false);
//			else {
//				return QApplication::HtmlEntities($objRecord->Id);
//			}
//		}

		 public function render_PersonIdColumn($parControl, Address $objRecord) {
			if (($objRecord->Id == $this->intEditAddressId) ||
					(($this->intEditAddressId == -1) && (!$objRecord->Id))) {
				return $this->lblPerson->RenderWithError(false);
			} else {
				return QApplication::HtmlEntities($objRecord->PersonId);
			}
		}

		public function render_StreetColumn($parControl, Address $objRecord) {
			if (($objRecord->Id == $this->intEditAddressId) ||
					(($this->intEditAddressId == -1) && (!$objRecord->Id))) {
				return $this->txtStreet->RenderWithError(false);
			} else {
				return QApplication::HtmlEntities($objRecord->Street);
			}
		}

		public function render_CityColumn($parControl, Address $objRecord) {
			if (($objRecord->Id == $this->intEditAddressId) ||
				(($this->intEditAddressId == -1) && (!$objRecord->Id))) {
				return $this->txtCity->RenderWithError(false); 
			} else {
				return QApplication::HtmlEntities($objRecord->City);
			}
		}


		public function render_btnRecordEdit($parControl, Address $objRecord) {
			++$this->tempedit;
			if (($objRecord->Id == $this->intEditAddressId) ||
				(($this->intEditAddressId == -1) && (!$objRecord->Id))) {
				if ($objRecord->Id) {
					return $this->btnSave->Render(false) . '&nbsp;' . $this->btnCancel->Render(false);
				} else {
					return $this->btnSave->Render(false);
				}
			} else {
				$strControlId = 'btnRecordEdit' . $objRecord->Id.'edt'.$this->tempedit;
				if (!$objControl = $this->Form->GetControl($strControlId)) {
					$objControl = new QButton($parControl, $strControlId);
					$objControl->Text = 'Edit';
					$objControl->CssClass = 'inputbutton';
					$objControl->ActionParameter = $objRecord->Id;
					$objControl->AddAction(new QClickEvent(), new QAjaxControlAction($this,'btnRecordEdit_Click',$this->dtgAddresses->WaitIcon));

				 return $objControl->Render(false);
				}
			}
		}

		public function render_btnRecordDelete($parControl, Address $objRecord) {
			++$this->tempdelete;
			if (($objRecord->Id == $this->intEditAddressId) ||
					(($this->intEditAddressId == -1) && (!$objRecord->Id))) {
				return null;
			} else {
				$strControlId = 'btnRecordDelete' . $objRecord->Id .'dlt'.$this->tempdelete;
				if (!$objControl = $this->Form->GetControl($strControlId)) {
					$objControl = new QButton($parControl, $strControlId);
					$objControl->Text = 'Delete';
					$objControl->CssClass = 'inputbutton';
					$objControl->ActionParameter = $objRecord->Id;
					$objControl->AddAction(new QClickEvent(), new QConfirmAction('Are you sure ?'));
					$objControl->AddAction(new QClickEvent(), new QAjaxControlAction($this,'btnRecordDelete_Click',$this->dtgAddresses->WaitIcon));
					return $objControl->Render(false);
				}
			}
		}
		
		// edit address
		public function btnRecordEdit_Click($strFormId, $strControlId, $strParameter) {
			try {
				// get actual intEditRecordId
				$this->intEditAddressId = intval($strParameter);
				$objRecord = Address::LoadById($this->intEditAddressId);
				$this->txtStreet->Text = $objRecord->Street;
				$this->txtCity->Text = $objRecord->City;
			}
			catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		   
			$this->dtgAddresses->Refresh();
			QApplication::ExecuteControlCommand($this->txtStreet->ControlId, 'focus');
		}

		public function btnRecordDelete_Click($strFormId, $strControlId, $strParameter) {
			try {
				$intRecordId = intval($strParameter);
				$objRecord = Address::LoadById($intRecordId);

				if ($objRecord) {
					$objRecord->Delete();
				}

				$this->btnCancel_Click($strFormId, $strControlId, $strParameter);
			}
			catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function btnSave_Click($strFormId, $strControlId, $strParameter) {                    
			try {
				if ($this->intEditAddressId == -1) {
					$objRecord = new Address();
				} else {
					$objRecord = Address::Load($this->intEditAddressId);
				}
			  
				$objRecord->Street = $this->txtStreet->Text;
				$objRecord->City = $this->txtCity->Text;
				$objRecord->Save();				
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			$this->btnCancel_Click($strFormId, $strControlId, $strParameter);
		 }

		 public function btnCancel_Click($strFormId, $strControlId, $strParameter) {
			$this->intEditAddressId = -1;
			$this->txtStreet->Text =
			$this->txtCity->Text = null;
			
			$this->dtgAddresses->Refresh();
		 }
	}

?>