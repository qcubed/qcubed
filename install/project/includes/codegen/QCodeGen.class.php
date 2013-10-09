<?php
    /**
	 * QCodeGen
	 * 
     * Feel free to override any core QCodeGenBase methods here
	 * 
     * @package Codegen   
	 * @author Qcubed
	 * @copyright 
	 * @version 2011
	 * @access public
	 */
     
    require(__QCUBED_CORE__ . '/codegen/QCodeGenBase.class.php');     
	
    /**
	 * QCodeGen
	 * 
     * Feel free to override any core QCodeGenBase methods here
	 * 
     * @package Codegen   
	 * @author Qcubed
	 * @copyright 
	 * @version 2011
	 * @access public
	 */
	class QCodeGen extends QCodeGenBase {
		
		/**
		 * Construct the QCodeGen object.
		 * 
		 * Gives you an opportunity to read your xml file and make codegen changes accordingly.
		 */
		public function __construct($objSettingsXml) {}
				
		/**
		 * QCodeGen::Pluralize()
         * 
         * Example: Overriding the Pluralize method
		 * 
		 * @param string $strName
		 * @return string
		 */
		protected function Pluralize($strName) {
			// Special Rules go Here
			switch (true) {
				case ($strName == 'person'):
					return 'people';
				case ($strName == 'Person'):
					return 'People';
				case ($strName == 'PERSON'):
					return 'PEOPLE';

				// Trying to be cute here...
				case (strtolower($strName) == 'fish'):
					return $strName . 'ies';

				// Otherwise, call parent
				default:
					return parent::Pluralize($strName);
			}
		}
	}

	require(__QCUBED_CORE__ . '/codegen/library.inc.php');
?>