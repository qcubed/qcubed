<?php

/**
 * A SimpleTable that is connected to data. Detects and responds to sort clicks.
 *
 * Uses FontAwesome for sort indicators - a font based icon library. Using this allows the icons to be colored
 * and styled, and will size along with the rest of the text.
 *
 * @package Controls
 */


class QDataGrid2_SortEvent extends QEvent {
	const JsReturnParam = 'ui'; // returns the col id
	const EventName = 'qdg2sort';
}


/**
 * Class QDataGrid2
 */
class QDataGrid2Base extends QSimpleTable
{
	/** Numbers than can be used to multiply against the results of comparison functions to reverse the order. */
	const SortAscending = 1;
	const SortDescending = -1;

	/** @var int Couter to generate column ids for columns that do not have them. */
	protected $intLastColumnId = 0;

	/** @var  Keeps track of current sort column. We do it by id so that the table can add/hide/show or rearrange columns and maintain the sort column. */
	protected $strSortColumnId;
	protected $intSortDirection = self::SortAscending;
	protected $strCssClass = 'datagrid';



	public function __construct($objParentObject, $strControlId = null)	{
		try {
			parent::__construct($objParentObject, $strControlId);

			$this->AddCssFile('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

			$this->AddActions();

		} catch (QCallerException  $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}
	}
/*
	protected function GetControlHtml() {
		$strHtml = parent::GetControlHtml();

		if ($this->objPaginator) {
			$strHtml = $this->RenderPaginator($this->objPaginator) . $strHtml;
		}

		if ($this->objPaginatorAlternate) {
			$strHtml .= $this->RenderPaginator($this->objPaginatorAlternate);
		}

		if ($this->objPaginator || $this->objPaginatorAlternate) {
			$this->UseWrapper = true;	// must use a wrapper, since we are drawing multiple controls
		}
		return $strHtml;
	}*/

	protected function RenderCaption() {
		return $this->RenderPaginator();
	}

	protected function RenderPaginator () {
		$objPaginator = $this->objPaginator;
		if (!$objPaginator) return '';

		$strHtml = $objPaginator->Render(false);
		$strHtml = QHtml::RenderTag('span', ['class'=>'paginator-control'], $strHtml);
		$strHtml = QHtml::RenderTag('caption', null, $strHtml);

		/*
		$strToReturn = "  <span class=\"paginator-control\">";
		$strToReturn .= $objPaginator->Render(false);
		$strToReturn .= "</span>\r\n  <span class=\"paginator-results\">";
		if ($this->TotalItemCount > 0) {
			$intStart = (($this->PageNumber - 1) * $this->ItemsPerPage) + 1;
			$intEnd = $intStart + count($this->DataSource) - 1;
			$strToReturn .= sprintf($this->strLabelForPaginated,
				$this->strNounPlural,
				$intStart,
				$intEnd,
				$this->TotalItemCount);
		} else {
			$intCount = count($this->objDataSource);
			if ($intCount == 0)
				$strToReturn .= sprintf($this->strLabelForNoneFound, $this->strNounPlural);
			else if ($intCount == 1)
				$strToReturn .= sprintf($this->strLabelForOneFound, $this->strNoun);
			else
				$strToReturn .= sprintf($this->strLabelForMultipleFound, $intCount, $this->strNounPlural);
		}

		$strToReturn .= "</span>\r\n";

		return $strToReturn;
		*/

		return $strHtml;
	}

	/**
	 * Adds the actions for the table. Override to add additional actions. If you are detecting clicks
	 * that need to cancel the default action, put those in front of this function.
	 */
	public function AddActions() {
		$this->AddAction(new QSimpleTableCheckBoxColumn_ClickEvent(), new QAjaxControlAction ($this, 'CheckClick'));
		$this->AddAction(new QDataGrid2_SortEvent(), new QAjaxControlAction ($this, 'SortClick'));
	}

	public function AddColumnAt($intColumnIndex, QAbstractSimpleTableColumn $objColumn) {
		parent::AddColumnAt($intColumnIndex, $objColumn);
		// Make sure the column has an Id, since we use that to track sorting.
		if (!$objColumn->Id) {
			$objColumn->Id = $this->ControlId . '_col_' . $this->intLastColumnId++;
		}
	}

	protected function CheckClick($strFormId, $strControlId, $strParameter) {
		$intColumnIndex = $strParameter['col'];
		$objColumn = $this->GetColumn ($intColumnIndex, true);

		if ($objColumn instanceof QDataGrid2_CheckboxColumn) {
			$objColumn->Click($strParameter);
		}
	}

	public function ClearCheckedItems() {
		foreach ($this->objColumnArray as $objColumn) {
			if ($objColumn instanceof QDataGrid2_CheckboxColumn) {
				$objColumn->ClearCheckedItems();
			}
		}
	}

	protected function SortClick($strFormId, $strControlId, $strParameter) {

		$intColumnIndex = QType::Cast($strParameter, QType::Integer);
		$objColumn = $this->GetColumn ($intColumnIndex, true);

		//$objColumn = $this->GetColumnById ($strParameter);

		if (!$objColumn) return;

		$this->blnModified = true;

		$strId = $objColumn->Id;

		if (!$objColumn) {
			return;
		}

		// Reset pagination (if applicable)
		if ($this->objPaginator) {
			$this->PageNumber = 1;
		}

		// Make sure the Column is Sortable
		if ($objColumn->OrderByClause) {
			// It is

			// Are we currently sorting by this column?
			if ($this->strSortColumnId === $strId) {
				// Yes we are currently sorting by this column

				// In Reverse?
				if ($this->intSortDirection == self::SortDescending) {
					// Yep -- unreverse the sort
					$this->intSortDirection = self::SortAscending;
				} else {
					// Nope -- can we reverse?
					if ($objColumn->ReverseOrderByClause) {
						$this->intSortDirection = self::SortDescending;
					}
				}
			} else {
				// Nope -- so let's set it to this column
				$this->strSortColumnId = $strId;
				$this->intSortDirection = self::SortAscending;
			}
		} else {
			// It isn't -- clear all sort properties
			$this->intSortDirection = self::SortAscending;
			$this->strSortColumnId = null;
		}
	}

	protected function GetHeaderRowHtml() {
		$strToReturn = '';
		for ($i = 0; $i < $this->intHeaderRowCount; $i++) {
			$this->intCurrentHeaderRowIndex = $i;

			$strCells = '';
			if ($this->objColumnArray) foreach ($this->objColumnArray as $objColumn) {

				if ($objColumn->Visible) {
					$strCellValue = $this->GetHeaderCellContent($objColumn);
					$aParams = $objColumn->GetHeaderCellParams();
					$aParams['id'] = $objColumn->Id;
					if ($objColumn->OrderByClause) {
						if (isset($aParams['class'])) {
							$aParams['class'] .= ' ' . 'sortable';
						} else {
							$aParams['class'] = 'sortable';
						}
					}
					$strCells .= QHtml::RenderTag('th', $aParams, $strCellValue);
				}
			}
			$strToReturn .= QHtml::RenderTag('tr', $this->GetHeaderRowParams(), $strCells);
		}

		return $strToReturn;
	}

	protected function GetHeaderCellContent($objColumn) {
		$blnSortable = false;
		$strCellValue = $objColumn->FetchHeaderCellValue();
		if ($objColumn->HtmlEntities) {
			$strCellValue = QApplication::HtmlEntities($strCellValue);
		}

		if ($this->strSortColumnId === $objColumn->Id) {
			if ($this->intSortDirection == self::SortAscending) {
				$strCellValue = $strCellValue . ' ' . QHtml::RenderTag('i', ['class'=>'fa fa-sort-desc fa-lg']);
			} else {
				$strCellValue = $strCellValue . ' ' . QHtml::RenderTag('i', ['class'=>'fa fa-sort-asc fa-lg']);
			}
			$blnSortable = true;
		}
		else if ($objColumn->OrderByClause) {	// sortable, but not currently being sorted
			$strCellValue = $strCellValue . ' ' . QHtml::RenderTag('i', ['class'=>'fa fa-sort fa-lg', 'style'=>'opacity:0.8']);
			$blnSortable = true;
		}

		if ($blnSortable) {
			// Wrap header cell in an html5 block-link to help with assistive technologies.
			$strCellValue = QHtml::RenderTag('div', null, $strCellValue);
			$strCellValue = QHtml::RenderTag('a', ['href'=>'#'], $strCellValue); // action will be handled by qcubed.js click handler in qcubed.datagrid2()
		}

		return $strCellValue;
	}

	/**
	 * Return the javascript associated with the control.
	 * @return string
	 */
	public function GetEndScript() {
		$strJS = parent::GetEndScript();
		QApplication::ExecuteJsFunction('qcubed.datagrid2', $this->ControlId);
		return $strJS;
	}


	/**
	 * Returns the current state of the control to be able to restore it later.
	 * @return mixed
	 */
	public function GetState() {
		$state = array();
		if ($this->strSortColumnId !== null) {
			$state["c"] = $this->strSortColumnId;
			$state["d"] = $this->intSortDirection;
		}
		if ($this->Paginator || $this->PaginatorAlternate) {
			$state["p"] = $this->PageNumber;
		}
		return $state;
	}

	/**
	 * Restore the state of the control.
	 * @param mixed $state Previously saved state as returned by GetState above.
	 */
	public function PutState($state) {
		// use the name as the column key because columns might be added or removed for some reason
		if (isset ($state["c"])) {
			$this->strSortColumnId = $state["c"];
		}
		if (isset ($state["d"])) {
			$this->intSortDirection = $state["d"];
			if ($this->intSortDirection != self::SortDescending) {
				$this->intSortDirection = self::SortAscending;	// make sure its only one of two values
			}
		}
		if (isset ($state["p"]) &&
			($this->Paginator || $this->PaginatorAlternate)) {
			$this->PageNumber = $state["p"];
		}
	}

	public static function GetCodeGenerator($strClass = 'QDataGrid2') {
		return new QDataGrid2_CodeGenerator($strClass);
	}

	public function GetSortColumnIndex() {
		if ($this->objColumnArray && ($count = count($this->objColumnArray))) {
			for($i = 0; $i < $count; $i++) {
				if ($this->objColumnArray[$i]->Id == $this->SortColumnId) {
					return $i;
				}
			}
		}
		return false;
	}

	/**
	 * Return information on sorting the data. For SQL databases, this would be a QQClause. But since this just
	 * gets the clause from the currently active column, it could be anything.
	 *
	 * This clause should not affect counting or limiting.
	 *
	 * @return mixed
	 */
	public function GetOrderByInfo() {
		if ($this->strSortColumnId !== null) {
			$objColumn = $this->GetColumnById($this->strSortColumnId);
			if ($objColumn && $objColumn->OrderByClause) {
				if ($this->intSortDirection == self::SortAscending) {
					return $objColumn->OrderByClause;
				}
				else {
					if ($objColumn->ReverseOrderByClause) {
						return $objColumn->ReverseOrderByClause;
					}
					else {
						return $objColumn->OrderByClause;
					}
				}
			}
			else {
				return null;
			}
		} else {
			return null;
		}
	}

	public function __get($strName) {
		switch ($strName) {
			// MISC
			case "OrderByClause": return $this->GetOrderByInfo();

			case "SortColumnId": return $this->strSortColumnId;
			case "SortDirection": return $this->intSortDirection;

			case "SortColumnIndex": return $this->GetSortColumnIndex();

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

	public function __set($strName, $mixValue) {
		switch ($strName) {
			case "SortColumnId":
				try {
					$this->strSortColumnId = QType::Cast($mixValue, QType::String);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "SortColumnIndex":
				try {
					$intIndex = QType::Cast($mixValue, QType::Integer);
					if ($intIndex < 0) {
						$intIndex = 0;
					}
					if ($intIndex < count($this->objColumnArray)) {
						 $objColumn = $this->objColumnArray[$intIndex];
					} elseif (count($this->objColumnArray) > 0) {
						$objColumn = end($this->objColumnArray);
					} else {
						// no columns
						$objColumn = null;
					}
					if ($objColumn && $objColumn->OrderByClause) {
						$this->strSortColumnId = $objColumn->Id;
					}
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
				break;

			case "SortDirection":
				try {
					$this->intSortDirection = QType::Cast($mixValue, QType::Integer);
					if ($this->intSortDirection != self::SortDescending) {
						$this->intSortDirection = self::SortAscending;	// make sure its only one of two values
					}
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			default:
				try {
					parent::__set($strName, $mixValue);
					break;
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

}
