<?php
    require_once('../qcubed.inc.php');
    
	class PaginatorForm extends QForm {
		/** @var  QDataGrid */
		protected $dtg;
		/** @var  QIntegerTextBox */
		protected $txtCount;
		/** @var  QIntegerTextBox */
		protected $txtPageSize;

		protected function Form_Create() {
			$this->dtg = new QDataGrid($this);
			$this->dtg->SetDataBinder("dtg_Bind");
			$this->dtg->Paginator = new QPaginator($this->dtg);
			$this->dtg->CreateIndexedColumn("Item", 0);

			$this->txtCount = new QIntegerTextBox($this);
			$this->txtCount->Name = "Count";
			$this->txtCount->SaveState = true;
			$this->txtCount->AddAction(new QChangeEvent(), new QAjaxAction("refreshGrid"));

			$this->txtPageSize = new QIntegerTextBox($this);
			$this->txtPageSize->Name = "Page Size";
			$this->txtPageSize->Text = 10;
			$this->txtPageSize->SaveState = true;
			$this->txtPageSize->AddAction(new QChangeEvent(), new QAjaxAction("refreshGrid"));

			$intPageSize = (int)$this->txtPageSize->Text;
			$this->dtg->ItemsPerPage = $intPageSize;

		}

		protected function refreshGrid() {
			$this->dtg->Refresh();
		}

		public function dtg_Bind() {
			$intPageSize = (int)$this->txtPageSize->Text;
			$this->dtg->ItemsPerPage = $intPageSize;
			$intCount = (int)$this->txtCount->Text;
			$this->dtg->TotalItemCount = $intCount;
			$intStart = $this->dtg->ItemsOffset;
			$intEnd = min($intCount, $intStart + $intPageSize);
			for ($i = $intStart; $i < $intEnd; $i++) {
				$a[] = [self::NumToWord($i)];
			}
			if (!empty($a)) {
				$this->dtg->DataSource = $a;
			}
		}

		protected static function NumToWord($intNum) {
			$c = chr($intNum % 26 + 65);
			$intNewNum = (int)floor($intNum / 26);
			if ($intNewNum) {
				$c = self::NumToWord($intNewNum) . $c;
			}
			return $c;
		}
		
	}
PaginatorForm::Run('PaginatorForm');
?>