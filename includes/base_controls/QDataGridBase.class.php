<?php

/**
 * A HtmlTable that is connected to data. Detects and responds to sort clicks.
 *
 * Uses FontAwesome for sort indicators - a font based icon library. Using this allows the icons to be colored
 * and styled, and will size along with the rest of the text.
 *
 * @package Controls
 */


if (!defined('__FONT_AWESOME__')) {
	define('__FONT_AWESOME__', 'https://opensource.keycdn.com/fontawesome/4.6.3/font-awesome.min.css');
}

class QDataGrid_SortEvent extends QEvent {
	const JsReturnParam = 'ui'; // returns the col id
	const EventName = 'qdg2sort';
}


/**
 * Class QDataGridBase
 *
 * This class is designed primarily to work alongside the code generator, but it can be independent as well. It creates
 * an html table that displays data from the database. The data can possibly be sorted by clicking on the header cell
 * of the sort column.
 *
 * This grid also has close ties to the QDataGrid_CheckboxColumn to easily enable the addition of a column or columns
 * of checkboxes.
 *
 * This class is NOT intended to support column filters, but a subclass could be created that could do so. Just don't
 * do that here.
 *
 * @property-read  QQClause $OrderByClause The sorting clause based on the selected headers.
 * @property  string 	$SortColumnId The id of the currently sorted column. Does not change if columns are re-ordered.
 * @property  int 		$SortColumnIndex The index of the currently sorted column.
 * @property  int 		$SortDirection SortAscending or SortDescending.
 * @property  array 	$SortInfo An array containing the sort data, so you can save and restore it later if needed.
 *
 */
class QDataGridBase extends QHtmlTable
{
	/** Numbers than can be used to multiply against the results of comparison functions to reverse the order. */
	const SortAscending = 1;
	const SortDescending = -1;

	/** @var int Couter to generate column ids for columns that do not have them. */
	protected $intLastColumnId = 0;

	/** @var  string Keeps track of current sort column. We do it by id so that the table can add/hide/show or rearrange columns and maintain the sort column. */
	protected $strSortColumnId;

	/** @var int The direction of the currently sorted column.  */
	protected $intSortDirection = self::SortAscending;

	/** @var string Default class */
	protected $strCssClass = 'datagrid';


	/**
	 * QDataGridBase constructor.
	 * @param QControl|QControlBase|QForm $objParentObject
	 * @param string|null $strControlId
	 */
	public function __construct($objParentObject, $strControlId = null)	{
		try {
			parent::__construct($objParentObject, $strControlId);

			$this->AddCssFile(__FONT_AWESOME__);

			$this->AddActions();

		} catch (QCallerException  $objExc) {
			$objExc->IncrementOffset();
			throw $objExc;
		}
	}

	/**
	 * An override to add the paginator to the caption area.
	 * @return string
	 */
	protected function RenderCaption() {
		return $this->RenderPaginator();
	}

	/**
	 * Renders the given paginator in a span in the caption. If a caption already exists, it will add the caption.
	 * @return string
	 * @throws QCallerException
	 */
	protected function RenderPaginator () {
		$objPaginator = $this->objPaginator;
		if (!$objPaginator) return '';

		$strHtml = $objPaginator->Render(false);
		$strHtml = QHtml::RenderTag('span', ['class'=>'paginator-control'], $strHtml);
		if ($this->strCaption) {
			$strHtml = '<span>' . QApplication::HtmlEntities($this->strCaption) . '</span>' . $strHtml;
		}

		$strHtml = QHtml::RenderTag('caption', null, $strHtml);

		return $strHtml;
	}

	/**
	 * Adds the actions for the table. Override to add additional actions. If you are detecting clicks
	 * that need to cancel the default action, put those in front of this function.
	 */
	public function AddActions() {
		$this->AddAction(new QHtmlTableCheckBoxColumn_ClickEvent(), new QAjaxControlAction ($this, 'CheckClick'));
		$this->AddAction(new QHtmlTableCheckBoxColumn_ClickEvent(), new QStopPropagationAction()); // prevent check click from bubbling as a row click.

		$this->AddAction(new QDataGrid_SortEvent(), new QAjaxControlAction ($this, 'SortClick'));
	}

	/**
	 * An override to create an id for every column, since the id is what we use to track sorting.
	 *
	 * @param int $intColumnIndex
	 * @param QAbstractHtmlTableColumn $objColumn
	 * @throws QInvalidCastException
	 */
	public function AddColumnAt($intColumnIndex, QAbstractHtmlTableColumn $objColumn) {
		parent::AddColumnAt($intColumnIndex, $objColumn);
		// Make sure the column has an Id, since we use that to track sorting.
		if (!$objColumn->Id) {
			$objColumn->Id = $this->ControlId . '_col_' . $this->intLastColumnId++;
		}
	}

