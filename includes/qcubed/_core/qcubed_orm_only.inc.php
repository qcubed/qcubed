Please note: this file is deprecated as of 0.3.33

If you wish to run QCubed without any QForm interactions, simply comment out the following lines in qcubed.inc.php:

	QApplicationBase::$PreloadedClassFile['_enumerations'] = __QCUBED_CORE__ . '/base_controls/_enumerations.inc.php';
	QApplicationBase::$PreloadedClassFile['QControlBase'] = __QCUBED_CORE__ . '/base_controls/QControlBase.class.php';
	QApplicationBase::$PreloadedClassFile['QControl'] = __QCUBED__ . '/controls/QControl.class.php';
	QApplicationBase::$PreloadedClassFile['QFormBase'] = __QCUBED_CORE__ . '/base_controls/QFormBase.class.php';
	QApplicationBase::$PreloadedClassFile['QForm'] = __QCUBED__ . '/controls/QForm.class.php';
	QApplicationBase::$PreloadedClassFile['_actions'] = __QCUBED_CORE__ . '/base_controls/_actions.inc.php';
	QApplicationBase::$PreloadedClassFile['_events'] = __QCUBED_CORE__ . '/base_controls/_events.inc.php';

With those lines commented out, nothing QForm-related will ever get loaded into your application.