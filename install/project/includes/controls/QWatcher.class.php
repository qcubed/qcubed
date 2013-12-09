<?php
	/**
	 * QWatcher is a controller for allowing controls to watch a database table to detect changes
	 * and automatically update when changes are detected.
	 * 
	 * It relies on the presence of a helper database in the system. 
	 * 
	 * Static functions handle the database updating, while member variables store the current state
	 * of a control's watched tables.
	 */
	
	class QWatcher extends QWatcherBase {
		
		protected static function GetId() {
			return QApplication::CurrentCompanyId();
		}
		
	}
?>
	