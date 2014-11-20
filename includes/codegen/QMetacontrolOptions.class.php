<?php
/**
 * Interface to the Metacontrol options that let you specify various options per field to be placed in the codegened
 * MetacontrolGen classes.
 *
 * Note that this ties table and field names in the database to options in the metacontrol. If the table or field name
 * changes in the database, the options will be lost. We can try to guess as to whether changes were made based upon
 * the index of the changes in the field list, but not entirely easy to do. Best would be for developer to hand-code
 * the changes in the json file in this case.
 *
 * This will be used by the designer to record the changes in preparation for codegen.
 */

class QMetacontrolOptions extends QBaseClass {
	protected $options = array();
	protected $blnChanged = false;

	public function __construct() {
		if (file_exists(__CONFIGURATION__ . '/codegen_options.json')) {
			$strContent = file_get_contents(__CONFIGURATION__ . '/codegen_options.json');

			if ($strContent) {
				$this->options = json_decode($strContent, true);
			}
		}

		// TODO: Analyze the result for changes and make a guess as to whether a table name or field name was changed
	}

	/**
	 * Save the current configuration into the options file.
	 */
	function Save() {
		if (!$this->blnChanged) {
			return;
		}
		$flags = 0;
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			$flags = JSON_PRETTY_PRINT;
		}
		$strContent = json_encode ($this->options, $flags);

		file_put_contents(__CONFIGURATION__ . '/codegen_options.json', $strContent);
		$this->blnChanged = false;
	}

	/**
	 * Makes sure save is the final step.
	 */
	function __destruct() {
		$this->Save();
	}


	/**
	 * Set an option for a widget associated with the given table and field.
	 *
	 * @param $strTableName
	 * @param $strFieldName
	 * @param $strOptionName
	 * @param $mixValue
	 */
	public function SetOption ($strTableName, $strFieldName, $strOptionName, $mixValue) {
		$this->options[$strTableName][$strFieldName][$strOptionName] = $mixValue;
		$this->blnChanged = true;
	}

	/**
	 * Bulk option setting.
	 *
	 * @param $strClassName
	 * @param $strFieldName
	 * @param $mixValue
	 */
	public function SetOptions ($strClassName, $strFieldName, $mixValue) {
		if (empty ($mixValue)) {
			unset($this->options[$strClassName][$strFieldName]);
		}
		else {
			$this->options[$strClassName][$strFieldName] = $mixValue;
		}
		$this->blnChanged = true;
	}

	/**
	 * Remove the option
	 *
	 * @param $strClassName
	 * @param $strFieldName
	 * @param $strOptionName
	 */
	public function UnsetOption ($strClassName, $strFieldName, $strOptionName) {
		unset ($this->options[$strClassName][$strFieldName][$strOptionName]);
		$this->blnChanged = true;
	}

	/**
	 * Lookup an option.
	 *
	 * @param $strClassName
	 * @param $strFieldName
	 * @param $strOptionName
	 * @return mixed
	 */
	public function GetOption ($strClassName, $strFieldName, $strOptionName) {
		if (isset ($this->options[$strClassName][$strFieldName][$strOptionName])) {
			return $this->options[$strClassName][$strFieldName][$strOptionName];
		} else {
			return null;
		}
	}

	/**
	 * Return all the options associated with the given table and field.
	 * @param $strClassName
	 * @param $strFieldName
	 * @return mixed
	 */
	public function GetOptions ($strClassName, $strFieldName) {
		if (isset($this->options[$strClassName][$strFieldName])) {
			return $this->options[$strClassName][$strFieldName];
		} else {
			return array();
		}
	}

}
?>