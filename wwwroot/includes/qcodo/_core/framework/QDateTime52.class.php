	<?php
	
	// PHP 5.3 and above does not allow for __toString() function to
	// accept arguments. Previous versions of QCubed (including 1.0 and QCodo)
	// allowed it. Preserving this for back-compat. 
	class QDateTime extends QDateTimeBase {
		public function __toString($strFormat = null) {
			return parent::qFormat($strFormat);
		}
	}
	
	?>