<?php
include_once('simple_html_dom.php');
require('../../qcubed.inc.php');
require(__INCLUDES__ . '/qcubed/codegen/QCodeGen.class.php');

class JqAttributes {
	public $name;
	public $description;

	public function __construct($origName, $description) {
		$this->name = $origName;
		$this->description = $description;
	}
}

class Option extends JqAttributes {
	public $type;
	public $phpType;
	public $defaultValue = null;
	public $propName;
	public $varName;
	public $phpQType;

	static public function php_type($jsType) {
		$jsType = preg_replace('/\([^\)]*\)/', '', $jsType); // erase possible function args
		if (strchr($jsType, ',')) return 'mixed';
		if (stripos($jsType, 'array') === 0) return 'array';
		switch ($jsType) {
		case 'Boolean': return 'boolean';
		case 'String': return 'string';
		case 'Object': return 'mixed';
		case 'Selector': return 'mixed';
		case 'Integer': return 'integer';
		case 'Number': return 'integer';
		case 'Date': return 'QDateTime';
		case 'Options': return 'array';
		default: return 'QJsClosure';
		}
	}

	static public function php_qtype($phpType) {
		switch ($phpType) {
		case 'boolean': return 'QType::Boolean';
		case 'string': return 'QType::String';
		case 'mixed': return null;
		case 'integer': return 'QType::Integer';
		case 'array': return 'QType::ArrayType';
		case 'QDateTime': return 'QType::DateTime';
		default: return "'".$phpType."'";
		}
	}

	static public function php_type_prefix($phpType) {
		switch ($phpType) {
		case 'boolean': return 'bln';
		case 'string': return 'str';
		case 'mixed': return 'mix';
		case 'integer': return 'int';
		case 'array': return 'arr';
		case 'QDateTime': return 'dtt';
		default: return 'mix';
		}
	}

	static public function php_value($jsValue) {
		//todo: add proper parsing
		$jsValue = trim($jsValue);
		if (!$jsValue)
			return null;
		if ($jsValue[0] == '{') {
			$str = str_replace('{', 'array(', $jsValue);
			$str = str_replace('}', ')', $str);
			$str = str_replace(':', '=>', $str);
			return $str;
		}
		if ($jsValue[0] == '[') {
			$str = str_replace('[', 'array(', $jsValue);
			$str = str_replace(']', ')', $str);
			return $str;
		}

		try {
			// make sure the value is valid php code
			//todo: find better/safer way to check for this
			if (@eval($jsValue. ';') === false) {
				return null;
			}
		} catch (exception $ex) {
			return null;
		}
		return $jsValue;
	}


	public function __construct($name, $origName, $jsType, $defaultValue, $description) {
		parent::__construct($origName, $description);
		$this->type = $jsType;
		if ($defaultValue !== null)
			$this->defaultValue = self::php_value($defaultValue);

		if (($origName === 'dateFormat' || $origName === 'dateTimeFomat') && $name === $origName)
			$name = 'jq'.ucfirst($name);

		$this->phpType = self::php_type($jsType);
		$this->propName = ucfirst($name);
		$this->varName = self::php_type_prefix($this->phpType).$this->propName;
		$this->phpQType = self::php_qtype($this->phpType);
	}
}

class Event extends Option
{
	public $eventClassName;
	public $eventName;

	public function __construct($strQcClass, $name, $origName, $type, $description) {
		parent::__construct($name, $origName, $type, 'null', $description);
		$this->eventName = $strQcClass . '_' . substr($name, 2);
		$this->eventClassName = $this->eventName . 'Event';
	}

}

class Method extends JqAttributes {
	public $signature;
	public $call;
	public $phpSignature;
	public $requiredArgs = array();
	public $optionalArgs = array();

	public function __construct($name, $origName, $signature, $description) {
		parent::__construct($origName, $description);
		$this->name = ucfirst($name);
		$signature = str_replace("\n", '', $signature);
		$this->signature = $signature;

		$this->phpSignature = ucfirst($name).'(';
		$this->call = preg_replace('/(.*)\(.*/', '$1', $signature);
		$args = explode(',', preg_replace('/.*\((.*)\)/', '$1', $signature));
		for ($i = 0, $cnt = count($args); $i < $cnt; ++$i) {
			$arg = trim($args[$i]);
			if ($arg{0} == '"') {
				// constant argument (most likely the name of the method itself)
				$this->requiredArgs[] = $arg;
				continue;
			}
			if ($arg{0} == '[') {
				// optional arg
				$arg = trim($arg, '[]');
				$this->phpSignature .= '$'.$arg.' = null';
				$this->optionalArgs[] = '$'.$arg;
			} else {
				$this->phpSignature .= '$'.$arg;
				$this->requiredArgs[] = '$'.$arg;
			}
			if ($i < $cnt - 1) {
				$this->phpSignature .= ', ';
			}
		}
		$this->phpSignature .= ')';
	}
}

class JqDoc {
	public $strJqClass;
	public $strJqSetupFunc;
	public $strQcClass;
	public $strQcBaseClass;
	public $options = array();
	public $methods = array();

