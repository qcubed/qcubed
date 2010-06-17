		protected function <%= $objCodeGen->FormControlVariableNameForManyToManyReference($objManyToManyReference); %>_Update() {
			if ($this-><%= $strControlId %>) {
				$changedIds = $this->col<%= $objManyToManyReference->ObjectDescription %>Selected->GetChangedIds();
				$temp = <%= $objManyToManyReference->VariableType %>::QueryArray(QQ::In(QQN::<%= $objManyToManyReference->VariableType %>()->Id, array_keys($changedIds)));
				$changedItems = array();
				foreach($temp as $item) {
					$changedItems[$item->Id] = $item;
				}
				
				foreach($changedIds as $id=>$blnSelected) {
					$item = $changedItems[$id];
					if($blnSelected) {
						// Associate this <%= $objManyToManyReference->VariableType %>
						$this-><%= $strObjectName %>->Associate<%= $objManyToManyReference->ObjectDescription %>($item);
					} else {
						// Unassociate this <%= $objManyToManyReference->VariableType %>
						$results = $this-><%= $strObjectName %>->Unassociate<%= $objManyToManyReference->ObjectDescription %>($item);
					}
				}
			}
		}
