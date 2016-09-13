<?php

/**
 * 
 * @package Tests
 */
if(!class_exists('Person')){
	require_once __INCLUDES__ .'/model/Person.class.php';
}
if(!class_exists('Project')){
	require_once __INCLUDES__ .'/model/Project.class.php';
}
if(!class_exists('Login')){
	require_once __INCLUDES__ .'/model/Login.class.php';
}
if(!class_exists('Milestone')){
	require_once __INCLUDES__ .'/model/Milestone.class.php';
}
if(!class_exists('Address')){
	require_once __INCLUDES__ .'/model/Address.class.php';
}

if(!class_exists('QAbstractCacheProvider')){
	include_once __QCUBED_CORE__ . '/framework/QCacheProviderLocalMemory.class.php';
}

/**
 * Cache provider like QCacheProviderLocalMemory, but with public access to the underlying array.
 */
class QCacheProviderLocalMemoryTest extends QCacheProviderLocalMemory {
	/**
	 * @var array
	 * @access public We need it to be public to enumerate cached values.
	 */
	public $arrLocalCache;

}

// test cases:
// 1. roll back after save should leave cache empty
// 2. roll back after save should leave object in a cache unchanged (object already were in a cache)
// 3. commit after save should place modified object into the cache
// 3a. commit after save should place modified object into the cache (object already were in a cache)
// 4. commit after the object delete should leave the cache empty
// 4a. commit after the object delete should leave the cache empty (cache was populated with object to be deleted before transaction begins)
// 5. roll back after the object delete should leave the cache empty
// 5a. roll back after the object delete should leave object in a cache unchanged (cache was populated with object to be deleted before transaction begins)
//
class QDatabaseTests extends QUnitTestCaseBase {
	// 1. roll back after save should leave cache empty
	public function testTransactionWithCacheSaveRollBack() {
		Person::GetDatabase()->Caching = true;
		// establish a cache object we can work with
		$objCacheProvider = QApplication::$objCacheProvider;
		QApplication::$objCacheProvider = new QCacheProviderLocalMemoryTest(array());
		// cache is empty now
		
		$strPerson1_FirstName = null;
		try {
			Person::GetDatabase()->TransactionBegin();
			// cache is substituted
			$objPerson1 = Person::Load(1);
			// person object is placed in the temporary cache
			$strPerson1_FirstName = $objPerson1->FirstName;
			$objPerson1->FirstName = "New value 1";
			$objPerson1->Save();
			// person object is removed from the temporary cache
			
			// imitate the load made by other client.
			// It populates the cache with old value.
			$objPerson1a = Person::Load(1);
			// old person value is placed in the temporary cache

			throw new Exception("DATABASE ERROR!"); // imitate the database error in the next Save call
			
			Person::GetDatabase()->TransactionCommit();
		} catch(Exception $ex) {
			Person::GetDatabase()->TransactionRollBack();
			// temporary cache is throwed out. the empty application cache is restored.
		}
		$this->assertEquals(0, count(QApplication::$objCacheProvider->arrLocalCache), "Object is not placed in a cache because of the transaction roll back.");

		// restore the actual cache object
		QApplication::$objCacheProvider = $objCacheProvider;
	}
	
	// 2. roll back after save should leave object in a cache unchanged (object already were in a cache)
	public function testTransactionWithCacheSaveRollBack2() {
		Person::GetDatabase()->Caching = true;
		// establish a cache object we can work with
		$objCacheProvider = QApplication::$objCacheProvider;
		QApplication::$objCacheProvider = new QCacheProviderLocalMemoryTest(array());
		// cache is empty now
		
		$objPerson1z = Person::Load(1);
		// original person value is placed in a cache
		$strPerson1_FirstName = $objPerson1z->FirstName;
		try {
			Person::GetDatabase()->TransactionBegin();
			// cache is substituted
			$objPerson1 = Person::Load(1);
			// person object is placed in the temporary cache
			$objPerson1->FirstName = "New value 1";
			$objPerson1->Save();
			// person object is removed from the temporary cache
			
			// imitate the load made by other client.
			// It populates the cache with old value.
			$objPerson1a = Person::Load(1);
			// old person value is placed in the temporary cache

			throw new Exception("DATABASE ERROR!"); // imitate the database error in the next Save call

			Person::GetDatabase()->TransactionCommit();
		} catch(Exception $ex) {
			Person::GetDatabase()->TransactionRollBack();
			// temporary cache is throwed out. the empty application cache is restored.
		}
		$this->assertEquals(1, count(QApplication::$objCacheProvider->arrLocalCache), "Object is not dropped from a cache because of the transaction roll back.");
		foreach (QApplication::$objCacheProvider->arrLocalCache as $objPerson) {
			$this->assertEquals($strPerson1_FirstName, $objPerson->FirstName, "Object is not modified in a cache because of the transaction roll back.");
		}

		// restore the actual cache object
		QApplication::$objCacheProvider = $objCacheProvider;
	}
	
