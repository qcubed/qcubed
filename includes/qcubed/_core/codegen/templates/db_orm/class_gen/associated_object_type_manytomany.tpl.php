<?php
	// associated_object_type_manytomany.tpl
	$objManyToManyReferenceTable = $objCodeGen->TypeTableArray[strtolower($objManyToManyReference->AssociatedTable)];
?>   
        // Related Many-to-Many Object Methods for <?php echo $objManyToManyReference->ObjectDescription; ?>
        
        //-------------------------------------------------------------------
  
        /**
         * Gets all many-to-many associated <?php echo $objManyToManyReference->ObjectDescriptionPlural; ?> as an array of id=>name pairs.
         * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
         * @return array
         */ 
        public function Get<?php echo $objManyToManyReference->ObjectDescription; ?>Array($objOptionalClauses = null) {        
            if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                return array();        
                
            if($objOptionalClauses) 
                throw new QException('Unable to call Get<?php echo $objManyToManyReference->ObjectDescription; ?>Array with parameters.'); 
                
            $rowArray = array();
                
            // Get the Database Object for this Class
            $objDatabase = <?php echo $objTable->ClassName; ?>::GetDatabase();
                
            $strQuery = sprintf("SELECT <?php echo $objManyToManyReference->OppositeColumn; ?> FROM <?php echo $objManyToManyReference->Table; ?> WHERE <?php echo $objManyToManyReference->Column; ?> = %s", $this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>);            
            
            // Perform the Query
            $objDbResult = $objDatabase->Query($strQuery);
            
            while ($mixRow = $objDbResult->FetchArray()) {                                   
                $rowArray[$mixRow['<?php echo $objManyToManyReference->OppositeColumn; ?>']] =   <?php echo $objManyToManyReference->VariableType; ?>::ToString($mixRow['<?php echo $objManyToManyReference->OppositeColumn; ?>']);
            }

            try {
                //return <?php echo $objManyToManyReference->VariableType; ?>::LoadArrayBy<?php echo $objManyToManyReference->OppositeObjectDescription; ?>($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>, $objOptionalClauses);
                return $rowArray;
            } catch (QCallerException $objExc) {
                $objExc->IncrementOffset();
                throw $objExc;
            }                
        }        
        
        /**
         * Counts all many-to-many associated <?php echo $objManyToManyReference->ObjectDescriptionPlural; ?>
         
         * @return int
         */ 
        public function Count<?php echo $objManyToManyReference->ObjectDescriptionPlural; ?>() {
            if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                return 0;                 

            // Get the Database Object for this Class
            $objDatabase = <?php echo $objTable->ClassName; ?>::GetDatabase();
            
            $strQuery = sprintf("SELECT count(*) as total_count FROM <?php echo $objManyToManyReference->Table; ?> WHERE <?php echo $objManyToManyReference->Column; ?> = %s", $this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>);            
            
            // Perform the Query
            $objDbResult = $objDatabase->Query($strQuery);                            
            $row = $objDbResult->FetchArray();
            return $row['total_count'];
        }        
        
        /**
         * Checks to see if an association exists with a specific <?php echo $objManyToManyReference->ObjectDescription; ?>
         
         * @param <?php echo $objManyToManyReference->VariableType; ?> $<?php echo $objManyToManyReference->VariableName; ?>
         
         * @return bool
         */
        public function Is<?php echo $objManyToManyReference->ObjectDescription; ?>Associated($intId) {
            if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                throw new QUndefinedPrimaryKeyException('Unable to call Is<?php echo $objManyToManyReference->ObjectDescription; ?>Associated on this unsaved <?php echo $objTable->ClassName; ?>.');
                        

            $intRowCount = <?php echo $objTable->ClassName; ?>::QueryCount(
                QQ::AndCondition(
                    QQ::Equal(QQN::<?php echo $objTable->ClassName; ?>()-><?php echo $objTable->PrimaryKeyColumnArray[0]->PropertyName; ?>, $this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>),
                    QQ::Equal(QQN::<?php echo $objTable->ClassName; ?>()-><?php echo $objManyToManyReference->ObjectDescription; ?>-><?php echo $objManyToManyReference->OppositePropertyName; ?>, $intId )
                )
            );

            return ($intRowCount > 0);        
        }    
        
        /**
         * Associates a <?php echo $objManyToManyReference->ObjectDescription; ?>
         
         * @param mixed $mixId	id or array of ids.
         * @return void
         */ 
        public function Associate<?php echo $objManyToManyReference->ObjectDescription; ?>($mixId) {
        
            if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                throw new QUndefinedPrimaryKeyException('Unable to call Associate<?php echo $objManyToManyReference->ObjectDescription; ?> on this unsaved <?php echo $objTable->ClassName; ?>.');
            

            // Get the Database Object for this Class
            $objDatabase = <?php echo $objTable->ClassName; ?>::GetDatabase();

			if(!is_array($mixId)) {
				$mixId = array($mixId);
			}
			foreach ($mixId as $intId) {
	            // Perform the SQL Query
	            $objDatabase->NonQuery('
	                INSERT INTO <?php echo $strEscapeIdentifierBegin; ?><?php echo $objManyToManyReference->Table; ?><?php echo $strEscapeIdentifierEnd; ?> (
	                    <?php echo $strEscapeIdentifierBegin; ?><?php echo $objManyToManyReference->Column; ?><?php echo $strEscapeIdentifierEnd; ?>,
	                    <?php echo $strEscapeIdentifierBegin; ?><?php echo $objManyToManyReference->OppositeColumn; ?><?php echo $strEscapeIdentifierEnd; ?>
	                ) VALUES (
	                    ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>) . ',
	                    ' . $objDatabase->SqlVariable($intId) . '
	                )
	            ');
			}
        }
        
        /**
         * Unassociates a <?php echo $objManyToManyReference->ObjectDescription; ?>
         
         * @param mixed $mixId	id or array of ids
         * @return void
         */ 
        public function Unassociate<?php echo $objManyToManyReference->ObjectDescription; ?>($mixId) {
            if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                throw new QUndefinedPrimaryKeyException('Unable to call Unassociate<?php echo $objManyToManyReference->ObjectDescription; ?> on this unsaved <?php echo $objTable->ClassName; ?>.');

            // Get the Database Object for this Class
            $objDatabase = <?php echo $objTable->ClassName; ?>::GetDatabase();

 			if(!is_array($mixId)) {
				$mixId = array($mixId);
			}
			foreach ($mixId as $intId) {
	            // Perform the SQL Query
	            $objDatabase->NonQuery('
					DELETE FROM
						<?php echo $strEscapeIdentifierBegin; ?><?php echo $objManyToManyReference->Table; ?><?php echo $strEscapeIdentifierEnd; ?>
	                WHERE
	                    <?php echo $strEscapeIdentifierBegin; ?><?php echo $objManyToManyReference->Column; ?><?php echo $strEscapeIdentifierEnd; ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>) . ' AND
	                    <?php echo $strEscapeIdentifierBegin; ?><?php echo $objManyToManyReference->OppositeColumn; ?><?php echo $strEscapeIdentifierEnd; ?> = ' . $objDatabase->SqlVariable($intId) . '
	            ');
			}
        }        
        
        /**
         * Unassociates all <?php echo $objManyToManyReference->ObjectDescriptionPlural; ?>
         
         * @return void
         */ 
        public function UnassociateAll<?php echo $objManyToManyReference->ObjectDescriptionPlural; ?>() {
            if (<?php echo $objCodeGen->ImplodeObjectArray(' || ', '(is_null($this->', '))', 'VariableName', $objTable->PrimaryKeyColumnArray); ?>)
                throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAll<?php echo $objManyToManyReference->ObjectDescription; ?>Array on this unsaved <?php echo $objTable->ClassName; ?>.');

            // Get the Database Object for this Class
            $objDatabase = <?php echo $objTable->ClassName; ?>::GetDatabase();

            // Perform the SQL Query
            $objDatabase->NonQuery('
                DELETE FROM
                    <?php echo $strEscapeIdentifierBegin; ?><?php echo $objManyToManyReference->Table; ?><?php echo $strEscapeIdentifierEnd; ?>
                WHERE
                    <?php echo $strEscapeIdentifierBegin; ?><?php echo $objManyToManyReference->Column; ?><?php echo $strEscapeIdentifierEnd; ?> = ' . $objDatabase->SqlVariable($this-><?php echo $objTable->PrimaryKeyColumnArray[0]->VariableName; ?>) . '
            ');
        }        

