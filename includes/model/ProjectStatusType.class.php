<?php
	require(__MODEL_GEN__ . '/ProjectStatusTypeGen.class.php');

	/**
	 * The ProjectStatusType class defined here contains any
	 * customized code for the ProjectStatusType enumerated type.
	 *
	 * It represents the enumerated values found in the "project_status_type" table in the database,
	 * and extends from the code generated abstract ProjectStatusTypeGen
	 * class, which contains all the values extracted from the database.
	 *
	 * Type classes which are generally used to attach a type to data object.
	 * However, they may be used as simple database indepedant enumerated type.
	 *
	 * @package My QCubed Application
	 * @subpackage DataObjects
	 */
	abstract class ProjectStatusType extends ProjectStatusTypeGen {
	}
?>