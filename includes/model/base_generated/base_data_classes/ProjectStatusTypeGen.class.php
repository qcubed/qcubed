<?php
	/**
	 * The ProjectStatusType class defined here contains
	 * code for the ProjectStatusType enumerated type.  It represents
	 * the enumerated values found in the "project_status_type" table
	 * in the database.
	 * 
	 * To use, you should use the ProjectStatusType subclass which
	 * extends this ProjectStatusTypeGen class.
	 * 
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the ProjectStatusType class.
	 * 
	 * @package My Application
	 * @subpackage GeneratedDataObjects
	 */
	abstract class ProjectStatusTypeGen extends QBaseClass {
		const Open = 1;
		const Cancelled = 2;
		const Completed = 3;

		const MaxId = 3;

		public static $NameArray = array(
			1 => 'Open',
			2 => 'Cancelled',
			3 => 'Completed');

		public static $TokenArray = array(
			1 => 'Open',
			2 => 'Cancelled',
			3 => 'Completed');

		public static $ExtraColumnNamesArray = array(
			'Description',
			'Guidelines');

		public static $ExtraColumnValuesArray = array(
			1 => array (
						'Description' => 'The project is currently active',
						'Guidelines' => 'All projects that we are working on should be in this state'),
			2 => array (
						'Description' => 'The project has been canned',
						'Guidelines' => ''),
			3 => array (
						'Description' => 'The project has been completed successfully',
						'Guidelines' => 'Celebrate successes!'));


		public static function ToString($intProjectStatusTypeId) {
			switch ($intProjectStatusTypeId) {
				case 1: return 'Open';
				case 2: return 'Cancelled';
				case 3: return 'Completed';
				default:
					throw new QCallerException(sprintf('Invalid intProjectStatusTypeId: %s', $intProjectStatusTypeId));
			}
		}

		public static function ToToken($intProjectStatusTypeId) {
			switch ($intProjectStatusTypeId) {
				case 1: return 'Open';
				case 2: return 'Cancelled';
				case 3: return 'Completed';
				default:
					throw new QCallerException(sprintf('Invalid intProjectStatusTypeId: %s', $intProjectStatusTypeId));
			}
		}

		public static function ToDescription($intProjectStatusTypeId) {
			if (array_key_exists($intProjectStatusTypeId, ProjectStatusType::$ExtraColumnValuesArray))
				return ProjectStatusType::$ExtraColumnValuesArray[$intProjectStatusTypeId]['Description'];
			else
				throw new QCallerException(sprintf('Invalid intProjectStatusTypeId: %s', $intProjectStatusTypeId));
		}

		public static function ToGuidelines($intProjectStatusTypeId) {
			if (array_key_exists($intProjectStatusTypeId, ProjectStatusType::$ExtraColumnValuesArray))
				return ProjectStatusType::$ExtraColumnValuesArray[$intProjectStatusTypeId]['Guidelines'];
			else
				throw new QCallerException(sprintf('Invalid intProjectStatusTypeId: %s', $intProjectStatusTypeId));
		}

	}
?>