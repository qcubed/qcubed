/**
		 * Will add an "edit" link-based column, using a standard HREF link to redirect the user to a page
		 * that must be specified.
		 *
		 * @param string $strLinkUrl the URL to redirect the user to
		 * @param string $strLinkHtml the HTML of the link text
		 * @param string $strColumnTitle the HTML of the link text
		 * @param string $intArgumentType the method used to pass information to the edit page (defaults to PathInfo)
		 */
		public function AddEditLinkColumn($strLinkUrl, $strLinkHtml = 'Edit', $strColumnTitle = 'Edit', $intArgumentType = QModelConnectorArgumentType::PathInfo) {
			switch ($intArgumentType) {
				case QModelConnectorArgumentType::QueryString:
					$strLinkUrl .= (strpos($strLinkUrl, '?') !== false ? '&' : '?').'<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {?><?= $objColumn->VariableName ?>=<?php print("<?="); ?>urlencode($_ITEM-><?= $objColumn->PropertyName ?>)?>&<?php }?><?php GO_BACK(1); ?>';
					break;
				case QModelConnectorArgumentType::PathInfo:
					$strLinkUrl .= '<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {?>/<?php print("<?="); ?>urlencode($_ITEM-><?= $objColumn->PropertyName ?>)?><?php }?>';
					break;
				default:
					throw new QCallerException('Unable to pass arguments with this intArgumentType: ' . $intArgumentType);
			}

			$strHtml = '<a href="' . $strLinkUrl . '">' . $strLinkHtml . '</a>';
			$colEditColumn = new QDataGridColumn($strColumnTitle, $strHtml, 'HtmlEntities=False');
			$this->AddColumn($colEditColumn);
			return $colEditColumn;
		}

		/**
		 * Will add an "edit" control proxy-based column, calling any actions on a given control proxy
		 * that must be specified.
		 *
		 * @param QControlProxy $pxyControl the control proxy to use
		 * @param string $strLinkHtml the HTML of the link text
		 * @param string $strColumnTitle the HTML of the link text
		 */
		public function AddEditProxyColumn(QControlProxy $pxyControl, $strLinkHtml = 'Edit', $strColumnTitle = 'Edit') {
			$strHtml = '<a href="#" <?php print("<?="); ?> $_FORM->GetControl("' . $pxyControl->ControlId . '")->RenderAsEvents(<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) {?>$_ITEM-><?= $objColumn->PropertyName ?> . "," . <?php }?><?php GO_BACK(9); ?>, false); ?>>' . $strLinkHtml . '</a>';
			$colEditColumn = new QDataGridColumn($strColumnTitle, $strHtml, 'HtmlEntities=False');
			$this->AddColumn($colEditColumn);
			return $colEditColumn;
		}