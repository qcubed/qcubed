<?php
	/** @noinspection PhpIncludeInspection */
	require_once('../qcubed.inc.php');

	// adding the javascript return parameter to the event is one 
	// possibility to retrieve values/objects/arrays via an Ajax or Server Action
	class MyQSlider_ChangeEvent extends QEvent {
		const EventName = 'slidechange';
		const JsReturnParam = 'arguments[1].value';
	}

	class ExampleForm extends QForm {
		/** @var QResizable */
		protected $Resizable;
		/** @var QSelectable */
		protected $Selectable;
		/** @var QSortable */
		protected $Sortable;
		/** @var QSlider */
		protected $Slider;
		/** @var QButton */
		protected $btnSubmit;
		/** @var QSortable */
		protected $Sortable2;

		/** @var QPanel */
		protected $SortableResult;
		/** @var QPanel */
		protected $Sortable2Result;
		/** @var QPanel */
		protected $ResizableResult;
		/** @var QPanel */
		protected $SelectableResult;
		/** @var QPanel */
		protected $SubmitResult;
		/** @var QPanel */
		protected $SliderResult;

		protected function Form_Create() {
			$strServerActionJsParam = "";

			$this->btnSubmit = new QButton($this);
			$this->btnSubmit->Text = "ServerAction Submit";
			$this->SubmitResult = new QPanel($this);

			// Slider
			$this->Slider = new QSlider($this);
			$this->Slider->Max = 1250;
			$this->Slider->AddAction(new MyQSlider_ChangeEvent(), new QAjaxAction('onSlide'));
			$this->SliderResult = new QPanel($this);

			// Resizable
			$this->Resizable = new QPanel($this);
			$this->Resizable->CssClass = 'resizable';
			$this->Resizable->Resizable = true;
			$this->ResizableResult = new QPanel($this);
			$strJsParam = '{ 
				"width": $j("#' . $this->Resizable->ControlId . '").width(), 
				"height": $j("#' . $this->Resizable->ControlId . '").height() 
			}';
			$this->Resizable->AddAction(new QResizable_StopEvent(), new QAjaxAction("onResize", "default", null, $strJsParam));
			$this->ResizableResult = new QPanel($this);

			$strServerActionJsParam = '{"resizable": ' . $strJsParam . ', ';

			// Selectable
			$this->Selectable = new QSelectable($this);
			$this->Selectable->AutoRenderChildren = true;
			$this->Selectable->CssClass = 'selectable';
			for ($i = 1; $i <= 5; ++$i) {
				$pnl = new QPanel($this->Selectable);
				$pnl->Text = 'Item ' . $i;
				$pnl->CssClass = 'selitem';
			}
			$this->Selectable->Filter = 'div.selitem';

			/*
			* if your objects to return get more complex you can define a javascript function that returns your
			* object. the essential thing is the ".call()", this executes the function that you have just defined
			* and returns your object.
			* In this example a function is uesd to temporary store jquery's search result for selected items,
			* because it is needed twice. then the ids are stored to objRet.ids as a comma-separated string and
			* the contents of the selected items are stored to objRet.content as an array.
			*
			*/
			$this->SelectableResult = new QPanel($this);
			$strJsParam = 'function() { 
				objRet = new Object(); 
				selection = $j("#' . $this->Selectable->ControlId . '")
					.find(".ui-selected");
				objRet.ids = selection.map(function(){
						return this.id;
					}).get()
					.join(",");
				objRet.content = selection.map(function() { 
					return $j(this).html();
				}).get(); 
				return objRet;
			}.call()';
			$this->Selectable->AddAction(new QSelectable_StopEvent(), new QAjaxAction("onSelect", "default", null, $strJsParam));

			$strServerActionJsParam .= '"selectable": ' . $strJsParam . ', ';


			// Sortable
			$this->Sortable = new QSortable($this);
			$this->Sortable->AutoRenderChildren = true;
			$this->Sortable->CssClass = 'sortable';
			for ($i = 1; $i <= 5; ++$i) {
				$pnl = new QPanel($this->Sortable);
				$pnl->Text = 'Item ' . $i;
				$pnl->CssClass = 'sortitem';
			}
			$this->Sortable->Items = 'div.sortitem';

			$this->SortableResult = new QPanel($this);
			$strJsParam = '$j("#' . $this->Sortable->ControlId . '").
				find("div.sortitem").
				map(function() { 
					return $j(this).html()
				}).get()';
			$this->Sortable->AddAction(new QSortable_UpdateEvent(), new QAjaxAction("onSort", "default", null, $strJsParam));

			$strServerActionJsParam .= '"sortable": ' . $strJsParam . '}';


			//a second Sortable that can receive items from the first Sortable
			//when an item is dragged over from the first sortable an receive event is triggered
			$this->Sortable2 = new QSortable($this);
			$this->Sortable2->AutoRenderChildren = true;
			$this->Sortable2->CssClass = 'sortable';
			for ($i = 6; $i <= 10; ++$i) {
				$pnl = new QPanel($this->Sortable2);
				$pnl->Text = 'Item ' . $i;
				$pnl->CssClass = 'sortitem';
			}
			$this->Sortable2->Items = 'div.sortitem';

			//allow dragging from Sortable to Sortable2
			$this->Sortable->ConnectWith = '#' . $this->Sortable2->ControlId;
			//enable the following line to allow dragging Sortable2 child items to the Sortable list
			// $this->Sortable2->ConnectWith = '#' . $this->Sortable->ControlId;

			//using a QJsClosure as the ActionParameter for Sortable2 to return a Js object
			//the ActionParameter is used for every ajax / server action defined on this control
			$this->Sortable2->ActionParameter = 
				new QJsClosure('return $j("#' . $this->Sortable2->ControlId . '")
					.find("div.sortitem")
					.map(function() { 
						return $j(this).html()
					}).get();');

			//(the list of names from the containing items) is returned for the following two Ajax Actions
			$this->Sortable2->AddAction(new QSortable_UpdateEvent(), new QAjaxAction("onSort2"));
			//$this->Sortable2->AddAction(new QSortable_ReceiveEvent() ,new QAjaxAction("onSort2"));

			$this->Sortable2Result = new QPanel($this);

			$this->btnSubmit->AddAction(new QClickEvent(), new QServerAction("onSubmit", null, $strServerActionJsParam));
		}

		public function onSort($formId, $objId, $objParam) {
			$this->SortableResult->Text = print_r($objParam, true);
		}

		public function onSort2($formId, $objId, $objParam) {
			$this->Sortable2Result->Text = print_r($objParam, true);
		}

		public function onResize($formId, $objId, $objParam) {
			$this->ResizableResult->Text = print_r($objParam, true);
		}

		public function onSelect($formId, $objId, $objParam) {
			$this->SelectableResult->Text = print_r($objParam, true);
		}

		public function onSubmit($formId, $objId, $objParam) {
			$this->SubmitResult->Text = print_r($objParam, true);
		}

		public function onSlide($formId, $objId, $objParam) {
			$this->SliderResult->Text = print_r($objParam, true);
		}
	}

	ExampleForm::Run('ExampleForm');
?>