	/**
	 * Transfers clicks to any checkbox columns.
	 *
	 * @param $strFormId
	 * @param $strControlId
	 * @param $strParameter
	 */
	protected function CheckClick($strFormId, $strControlId, $strParameter) {
		$intColumnIndex = $strParameter['col'];
		$objColumn = $this->GetColumn ($intColumnIndex, true);

		if ($objColumn instanceof QDataGrid_CheckboxColumn) {
			$objColumn->Click($strParameter);
		}
	}

	/**
	 * Clears all checkboxes in checkbox columns. If you have multiple checkbox columns, you can specify which column
	 * to clear. Otherwise, it will clear all of them.
	 *
	 * @param string|null $strColId
	 */
	public function ClearCheckedItems($strColId = null) {
		foreach ($this->objColumnArray as $objColumn) {
			if ($objColumn instanceof QDataGrid_CheckboxColumn) {
				if (is_null($strColId) || $objColumn->Id === $strColId) {
					$objColumn->ClearCheckedItems();
				}
			}
		}
	}

	/**
	 * Returns the checked item ids if the data grid has a QDataGrid_CheckboxColumn column. If there is more than
	 * one column, you can specify which column to want to query. If no id is specified, it
	 * will return the ids from the first column found. If no column was found, then null is returned.
	 *
	 * @param mixed $strColId
	 * @return array|null
	 */
	public function GetCheckedItemIds($strColId = null) {
		foreach ($this->objColumnArray as $objColumn) {
			if ($objColumn instanceof QDataGrid_CheckboxColumn) {
				if (is_null($strColId) ||
						$objColumn->Id === $strColId) {
					return $objColumn->GetCheckedItemIds();
				}
			}
		}
		return null; // column not found
	}

	/**
	 * Processes clicks on a sortable column head.
	 *
	 * @param string $strFormId
	 * @param string $strControlId
	 * @param mixed $mixParameter
	 * @throws QCallerException
	 * @throws QInvalidCastException
	 */
	protected function SortClick($strFormId, $strControlId, $mixParameter) {

		$intColumnIndex = QType::Cast($mixParameter, QType::Integer);
		$objColumn = $this->GetColumn ($intColumnIndex, true);

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

	/**
	 * Override to return the header row to indicate when a column is sortable.
	 *
	 * @return string
	 */
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

	/**
	 * Override to return sortable column info.
	 *
	 * @param $objColumn
	 * @return string
	 */
	protected function GetHeaderCellContent($objColumn) {
		$blnSortable = false;
		$strCellValue = $objColumn->FetchHeaderCellValue();
		if ($objColumn->HtmlEntities) {
			$strCellValue = QApplication::HtmlEntities($strCellValue);
		}
		$strCellValue = QHtml::RenderTag('span', null, $strCellValue);	// wrap in a span for positioning

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
			$strCellValue = QHtml::RenderTag('a', ['href'=>'javascript:;'], $strCellValue); // action will be handled by qcubed.js click handler in qcubed.datagrid2()
		}

		return $strCellValue;
	}

	/**
	 * Override to enable the datagrid2 javascript.
	 *
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

	/**
	 * Override to return the code generator for the list functionality.
	 *
	 * @param string $strClass
	 * @return QDataGrid_CodeGenerator
	 */
	public static function GetCodeGenerator($strClass = 'QDataGrid') {
		return new QDataGrid_CodeGenerator($strClass);
	}

	/**
	 * Returns the index of the currently sorted column.
	 * Returns false if nothing selected.
	 *
	 * @return bool|int
	 */
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

	/**
	 * @param string $strName
	 * @return bool|int|Keeps|mixed|null
	 * @throws QCallerException
	 */
	public function __get($strName) {
		switch ($strName) {
			// MISC
			case "OrderByClause": return $this->GetOrderByInfo();

			case "SortColumnId": return $this->strSortColumnId;
			case "SortDirection": return $this->intSortDirection;

			case "SortColumnIndex": return $this->GetSortColumnIndex();

			case "SortInfo": return ['id'=>$this->strSortColumnId, 'dir'=>$this->intSortDirection];

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

	/**
	 * @param string $strName
	 * @param string $mixValue
	 * @throws QCallerException
	 * @throws QInvalidCastException
	 */
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

			case "SortInfo":	// restore the SortInfo obtained from the getter
				try {
					if (isset($mixValue['id']) && isset($mixValue['dir'])) {
						$this->intSortDirection = QType::Cast($mixValue['dir'], QType::Integer);
						$this->strSortColumnId = QType::Cast($mixValue['id'], QType::String);
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