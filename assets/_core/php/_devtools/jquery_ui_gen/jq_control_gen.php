<?php
require('jq_control.php');

function jq_control_gen($strUrl, $strQcClass = null, $strQcBaseClass = 'QPanel') {
	$jqControlGen = new JqControlGen();
	$objJqDoc = new HtmlJqDoc($strUrl, null, null, $strQcClass, $strQcBaseClass);
	$jqControlGen->GenerateControl($objJqDoc);
}

$baseUrl = "http://docs.jquery.com/UI";

jq_control_gen($baseUrl."/Draggable");
jq_control_gen($baseUrl."/Droppable");
jq_control_gen($baseUrl."/Resizable");
jq_control_gen($baseUrl."/Selectable");
jq_control_gen($baseUrl."/Sortable");

jq_control_gen($baseUrl."/Accordion");
jq_control_gen($baseUrl."/Autocomplete", null, 'QTextBox');
jq_control_gen($baseUrl."/Button", 'QJqButton', 'QButton');
jq_control_gen($baseUrl."/Datepicker");
jq_control_gen($baseUrl."/Datepicker", 'QDatepickerBox', 'QTextBox');
jq_control_gen($baseUrl."/Dialog");
jq_control_gen($baseUrl."/Progressbar");
jq_control_gen($baseUrl."/Slider");
jq_control_gen($baseUrl."/Tabs");

?>