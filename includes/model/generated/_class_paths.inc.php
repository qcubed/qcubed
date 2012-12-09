<?php
	// ClassPaths for the Comment class
		QApplicationBase::$ClassFile['comment'] = __MODEL__ . '/Comment.class.php';
		QApplicationBase::$ClassFile['qqnodecomment'] = __MODEL__ . '/Comment.class.php';
		QApplicationBase::$ClassFile['qqreversereferencenodecomment'] = __MODEL__ . '/Comment.class.php';
		QApplicationBase::$ClassFile['commentmetacontrol'] = __META_CONTROLS__ . '/CommentMetaControl.class.php';
		QApplicationBase::$ClassFile['commentdatagrid'] = __META_CONTROLS__ . '/CommentDataGrid.class.php';

	// ClassPaths for the Post class
		QApplicationBase::$ClassFile['post'] = __MODEL__ . '/Post.class.php';
		QApplicationBase::$ClassFile['qqnodepost'] = __MODEL__ . '/Post.class.php';
		QApplicationBase::$ClassFile['qqreversereferencenodepost'] = __MODEL__ . '/Post.class.php';
		QApplicationBase::$ClassFile['postmetacontrol'] = __META_CONTROLS__ . '/PostMetaControl.class.php';
		QApplicationBase::$ClassFile['postdatagrid'] = __META_CONTROLS__ . '/PostDataGrid.class.php';

?>