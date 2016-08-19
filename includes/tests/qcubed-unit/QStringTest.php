<?php
/**
 * 
 * @package Tests
 */
class QStringTest extends QUnitTestCaseBase {

	public function testLongestCommonSubsequence() {
		$this->lcsCheckValueHelper("hello world", "world war 2", "world");
		$this->lcsCheckValueHelper("what's up people", "what in the world is going on", "what");
		$this->lcsCheckValueHelper("foo bar", "bar foo", "foo"); // not bar! foo is first!
		
		$this->lcsCheckValueHelper("aaa", "aa", "aa");
		$this->lcsCheckValueHelper("cc", "bbbbcccccc", "cc");
		$this->lcsCheckValueHelper("ccc", "bcbb", "c");
		$this->lcsCheckValueHelper("aaa", "b", null);
		$this->lcsCheckValueHelper("", "bb", null);
		$this->lcsCheckValueHelper("aa", "", null);
		$this->lcsCheckValueHelper("", null, null);
		$this->lcsCheckValueHelper(null, null, null);
	}

	public function testEndsWithStartsWith() {
		$this->assertTrue(QString::StartsWith("This is a test", "This"));
		$this->assertFalse(QString::StartsWith("This is a test", "this"));
		$this->assertTrue(QString::StartsWith("This is a test", "Thi"));
		$this->assertFalse(QString::StartsWith("This is a test", "is a"));
		$this->assertFalse(QString::StartsWith("This is a test", "X"));
		$this->assertTrue(QString::StartsWith("This is a test", ""));

		$this->assertTrue(QString::EndsWith("This is a test", "test"));
		$this->assertFalse(QString::EndsWith("This is a test", "Test"));
		$this->assertTrue(QString::EndsWith("This is a test", "est"));
		$this->assertFalse(QString::EndsWith("This is a test", "is a"));
		$this->assertFalse(QString::EndsWith("This is a test", "X"));
		$this->assertTrue(QString::EndsWith("This is a test", ""));
	}

	private function lcsCheckValueHelper($str1, $str2, $strExpectedResult) {
		$strResult = QString::LongestCommonSubsequence($str1, $str2); 
		$this->assertEquals($strExpectedResult, $strResult, "Longest common subsequence of '" . $str1 .
			"' and '" . $str2 . "' is '" . $strResult . "'");
	}	
}