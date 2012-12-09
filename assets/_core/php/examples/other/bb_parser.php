<?php

class BBCodeParser {
	private $objTokens;

	public function __construct($strText) {
		$strText = str_replace(array("\n","\r\n","\r"),"\n",$strText);
		
		$objLexer = new QLexer();
		
		// Build the rules

		// Bold - input looks like "[b]hello[/b]"
		$objLexer->addEntryPattern("\[b\]","start_bold",QLexer::DefaultMode,"bold");
		$objLexer->addExitPattern ("\[/b\]","end_bold","bold",QLexer::DefaultMode);

		// Italic - input looks like "[i]foo[/i]"
		$objLexer->addEntryPattern("\[i\]","start_italic",QLexer::DefaultMode,"italic");
		$objLexer->addExitPattern ("\[/i\]","end_italic","italic",QLexer::DefaultMode);

		// Line Break
		// Save the pain of multiple line endings - varies depending on the platform
		$objLexer->addPattern("\n","line_break");

		// Code Block - look like
		$objLexer->addEntryPattern("\[code\]","start_code",QLexer::DefaultMode,"code");
		$objLexer->addExitPattern ("\[/code\]","end_code","code",QLexer::DefaultMode);

		// Image - input looks like "[img]http://www.foo.com/image.gif[/img]"
		$objLexer->addEntryPattern("\[img\]","start_img",QLexer::DefaultMode,"img");
		$objLexer->addExitPattern ("\[/img\]","end_img","img",QLexer::DefaultMode);

		// Link - input looks like "[url=http://example.com]My Site[/url]"
		$objLexer->addEntryPattern("\[url=","start_url",QLexer::DefaultMode,"url");
		$objLexer->addExitPattern ("\[/url\]","end_url","url",QLexer::DefaultMode);

		$this->objTokens = $objLexer->Tokenize($strText);
	}

	private function renderRaw($strRaw) {
		return strip_tags($strRaw);
	}

	private function renderLink($objTokens) {
		$objToken = array_shift($objTokens);

		$parts = explode("]", $objToken["raw"]);
		$url = $parts[0];
		$label = $parts[1];
		return sprintf("<a href='%s'>%s</a>",$url, $label);
	}

	private function renderImage($objTokens) {
		$objToken = array_shift($objTokens);
		return sprintf("<img src='%s' />",$objToken["raw"]);
	}

	private function renderCode($objTokens) {
		$strOut = "";
		foreach($objTokens as $objToken) {
			if($objToken["token"] != QLexer::UNMATCHED) continue;
			$strOut .= highlight_string(stripslashes(trim($objToken["raw"])), true);
		}
		return $strOut;
	}

	private function renderFormatting($objTokens,$type,$tag) {
		$strOut = "";
		foreach($objTokens as $objToken) {
			if($objToken["token"] == "end_".$type) break;
			$strOut .= $this->renderRaw($objToken["raw"]);
		}

		return sprintf("<%s>%s</%s>",$tag,$strOut,$tag);
	}

	public function Render() {
		$this->strOut = "";

		foreach($this->objTokens as $objToken) {
			switch($objToken['token']) {
				case QLexer::UNMATCHED:
					$this->strOut .= $this->renderRaw($objToken['raw']);
					break;
				case "start_bold":
					$this->strOut .= $this->renderFormatting($objToken['raw'],"bold","strong");
					break;
				case "start_italic":
					$this->strOut .= $this->renderFormatting($objToken['raw'],"italic","em");
					break;
				case "line_break":
					$this->strOut .= "<br />";
					break;
				case "start_code":
					$this->strOut .= $this->renderCode($objToken["raw"]);
					break;
				case "start_url":
					$this->strOut .= $this->renderLink($objToken["raw"]);
					break;
				case "start_img":
					$this->strOut .= $this->renderImage($objToken["raw"]);
					break;
				default:
					$this->strOut .= "#unmatched_token#";
					break;
			}
		}

		return $this->strOut;
	}
}

?>