	public function __construct($strJqClass = null, $strJqSetupFunc = null, $strQcClass = null, $strQcBaseClass = 'QPanel')
	{
		$this->strJqClass = $strJqClass;

		if ($strJqSetupFunc === null) {
			if ($this->strJqClass !== null)
				$this->strJqSetupFunc = strtolower($this->strJqClass);
		} else {
			$this->strJqSetupFunc = $strJqSetupFunc;
		}

		if ($strQcClass === null) {
			if ($this->strJqClass !== null)
				$this->strQcClass = 'Q'.$this->strJqClass;
		} else {
			$this->strQcClass = $strQcClass;
		}

		$this->strQcBaseClass = $strQcBaseClass;
	}
}

class HtmlJqDoc extends JqDoc {

	public function __construct($strUrl, $strJqClass = null, $strJqSetupFunc = null, $strQcClass = null, $strQcBaseClass = 'QPanel')
	{
		$html = file_get_html($strUrl);

		if ($strJqClass === null) {
			$nodes = $html->find('h1.firstHeading');
			$strJqClass = preg_replace('/.*\//', '', $nodes[0]->plaintext());
		}

		parent::__construct($strJqClass, $strJqSetupFunc, $strQcClass, $strQcBaseClass);

		$htmlOptions = $html->find('div[id=options] li.option');

		$names = array();
		foreach ($htmlOptions as $htmlOption) {
			$nodes = $htmlOption->find('h3.option-name');
			$origName = $name = $nodes[0]->plaintext();

			// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
			$i = 1;
			while (array_key_exists($name, $names)) {
				$name .= $i;
				++$i;
			}

			$nodes = $htmlOption->find('dd.option-type');
			$type = $nodes[0]->plaintext();

			$defaultValue = null;
			$nodes = $htmlOption->find('dt.option-default-label');
			if ($nodes) {
				$nodes = $htmlOption->find('dd.option-default');
				$defaultValue = html_entity_decode($nodes[0]->plaintext(), ENT_COMPAT, 'UTF-8');
			}

			$nodes = $htmlOption->find('div.option-description');
			$description = $nodes[0]->plaintext();

			$this->options[] = new Option($name, $origName, $type, $defaultValue, $description);
			$names[$name] = $name;
		}

		$htmlEvents = $html->find('div[id=events] li.event');
		foreach ($htmlEvents as $htmlEvent) {
			$nodes = $htmlEvent->find('h3.event-name');
			$origName = $name = $nodes[0]->plaintext();
			if (substr($name, 0, 2) !== "on") {
				$name = "on".ucfirst($name);
			}

			// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
			$i = 1;
			while (array_key_exists($name, $names)) {
				$name .= $i;
				++$i;
			}

			$nodes = $htmlEvent->find('dd.event-type');
			$type = $nodes[0]->plaintext();

			$nodes = $htmlEvent->find('div.event-description');
			$description = $nodes[0]->plaintext();

			$this->options[] = new Event($this->strQcClass, $name, $origName, $type, $description);
			$names[$name] = $name;
		}

		$htmlMethods = $html->find('div[id=methods] li.method');
		$names = array();
		foreach ($htmlMethods as $htmlMethod) {
			$nodes = $htmlMethod->find('h3.method-name');
			$origName = $name = $nodes[0]->plaintext();
			if ($origName === "widget") {
				// the widget method doesn't make much sense in our context
				// skip it
				continue;
			}

			// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
			$i = 1;
			while (array_key_exists($name, $names)) {
				$name .= $i;
				++$i;
			}

			$nodes = $htmlMethod->find('dd.method-signature');
			$signature = $nodes[0]->plaintext();

			$nodes = $htmlMethod->find('div.method-description');
			$description = $nodes[0]->plaintext();

			$this->methods[] = new Method($name, $origName, $signature, $description);
			$names[$name] = $name;
		}

	}
}

class JqControlGen extends QCodeGenBase {
	protected $intDatabaseIndex = 0;

	public function __construct() {
		QCodeGen::$TemplateEscapeBegin = '<%';
		QCodeGen::$TemplateEscapeEnd = '%>';
		QCodeGen::$TemplateEscapeBeginLength = strlen(QCodeGen::$TemplateEscapeBegin);
		QCodeGen::$TemplateEscapeEndLength = strlen(QCodeGen::$TemplateEscapeEnd);
	}

	public function GenerateControl($objJqDoc ) {
		$strOutDirControls = __INCLUDES__ . "/qcubed/controls";
		$strOutDirControlsBase = __INCLUDES__ . "/qcubed/_core/base_controls";

		$mixArgumentArray = array('objJqDoc' => $objJqDoc);
		$strTemplate = file_get_contents('jq_control.tpl');
		//use EvaluateTemplate to avoid dealing with XML
		$strResult = $this->EvaluateTemplate($strTemplate, 'jq_ctl', $mixArgumentArray);
		$strOutFileName = $strOutDirControlsBase . '/'.$objJqDoc->strQcClass . 'Base.class.php';
		file_put_contents($strOutFileName, $strResult);

		$strOutFileName = $strOutDirControls . '/' . $objJqDoc->strQcClass . '.class.php';
		if (!file_exists($strOutFileName)) {
			$strEmpty = "<?php\n\tclass ".$objJqDoc->strQcClass." extends ".$objJqDoc->strQcClass."Base\n\t{\n\t}\n?>";
			file_put_contents($strOutFileName, $strEmpty);
		}

		echo "Generated class: " . $objJqDoc->strQcClass . "<br>";
	}
}
?>