	// 3. commit after save should place modified object into the cache
	public function testTransactionWithCacheSaveCommit() {
		Person::GetDatabase()->Caching = true;
		// establish a cache object we can work with
		$objCacheProvider = QApplication::$objCacheProvider;
		QApplication::$objCacheProvider = new QCacheProviderLocalMemoryTest(array());
		// cache is empty now
		
		try {
			Person::GetDatabase()->TransactionBegin();
			// cache is substituted
			$objPerson1 = Person::Load(1);
			// person object is placed in the temporary cache
			$objPerson1->FirstName = "New value 1";
			$objPerson1->Save();
			// person object is removed from the temporary cache
			
			Person::GetDatabase()->TransactionCommit();
			// new person value is placed in the database
		} catch(Exception $ex) {
			Person::GetDatabase()->TransactionRollBack();
		}
		
		$this->assertEquals(0, count(QApplication::$objCacheProvider->arrLocalCache), "Object is not placed in a cache after save because of the transaction commit.");
		
		// imitate the load made by other client.
		// It populates the cache with new value.
		$objPerson1a = Person::Load(1);
		// new person value is placed in the application cache
		
		$this->assertEquals(1, count(QApplication::$objCacheProvider->arrLocalCache), "Object is added to a cache because of the transaction commit.");
		foreach (QApplication::$objCacheProvider->arrLocalCache as $objPerson) {
			$this->assertEquals("New value 1", $objPerson->FirstName, "Object is modified in a cache because of the transaction commit.");
		}
		
		// Restore the original value to not break other tests
		$objPerson1a->FirstName = "John";
		$objPerson1a->Save();

		// restore the actual cache object
		QApplication::$objCacheProvider = $objCacheProvider;
	}
	
	// 3a. commit after save should place modified object into the cache (object already were in a cache)
	public function testTransactionWithCacheSaveCommit2() {
		Person::GetDatabase()->Caching = true;
		// establish a cache object we can work with
		$objCacheProvider = QApplication::$objCacheProvider;
		QApplication::$objCacheProvider = new QCacheProviderLocalMemoryTest(array());
		// cache is empty now
		
		$objPerson1z = Person::Load(1);
		// original person value is placed in a cache
		$strPerson1_FirstName = $objPerson1z->FirstName;
		try {
			Person::GetDatabase()->TransactionBegin();
			// cache is substituted
			$objPerson1 = Person::Load(1);
			// person object is placed in the temporary cache
			$objPerson1->FirstName = "New value 1";
			$objPerson1->Save();
			// person object is removed from the temporary cache
			
			Person::GetDatabase()->TransactionCommit();
			// new person value is placed in the database
		} catch(Exception $ex) {
			Person::GetDatabase()->TransactionRollBack();
		}
		
		$this->assertEquals(0, count(QApplication::$objCacheProvider->arrLocalCache), "Object is dropped from a cache because of the transaction commit.");
		
		// imitate the load made by other client.
		// It populates the cache with new value.
		$objPerson1a = Person::Load(1);
		// new person value is placed in the application cache
		
		$this->assertEquals(1, count(QApplication::$objCacheProvider->arrLocalCache), "Object is not dropped from a cache because of the transaction commit.");
		foreach (QApplication::$objCacheProvider->arrLocalCache as $objPerson) {
			$this->assertEquals("New value 1", $objPerson->FirstName, "Object is modified in a cache because of the transaction commit.");
		}
		
		// Restore the original value to not break other tests
		$objPerson1a->FirstName = "John";
		$objPerson1a->Save();

		// restore the actual cache object
		QApplication::$objCacheProvider = $objCacheProvider;
	}
	
	// 4. commit after the object delete should leave the cache empty
	public function testTransactionWithCacheDeleteCommit() {
		// create an object in the database
		$objPerson1z = new Person;
		$objPerson1z->FirstName = "test";
		$objPerson1z->LastName = "test";
		$objPerson1z->Save();
		
		Person::GetDatabase()->Caching = true;
		// establish a cache object we can work with
		$objCacheProvider = QApplication::$objCacheProvider;
		QApplication::$objCacheProvider = new QCacheProviderLocalMemoryTest(array());
		// cache is empty now
		
		try {
			Person::GetDatabase()->TransactionBegin();
			// cache is substituted
			$objPerson1 = Person::Load($objPerson1z->Id);
			// person object is placed in the temporary cache
			$objPerson1->Delete();
			// person object is removed from the temporary cache
			
			Person::GetDatabase()->TransactionCommit();
			// person value is removed from the database
		} catch(Exception $ex) {
			Person::GetDatabase()->TransactionRollBack();
		}
		
		$this->assertEquals(0, count(QApplication::$objCacheProvider->arrLocalCache), "Object is not placed in a cache after delete because of the transaction commit.");
		
		// restore the actual cache object
		QApplication::$objCacheProvider = $objCacheProvider;
	}
	
