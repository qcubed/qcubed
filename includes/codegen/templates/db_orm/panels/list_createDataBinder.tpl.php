	/**
	 *	Bind Data to the list control.
	 **/
	public function BindData() {
		$objCondition = $this->GetCondition();
		$this-><?= $strListVarName ?>->BindData($objCondition);
	}


	/**
	 *  Get the condition for the data binder.
	 *  @return QQCondition;
	 **/
	protected function GetCondition() {
<?php if (isset($objTable->Options['CreateFilter']) && $objTable->Options['CreateFilter'] === false) { ?>
		return QQ::All();
<?php } else { ?>
		$strSearchValue = $this->txtFilter->Text;
		$strSearchValue = trim($strSearchValue);

		if (is_null($strSearchValue) || $strSearchValue === '') {
			 return QQ::All();
		} else {
<?php
		$cond = array();
		foreach ($objTable->ColumnArray as $objColumn) {
			switch ($objColumn->VariableTypeAsConstant) {
				case 'QType::Integer':
					$cond[] = 'QQ::Equal(QQN::' . $objTable->ClassName . '()->' . $objColumn->PropertyName . ', $strSearchValue)';
					break;
				case 'QType::String':
					$cond[] = 'QQ::Like(QQN::' . $objTable->ClassName . '()->' . $objColumn->PropertyName. ', "%" . $strSearchValue . "%")';
					break;
			}
		}

		$strCondition = implode (",\n            ", $cond);
		if ($strCondition) {
			$strCondition = "QQ::OrCondition(
				$strCondition
			)";
		} else {
			$strCondition = 'QQ::All()';
		}
?>
			return <?= $strCondition ?>;
<?php } ?>
		}

	}

