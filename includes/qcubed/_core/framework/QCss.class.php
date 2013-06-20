<?php
	/**
	 * An abstract utility class to handle Css manipulation.  All methods
	 * are statically available.
	 */

	/**
	 * An abstract utility class to handle Css manipulation.  All methods
	 * are statically available.
	 */
	abstract class QCss {
		/**
		 * This faux constructor method throws a caller exception.
		 * The Css object should never be instantiated, and this constructor
		 * override simply guarantees it.
		 *
		 * @return void
		 */
		public final function __construct() {
			throw new QCallerException('Css should never be instantiated.  All methods and variables are publically statically accessible.');
		}

		/**
		 * Returns the formatted value of type <length>.
		 * See http://www.w3.org/TR/CSS1/#units for more info.
		 * @param string $strValue The number or string to be formatted to the <length> compatible value.
		 * @return string the formatted value of type <length>.
		 */
		public final static function FormatLength($strValue) {
			if (is_numeric($strValue)) {
				if (0 == $strValue) {
					if (!is_int($strValue)) {
						$fltValue = floatval($strValue);
						return sprintf('%s', $fltValue);
					} else {
						return sprintf('%s', $strValue);
					}
				} else {
					if (!is_int($strValue)) {
						$fltValue = floatval($strValue);
						return sprintf('%spx', $fltValue);
					} else {
						return sprintf('%spx', $strValue);
					}
				}
			} else {
				return sprintf('%s', $strValue);
			}
		}
	}
?>