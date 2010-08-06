<?php
/*****
 * A QDataGridColumn that contains checkboxes
 * Inspired by Hunter Jensen's work at <http://www.qcodo.com/forums/topic.php/3267>
 * 
 * @author Ryan Peters
 * @copyright ICOM Productions 2010
 * @license MIT
 * @name QCheckBoxColumn
 * 
 */

class QCheckBoxColumn extends QDataGridColumn
{
	protected $objDataGrid;
	protected $blnHtmlEntities = false;
	protected $chkSelectAll;
	protected $colIndex = -1;
	protected $objCheckboxCallback = null;
	protected $strCheckboxCallbackFunc = null;
	protected $strPrimaryKey = 'Id';
	
	/**
	 * Creates a QDataGridColumn of checkboxes
	 *
	 * @param string $strName The header to give the column, shown as the label for the Select All checkbox
	 * @param QDataGrid $dataGrid The parent DataGrid. This does not add the column to that datagrid
	 * @param mixed $objOverrideParameters Same as QDataGrid
	 * @return void 
	 *
	 */
	public function __construct($strName = '', QDataGrid $dataGrid, $objOverrideParameters = null)
	{
		$this->objDataGrid = $dataGrid;
		
		$arrParentArgs = func_get_args();
		
		//change the QDataGrid argument we get, and pass the parent constructor the HTML parameter it expects
		$arrParentArgs[1] = '<?=$_COLUMN->chkSelected_Render($_ITEM) ?>';
		if (version_compare(PHP_VERSION, '5.1.6', '>=')) 
			return call_user_func_array(array($this, 'parent::__construct'), $arrParentArgs);
		else
		{
			$parent_class=get_parent_class($this);
			return call_user_func_array(array($parent_class, '__construct'), $arrParentArgs);
		}
	}
	
	/**
	 * Returns the index of this column in the parent datagrid
	 *
	 * @return int The index of the column, or -1 if not found
	 *
	 */
	protected function GetColIndex()
	{
		//cached, to improve performance
		if($this->colIndex == -1)
		{
			$columns = $this->objDataGrid->GetAllColumns();
			foreach($columns as $index=>$col)
				//=== so that we don't spent CPU time comparing properties, and nesting too deep
				if($col === $this)
					$this->colIndex = $index;	
		}
		return $this->colIndex;
	}
	
	
	/**
	 * Sets the callback method for when a checkbox is created. Used for initializing the checkbox state
	 *
	 * @param Object $objParent The object the callback function belongs to
	 * @param string $strFuncName The name of the function to call back
	 * @return void
	 *
	 */
	public function SetCheckboxCallback($objParent, $strFuncName)
	{
		$this->objCheckboxCallback = $objParent;
		$this->strCheckboxCallbackFunc = $strFuncName;
	}
	
	// Render the Select All checkbox to be displayed in the datagrid header row
	public function chkSelectAll_Render() {
		$colIndex = $this->GetColIndex();
		
		$controlId = 'chkSelectAll' . $colIndex.$this->objDataGrid->ControlId ;
		
		$this->chkSelectAll = $this->objDataGrid->GetChildControl($controlId);
		
		if(null === $this->chkSelectAll) {
			
			$this->chkSelectAll = new QCheckBox($this->objDataGrid, $controlId);
			$this->chkSelectAll->Name = QApplication::Translate('Select All');
			
			$colIndex = $this->GetColIndex();
			$strControlIdStart = 'chkSelect' . $colIndex.$this->objDataGrid->ControlId.'n';
			$strControlIdStartLen = strlen($strControlIdStart);
			
			//Since a QDataGridColumn isn't a control, we can't include external js files, or have EndScripts
			//so we'll just have to include all the code in the onclick itself
			//hopefully this won't result in much duplication, since there shouldn't be too many
			//of these on a single form
			$strJavascript = "var datagrid = document.getElementById('{$this->objDataGrid->ControlId}');var selectAll = document.getElementById('{$this->chkSelectAll->ControlId}');var childInputs = datagrid.getElementsByTagName('input');for(var i = 0; i < childInputs.length; i++){var subid = childInputs[i].id.substring($strControlIdStartLen, 0);if(subid == '$strControlIdStart')childInputs[i].checked = selectAll.checked;}";
			
			$this->chkSelectAll->AddAction(new QClickEvent(), new QJavaScriptAction($strJavascript));
		}
		
		return $this->chkSelectAll->Render(false);
	}
	
	public function chkSelected_Render($_ITEM) {
		$intId = $_ITEM->{$this->strPrimaryKey};
		$colIndex = $this->GetColIndex();
		$strControlId = 'chkSelect' . $colIndex.$this->objDataGrid->ControlId .'n'.$intId;
		
		//Don't re-render an existing checkbox
		$chkSelected = $this->objDataGrid->GetChildControl($strControlId);
		if (!$chkSelected) {
			$chkSelected = new QCheckBox($this->objDataGrid, $strControlId);
			//callback so the creator can set up the checkbox checked state
			if(null !== $this->objCheckboxCallback && null !== $this->strCheckboxCallbackFunc)
			{
				$funcName = $this->strCheckboxCallbackFunc;
				$this->objCheckboxCallback->$funcName($_ITEM, $chkSelected);
			}
			
			//remember the Item ID this checkbox is for and it's original state
			$chkSelected->ActionParameter = $intId.','.($chkSelected->Checked?1:0);
		}
		return $chkSelected->Render(false);
	}
	
