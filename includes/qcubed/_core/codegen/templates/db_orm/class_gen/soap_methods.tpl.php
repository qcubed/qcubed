////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="<?php echo $objTable->ClassName  ?>"><sequence>';
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (!$objColumn->Reference || $objColumn->Reference->IsType) { ?>
			$strToReturn .= '<element name="<?php echo $objColumn->PropertyName  ?>" type="xsd:<?php echo QType::SoapType($objColumn->VariableType)  ?>"/>';
<?php } ?><?php if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { ?>
			$strToReturn .= '<element name="<?php echo $objColumn->Reference->PropertyName  ?>" type="xsd1:<?php echo $objColumn->Reference->VariableType  ?>"/>';
<?php } ?>
<?php } ?>
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('<?php echo $objTable->ClassName  ?>', $strComplexTypeArray)) {
				$strComplexTypeArray['<?php echo $objTable->ClassName  ?>'] = <?php echo $objTable->ClassName  ?>::GetSoapComplexTypeXml();
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { ?>
				<?php echo $objColumn->Reference->VariableType ?>::AlterSoapComplexTypeArray($strComplexTypeArray);
<?php } ?>
<?php } ?>
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, <?php echo $objTable->ClassName  ?>::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new <?php echo $objTable->ClassName  ?>();
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if (!$objColumn->Reference || $objColumn->Reference->IsType) { ?>
			if (property_exists($objSoapObject, '<?php echo $objColumn->PropertyName  ?>'))
<?php if ($objColumn->VariableType != QType::DateTime) { ?>
				$objToReturn-><?php echo $objColumn->VariableName  ?> = $objSoapObject-><?php echo $objColumn->PropertyName  ?>;
<?php } ?><?php if ($objColumn->VariableType == QType::DateTime) { ?>
				$objToReturn-><?php echo $objColumn->VariableName  ?> = new QDateTime($objSoapObject-><?php echo $objColumn->PropertyName  ?>);
<?php } ?>
<?php } ?><?php if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { ?>
			if ((property_exists($objSoapObject, '<?php echo $objColumn->Reference->PropertyName  ?>')) &&
				($objSoapObject-><?php echo $objColumn->Reference->PropertyName  ?>))
				$objToReturn-><?php echo $objColumn->Reference->PropertyName  ?> = <?php echo $objColumn->Reference->VariableType  ?>::GetObjectFromSoapObject($objSoapObject-><?php echo $objColumn->Reference->PropertyName  ?>);
<?php } ?>
<?php } ?>
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, <?php echo $objTable->ClassName  ?>::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
<?php if ($objColumn->VariableType == QType::DateTime) { ?>
			if ($objObject-><?php echo $objColumn->VariableName  ?>)
				$objObject-><?php echo $objColumn->VariableName  ?> = $objObject-><?php echo $objColumn->VariableName  ?>->qFormat(QDateTime::FormatSoap);
<?php } ?><?php if ($objColumn->Reference && (!$objColumn->Reference->IsType)) { ?>
			if ($objObject-><?php echo $objColumn->Reference->VariableName  ?>)
				$objObject-><?php echo $objColumn->Reference->VariableName  ?> = <?php echo $objColumn->Reference->VariableType  ?>::GetSoapObjectFromObject($objObject-><?php echo $objColumn->Reference->VariableName  ?>, false);
			else if (!$blnBindRelatedObjects)
				$objObject-><?php echo $objColumn->VariableName  ?> = null;
<?php } ?>
<?php } ?>
			return $objObject;
		}
