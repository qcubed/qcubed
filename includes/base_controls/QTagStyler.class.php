<?php
	/* This file contains the QTagStyler class.
	 *
	 * @package Controls
	 */

	/**
	 * A class that encapsulates the styles for a tag. It can be used to swap out a collection of styles for another
	 * collection of styles. Note that this is pretty much just an implementation of the QHtmlAttributeManager,
	 * which manages both html attributes and css styles. Modern HTML, CSS frameworks and javascript frameworks use
	 * more that just the "style" attribute to style an html object.
	 *
	 * @package Controls
	 */
	class QTagStyler extends QHtmlAttributeManager {

		/**
		 * Allows the row style to be overriden with an already existing QDataGridLegacyRowStyle
		 *
		 * @param QTagStyler $objOverrideStyle
		 *
		 * @return QTagStyler
		 */
		public function ApplyOverride(QTagStyler $objOverrideStyle) {
			$objNewStyle = clone $this;

			$objNewStyle->Override($objOverrideStyle);

			return $objNewStyle;
		}

		/**
		 * Returns HTML attributes for the QDataGridLegacy row.
		 * Deprecated. Please use renderHtmlAttributes().
		 *
		 * @return string HTML attributes
		 * @deprecated
		 */
		public function GetAttributes() {
			return $this->RenderHtmlAttributes();
		}

		/**
		 * Sets the attributes to the given array.
		 *
		 * @param $attributes
		 */
		public function SetAttributes($attributes) {
			$this->attributes = $attributes;
		}
	}