	/**
	 * Returns an array of items for the selected rows, loaded using the specified class's Load($id) method
	 *
	 * @param string $strClass The class name of the object type to return
	 * @param bool $blnIndex Whether to spend extra time indexing the array by Id
	 * @return array An array of selected Items
	 *
	 */
	public function GetSelectedItems($strClass, $blnIndex = true, $objClauses = null)
	{
		$itemIds = $this->GetSelectedIds();
		
		//load these items, using QQ::In so that it's a single DB hit
		$idQQNode = QQN::$strClass()->{$this->strPrimaryKey};
		$conditions = QQ::In($idQQNode, $itemIds);
		$items = call_user_func(array($strClass, 'QueryArray'), $conditions, $objClauses);
		
		//Use the item's Id as the index, if desired.
		if($blnIndex)
		{
			$newitems = array();
			foreach($items as $item)
				$newitems[$item->{$this->strPrimaryKey}] = $item;
			return $newitems;
		}
		
		return $items;
	}
	
	/**
	 * Returns an array of the Ids of the items selected. Note this only includes rendered controls.
	 *
	 * @return array An array of selected Ids
	 *
	 */
	public function GetSelectedIds()
	{
		//because of formstate, this will even include ones not currently displayed.
		$childControls = $this->objDataGrid->GetChildControls();
		
		$colIndex = $this->GetColIndex();
		$strSubId = 'chkSelect' . $colIndex.$this->objDataGrid->ControlId .'n';
		
		$itemIds = array();
		foreach ($childControls as $objControl) 
			//if it's a checkbox for this column
			if($objControl instanceof QCheckBox && substr($objControl->ControlId, 0, strlen($strSubId)) == $strSubId)
				if($objControl->Checked)
				{
					$arrParams = explode(',',$objControl->ActionParameter);
					$id = $arrParams[0];
					$itemIds[$id] = $id;
				}
		
		return $itemIds;
	}
	
	
	/**
	 * Returns an array of changed ids.
	 *
	 * @return bool[] Key is the object Id, value is the new check state (bln)
	 *
	 */
	public function GetChangedIds($blnRemember = false)
	{
		//because of formstate, this will even include ones not currently displayed.
		$childControls = $this->objDataGrid->GetChildControls();
		
		$colIndex = $this->GetColIndex();
		$strSubId = 'chkSelect' . $colIndex.$this->objDataGrid->ControlId .'n';
		
		$itemIds = array();
		foreach ($childControls as $objControl) 
		{
			//if it's a checkbox for this column
			if($objControl instanceof QCheckBox && substr($objControl->ControlId, 0, strlen($strSubId)) == $strSubId)
			{
				$arrParams = explode(',',$objControl->ActionParameter);
				$id = $arrParams[0];
				$wasChecked = $arrParams[1] == 1;
				if($wasChecked != $objControl->Checked)
					$itemIds[$id] = $objControl->Checked;
				if($blnRemember)
					$objControl->ActionParameter = $id.','.($objControl->Checked?1:0);
			}
		}
		
		return $itemIds;
	}
	
	/**
	 * Considers the current state to be the new baseline
	 *
	 * @return void
	 *
	 */
	public function AcceptChanges()
	{
		$this->GetChangedIds(true);
	}
	
	public function SetSelectAllCheckbox($value)
	{
		$colIndex = $this->GetColIndex();
		
		$controlId = 'chkSelectAll' . $colIndex.$this->objDataGrid->ControlId ;
		$checkbox = $this->objDataGrid->GetChildControl($controlId);
		if(null === $checkbox)
			throw new exception('Select All Checkbox not found');
		$checkbox->Checked = $value;
	}
	
	public function SetCheckbox($itemId, $value)
	{
		$colIndex = $this->GetColIndex();
		$controlId = 'chkSelect' . $colIndex.$this->objDataGrid->ControlId .'n'.$itemId;
		$checkbox = $this->objDataGrid->GetChildControl($controlId);
		if(null === $checkbox)
			return;
		$checkbox->Checked = $value;
	}
	
	public function __get($strName) {
		switch ($strName) {
			
			case "Name": 
				$strControl = $this->chkSelectAll_Render();
				return '<label for="'.$this->chkSelectAll->ControlId.'">' .$this->strName . ' ' . $strControl. '</label>';
			case "PrimaryKey": 
				return $this->strPrimaryKey;
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
	 * Override method to perform a property "Set"
	 * This will set the property $strName to be $mixValue
	 *
	 * @param string $strName Name of the property to set
	 * @param string $mixValue New value of the property
	 * @return mixed
	 */
	public function __set($strName, $mixValue) {
		switch ($strName) {
			///////////////////
			// Member Variables
			///////////////////
			case 'PrimaryKey':
				/**
				 * Sets the value for strPrimaryKey 
				 * @param integer $mixValue
				 * @return string
				 */
				try {
					return ($this->strPrimaryKey = QType::Cast($mixValue, QType::String));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			default:
				try {
					return parent::__set($strName, $mixValue);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
			}
		}
	}

}

?>
