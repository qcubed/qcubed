<?php
	/**
	 * QWatcher is a controller for allowing controls to watch a database table to detect changes
	 * and automatically update when changes are detected.
	 *
	 * To select the type of cache used, change the class that QWatcher extends from.
	 * QWatcherDB is based on a standard SQL database.
	 * QWatcherAPC is based on the php APC cache, which requires a PECL installation.
	 */

	//include (__QCUBED_CORE__ . '/watchers/QWatcherAPC.class.php');
	include (__QCUBED_CORE__ . '/watchers/QWatcherDB.class.php');

	//class QWatcher extends QWatcherAPC {
	class QWatcher extends QWatcherDB {
	}
?>
	