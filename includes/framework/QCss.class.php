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
		 * @throws QCallerException
		 * @return \QCss
		 */
		public final function __construct() {
			throw new QCallerException('Css should never be instantiated.  All methods and variables are publicly statically accessible.');
		}

		/**
		 * Returns the formatted value of type <length>.
		 * See http://www.w3.org/TR/CSS1/#units for more info.
		 * @param string $strValue The number or string to be formatted to the <length> compatible value.
		 * @return string the formatted value of type <length>.
		 * @deprecated use QHtml::formatLength
		 */
		public final static function FormatLength($strValue) {
			return QHtml::formatLength($strValue);
		}
	}
?>