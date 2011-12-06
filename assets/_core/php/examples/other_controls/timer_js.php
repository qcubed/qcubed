<?php
	require_once('../qcubed.inc.php');

	class Order {
		public $Id;
		public $Items;
	}
	
	class ExampleForm extends QForm {
		// Declare the DataGrid
		protected $dtgOrders;
        public $objOrdersArray = array();

		protected $intOrderCnt = 0;

		protected $btnServerAction;
		protected $btnRestartOnServerAction;

		protected $ctlTimer;

		protected $btnStart;
		protected $btnStop;

		protected $objRandomProductsArray = array();

		protected function Form_Create() {

			$this->objRandomProductsArray[0] = '1x Sandwich, 2x Coke, 1x Big Pekahuna Burger';
			$this->objRandomProductsArray[1] = '2x French fries, 3x Burritos, 1x Hot Dog';
			$this->objRandomProductsArray[2] = '1x Steak - Lone Star, 5x Wiener Schnitzel';
			$this->objRandomProductsArray[3] = '3x Socks, 3x Shorts';

			// Define the DataGrid
			$this->dtgOrders = new QDataGrid($this);
			$this->dtgOrders->UseAjax = true;

			//button to simulate a server action
			$this->btnServerAction = new QButton($this);
			$this->btnServerAction->SetCustomStyle('float','right');

			//button for switching the 'RestartOnServer' capability of QJsTimer on/off
			$this->btnRestartOnServerAction = new QButton($this);
			$this->btnStop = new QButton($this);
			$this->btnStart = new QButton($this);

			//create the timer: parent = $this, $time = 3000ms, periodic = true, autostart=true
			$this->ctlTimer = new QJsTimer($this,3000,true,true);


			$this->dtgOrders->AddColumn(new QDataGridColumn('Order-Id', '<?= $_ITEM->Id ?>'));
			$this->dtgOrders->AddColumn(new QDataGridColumn('Products', '<?= $_ITEM->Items ?>'));
			$this->dtgOrders->AddColumn(new QDataGridColumn('Remove', '<?= $_FORM->renderRemoveButton($_ITEM->Id) ?>', 'HtmlEntities=false'));
			$this->dtgOrders->SetDataBinder('dtgOrders_Bind');

			$this->btnServerAction->AddAction(new QClickEvent(),new QServerAction('OnServerAction'));
			$this->btnServerAction->Text = "Server Action";

			$this->btnRestartOnServerAction->AddAction(new QClickEvent(),new QAjaxAction('OnToggleRestartOnServerAction'));
			$this->btnRestartOnServerAction->Text = "Restart On Server Action [off]";


			$this->ctlTimer->AddAction(new QTimerExpiredEvent(), new QAjaxAction('OnUpdateDtg'));

			$this->btnStart->AddAction(new QClickEvent(),new QAjaxControlAction($this->ctlTimer,'Start'));
			$this->btnStop->AddAction(new QClickEvent(),new QAjaxControlAction($this->ctlTimer,'Stop'));
			$this->btnStart->Text = 'Start';
			$this->btnStop->Text = 'Stop';

		}

		//the timer callback function for updating the orders
		public function OnUpdateDtg() {
			//fetch new orders
			$randProdNum = rand(0,3);
			$order = new Order();
			$this->intOrderCnt++;
			$order->Id = $this->intOrderCnt;
			$order->Items = $this->objRandomProductsArray[$randProdNum];
			$this->objOrdersArray[$this->intOrderCnt] = $order;
			$this->dtgOrders->MarkAsModified();
		}

		public function OnToggleRestartOnServerAction() {
			$blnRestart = $this->ctlTimer->RestartOnServerAction;
			if($blnRestart)
				$this->btnRestartOnServerAction->Text = "Restart On Server Action [off]";
			else
				$this->btnRestartOnServerAction->Text = "Restart On Server Action [on]";
			
			$this->ctlTimer->RestartOnServerAction = !$blnRestart;
		}

		public function OnServerAction() {
			//ServerAction test
		}
			
		public function renderRemoveButton($orderId) {
			$objControlId = "removeButton" . $orderId;
            $objControl = $this->GetControl($objControlId);
			if (!$objControl) {
				$objControl = new QJqButton($this->dtgOrders, $objControlId);
				$objControl->Text = true;
				$objControl->ActionParameter = $orderId;
				$objControl->AddAction(new QClickEvent(), new QAjaxAction("removeButton_Click"));
			}
                        
			$objControl->Label = "Remove";

			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
		}
		
		
		public function removeButton_Click($strFormId, $strControlId, $strParameter) {
			unset($this->objOrdersArray[$strParameter]);
            $this->dtgOrders->MarkAsModified();
		}

		protected function dtgOrders_Bind() {
			// We load the data source, and set it to the datagrid's DataSource parameter
			$this->dtgOrders->DataSource = $this->objOrdersArray;
		}
	}

	ExampleForm::Run('ExampleForm');
?>
