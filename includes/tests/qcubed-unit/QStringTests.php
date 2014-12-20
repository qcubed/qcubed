<?php
/**
 * 
 * @package Tests
 */
class QStringTests extends QUnitTestCaseBase {    
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

		$this->assetEqual(QString::StartsWith("This is a test", "This"), true);
		$this->assetEqual(QString::StartsWith("This is a test", "this"), false);
		$this->assetEqual(QString::StartsWith("This is a test", "Thi"), true);
		$this->assetEqual(QString::StartsWith("This is a test", "X"), false);
		$this->assetEqual(QString::StartsWith("This is a test", ""), true);

		$this->assetEqual(QString::EndsWith("This is a test", "test"), true);
		$this->assetEqual(QString::EndsWith("This is a test", "Test"), false);
		$this->assetEqual(QString::EndsWith("This is a test", "est"), true);
		$this->assetEqual(QString::EndsWith("This is a test", "X"), false);
		$this->assetEqual(QString::EndsWith("This is a test", ""), true);
	}

	private function lcsCheckValueHelper($str1, $str2, $strExpectedResult) {
		$strResult = QString::LongestCommonSubsequence($str1, $str2); 
		$this->assertEqual($strResult, $strExpectedResult, "Longest common subsequence of '" . $str1 . 
			"' and '" . $str2 . "' is '" . $strResult . "'");
	}	
}
?>