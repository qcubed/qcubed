<?php
	/**
	 * QWatcher is a controller for allowing controls to watch a database table to detect changes
	 * and automatically update when changes are detected.
	 *
	 * To select the type of cache used, change the class that QWatcher extends from.
	 * QWatcherDB is based on a standard SQL database. See QWatcherDB.class.php for details on setup.
	 * QWatcherAPC is uses the php APC or APCu cache, which requires a PECL installation.
	 *
	 * Once you configure the QWatcher subclass, to use the QWatcher system, do the following:
	 * 1) At the top of each model file that you want to watch, (project/includes/model/*) set $blnWatchChanges to true.
	 * 2) During the creation of a control that needs to watch a database table, call $ctl->Watch($dbNode), where
	 *    $dbNode is the node that represents the table you want to watch. For example, to have a datagrid watch the
	 *    people table, call:
	 *   $dtg->Watch (QQN::People());
	 *
	 * That's it. From then on, when the system detects a change in the watched tables, the watching controls will automatically
	 * redraw. Even controls in other windows will automatically redraw.
	 *
	 * Note that a control can watch multiple tables by calling Watch multiple times, and if given a node chain
	 * (like QQN::Project()->ProjectAsManager->etc.), it will watch all the tables in the chain.
	 *
	 * Detection currently happens on any ajax call. You can use QJsTimer to force periodic ajax calls if needed, or
	 * just let the user's activity generate periodic ajax calls. A more advanced system would be to use a WebSocket server,
	 * Socket.IO or something similar, but these things require server configurations that are currently beyond the scope
	 * of QCubed.
	 */

	//include (__QCUBED_CORE__ . '/watchers/QWatcherAPC.class.php');
	include (__QCUBED_CORE__ . '/watchers/QWatcherDB.class.php');

	//class QWatcher extends QWatcherAPC {
	class QWatcher extends QWatcherDB {
	}
?>
	