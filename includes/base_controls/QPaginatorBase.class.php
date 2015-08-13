<?php

	/**
	 * This controls works together with a QPaginatedControl to implement a pagintor for that control. Multiple
	 * pagintors per QPaginatedControl can be declared.
	 *
	 * @package Controls
	 * 
	 * @property integer $ItemsPerPage 		How many items you want to display per page when Pagination is enabled
	 * @property integer $PageNumber 		The current page number you are viewing. 1 is the first page, there is no page zero.
	 * @property integer $TotalItemCount 	The total number of items in the ENTIRE recordset -- only used when Pagination is enabled
	 * @property boolean $UseAjax			Whether to use ajax in the drawing.
	 * @property-read integer $PageCount	Current number of pages being represented
	 * @property mixed $WaitIcon			The wait icon to display
	 * @property-read mixed $PaginatedControl	The paginated control linked to this control
	 * @property integer $IndexCount		The maximum number of page numbers to disply in the paginator
	 * @property string LabelForPrevious
	 * @property string LabelForNext
	 */
	abstract class QPaginatorBase extends QControl {
		/** @var string Label for the 'Previous' link */
		protected $strLabelForPrevious;
		/** @var string Label for the 'Next' link */
		protected $strLabelForNext;

		// BEHAVIOR
		/** @var int  */
		protected $intItemsPerPage = 15;
		/** @var int  */
		protected $intPageNumber = 1;
		/** @var int  */
		protected $intTotalItemCount = 0;
		/** @var bool  */
		protected $blnUseAjax = true;
		/** @var  QPaginatedControl */
		protected $objPaginatedControl;
		/** @var string */
		protected $objWaitIcon = 'default';

		/** @var null|\QControlProxy  */
		protected $prxPagination = null;

		// SETUP
		/** @var bool  */
		protected $blnIsBlockElement = false;
		/** @var string */
		protected $strTag = 'span';

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
			$this->strLabelForPrevious = QApplication::Translate('Previous');
			$this->strLabelForNext = QApplication::Translate('Next');

			$this->Setup();
		}

		/**
		 * Setup the proxy events.
		 */
		protected function Setup() {
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

		/**
		 * Respond to the Page_Click event
		 *
		 * @param $strFormId
		 * @param $strControlId
		 * @param $strParameter
		 */
		public function Page_Click($strFormId, $strControlId, $strParameter) {
			$this->objPaginatedControl->PageNumber = QType::Cast($strParameter, QType::Integer);			
		}

		/**
		 * Assign a paginated control to the paginator.
		 *
		 * @param QPaginatedControl $objPaginatedControl
		 */
		public function SetPaginatedControl(QPaginatedControl $objPaginatedControl) {
			$this->objPaginatedControl = $objPaginatedControl;

			$this->UseAjax = $objPaginatedControl->UseAjax;
			$this->ItemsPerPage = $objPaginatedControl->ItemsPerPage;
			$this->PageNumber = $objPaginatedControl->PageNumber;
			$this->TotalItemCount = $objPaginatedControl->TotalItemCount;
		}


		/**
		 * Renders the set of previous buttons. This would be whatever comes before the page numbers in the paginator.
		 * This particular implementation renders a "Previous" text button, with a separator, and a Rewind button
		 * that looks like a number followed by an ellipsis.
		 *
		 * @return string
		 */
		protected function GetPreviousButtonsHtml () {
			if ($this->intPageNumber <= 1) {
				$strPrevious = $this->strLabelForPrevious;
			} else {
				$this->mixActionParameter = $this->intPageNumber - 1;
				$strPrevious = $this->prxPagination->RenderAsLink($this->strLabelForPrevious, $this->mixActionParameter, ['id'=>$this->ControlId . "_arrow_" . $this->mixActionParameter]);
				/*
				$strPrevious = sprintf('<a id="%s" href="%s" %s>%s</a>',
					$this->ControlId . "_arrow_" . $this->mixActionParameter,
					QApplication::$RequestUri,
					$this->prxPagination->RenderAsEvents($this->mixActionParameter, true, $this->ControlId . "_arrow_" . $this->mixActionParameter, false),
					$this->strLabelForPrevious);*/
			}

			$strToReturn = sprintf('<span class="arrow previous">%s</span><span class="break">|</span>', $strPrevious);

			if ($this->PageCount > $this->intIndexCount) {
				// We have more pages than we can display, so make a rewind button
				// This style uses an ellipsis and the page number.

				$intLeftOfBunchCount = floor(($this->intIndexCount - 5) / 2);

				$intLeftBunchTrigger = 4 + $intLeftOfBunchCount;

				if ($this->intPageNumber >= $intLeftBunchTrigger) {
					$this->mixActionParameter = 1;
					$strToReturn .= $this->prxPagination->RenderAsLink(1, $this->mixActionParameter, ['id'=>$this->ControlId . "_page_" . $this->mixActionParameter]);
					$strToReturn = sprintf('<span class="page">%s</span>',$strToReturn);
/*
					$strToReturn .= sprintf('<span class="page"><a id="%s" href="%s" %s>%s</a></span>',
						$this->ControlId . "_page_" . $this->mixActionParameter,
						QApplication::$RequestUri,
						$this->prxPagination->RenderAsEvents($this->mixActionParameter, true, $this->ControlId . "_page_" . $this->mixActionParameter, false),
						1);*/

					$strToReturn .= '<span class="ellipsis">...</span>';
				}
			}

			return $strToReturn;
		}


		/**
		 * Return the html for a particular page button.
		 *
		 * @param $intIndex
		 * @return string
		 */
		protected function GetPageButtonHtml ($intIndex) {
			if ($this->intPageNumber == $intIndex) {
				$strToReturn = sprintf('<span class="selected">%s</span>', $intIndex);
			} else {
				$this->mixActionParameter = $intIndex;
				$strToReturn = $this->prxPagination->RenderAsLink($intIndex, $this->mixActionParameter, ['id'=>$this->ControlId . "_page_" . $this->mixActionParameter]);
				$strToReturn = sprintf('<span class="page">%s</span>',$strToReturn);
				/*
				$strToReturn = sprintf('<span class="page"><a id="%s" href="%s" %s>%s</a></span>',
					$this->ControlId . "_page_" . $this->mixActionParameter,
					QApplication::$RequestUri,
					$this->prxPagination->RenderAsEvents($this->mixActionParameter, true,
						$this->ControlId . "_page_" . $this->mixActionParameter, false), $intIndex);*/
			}
			return $strToReturn;
		}

		/**
		 * Returns the HTML for the group of buttons that come after the group of page buttons.
		 * @return string
		 */
		protected function GetNextButtonsHtml() {
			// build it backwards

			if ($this->intPageNumber >= $this->PageCount) {
				$strNext = $this->strLabelForNext;
			} else {
				$this->mixActionParameter = $this->intPageNumber + 1;
				$strNext = $this->prxPagination->RenderAsLink($this->strLabelForNext, $this->mixActionParameter, ['id'=>$this->ControlId . "_arrow_" . $this->mixActionParameter]);
/*
				$strNext = sprintf('<a id="%s" href="%s" %s>%s</a>',
					$this->ControlId . "_arrow_" . $this->mixActionParameter,
					QApplication::$RequestUri,
					$this->prxPagination->RenderAsEvents($this->mixActionParameter, true, $this->ControlId . "_arrow_" . $this->mixActionParameter, false),
					$this->strLabelForNext);*/
			}

			$strToReturn = sprintf('<span class="arrow next">%s</span>', $strNext);

			$strToReturn = '<span class="break">|</span>' . $strToReturn;

			if ($this->PageCount > $this->intIndexCount) {
				$intMaximumStartOfBunch = $this->PageCount - $this->intIndexCount + 3;

				$intRightBunchTrigger = max ($intMaximumStartOfBunch + round(($this->intIndexCount - 8.0) / 2.0), 1);

				if ($this->intPageNumber <= $intRightBunchTrigger) {

					$this->mixActionParameter = $this->PageCount;
					$strToReturn = $this->prxPagination->RenderAsLink($this->PageCount, $this->mixActionParameter, ['id'=>$this->ControlId . "_page_" . $this->mixActionParameter]);
					$strToReturn = sprintf('<span class="page">%s</span>',$strToReturn);

/*
					$strToReturn = sprintf('<span class="page"><a id="%s" href="%s" %s>%s</a></span>',
						$this->ControlId . "_page_" . $this->mixActionParameter,
						QApplication::$RequestUri,
						$this->prxPagination->RenderAsEvents($this->mixActionParameter, true, $this->ControlId . "_page_" . $this->mixActionParameter, false),
						$this->PageCount) . $strToReturn;*/

					$strToReturn = '<span class="ellipsis">...</span>' . $strToReturn;
				}
			}

			return $strToReturn;
		}

		public function GetControlHtml() {
			$this->objPaginatedControl->DataBind();

			$strToReturn = $this->GetPreviousButtonsHtml();

			// Figure Out Constants

			/**
			 * "Bunch" is defined as the collection of numbers that lies in between the pair of Ellipsis ("...")
			 *
			 * LAYOUT
			 *
			 * For IndexCount of 10
			 * 2   213   2 (two items to the left of the bunch, and then 2 indexes, selected index, 3 indexes, and then two items to the right of the bunch)
			 * e.g. 1 ... 5 6 *7* 8 9 10 ... 100
			 *
			 * For IndexCount of 11
			 * 2   313   2
			 *
			 * For IndexCount of 12
			 * 2   314   2
			 *
			 * For IndexCount of 13
			 * 2   414   2
			 *
			 * For IndexCount of 14
			 * 2   415   2
			 *
			 *
			 *
			 * START/END PAGE NUMBERS FOR THE BUNCH
			 *
			 * For IndexCount of 10
			 * 1 2 3 4 5 6 7 8 .. 100
			 * 1 .. 4 5 *6* 7 8 9 .. 100
			 * 1 .. 92 93 *94* 95 96 97 .. 100
			 * 1 .. 93 94 95 96 97 98 99 100
			 *
			 * For IndexCount of 11
			 * 1 2 3 4 5 6 7 8 9 .. 100
			 * 1 .. 4 5 6 *7* 8 9 10 .. 100
			 * 1 .. 91 92 93 *94* 95 96 97 .. 100
			 * 1 .. 92 93 94 95 96 97 98 99 100
			 *
			 * For IndexCount of 12
			 * 1 2 3 4 5 6 7 8 9 10 .. 100
			 * 1 .. 4 5 6 *7* 8 9 10 11 .. 100
			 * 1 .. 90 91 92 *93* 94 95 96 97 .. 100
			 * 1 .. 91 92 93 94 95 96 97 98 99 100
			 *
			 * For IndexCount of 13
			 * 1 2 3 4 5 6 7 8 9 11 .. 100
			 * 1 .. 4 5 6 7 *8* 9 10 11 12 .. 100
			 * 1 .. 89 90 91 92 *93* 94 95 96 97 .. 100
			 * 1 .. 90 91 92 93 94 95 96 97 98 99 100
			 */
			$intMinimumEndOfBunch = $this->intIndexCount - 2;
			$intMaximumStartOfBunch = $this->PageCount - $this->intIndexCount + 3;

			$intLeftOfBunchCount = floor(($this->intIndexCount - 5) / 2);
			$intRightOfBunchCount = round(($this->intIndexCount - 5.0) / 2.0);

			$intLeftBunchTrigger = 4 + $intLeftOfBunchCount;
			$intRightBunchTrigger = $intMaximumStartOfBunch + round(($this->intIndexCount - 8.0) / 2.0);

			if ($this->intPageNumber < $intLeftBunchTrigger) {
				$intPageStart = 1;
			} else {
				$intPageStart = min($intMaximumStartOfBunch, $this->intPageNumber - $intLeftOfBunchCount);
			}

			if ($this->intPageNumber > $intRightBunchTrigger) {
				$intPageEnd = $this->PageCount;
			} else {
				$intPageEnd = max($intMinimumEndOfBunch, $this->intPageNumber + $intRightOfBunchCount);
			}

			for ($intIndex = $intPageStart; $intIndex <= $intPageEnd; $intIndex++) {
				$strToReturn .= $this->GetPageButtonHtml($intIndex);
			}

			$strToReturn .= $this->GetNextButtonsHtml();

			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf(' style="%s"', $strStyle);

			// Wrap the whole paginator in the main control tag
			$strToReturn = sprintf('<%s id="%s" %s%s>%s</%s>', $this->strTag, $this->strControlId, $strStyle, $this->GetAttributes(), $strToReturn, $this->strTag);

			return $strToReturn;
		}

		/**
		 * After adjusting the total item count, or page size, or other parameters, call this to adjust the page number
		 * to make sure it is not off the end.
		 */
		public function LimitPageNumber() {
			$pageCount = $this->CalcPageCount();
			if ($this->intPageNumber > $pageCount) {
				if ($pageCount <= 1) {
					$this->intPageNumber = 1;
				} else {
					$this->intPageNumber = $pageCount;
				}
			}
		}

		public function CalcPageCount() {
			return floor($this->intTotalItemCount / $this->intItemsPerPage) +
				((($this->intTotalItemCount % $this->intItemsPerPage) != 0) ? 1 : 0);

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
					return $this->CalcPageCount();
				case 'WaitIcon':
					return $this->objWaitIcon;
				case "PaginatedControl":
					return $this->objPaginatedControl;
				case 'IndexCount':
					return $this->intIndexCount;
				case 'LabelForNext':
					return $this->strLabelForNext;
				case 'LabelForPrevious':
					return $this->strLabelForPrevious;
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
						if ($mixValue > 0) {
							$this->intTotalItemCount = QType::Cast($mixValue, QType::Integer);
						}
						else {
							$this->intTotalItemCount = 0;
						}
						$this->LimitPageNumber();
						return $this->intTotalItemCount;
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

				case 'IndexCount':
					$this->intIndexCount = QType::Cast($mixValue, QType::Integer);
					if ($this->intIndexCount < 7)
						throw new QCallerException('Paginator must have an IndexCount >= 7');
					return $this->intIndexCount;

				case 'LabelForNext':
					try {
						return ($this->strLabelForNext = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case 'LabelForPrevious':
					try {
						return ($this->strLabelForPrevious = QType::Cast($mixValue, QType::String));
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
