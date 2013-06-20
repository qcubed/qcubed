// Override or Create New Load/Count methods
		// (For obvious reasons, these methods are commented out...
		// but feel free to use these as a starting point)
/*
		public static function LoadArrayBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return an array of <?php echo $objTable->ClassName  ?> objects
			return <?php echo $objTable->ClassName  ?>::QueryArray(
				QQ::AndCondition(
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()->Param1, $strParam1),
					QQ::GreaterThan(QQN::<?php echo $objTable->ClassName  ?>()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function LoadBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return a single <?php echo $objTable->ClassName  ?> object
			return <?php echo $objTable->ClassName  ?>::QuerySingle(
				QQ::AndCondition(
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()->Param1, $strParam1),
					QQ::GreaterThan(QQN::<?php echo $objTable->ClassName  ?>()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function CountBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return a count of <?php echo $objTable->ClassName  ?> objects
			return <?php echo $objTable->ClassName  ?>::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()->Param1, $strParam1),
					QQ::Equal(QQN::<?php echo $objTable->ClassName  ?>()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function LoadArrayBySample($strParam1, $intParam2, $objOptionalClauses) {
			// Performing the load manually (instead of using QCubed Query)

			// Get the Database Object for this Class
			$objDatabase = <?php echo $objTable->ClassName  ?>::GetDatabase();

			// Properly Escape All Input Parameters using Database->SqlVariable()
			$strParam1 = $objDatabase->SqlVariable($strParam1);
			$intParam2 = $objDatabase->SqlVariable($intParam2);

			// Setup the SQL Query
			$strQuery = sprintf('
				SELECT
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?>.*
				FROM
					<?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?> AS <?php echo $strEscapeIdentifierBegin  ?><?php echo $objTable->Name  ?><?php echo $strEscapeIdentifierEnd  ?>

				WHERE
					param_1 = %s AND
					param_2 < %s',
				$strParam1, $intParam2);

			// Perform the Query and Instantiate the Result
			$objDbResult = $objDatabase->Query($strQuery);
			return <?php echo $objTable->ClassName  ?>::InstantiateDbResult($objDbResult);
		}
*/
