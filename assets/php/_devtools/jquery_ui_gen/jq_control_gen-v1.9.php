<?php
require('jq_control.php');
	require('qcubed.inc.php');

class HtmlJqDoc extends JqDoc {

	public function description($desc_node) {
		$description = '';
		while ($desc_node) {
			if (strpos($desc_node->plaintext, 'Code examples:') !== false) {
				break;
			}
//			if ($description)
//				$description .= "\n";
			$text = $desc_node->outertext();
			$text = preg_replace('/<(\w+)[^>]*>\s*/', '<$1>', $text);
			$text = preg_replace('/\s*<\/(\w+)>/', '</$1>', $text);
			$text = preg_replace('/<\/code>\s*<code>/', '', $text);
			$text = preg_replace('/<div>/', '', $text);
			$text = preg_replace('/<\/div>/', '', $text);
			$description .= $text;
			$desc_node = $desc_node->next_sibling();
		}
		return $description;
	}

	public function __construct($strUrl, $strJqClass = null, $strJqSetupFunc = null, $strQcClass = null, $strQcBaseClass = 'QPanel')
	{
		$this->hasDisabledProperty = false;
		$html = file_get_html($strUrl);

		if ($strJqClass === null) {
			$nodes = $html->find('h1.entry-title');
			$strJqClass = preg_replace('/ .*/', '', $nodes[0]->plaintext);
		}

		parent::__construct($strJqClass, $strJqSetupFunc, $strQcClass, $strQcBaseClass);

		$htmlOptions = $html->find('section[id=options] div.api-item');

		foreach ($htmlOptions as $htmlOption) {
			$type = $this->add_option($htmlOption);
			if ($this->is_event_option($type)) {
				$this->add_event($htmlOption, $type);
			}
		}

		$htmlEvents = $html->find('section[id=events] div.api-item');
		foreach ($htmlEvents as $htmlEvent) {
			$this->add_event($htmlEvent);
		}

		$htmlMethods = $html->find('section[id=methods] div.api-item');
		$this->reset_names();
		foreach ($htmlMethods as $htmlMethod) {
			$this->add_method($htmlMethod);
		}
	}

	public function add_option($htmlOption) {
		$nodes = $htmlOption->find('h3');
		$name_node = $nodes[0];
		$origName = $name = preg_replace('/\W.*/', '', $name_node->innertext());

		$nodes = $htmlOption->find('span.option-type');
		$type = preg_replace('/Type: /', '', $nodes[0]->plaintext);
		if ($this->is_event_option($type))
			return $type;

		// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
		$name = $this->unique_name($name);

		$defaultValue = null;
		$nodes = $htmlOption->find('div.default');
		if ($nodes) {
			$desc_node = $nodes[0]->next_sibling();
			$nodes = $nodes[0]->find('code');
			$defaultValue = html_entity_decode($nodes[0]->plaintext, ENT_COMPAT, 'UTF-8');
		} else {
			$desc_node = $name_node->next_sibling();
		}
		$description = $this->description($desc_node);
		if ($name == 'disabled') {
			$this->hasDisabledProperty = true;
		}

		$this->options[] = new Option($name, $origName, $type, $defaultValue, $description);
		return $type;
	}

	public function is_event_option($type) {
		return stripos($type, 'function') !== false && strpos($type, ' or ') === false;
	}

	public function add_event($htmlEvent, $type = null) {
		$nodes = $htmlEvent->find('h3');
		$name_node = $nodes[0];
		$origName = $name = preg_replace('/\W.*/', '', $name_node->innertext());
		if (substr($name, 0, 2) !== "on") {
			$name = "on" . ucfirst($name);
		}

		if ($type == null) {
			$nodes = $htmlEvent->find('span.returns');
			$type = preg_replace('/Type: /', '', $nodes[0]->plaintext);
		}

		// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
		$name = $this->unique_name($name);

		$desc_node = $name_node->next_sibling();
		$description = $this->description($desc_node);

		if (stripos($type, 'function') === 0) { // this can only be declared at init time
			$this->options[] = new Event($this->strQcClass, $name, $origName, $type, $description);
		} else {
			$this->events[] = new Event($this->strQcClass, $name, $origName, $type, $description);
		}
	}

	public function add_method($htmlMethod) {
		$nodes = $htmlMethod->find('h3');
		$name_node = $nodes[0];
		$origName = $name = preg_replace('/\W.*/', '', $name_node->innertext());
		if ($origName === "widget") {
			// the widget method doesn't make much sense in our context
			// skip it
			return;
		}

		$signature = preg_replace('/\).*/', ')', $name_node->innertext());
		$signature = str_replace('[,', ',[', $signature);

		// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
		$name = $this->unique_name($name);

		$desc_node = $name_node->next_sibling();
		$description = $this->description($desc_node);

		$this->methods[] = new Method($name, $origName, $signature, $description);
	}
}

$aryPathsList  = array();

function CamelCaseFromDash($strName) {
	$strToReturn = '';

	// If entire underscore string is all uppercase, force to all lowercase
	// (mixed case and all lowercase can remain as is)
	if ($strName == strtoupper($strName))
		$strName = strtolower($strName);

	while (($intPosition = strpos($strName, "-")) !== false) {
		// Use 'ucfirst' to create camelcasing
		$strName = ucfirst($strName);
		if ($intPosition == 0) {
			$strName = substr($strName, 1);
		} else {
			$strToReturn .= substr($strName, 0, $intPosition);
			$strName = substr($strName, $intPosition + 1);
		}
	}

	$strToReturn .= ucfirst($strName);
	return $strToReturn;
}


