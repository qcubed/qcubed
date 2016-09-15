<?php
/**
 * 
 * @package Tests
 */
class QCryptoTest extends QUnitTestCaseBase {

	public function testDefaultCrypto() {
		$strKey = 'abcdef';
		$crypt = new QCryptography($strKey, true);

		$str = 'Bilge and bath water';

		$e = $crypt->Encrypt($str);
		$this->assertNotEquals($str, $e);

		$str2  = $crypt->Decrypt($e);
		$this->assertEquals($str, $str2);

	}

	public function testHashKey() {
		$strKey = '1ab3cd5ef';
		$strHashKey = '498un4';
		$crypt = new QCryptography($strKey, null, null, $strHashKey);

		$str = "She's buying a stairway to heaven";

		$e = $crypt->Encrypt($str);
		$this->assertNotEquals($str, $e);

		$crypt2 = new QCryptography($strKey, null, null, $strHashKey);
		// test decrypt using 2nd instance of crypto using same key
		// should use the embedded IV rather than the generated one
		$str2  = $crypt2->Decrypt($e);
		$this->assertEquals($str, $str2);
	}

	public function testBase64Off() {
		$strKey = 'i4kl36';
		$strHashKey = 'p834875';
		$crypt = new QCryptography($strKey, false, null, $strHashKey);

		$str = "I still haven't found what I'm looking for";

		$e = $crypt->Encrypt($str);
		$this->assertNotEquals($str, $e);

		$crypt2 = new QCryptography($strKey, false, null, $strHashKey);
		// test decrypt using 2nd instance of crypto using same key
		// should use the embedded IV rather than the generated one
		$str2  = $crypt2->Decrypt($e);
		$this->assertEquals($str, $str2);
	}

	public function testSerialize() {
		$strKey = '438ppp87dgf';
		$crypt = new QCryptography($strKey);

		$str = 'Mary had a little lamb, a little beef, a little ham';

		$e = $crypt->Encrypt($str);
		$this->assertNotEquals($str, $e);

		$encoded = serialize($crypt);

		$crypt2 = unserialize($encoded);
		$str2  = $crypt2->Decrypt($e);
		$this->assertEquals($str, $str2);
	}

}