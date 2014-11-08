/**
		 * Used internally by the Meta-based Add Column tools.
		 *
		 * Given a QQNode or a Text String, this will return a <?= $objTable->ClassName ?>-based QQNode.
		 * It will also verify that it is a proper <?= $objTable->ClassName ?>-based QQNode, and will throw an exception otherwise.
		 *
		 * @param mixed $mixContent
		 * @return QQNode
		 */
		protected function ResolveContentItem($mixContent) {
			if ($mixContent instanceof QQNode) {
				if (!$mixContent->_ParentNode)
					throw new QCallerException('Content QQNode cannot be a Top Level Node');
				if ($mixContent->_RootTableName == '<?= $objTable->Name ?>') {
					if (($mixContent instanceof QQReverseReferenceNode) && !($mixContent->_PropertyName))
						throw new QCallerException('Content QQNode cannot go through any "To Many" association nodes.');
					$objCurrentNode = $mixContent;
					while ($objCurrentNode = $objCurrentNode->_ParentNode) {
						if (!($objCurrentNode instanceof QQNode))
							throw new QCallerException('Content QQNode cannot go through any "To Many" association nodes.');
						if (($objCurrentNode instanceof QQReverseReferenceNode) && !($objCurrentNode->_PropertyName))
							throw new QCallerException('Content QQNode cannot go through any "To Many" association nodes.');
					}
					return $mixContent;
				} else
					throw new QCallerException('Content QQNode has a root table of "' . $mixContent->_RootTableName . '". Must be a root of "<?= $objTable->Name ?>".');
			} else if (is_string($mixContent)) switch ($mixContent) {
<?php foreach ($objTable->ColumnArray as $objColumn) { ?><?php 
	$strClassName = $objTable->ClassName;
	$strPropertyName = $objColumn->PropertyName;
?><?php include("resolve_content_item_case.tpl.php"); ?><?php if ($objColumn->Reference && !$objColumn->Reference->IsType) { ?><?php 
	$strClassName = $objTable->ClassName;
	$strPropertyName = $objColumn->Reference->PropertyName;
?><?php include("resolve_content_item_case.tpl.php"); ?><?php } ?><?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReverseReference) { ?><?php if ($objReverseReference->Unique) { ?><?php 
	$strClassName = $objTable->ClassName;
	$strPropertyName = $objReverseReference->ObjectDescription;
?><?php include("resolve_content_item_case.tpl.php"); ?>
<?php } ?><?php } ?>
				default: throw new QCallerException('Simple Property not found in <?= $objTable->ClassName ?>DataGrid content: ' . $mixContent);
			} else if ($mixContent instanceof QQAssociationNode)
				throw new QCallerException('Content QQNode cannot go through any "To Many" association nodes.');
			else
				throw new QCallerException('Invalid Content type');
		}