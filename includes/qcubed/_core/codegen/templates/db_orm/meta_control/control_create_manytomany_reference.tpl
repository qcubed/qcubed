		protected $col<%= $objManyToManyReference->ObjectDescription %>Selected;

		/**
		 * Create and setup QDataGrid <%= $strControlId %>
		 * @param string $strControlId optional ControlId to use
		 * @param string $strURL the URL to go to when clicking on the label. Note that the object ID will be appended to this URL.
		 * @return QDataGrid
		 */
		public function <%= $strControlId %>_Create($strControlId = null, $strURL = null) {
			// Setup DataGrid
			$this-><%= $strControlId %> = new QDataGrid($this->objParentObject, $strControlId);
			$this-><%= $strControlId %>->CssClass = 'datagrid';
			$this-><%= $strControlId %>->Owner = $this;

			// Datagrid Paginator
			$this-><%= $strControlId %>->Paginator = new QPaginator($this-><%= $strControlId %>);
			//If desired, use this to set the numbers of items to show per page
			//$this-><%= $strControlId %>->ItemsPerPage = 20;

			// Specify Whether or Not to Refresh using Ajax
			$this-><%= $strControlId %>->UseAjax = true;

			// Specify the local databind method this datagrid will use
			$this-><%= $strControlId %>->SetDataBinder('<%= $strControlId %>_Bind', $this);

			// Setup DataGridColumns
			$this->col<%= $objManyToManyReference->ObjectDescription %>Selected = new QCheckBoxColumn(QApplication::Translate('Select'), $this-><%= $strControlId %>);
			$this->col<%= $objManyToManyReference->ObjectDescription %>Selected->PrimaryKey = '<%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %>';
			$this->col<%= $objManyToManyReference->ObjectDescription %>Selected->SetCheckboxCallback($this, '<%= $strControlId %>Select_Created');
			$this-><%= $strControlId %>->AddColumn($this->col<%= $objManyToManyReference->ObjectDescription %>Selected);

			$colName = new QDataGridColumn(QApplication::Translate('Name'), '<?= $_OWNER-><%= $strControlId %>_Name_Render($_ITEM, "'.$strURL.'"); ?>');
			//, array('OrderByClause' => QQ::OrderBy(<%= $objManyToManyReference->VariableType %>::GetNameOrderByNode()),
			//	'ReverseOrderByClause' => QQ::OrderBy(<%= $objManyToManyReference->VariableType %>::GetNameOrderByNode(), false)));
			//$colName->Filter = QQ::Like(<%= $objManyToManyReference->VariableType %>::GetNameOrderByNode(), null);
			//$colName->FilterPrefix = $colName->FilterPostfix = "%";

			$colName->HtmlEntities = false;
			$this-><%= $strControlId %>->AddColumn($colName);

			$this-><%= $strControlId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural) %>');

			return $this-><%= $strControlId %>;
		}

		public function <%= $strControlId %>Select_Created(<%= $objManyToManyReference->VariableType %> $_ITEM, QCheckBox $ctl) {
			if(null !== $_ITEM->GetVirtualAttribute('assn_item')) {
				$ctl->Checked = true;
			}
		}

		public function <%= $strControlId %>_Name_Render(<%= $objManyToManyReference->VariableType %> $_ITEM, $strURL = '') {
			$strName = QApplication::Translate(QApplication::HtmlEntities($_ITEM->__toString()));

			// Link to the edit page for this object
			if('' != $strURL)
				return sprintf('<a href="%s%s">%s</a>',
					$strURL,
					$_ITEM-><%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %>,
					$strName);
			return $strName;
		}

		protected function Get<%= $strControlId %>Conditions() {
			// Override this function if there are additional limitations on this list.
			return null;
		}

		public function <%= $strControlId %>_Bind() {
			// Get Total Count b/c of Pagination
			$conditions = $this-><%= $strControlId %>->Conditions;

			$moreConditions = $this->Get<%= $strControlId %>Conditions();
			if($moreConditions !== null) {
				$conditions = QQ::AndCondition($conditions, $moreConditions);
			}

			$this-><%= $strControlId %>->TotalItemCount = <%= $objManyToManyReference->VariableType %>::QueryCount($conditions);

			$objClauses = array();
			if ($objClause = $this-><%= $strControlId %>->OrderByClause) {
				array_push($objClauses, $objClause);
			}
			if ($objClause = $this-><%= $strControlId %>->LimitClause) {
				array_push($objClauses, $objClause);
			}

			$objDatabase = <%= $objTable->ClassName %>::GetDatabase();
			$objClauses[] = QQ::Expand(
				QQ::Virtual('assn_item',
					QQ::SubSql("select <%= $strEscapeIdentifierBegin %><%= $objManyToManyReference->Column %><%= $strEscapeIdentifierEnd %>
					 from <%= $strEscapeIdentifierBegin %><%= $objManyToManyReference->Table %><%= $strEscapeIdentifierEnd %>
					 where
					<%= $strEscapeIdentifierBegin %><%= $objManyToManyReference->OppositeColumn %><%= $strEscapeIdentifierEnd %> = {1}
					and <%= $strEscapeIdentifierBegin %><%= $objManyToManyReference->Column %><%= $strEscapeIdentifierEnd %> = ".
					$objDatabase->SqlVariable($this-><%= $strObjectName %>-><%= $objTable->PrimaryKeyColumnArray[0]->PropertyName %>),
						QQN::<%= $objManyToManyReference->VariableType %>()-><%= $objCodeGen->GetTable($objManyToManyReference->AssociatedTable)->PrimaryKeyColumnArray[0]->PropertyName %>)
						)
					);

			$this-><%= $strControlId %>->DataSource = <%= $objManyToManyReference->VariableType %>::QueryArray($conditions, $objClauses);
		}

		/**
		 * Create and setup QLabel <%= $strLabelId %>
		 * @param string $strControlId optional ControlId to use
		 * @param string $strGlue glue to display in-between each associated object
		 * @return QLabel
		 */
		public function <%= $strLabelId %>_Create($strControlId = null, $strGlue = ', ') {
			$this-><%= $strLabelId %> = new QLabel($this->objParentObject, $strControlId);
			$this-><%= $strLabelId %>->Name = QApplication::Translate('<%= QConvertNotation::WordsFromCamelCase($objManyToManyReference->ObjectDescriptionPlural) %>');
			$this->str<%= $objManyToManyReference->ObjectDescription; %>Glue = $strGlue;

			$objAssociatedArray = $this-><%= $strObjectName %>->Get<%= $objManyToManyReference->ObjectDescription; %>Array();
			$strItems = array();
			foreach ($objAssociatedArray as $objAssociated) {
				$strItems[] = $objAssociated->__toString();
			}
			$this-><%= $strLabelId %>->Text = implode($this->str<%= $objManyToManyReference->ObjectDescription; %>Glue, $strItems);
			return $this-><%= $strLabelId %>;
		}
