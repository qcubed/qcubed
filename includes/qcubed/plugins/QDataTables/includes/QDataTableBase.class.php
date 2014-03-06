<?php

	class QDataTable_RowClickEvent extends QEvent {
		const EventName = 'QDataTable_RowClickEvent';
	}

	/**
	 * @property-read string $Filter
	 * @property-read QQLimitInfo $LimitInfo
	 * @property-read QQClause[] $Clauses
	 * @property int $FilteredItemCount
	 * @property int $TotalItemCount
	 * @property boolean $FilterOnReturn
	 * @property int $FilteringDelay
	 */
	class QDataTableBase extends QDataTableGen {
		protected $objLimitInfo;
		protected $objClauses;
		protected $strFilter;
		protected $intEcho = null;
		protected $intTotalItemCount = 0;
		protected $intFilteredItemCount = 0;
		protected $blnFilterOnReturn = false;
		protected $intFilteringDelay = 0;

		public function  __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);
			$this->AddJavascriptFile("../../plugins/QDataTables/DataTables-1.9.0/media/js/jquery.dataTables.min.js");
			$this->AddCssFile("../../plugins/QDataTables/DataTables-1.9.0/media/css/jquery.dataTables.css");
			$this->AddCssFile("../../plugins/QDataTables/DataTables-1.9.0/media/css/jquery.dataTables_themeroller.css");
			$this->UseAjax = false;
			$this->JQueryUI = true;
		}

		public function AddAction($objEvent, $objAction) {
			if ($objEvent instanceof QDataTable_RowClickEvent) {
				$objAction = new QNoScriptAjaxAction($objAction);
			}
			parent::AddAction($objEvent, $objAction);
		}

		public function ParsePostData() {
			$this->objClauses = array();
			$this->strFilter = null;
			$this->intEcho = null;
			// Check to see if this Control's Value was passed in via the POST data
			if (array_key_exists('Qform__FormControl', $_POST) && ($_POST['Qform__FormControl'] == $this->strControlId)) {
				if (isset($_REQUEST['iDisplayStart']) && $_REQUEST['iDisplayLength'] != '-1') {
					$intOffset = QType::Cast($_REQUEST['iDisplayStart'], QType::Integer);
					$intMaxRowCount = QType::Cast($_REQUEST['iDisplayLength'], QType::Integer);
					$this->objLimitInfo = QQ::LimitInfo($intMaxRowCount, $intOffset);
				}
				if (isset($_REQUEST['iSortCol_0'])) {
					$intSortColsCount = QType::Cast($_REQUEST['iSortingCols'], QType::Integer);
					for ($i = 0; $i < $intSortColsCount; $i++) {
						$intSortColIdx = QType::Cast($_REQUEST['iSortCol_' . $i], QType::Integer);
						$blnSortCol = QType::Cast($_REQUEST['bSortable_' . $intSortColIdx], QType::Boolean);
						if ($blnSortCol) {
							$objColumn = $this->GetColumn($intSortColIdx);
							$strSortDir = QType::Cast($_REQUEST['sSortDir_' . $i], QType::String);
							if (strtolower($strSortDir) == 'desc') {
								if ($objColumn->ReverseOrderByClause) {
									$this->objClauses[] = $objColumn->ReverseOrderByClause;
								}
							} else {
								if ($objColumn->OrderByClause) {
									$this->objClauses[] = $objColumn->OrderByClause;
								}
							}
						}
					}
				}
				if (isset($_REQUEST['sSearch'])) {
					$this->strFilter = QType::Cast($_REQUEST['sSearch'], QType::String);
				}
				if (isset($_REQUEST['sEcho'])) {
					$this->intEcho = QType::Cast($_REQUEST['sEcho'], QType::Integer);
				}
			}
		}

		public function RenderAjax($blnDisplayOutput = true) {
			if (!is_null($this->intEcho)) {
				$this->DataBind();
				$mixDataArray = array();
				if ($this->objDataSource) {
					foreach ($this->objDataSource as $objObject) {
						$row = array();
						foreach ($this->objColumnArray as $objColumn) {
							$row[] = $objColumn->FetchCellValue($objObject);
						}
						$mixDataArray[] = $row;
					}
				}
				$filteredCount = $this->strFilter ? $this->FilteredItemCount : $this->TotalItemCount;
				if (!$filteredCount || $filteredCount < count($mixDataArray)) {
					$filteredCount = count($mixDataArray);
				}
				$output = array(
					"sEcho" => $this->intEcho,
					"iTotalRecords" => $this->TotalItemCount,
					"iTotalDisplayRecords" => $filteredCount,
					"aaData" => $mixDataArray
				);
				while(ob_get_level()) ob_end_clean();
				echo json_encode($output);
				exit;
			}
			return parent::RenderAjax($blnDisplayOutput);
		}

		public function GetControlJavaScript() {
			// add row click handling
			// use a temporary ajax action with JsReturnParam to generate the ajax script for us
			$strJsReturnParam = sprintf("jQuery('#%s').%s().fnGetData(this)", $this->getJqControlId(), $this->getJqSetupFunction()); // 'this' is the row; fnGetData(this) returns the data for the row
			$action = new QAjaxAction('', 'default', null, $strJsReturnParam);
			$action->Event = new QDataTable_RowClickEvent();
			$strJsBody = $action->RenderScript($this);

			$strJS = parent::GetControlJavaScript();
			$strJS .= ".on('click', 'tbody tr', function () { $strJsBody })";
			return $strJS;
		}

		public function DataBind() {
			if (!array_key_exists('sEcho', $_REQUEST)) {
				$this->objDataSource = array();
				return;
			}
			parent::DataBind();
		}

		public function GetEndScript() {
			$strJs = parent::GetEndScript();
			if ($this->blnFilterOnReturn) {
				$this->AddJavascriptFile("../../plugins/QDataTables/DataTables-1.9.0/plugin-apis/media/js/dataTables.fnFilterOnReturn.js");
				$strJs .= sprintf('jQuery("#%s").%s().fnFilterOnReturn(); ',
						  $this->getJqControlId(),
						  $this->getJqSetupFunction());
			}
			if ($this->intFilteringDelay > 0) {
				$this->AddJavascriptFile("../../plugins/QDataTables/DataTables-1.9.0/plugin-apis/media/js/dataTables.fnSetFilteringDelay.js");
				$strJs .= sprintf('jQuery("#%s").%s().fnSetFilteringDelay(%d); ',
						  $this->getJqControlId(),
						  $this->getJqSetupFunction(),
						  $this->intFilteringDelay
						  );
			}
			return $strJs;
		}

		public function __get($strName) {
			switch ($strName) {
				case "Filter": return $this->strFilter;
				case "LimitInfo": return $this->objLimitInfo;
				case "Clauses": return $this->objClauses;
				case "FilteredItemCount": return $this->intTotalItemCount;
				case "TotalItemCount": return $this->intTotalItemCount;
				case "FilterOnReturn": return $this->blnFilterOnReturn;
				case "FilteringDelay": return $this->intFilteringDelay;
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
			$this->blnModified = true;

			switch ($strName) {
				case "TotalItemCount":
					try {
						$this->intTotalItemCount = QType::Cast($mixValue, QType::Integer);
						if ($this->intTotalItemCount < 0)
							$this->intTotalItemCount = 0;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "FilteredItemCount":
					try {
						$this->intFilteredItemCount = QType::Cast($mixValue, QType::Integer);
						if ($this->intFilteredItemCount < 0)
							$this->intFilteredItemCount = 0;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "FilterOnReturn":
					try {
						$this->blnFilterOnReturn = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "FilteringDelay":
					try {
						$this->intFilteringDelay = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'UseAjax':
					parent::__set($strName, $mixValue);
					if ($this->blnUseAjax) {
						$this->ServerSide = true;
						$this->ServerMethod = 'post';
						$this->AjaxSource = $_SERVER["SCRIPT_NAME"];
						$strJs = "aoData.push({'name': 'Qform__FormId', 'value': jQuery('#Qform__FormId').val()});";
						$strJs .= "aoData.push({'name': 'Qform__FormState', 'value': jQuery('#Qform__FormState').val()});";
						$strJs .= "aoData.push({'name': 'Qform__FormCallType', 'value': 'Ajax'});";
						$strJs .= "aoData.push({'name': 'Qform__FormUpdates', 'value': ''});";
						$strJs .= "aoData.push({'name': 'Qform__FormCheckableControls', 'value': ''});";
						$strJs .= "aoData.push({'name': 'Qform__FormEvent', 'value': ''});";
						$strJs .= sprintf("aoData.push({'name': 'Qform__FormControl', 'value': '%s'});", $this->strControlId);
						$this->ServerParams = new QJsClosure($strJs, array('aoData'));
					} else {
						$this->ServerSide = false;
						$this->AjaxSource = null;
						$this->ServerParams = null;
					}
					break;

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

?>
