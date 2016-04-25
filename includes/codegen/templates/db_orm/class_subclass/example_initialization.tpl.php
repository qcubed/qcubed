<?php
$blnAutoInitialize = $objCodeGen->AutoInitialize;
if (!$blnAutoInitialize) {
?>

/*
		// Initialize each property with default values from database definition
		public function __construct()
		{
			$this->Initialize();
		}
*/
<?php } ?>

/*
		public function Initialize()
		{
			parent::Initialize();
			// You additional initializations here
		}
*/
