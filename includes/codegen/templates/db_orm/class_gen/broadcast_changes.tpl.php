<?php
/**
 * Primitives to broadcast changes. The default uses QWatcher to mark a table as modified. Override if you want more
 * granular types of watching or to use other notifications
 */

if (count($objTable->PrimaryKeyColumnArray) == 1) {
	$pkType = $objTable->PrimaryKeyColumnArray[0]->VariableType;
} else {
	$pkType = 'string';	// combined pk
}

?>
   /**
	* The current record has just been inserted into the table. Let everyone know.
	* @param <?= $pkType ?>	Primary key of record just inserted.
	*/
	protected static function BroadcastInsert($pk) {
		if (static::$blnWatchChanges) {
			QWatcher::MarkTableModified (static::GetDatabase()->Database, '<?= $objTable->Name ?>');
		}
	}

   /**
	* The current record has just been updated. Let everyone know. $this->__blnDirty has the fields
    * that were just updated.
	* @param <?= $pkType ?>	Primary key of record just updated.
	* @param string[] array of field names that were modified.
	*/
	protected static function BroadcastUpdate($pk, $fields) {
		if (static::$blnWatchChanges) {
			QWatcher::MarkTableModified (static::GetDatabase()->Database, '<?= $objTable->Name ?>');
		}
	}

   /**
	* The current record has just been deleted. Let everyone know.
	* @param <?= $pkType ?>	Primary key of record just deleted.
	*/
	protected static function BroadcastDelete($pk) {
		if (static::$blnWatchChanges) {
			QWatcher::MarkTableModified (static::GetDatabase()->Database, '<?= $objTable->Name ?>');
		}
	}

   /**
	* All records have just been deleted. Let everyone know.
	*/
	protected static function BroadcastDeleteAll() {
		if (static::$blnWatchChanges) {
			QWatcher::MarkTableModified (static::GetDatabase()->Database, '<?= $objTable->Name ?>');
		}
	}