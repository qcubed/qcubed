<?php
	/**
	 * QWatcher is a helper class that allows controls and forms to watch database tables
	 * and automatically redraw when changes are detected. It works together with the codegened
	 * model classes, the controls, and the QForm class to draw or refresh when needed.
	 * 
	 * Static functions handle the database updating, while member variables store the current state
	 * of a control's watched tables.
	 *
	 * This Base class is a template on which to build watchers that use specific caching mechanisms.
	 * See QWatcher to select the caching mechanism you would like to use.
	 */
	
	abstract class QWatcherBase extends QBaseClass {

		/**
		 * @var string key representing all tables being watched. This is used to create a key so that we can
		 * know if any table that other forms care about changes, and notify other windows of the change. If you have multiple
		 * installations of QCubed running on the same machine, you should change this in the QWatcher override
		 * and make it different for each application. However, if two QCubed installations are modifying the same tables in
		 * the same databases, they should have the same app key
		 */
		public static $strAppKey = 'QWATCH_APPKEY';

		protected $strWatchedKeys = array();

		/**
		 * Returns a unique key corresponding to the given table in the given database.
		 * Override this function to return a key value that will define a subset of the table to
		 * watch. For example, if you have records associated with User Ids,
		 * combine the user id with the table name, and then
		 * only records associated with that user id will be watched.
		 *
		 * Also, override this if you have multiple instances of QCubed running on the same PHP process, with possibly the same
		 * table names.
		 *
		 * @return string
		 */
		protected static function GetKey($strDbName, $strTableName) {
			return $strDbName . ':' . $strTableName;
		}
		
		/**
		 * Call from control to watch a node. Watches all tables associated with the node.
		 * 
		 * @param QQTableNode $objNode
		 */
		public function Watch(QQTableNode $objNode) {
			$strClassName = $objNode->_ClassName;

			if (!$strClassName::$blnWatchChanges) {
				throw new QCallerException ($strClassName . ':$blnWatchChanges is false. To be able to watch this table, you should set it to true in your ' . $strClassName . '.class.php file.');
			}

			if ($strClassName) {
				$objDatabase = $strClassName::GetDatabase();
				$this->RegisterTable($objDatabase->Database, $objNode->_TableName);
			}
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
		protected function RegisterTable ($strDbName, $strTableName) {
			$key = static::GetKey($strDbName, $strTableName);
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
		static public function MarkTableModified ($strDbName, $strTableName) {}

		/**
		 * Support function for the Form to determine if any of the watchers have changed since the last time
		 * it drew something in the form.
		 *
		 * @param QWatcher[]|null $objWatchers
		 * @return bool
		 */
		static public function WatchersChanged ($objWatchers) {
			if ($objWatchers) {
				foreach ($objWatchers as $objWatcher) {
					if (!$objWatcher->IsCurrent()) {
						return true;
					}
				}
			}
			return false;
		}
	}
?>