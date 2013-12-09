<?php
	/**
	 * QWatcher is a helper class that allows controls to watch database tables
	 * and automatically redraw when changes are detected. It works together with the codegened
	 * model classes, the controls, and the QForm class to draw when needed.
	 * 
	 * Static functions handle the database updating, while member variables store the current state
	 * of a control's watched tables.
	 *
	 * This Base class is a template on which to build watchers that use specific caching mechanisms.
	 * See QWatcher to select the caching mechanism you would like to use.
	 */
	
	abstract class QWatcherBase extends QBaseClass {
		const ALL_WATCHERS = 'QWATCH_ALL';

		protected $strWatchedKeys = array();

		/**
		 * Override this function to return a key value that will define a subset of the table to
		 * watch. For example, if you have records associated with User Ids,
		 * combine the user id with the table name, and then
		 * only records associated with that user id will be watched.
		 *
		 * @return string
		 */
		protected static function GetKey($strTableName) {
			return $strTableName;
		}
		
		/**
		 * Call from control to watch a node. Watches all tables associated with the node.
		 * 
		 * @param QQNode $objNode
		 */
		public function Watch(QQNode $objNode) {
			$this->RegisterTable($objNode->_TableName);
			$objParentNode = $objNode->_ParentNode;
			if ($objParentNode) {
				$this->Watch ($objParentNode);
			}
		}
		
		/**
		 * 
		 * Internal function to watch a single table.
		 * 
		 * @param string $strTableName
		 */
		protected function RegisterTable ($strTableName) {
			$key = static::GetKey($strTableName);
			if (empty($this->strWatchedKeys[$key])) {
				$this->strWatchedKeys[$key] =  true;
			}
		}
		
		/**
		 * Controls should call this function just after rendering. Updates strWatchedTables
		 * to the current state of the database.
		 * 
		 */
		abstract public function MakeCurrent();

		/**
		 * QControlBase uses this from IsModified to detect if it should redraw.
		 * Returns false if the database has been changed since the last draw.
		 * @return bool
		 */
		abstract public function IsCurrent();
		
		/**
		 * Model Save() calls this to indicate that a table has changed.
		 * 
		 * @param string $strTableName
		 * @throws QCallerException
		 */
		static public function MarkTableModified ($strTableName) {}

		/**
		 * QFormBase calls this to tell if any of the watched tables have changed.
		 * Cache adapters should override this.
		 *
		 * @param $strFormWatcherTime
		 * @return bool
		 */
		static public function FormWatcherChanged (&$strFormWatcherTime) { return false;}
	}
?>
	