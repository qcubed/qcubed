<?php
	// associated_object_type_manytomany.tpl
	$objManyToManyReferenceTable = $objCodeGen->TypeTableArray[strtolower($objManyToManyReference->AssociatedTable)];
?>   
        // Related Many-to-Many Object Methods for <?= $objManyToManyReference->ObjectDescription; ?>
        
        //-------------------------------------------------------------------
  
        /**
         * Gets all many-to-many associated <?= $objManyToManyReference->ObjectDescriptionPlural; ?> as an array of id=>name pairs.
         * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
         * @return array
         */ 
        public function Get<?= $objManyToManyReference->ObjectDescription; ?>Array($objOptionalClauses = null) {
            if (<?= $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                return array();        
                
            if($objOptionalClauses) 
                throw new QException('Unable to call Get<?= $objManyToManyReference->ObjectDescription; ?>Array with parameters.');
                
            $rowArray = array();
                
            // Get the Database Object for this Class
            $objDatabase = <?= $objTable->ClassName; ?>::GetDatabase();
                
            $strQuery = sprintf("SELECT <?= $objManyToManyReference->OppositeColumn; ?> FROM <?= $objManyToManyReference->Table; ?> WHERE <?= $objManyToManyReference->Column; ?> = %s", $this-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>);
            
            // Perform the Query
            $objDbResult = $objDatabase->Query($strQuery);
            
            while ($mixRow = $objDbResult->FetchArray()) {                                   
                $rowArray[$mixRow['<?= $objManyToManyReference->OppositeColumn; ?>']] =   <?= $objManyToManyReference->VariableType; ?>::ToString($mixRow['<?= $objManyToManyReference->OppositeColumn; ?>']);
            }

            return $rowArray;
        }
        
        /**
         * Counts all many-to-many associated <?= $objManyToManyReference->ObjectDescriptionPlural; ?>
         
         * @return int
         */ 
        public function Count<?= $objManyToManyReference->ObjectDescriptionPlural; ?>() {
            if (<?= $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                return 0;                 

            // Get the Database Object for this Class
            $objDatabase = <?= $objTable->ClassName; ?>::GetDatabase();
            
            $strQuery = sprintf("SELECT count(*) as total_count FROM <?= $objManyToManyReference->Table; ?> WHERE <?= $objManyToManyReference->Column; ?> = %s", $this-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>);
            
            // Perform the Query
            $objDbResult = $objDatabase->Query($strQuery);                            
            $row = $objDbResult->FetchArray();
            return $row['total_count'];
        }        
        
        /**
         * Checks to see if an association exists with a specific <?= $objManyToManyReference->ObjectDescription; ?>
         
         * @param <?= $objManyToManyReference->VariableType; ?> $<?= $objManyToManyReference->VariableName; ?>
         
         * @return bool
         */
        public function Is<?= $objManyToManyReference->ObjectDescription; ?>Associated($intId) {
            if (<?= $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                throw new QUndefinedPrimaryKeyException('Unable to call Is<?= $objManyToManyReference->ObjectDescription; ?>Associated on this unsaved <?= $objTable->ClassName; ?>.');
                        

            $intRowCount = <?= $objTable->ClassName; ?>::QueryCount(
                QQ::AndCondition(
                    QQ::Equal(QQN::<?= $objTable->ClassName; ?>()-><?= $objTable->PrimaryKeyColumnArray[0]->PropertyName; ?>, $this-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>),
                    QQ::Equal(QQN::<?= $objTable->ClassName; ?>()-><?= $objManyToManyReference->ObjectDescription; ?>-><?= $objManyToManyReference->OppositePropertyName; ?>, $intId )
                )
            );

            return ($intRowCount > 0);        
        }    
        
        /**
         * Associates a <?= $objManyToManyReference->ObjectDescription; ?>
         
         * @param mixed $mixId	id or array of ids.
         * @return void
         */ 
        public function Associate<?= $objManyToManyReference->ObjectDescription; ?>($mixId) {
        
            if (<?= $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                throw new QUndefinedPrimaryKeyException('Unable to call Associate<?= $objManyToManyReference->ObjectDescription; ?> on this unsaved <?= $objTable->ClassName; ?>.');
            

            // Get the Database Object for this Class
            $objDatabase = <?= $objTable->ClassName; ?>::GetDatabase();

			if(!is_array($mixId)) {
				$mixId = array($mixId);
			}
			foreach ($mixId as $intId) {
	            // Perform the SQL Query
	            $objDatabase->NonQuery('
	                INSERT INTO <?= $strEscapeIdentifierBegin; ?><?= $objManyToManyReference->Table; ?><?= $strEscapeIdentifierEnd; ?> (
	                    <?= $strEscapeIdentifierBegin; ?><?= $objManyToManyReference->Column; ?><?= $strEscapeIdentifierEnd; ?>,
	                    <?= $strEscapeIdentifierBegin; ?><?= $objManyToManyReference->OppositeColumn; ?><?= $strEscapeIdentifierEnd; ?>
	                ) VALUES (
	                    ' . $objDatabase->SqlVariable($this-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>) . ',
	                    ' . $objDatabase->SqlVariable($intId) . '
	                )
	            ');
			}
        }
        
        /**
         * Unassociates a <?= $objManyToManyReference->ObjectDescription; ?>
         
         * @param mixed $mixId	id or array of ids
         * @return void
         */ 
        public function Unassociate<?= $objManyToManyReference->ObjectDescription; ?>($mixId) {
            if (<?= $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?= $objManyToManyReference->ObjectDescription; ?> on this unsaved <?= $objTable->ClassName; ?>.');

            // Get the Database Object for this Class
            $objDatabase = <?= $objTable->ClassName; ?>::GetDatabase();

 			if(!is_array($mixId)) {
				$mixId = array($mixId);
			}
			foreach ($mixId as $intId) {
	            // Perform the SQL Query
	            $objDatabase->NonQuery('
					DELETE FROM
						<?= $strEscapeIdentifierBegin; ?><?= $objManyToManyReference->Table; ?><?= $strEscapeIdentifierEnd; ?>
	                WHERE
	                    <?= $strEscapeIdentifierBegin; ?><?= $objManyToManyReference->Column; ?><?= $strEscapeIdentifierEnd; ?> = ' . $objDatabase->SqlVariable($this-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>) . ' AND
	                    <?= $strEscapeIdentifierBegin; ?><?= $objManyToManyReference->OppositeColumn; ?><?= $strEscapeIdentifierEnd; ?> = ' . $objDatabase->SqlVariable($intId) . '
	            ');
			}
        }        
        
        /**
         * Unassociates all <?= $objManyToManyReference->ObjectDescriptionPlural; ?>
         
         * @return void
         */ 
        public function UnassociateAll<?= $objManyToManyReference->ObjectDescriptionPlural; ?>() {
            if (<?= $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAll<?= $objManyToManyReference->ObjectDescription; ?>Array on this unsaved <?= $objTable->ClassName; ?>.');

            // Get the Database Object for this Class
            $objDatabase = <?= $objTable->ClassName; ?>::GetDatabase();

            // Perform the SQL Query
            $objDatabase->NonQuery('
                DELETE FROM
                    <?= $strEscapeIdentifierBegin; ?><?= $objManyToManyReference->Table; ?><?= $strEscapeIdentifierEnd; ?>
                WHERE
                    <?= $strEscapeIdentifierBegin; ?><?= $objManyToManyReference->Column; ?><?= $strEscapeIdentifierEnd; ?> = ' . $objDatabase->SqlVariable($this-><?= $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>) . '
            ');
        }        