	// 4a. commit after the object delete should leave the cache empty (cache was populated with object to be deleted before transaction begins)
	public function testTransactionWithCacheDeleteCommit2() {
		Person::GetDatabase()->Caching = true;
		// establish a cache object we can work with
		$objCacheProvider = QApplication::$objCacheProvider;
		QApplication::$objCacheProvider = new QCacheProviderLocalMemoryTest(array());
		// cache is empty now
		
		// create an object in the database
		$objPerson1z = new Person;
		$objPerson1z->FirstName = "test";
		$objPerson1z->LastName = "test";
		$objPerson1z->Save();
		
		Person::Load($objPerson1z->Id);
		// the person object is placed in a cache
		$this->assertEquals(1, count(QApplication::$objCacheProvider->arrLocalCache), "Object is placed in a cache.");
		
		try {
			Person::GetDatabase()->TransactionBegin();
			// cache is substituted
			$objPerson1 = Person::Load($objPerson1z->Id);
			// person object is placed in the temporary cache
			$objPerson1->Delete();
			// person object is removed from the temporary cache
			
			Person::GetDatabase()->TransactionCommit();
			// person value is removed from the database
		} catch(Exception $ex) {
			Person::GetDatabase()->TransactionRollBack();
		}
		
		$this->assertEquals(0, count(QApplication::$objCacheProvider->arrLocalCache), "Object is removed from a cache after delete because of the transaction commit.");
		
		// restore the actual cache object
		QApplication::$objCacheProvider = $objCacheProvider;
	}
	
	// 5. roll back after the object delete should leave the cache empty
	public function testTransactionWithCacheDeleteRollBack() {
		// create an object in the database
		$objPerson1z = new Person;
		$objPerson1z->FirstName = "test";
		$objPerson1z->LastName = "test";
		$objPerson1z->Save();
		
		Person::GetDatabase()->Caching = true;
		// establish a cache object we can work with
		$objCacheProvider = QApplication::$objCacheProvider;
		QApplication::$objCacheProvider = new QCacheProviderLocalMemoryTest(array());
		// cache is empty now
		
		try {
			Person::GetDatabase()->TransactionBegin();
			// cache is substituted
			$objPerson1 = Person::Load($objPerson1z->Id);
			// person object is placed in the temporary cache
			$objPerson1->Delete();
			// person object is removed from the temporary cache
			
			throw new Exception("DATABASE ERROR!"); // imitate the database error in the next Save call
			
			Person::GetDatabase()->TransactionCommit();
		} catch(Exception $ex) {
			Person::GetDatabase()->TransactionRollBack();
			// actual cache leaved unchanged
		}
		
		$this->assertEquals(0, count(QApplication::$objCacheProvider->arrLocalCache), "Object is not placed in a cache after delete because of the transaction roll back.");
		
		// restore the actual cache object
		QApplication::$objCacheProvider = $objCacheProvider;
		
		// clean up
		$objPerson1 = Person::Load($objPerson1z->Id);
		// person object is placed in the temporary cache
		$objPerson1->Delete();
	}

	// 5a. roll back after the object delete should leave object in a cache unchanged (cache was populated with object to be deleted before transaction begins)
	public function testTransactionWithCacheDeleteRollBack2() {
		Person::GetDatabase()->Caching = true;
		// establish a cache object we can work with
		$objCacheProvider = QApplication::$objCacheProvider;
		QApplication::$objCacheProvider = new QCacheProviderLocalMemoryTest(array());
		// cache is empty now
		
		// create an object in the database
		$objPerson1z = new Person;
		$objPerson1z->FirstName = "test";
		$objPerson1z->LastName = "test";
		$objPerson1z->Save();
		
		Person::Load($objPerson1z->Id);
		// the person object is placed in a cache
		$this->assertEquals(1, count(QApplication::$objCacheProvider->arrLocalCache), "Object is placed in a cache.");
		
		try {
			Person::GetDatabase()->TransactionBegin();
			// cache is substituted
			$objPerson1 = Person::Load($objPerson1z->Id);
			// person object is placed in the temporary cache
			$objPerson1->Delete();
			// person object is removed from the temporary cache
			
			throw new Exception("DATABASE ERROR!"); // imitate the database error in the next Save call
			
			Person::GetDatabase()->TransactionCommit();
		} catch(Exception $ex) {
			Person::GetDatabase()->TransactionRollBack();
			// actual cache leaved unchanged
		}
		
		$this->assertEquals(1, count(QApplication::$objCacheProvider->arrLocalCache), "Object is NOT removed from a cache after delete because of the transaction roll back.");
		
		// restore the actual cache object
		QApplication::$objCacheProvider = $objCacheProvider;
		
		// clean up
		$objPerson1 = Person::Load($objPerson1z->Id);
		// person object is placed in the temporary cache
		$objPerson1->Delete();
	}
}