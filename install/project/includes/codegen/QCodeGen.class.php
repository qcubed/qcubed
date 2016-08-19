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
		public function __construct($objSettingsXml) {
			// Specify the paths to your template files here. These paths will be searched in the order declared, to
			// find a particular template file. Template files found lower down in the order will override the previous ones.
			static::$TemplatePaths = array (
				__QCUBED_CORE__ . '/codegen/templates/',
				__QCUBED__ . '/codegen/templates/'
			);
		}
				
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