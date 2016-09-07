<?php

set_time_limit(86400);

if (php_sapi_name() !== 'cli') {
    echo 'Only for command line interface usage';
    exit();
}

require __DIR__ .'/../qcubed.inc.php';
$strSettingsSrc = __CONFIGURATION__ . '/codegen_settings.xml';

// Load in the QCodeGen Class
require(__QCUBED__ . '/codegen/QCodeGen.class.php');
// code generators
include (__QCUBED_CORE__ . '/codegen/controls/_class_paths.inc.php');

QCodeGen::Run($strSettingsSrc);


if ($strErrors = QCodeGen::$RootErrors) {
    printf("The following ROOT ERRORS were reported:\r\n%s\r\n\r\n", $strErrors);
} else {
    printf("CodeGen settings (as evaluted from %s):\r\n%s\r\n\r\n", $strSettingsSrc, QCodeGen::GetSettingsXml());
}

foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
    printf("%s\r\n---------------------------------------------------------------------\r\n", $objCodeGen->GetTitle());
    printf("%s\r\n", $objCodeGen->GetReportLabel());

    printf("%s\r\n", $objCodeGen->GenerateAll());

    if ($strErrors = $objCodeGen->Errors) {
        printf("The following errors were reported:\r\n%s\r\n", $strErrors);
    }
    print("\r\n");
}

foreach (QCodeGen::GenerateAggregate() as $strMessage) {
    printf("%s\r\n\r\n", $strMessage);
}
