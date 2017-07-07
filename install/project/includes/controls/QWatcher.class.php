<?php
/**
 * QWatcher is a controller for allowing controls to watch a database table to detect changes
 * and automatically update when changes are detected.
 *
 * To select the type of cache used, change the class that QWatcher extends from.
 * QWatcherDB is based on a standard SQL database. See QWatcherDB.class.php for details on setup.
 * QWatcherCache uses one of the QCacheProvider subclasses, which allow you to use Redis, APC or MemCache for example.
 *
 * Once you configure the QWatcher subclass, to use the QWatcher system, do the following:
 * During the creation of a control that needs to watch a database table, call $ctl->Watch($dbNode), where
 *    $dbNode is the node that represents the table you want to watch. For example, to have a datagrid watch the
 *    People table for changes, call:
 *   $dtg->Watch (QQN::People());
 *
 * That's it. From then on, when the system detects a change in the watched tables, the watching controls will automatically
 * redraw. Even controls in other windows or other browsers will automatically redraw.
 *
 * A control can watch multiple tables by calling Watch multiple times, and if given a node chain
 * (like QQN::Project()->ProjectAsManager->etc.), it will watch all the tables in the chain.
 *
 * Detection currently happens on any ajax call. You can use QJsTimer to force periodic ajax calls if needed, or
 * just let the user's activity generate periodic ajax calls. A more advanced system would be to use a WebSocket server,
 * Socket.IO, a messaging server like PubNub or Google Messages or something similar, but these things require
 * configurations that are currently beyond the scope of QCubed.
 */


// This default watcher allows the user's browser to watch events, but not other browsers.
// You SHOULD change this to get the full power of the watcher system. Either subclass the QWatcherDB class,
// or use one of the persistent memory caches.
class QWatcher extends QWatcherCache
{
	static protected function initCache()
	{
		static::$objCache = new QCacheProviderLocalMemory(['KeepInSession' => true]);
		//or, static::$objCache = new QCacheProviderAPC();
		//or, static::$objCache = new QCacheProviderMemCache(['host'=>'myhost', ...]);
		//or, static::$objCache = new QCacheProviderRedis(['parameters'=>[params...], 'options'=>[opts...]]);
	}
}

//or, class QWatcher extends QWatcherDB {}
