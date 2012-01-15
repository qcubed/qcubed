<?php
require('jq_control.php');

$aryPathsList  = array();

function jq_control_gen($strUrl, $strQcClass = null, $strQcBaseClass = 'QPanel') {
	global $aryPathsList;

	$jqControlGen = new JqControlGen();
	$objJqDoc = new HtmlJqDoc($strUrl, null, null, $strQcClass, $strQcBaseClass);
	$jqControlGen->GenerateControl($objJqDoc);
		
	foreach ($objJqDoc->events as $event) {
		$aryPathsList[strtolower($event->eventClassName)] = 
			sprintf ("__QCUBED_CORE__ . '/base_controls/%sGen.class.php'", $objJqDoc->strQcClass);
	}
	foreach ($objJqDoc->options as $option) {
		if ($option instanceof Event) {
			$aryPathsList[strtolower($option->eventClassName)] =
				sprintf ("__QCUBED_CORE__ . '/base_controls/%sGen.class.php'", $objJqDoc->strQcClass);
		}
	}
	$aryPathsList[strtolower($objJqDoc->strQcClass) . 'gen'] =
		sprintf ("__QCUBED_CORE__ . '/base_controls/%sGen.class.php'", $objJqDoc->strQcClass);

	$aryPathsList[strtolower($objJqDoc->strQcClass) . 'base'] = 
			sprintf ("__QCUBED_CORE__ . '/base_controls/%sBase.class.php'", $objJqDoc->strQcClass);
			
	
	$aryPathsList[strtolower($objJqDoc->strQcClass)] = 
			sprintf ("__QCUBED__ . '/controls/%s.class.php'", $objJqDoc->strQcClass);
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

// additional auto-includes
$aryPathsList["qautocompletelistitem"] = "__QCUBED_CORE__ . '/base_controls/QAutocompleteBase.class.php'";
$strResult = '';
foreach ($aryPathsList as $class=>$path) { 
	$strResult .= "QApplicationBase::\$ClassFile['$class'] = $path;\n";
}

$strResult = "<?php\n" . $strResult . "\n?>";

$strOutFileName = __QCUBED_CORE__ . '/'. '_jq_paths.inc.php';

file_put_contents($strOutFileName, $strResult);

?>