function jq_control_gen($strUrl, $strQcClass = null, $strQcBaseClass = 'QPanel') {
	global $aryPathsList;

	$jqControlGen = new JqControlGen();
	$objJqDoc = new HtmlJqDoc($strUrl, null, null, $strQcClass, $strQcBaseClass);
	$jqControlGen->GenerateControl($objJqDoc);
		
	foreach ($objJqDoc->events as $event) {
		$aryPathsList[strtolower($event->eventClassName)] = 
			sprintf("__QCUBED_CORE__ . '/base_controls/%sGen.class.php'", $objJqDoc->strQcClass);
	}
	foreach ($objJqDoc->options as $option) {
		if ($option instanceof Event) {
			$aryPathsList[strtolower($option->eventClassName)] =
				sprintf("__QCUBED_CORE__ . '/base_controls/%sGen.class.php'", $objJqDoc->strQcClass);
		}
	}
	$aryPathsList[strtolower($objJqDoc->strQcClass) . 'gen'] =
		sprintf("__QCUBED_CORE__ . '/base_controls/%sGen.class.php'", $objJqDoc->strQcClass);

	$aryPathsList[strtolower($objJqDoc->strQcClass) . 'base'] = 
			sprintf("__QCUBED_CORE__ . '/base_controls/%sBase.class.php'", $objJqDoc->strQcClass);
			
	
	$aryPathsList[strtolower($objJqDoc->strQcClass)] = 
			sprintf("__QCUBED__ . '/controls/%s.class.php'", $objJqDoc->strQcClass);
}

// generate an include file for use by the ui classes
function jq_inc_gen() {
	$html = file_get_html('http://jqueryui.com/themeroller/');
	$nodes = $html->find('#icons', 0)->children();

	$aNames = array();
	foreach ($nodes as $node) {
		$name = $node->title;
		$aNames[] = substr($name, 9);	// delete '.ui-icon-'
	}
	
	$strOutFileName = __QCUBED_CORE__ . '/base_controls/'. '_jq_ui.inc.php';
	$strResult = "// Generated include file for JQuery UI related classes.\n\n// JQuery UI icon names scraped from the themeroller website.\n\n";
	$strResult .= "abstract class JqIcon {\n";
	foreach ($aNames as $name) {
		$strVarName = CamelCaseFromDash($name);
		if ($strVarName == 'Print') {
			$strVarName = 'Jq' . $strVarName;	// avoid reserved word
		}
		$strResult .= sprintf("\tconst %s = 'ui-icon-%s';\n", $strVarName, $name);
	}
	$strResult .= "}";
	$strResult = "<?php\n" . $strResult . "\n?>";
	
	file_put_contents($strOutFileName, $strResult);
}

$baseUrl = "http://api.jqueryui.com/1.9/";

// QBlock control uses these differently to make these capabilities a part of any block control
jq_control_gen($baseUrl."/Draggable", null, 'QControl');
jq_control_gen($baseUrl."/Droppable", null, 'QControl');
jq_control_gen($baseUrl."/Resizable", null, 'QControl');

jq_control_gen($baseUrl."/Selectable");
jq_control_gen($baseUrl."/Sortable");

jq_control_gen($baseUrl."/Accordion");
jq_control_gen($baseUrl."/Autocomplete", null, 'QTextBox');
jq_control_gen($baseUrl."/Button", 'QJqButton', 'QButton');
jq_control_gen($baseUrl."/Button", 'QJqCheckBox', 'QCheckBox');
jq_control_gen($baseUrl."/Button", 'QJqRadioButton', 'QRadioButton');
jq_control_gen($baseUrl."/Datepicker");
jq_control_gen($baseUrl."/Datepicker", 'QDatepickerBox', 'QTextBox');
jq_control_gen($baseUrl."/Dialog");
jq_control_gen($baseUrl."/Progressbar");
jq_control_gen($baseUrl."/Slider");
jq_control_gen($baseUrl."/Tabs");
jq_control_gen($baseUrl."/Menu");
jq_control_gen($baseUrl."/Spinner", null, 'QTextBox');
//jq_control_gen($baseUrl."/Tooltip"); A JQuery UI tool tip is not a control, but rather is straight javascript that changes how tooltips work on a whole page. Implementation would need to be very different.

jq_inc_gen();

// additional auto-includes
$aryPathsList["qautocompletelistitem"] = "__QCUBED_CORE__ . '/base_controls/QAutocompleteBase.class.php'";
$aryPathsList["jqicon"] = "__QCUBED_CORE__ . '/base_controls/_jq_ui.inc.php'";

$strResult = '';
foreach ($aryPathsList as $class=>$path) { 
	$strResult .= "QApplicationBase::\$ClassFile['$class'] = $path;\n";
}

$strResult = "<?php\n" . $strResult . "\n?>";

$strOutFileName = __QCUBED_CORE__ . '/'. '_jq_paths.inc.php';

file_put_contents($strOutFileName, $strResult);	

?>