/**
		 * This will DELETE this object's <?php echo $objTable->ClassName;  ?> instance from the database.
		 * It will also unassociate itself from any ManyToManyReferences.
		 */
		public function Delete<?php echo $objTable->ClassName;  ?>() {
<?php foreach ($objTable->ManyToManyReferenceArray as $objManyToManyReference) { ?>
			$this-><?php echo $objCodeGen->VariableNameFromTable($objTable->Name)  ?>->UnassociateAll<?php echo $objManyToManyReference->ObjectDescriptionPlural  ?>();
<?php } ?>
			$this-><?php echo $objCodeGen->VariableNameFromTable($objTable->Name);  ?>->Delete();
		}