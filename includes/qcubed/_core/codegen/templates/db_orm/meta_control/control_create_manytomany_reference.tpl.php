		protected $col<?php echo $objManyToManyReference->ObjectDescription  ?>Selected;

		/**
		 * Create and setup QDataGrid <?php echo $strControlId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param string $strURL the URL to go to when clicking on the label. Note that the object ID will be appended to this URL.
		 * @return QDataGrid
		 */
		public function <?php echo $strControlId  ?>_Create($strControlId = null, $strURL = null) {
			// Setup DataGrid
			$this-><?php echo $strControlId  ?> = new QDataGrid($this->objParentObject, $strControlId);
			$this-><?php echo $strControlId  ?>->CssClass = 'datagrid';
			$this-><?php echo $strControlId  ?>->Owner = $this;

			// Datagrid Paginator
			$this-><?php echo $strControlId  ?>->Paginator = new QPaginator($this-><?php echo $strControlId  ?>);
			//If desired, use this to set the numbers of items to show per page
			//$this-><?php echo $strControlId  ?>->ItemsPerPage = 20;

			// Specify Whether or Not to Refresh using Ajax
			$this-><?php echo $strControlId  ?>->UseAjax = true;

			// Specify the local databind method this datagrid will use
			$this-><?php echo $strControlId  ?>->SetDataBinder('<?php echo $strControlId  ?>_Bind', $this);

			// Setup DataGridColumns
			$this->col<?php echo $objManyToManyReference->ObjectDescription  ?>Selected = new QCheckBoxColumn(QApplication::Translate('Select'), $this-><?php echo $strControlId  ?>);
			$this->col<?php echo $objManyToManyReference->ObjectDescription  ?>Selected->PrimaryKey = '<?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?>';
			$this->col<?php echo $objManyToManyReference->ObjectDescription  ?>Selected->SetCheckboxCallback($this, '<?php echo $strControlId  ?>Select_Created');
			$this-><?php echo $strControlId  ?>->AddColumn($this->col<?php echo $objManyToManyReference->ObjectDescription  ?>Selected);

			$colName = new QDataGridColumn(QApplication::Translate('Name'), '<?php print("<?="); ?> $_OWNER-><?php echo $strControlId  ?>_Name_Render($_ITEM, "'.$strURL.'"); ?>');
			//, array('OrderByClause' => QQ::OrderBy(<?php echo $objManyToManyReference->VariableType  ?>::GetNameOrderByNode()),
			//	'ReverseOrderByClause' => QQ::OrderBy(<?php echo $objManyToManyReference->VariableType  ?>::GetNameOrderByNode(), false)));
			//$colName->Filter = QQ::Like(<?php echo $objManyToManyReference->VariableType  ?>::GetNameOrderByNode(), null);
			//$colName->FilterPrefix = $colName->FilterPostfix = "%";

			$colName->HtmlEntities = false;
			$this-><?php echo $strControlId  ?>->AddColumn($colName);

			$this-><?php echo $strControlId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural)  ?>');

			return $this-><?php echo $strControlId  ?>;
		}

		public function <?php echo $strControlId  ?>Select_Created(<?php echo $objManyToManyReference->VariableType  ?> $_ITEM, QCheckBox $ctl) {
			if(null !== $_ITEM->GetVirtualAttribute('assn_item')) {
				$ctl->Checked = true;
			}
		}

		public function <?php echo $strControlId  ?>_Name_Render(<?php echo $objManyToManyReference->VariableType  ?> $_ITEM, $strURL = '') {
			$strName = QApplication::Translate(QApplication::HtmlEntities($_ITEM->__toString()));

			// Link to the edit page for this object
			if('' != $strURL)
				return sprintf('<a href="%s%s">%s</a>',
					$strURL,
					$_ITEM-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?>,
					$strName);
			return $strName;
		}

		protected function Get<?php echo $strControlId  ?>Conditions() {
			// Override this function if there are additional limitations on this list.
			return null;
		}

		public function <?php echo $strControlId  ?>_Bind() {
			// Get Total Count b/c of Pagination
			$conditions = $this-><?php echo $strControlId  ?>->Conditions;

			$moreConditions = $this->Get<?php echo $strControlId  ?>Conditions();
			if($moreConditions !== null) {
				$conditions = QQ::AndCondition($conditions, $moreConditions);
			}

			$this-><?php echo $strControlId  ?>->TotalItemCount = <?php echo $objManyToManyReference->VariableType  ?>::QueryCount($conditions);

			$objClauses = array();
			if ($objClause = $this-><?php echo $strControlId  ?>->OrderByClause) {
				array_push($objClauses, $objClause);
			}
			if ($objClause = $this-><?php echo $strControlId  ?>->LimitClause) {
				array_push($objClauses, $objClause);
			}

			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();
			$objClauses[] = QQ::Expand(
				QQ::Virtual('assn_item',
					QQ::SubSql('select <?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?>

					 from <?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Table  ?><?php echo $strEscapeIdentifierEnd  ?>

					 where
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->OppositeColumn  ?><?php echo $strEscapeIdentifierEnd  ?> = {1}
					and <?php echo $strEscapeIdentifierBegin  ?><?php echo $objManyToManyReference->Column  ?><?php echo $strEscapeIdentifierEnd  ?> = '.
					$objDatabase->SqlVariable($this-><?php echo $strObjectName  ?>-><?php echo $objTable->PrimaryKeyColumnArray[0]->PropertyName  ?>),
						QQN::<?php echo $objManyToManyReference->VariableType  ?>()-><?php echo $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName  ?>)
						)
					);

			$this-><?php echo $strControlId  ?>->DataSource = <?php echo $objManyToManyReference->VariableType  ?>::QueryArray($conditions, $objClauses);
		}

		/**
		 * Create and setup QLabel <?php echo $strLabelId  ?>

		 * @param string $strControlId optional ControlId to use
		 * @param string $strGlue glue to display in-between each associated object
		 * @return QLabel
		 */
		public function <?php echo $strLabelId  ?>_Create($strControlId = null, $strGlue = ', ') {
			$this-><?php echo $strLabelId  ?> = new QLabel($this->objParentObject, $strControlId);
			$this-><?php echo $strLabelId  ?>->Name = QApplication::Translate('<?php echo QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural)  ?>');
			$this->str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue = $strGlue;

			$objAssociatedArray = $this-><?php echo $strObjectName  ?>->Get<?php echo $objManyToManyReference->ObjectDescription;  ?>Array();
			$strItems = array();
			foreach ($objAssociatedArray as $objAssociated) {
				$strItems[] = $objAssociated->__toString();
			}
			$this-><?php echo $strLabelId  ?>->Text = implode($this->str<?php echo $objManyToManyReference->ObjectDescription;  ?>Glue, $strItems);
			return $this-><?php echo $strLabelId  ?>;
		}