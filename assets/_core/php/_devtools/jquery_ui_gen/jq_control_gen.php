<?php
require('jq_control.php');

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

		foreach ($htmlOptions as $htmlOption) {
			$nodes = $htmlOption->find('h3.option-name');
			$origName = $name = $nodes[0]->plaintext();

			// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
			$name = $this->unique_name($name);

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
		}

		$htmlEvents = $html->find('div[id=events] li.event');
		foreach ($htmlEvents as $htmlEvent) {
			$nodes = $htmlEvent->find('h3.event-name');
			$origName = $name = $nodes[0]->plaintext();
			if (substr($name, 0, 2) !== "on") {
				$name = "on".ucfirst($name);
			}

			// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
			$name = $this->unique_name($name);

			$nodes = $htmlEvent->find('dd.event-type');
			$type = $nodes[0]->plaintext();

			$nodes = $htmlEvent->find('div.event-description');
			$description = $nodes[0]->plaintext();

			if (substr($type, 0, 8) == 'function') {	// this can only be declared at init time
				$this->options[] = new Event($this->strQcClass, $name, $origName, $type, $description);
			} else {
				$this->events[] = new Event($this->strQcClass, $name, $origName, $type, $description);
			}
		}

		$htmlMethods = $html->find('div[id=methods] li.method');
		$this->reset_names();
		foreach ($htmlMethods as $htmlMethod) {
			$nodes = $htmlMethod->find('h3.method-name');
			$origName = $name = $nodes[0]->plaintext();
			if ($origName === "widget") {
				// the widget method doesn't make much sense in our context
				// skip it
				continue;
			}

			// sometimes jQuery controls (e.g. tabs) uses the same property name for more than one options
			$name = $this->unique_name($name);

			$nodes = $htmlMethod->find('dd.method-signature');
			$signature = $nodes[0]->plaintext();

			$nodes = $htmlMethod->find('div.method-description');
			$description = $nodes[0]->plaintext();

			$this->methods[] = new Method($name, $origName, $signature, $description);
		}
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

$baseUrl = "http://docs.jquery.com/UI";

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