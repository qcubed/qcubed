<?php
	/**
	 * This file contains the QPaginatorBase class.
	 *
	 * @package Controls
	 */

	/**
	 * @package Controls
	 * 
	 * @property integer $ItemsPerPage is how many items you want to display per page when Pagination is enabled
	 * @property integer $PageNumber is the current page number you are viewing
	 * @property integer $TotalItemCount is the total number of items in the ENTIRE recordset -- only used when Pagination is enabled
	 * @property boolean $UseAjax
	 * @property-read integer $PageCount
	 * @property mixed $WaitIcon
	 * @property-read mixed $PaginatedControl
	 */
	abstract class QPaginatorBase extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////
		
		// BEHAVIOR
		protected $intItemsPerPage = 15;
		protected $intPageNumber = 1;
		protected $intTotalItemCount = 0;
		protected $blnUseAjax = false;
		protected $objPaginatedControl;
		protected $objWaitIcon = 'default';

		protected $prxPagination = null;

		// SETUP
		protected $blnIsBlockElement = false;

		//////////
		// Methods
		//////////
		public function __construct($objParentObject, $strControlId = null) {
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException  $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			
			$this->prxPagination = new QControlProxy($this);
			$this->Setup();
		}
	
		protected function Setup()
		{
			// Setup Pagination Events
			$this->prxPagination->RemoveAllActions(QClickEvent::EventName);			
			if ($this->blnUseAjax) {
				$this->prxPagination->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'Page_Click'));
			}
			else {
				$this->prxPagination->AddAction(new QClickEvent(), new QServerControlAction($this, 'Page_Click'));
			}
			$this->prxPagination->AddAction(new QClickEvent, new QTerminateAction());
		}

		public function ParsePostData() {}
		public function Validate() {return true;}

		public function Page_Click($strFormId, $strControlId, $strParameter) {
			$this->objPaginatedControl->PageNumber = QType::Cast($strParameter, QType::Integer);			
		}

		public function SetPaginatedControl(QPaginatedControl $objPaginatedControl) {
			$this->objPaginatedControl = $objPaginatedControl;

			$this->UseAjax = $objPaginatedControl->UseAjax;
			$this->ItemsPerPage = $objPaginatedControl->ItemsPerPage;
			$this->PageNumber = $objPaginatedControl->PageNumber;
			$this->TotalItemCount = $objPaginatedControl->TotalItemCount;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// BEHAVIOR
				case "ItemsPerPage": return $this->intItemsPerPage;
				case "PageNumber": return $this->intPageNumber;
				case "TotalItemCount": return $this->intTotalItemCount;
				case "UseAjax": return $this->blnUseAjax;
				case "PageCount":
					return floor($this->intTotalItemCount / $this->intItemsPerPage) +
						((($this->intTotalItemCount % $this->intItemsPerPage) != 0) ? 1 : 0);
				case 'WaitIcon':
					return $this->objWaitIcon;
				case "PaginatedControl":
					return $this->objPaginatedControl;
				default:			
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}


		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// BEHAVIOR
				case "ItemsPerPage":
					try {
						if ($mixValue > 0)
							return ($this->intItemsPerPage = QType::Cast($mixValue, QType::Integer));
						else
							return ($this->intItemsPerPage = 10);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "PageNumber":
					try {
						$intNewPageNum = QType::Cast($mixValue, QType::Integer);
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					if ($intNewPageNum > 1) {
						return ($this->intPageNumber = $intNewPageNum);
					} else {
						return ($this->intPageNumber = 1);
					}
					break;

				case "TotalItemCount":
					try {
						if ($mixValue > 0)
							return ($this->intTotalItemCount = QType::Cast($mixValue, QType::Integer));
						else
							return ($this->intTotalItemCount = 0);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "UseAjax":
					try {
						$blnToReturn = ($this->blnUseAjax = QType::Cast($mixValue, QType::Boolean));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					// Because we are switching to/from Ajax, we need to reset the events
					$this->Setup();

					return $blnToReturn;

				case 'WaitIcon':
					try {
						$mixToReturn = $this->objWaitIcon = $mixValue;
						//ensure we update our ajax action to use it
						$this->Setup();
						return $mixToReturn;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>
