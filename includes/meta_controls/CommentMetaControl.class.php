<?php
	require(__META_CONTROLS_GEN__ . '/CommentMetaControlGen.class.php');

	/**
	 * This is a MetaControl customizable subclass, providing a QForm or QPanel access to event handlers
	 * and QControls to perform the Create, Edit, and Delete functionality of the
	 * Comment class.  This code-generated class extends from
	 * the generated MetaControl class, which contains all the basic elements to help a QPanel or QForm
	 * display an HTML form that can manipulate a single Comment object.
	 *
	 * To take advantage of some (or all) of these control objects, you
	 * must create a new QForm or QPanel which instantiates a CommentMetaControl
	 * class.
	 *
	 * This file is intended to be modified.  Subsequent code regenerations will NOT modify
	 * or overwrite this file.
	 *
	 * @package My QCubed Application
	 * @subpackage MetaControls
	 */
	class CommentMetaControl extends CommentMetaControlGen {
		// Initialize fields with default values from database definition
/*
		public function __construct($objParentObject, Comment $objComment) {
			parent::__construct($objParentObject,$objComment);
			if ( !$this->blnEditMode ){
				$this->objComment->Initialize();
			}
		}
*/
	}
?>