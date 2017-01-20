<?php
/**
 * Tests for using Redis as a Caching system in QCubed
 * 
 * @package Tests
 */

class RedisCacheTests extends QUnitTestCaseBase {
	/** @var Predis\Client Redis client which we have to use */
	protected $objClient;

	public function setUp() {
		if(class_exists('Predis\Client')) {
			$this->objClient = new Predis\Client();
		} else {
			$this->markTestSkipped(
				'Predis\Client class not found. Please install predis library. See https://github.com/nrk/predis'
			);
		}
	}

	/**
	 * Test single element set, get and delete
	 */
	public function testSingleElementCache() {
		$this->objClient->set('singleElementKey', 'singleElementvalue');
		$cachedResult = $this->objClient->get('singleElementKey');
		$this->assertEquals ('singleElementvalue', $cachedResult, 'Value not saved into Redis Properly');
		// Delete
		$this->objClient->del('singleElementKey');
		// Check again
		$cachedResult = $this->objClient->get('singleElementKey');
		$this->assertNotEquals ('singleElementvalue', $cachedResult, 'Value not deleted from Redis Properly');
	}

	/**
	 * Test Single element with auto expiration
	 */
	public function testSingleElementCacheWithExpiration() {
		// Set expiration to 2 seconds
		$this->objClient->set('singleElementKeyWithExpiration', 'singleElementvalueWhichWillExpire', 'ex', 2);
		// Get immediately
		$cachedResult = $this->objClient->get('singleElementKeyWithExpiration');
		$this->assertEquals ('singleElementvalueWhichWillExpire', $cachedResult, 'Set with expire failed - value was not set');
		// Sleep for 3 seconds
		sleep(3);
		$cachedResult = $this->objClient->get('singleElementKeyWithExpiration');
		$this->assertNotEquals ('singleElementvalueWhichWillExpire', $cachedResult, 'Set with expire failed - value did not expire');
	}

	/**
	 * Test basic set operations. If this is working then other data structures should also work!
	 */
	public function testSetOperations() {
		// add elements into set
		$this->objClient->sadd ('testSet', 10);
		$this->objClient->sadd ('testSet', 20);
		$this->objClient->sadd ('testSet', 30);
		$this->objClient->sadd ('testSet', 40);
		$this->objClient->sadd ('testSet', 50);
		$this->objClient->sadd ('testSet', 60);

		// There should be 6 elements
		$this->assertEquals(6, $this->objClient->scard('testSet'), 'Unexpected number of elements in set');

		// 40 should be a member
		$this->assertEquals(1, $this->objClient->sismember('testSet', 40), '40 was not found in set, as expected');
		// Remove 40 from list
		$this->objClient->srem('testSet', 40);
		// See that 40 is no longer a member of set
		$this->assertNotEquals(1, $this->objClient->sismember('testSet', 40), '40 was still found in set when it should have been removed.');
		// Remove the key
		$this->objClient->del(['testSet']);
		// Cardinality should now be 0
		$this->assertEquals(0, $this->objClient->scard('testSet'), 'Set was not removed');
	}
}
