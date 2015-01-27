<?php
/**
 * QHtmlAttributeManager class
 *
 * This class is designed to be used as a base class for controls and other classes that need to manage HTML attributes.
 *
 * @package Controls
 */

/**
 * A base blass for objects that manage html attributes. Uses array functions and defines a couple of arrays to manage
 * the attributes. Values will be html escaped when printed.
 *
 * Includes:
 * - helper functions to manage the class, style, and data-* attributes specially.
 * - helper functions to manage 'name-*' classes that are found in css frameworks like
 *   Bootstrap and Foundation.
 * - helpers for __get and __set functions, partially for backwards compatibility and also to make the setting
 *   of some attributes and styles easier (so you don't have to remember how to set them).
 *
 * Usage: Use the helper functions to setup your styles, classes, data-* and other attributes, then call
 *	      renderHtmlAttributes() to render the attributes to insert them into a tag.
 *
 *
 * @property string $AccessKey allows you to specify what Alt-Letter combination will automatically focus that control on the form
 * @property string $BackColor sets the CSS background-color of the control
 * @property string $BorderColor sets the CSS border-color of the control
 * @property string $BorderWidth sets the CSS border-width of the control
 * @property string $BorderStyle is used to set CSS border-style by {@link QBorderStyle}
 * @property string $BorderCollapse    defines the BorderCollapse css style for a table
 * @property string $CssClass sets or returns the CSS class for this control. When setting, if you precede the class name
 *  with a plus sign (+), it will add the class(es) to the currently existing classes, rather than replace them. Can add or
 *  set more than one class at once by separating names with a space.
 * @property string $Cursor is used to set CSS cursor property by {@link QCursor}
 * @property boolean $Display shows or hides the control using the CSS display property.  In either case, the control is
 *  still rendered on the page. See the Visible property if you wish to not render a control.
 * @property string $DisplayStyle is used to set CSS display property by {@link QDisplayStyle}
 * @property boolean $Enabled specifies whether or not this is enabled (it will grey out the control and make it
 *  inoperable if set to true)
 * @property boolean $FontBold sets the font bold or normal
 * @property boolean $FontItalic sets the Font italic or normal
 * @property string $FontNames sets the name of used fonts
 * @property boolean $FontOverline
 * @property string $FontSize sets the font-size of the control
 * @property boolean $FontStrikeout
 * @property boolean $FontUnderline sets the font underlined
 * @property string $ForeColor sets the CSS color property, which controls text color
 * @property string $Height
 * @property string $Left CSS left property
 * @property integer $Opacity sets the opacity of the control (0-100)
 * @property string $Overflow is used to set CSS overflow property by {@link QOverflow}
 * @property string $Position is used to set CSS position property by {@link QPosition}
 * @property integer $TabIndex specifies the index/tab order on a form
 * @property string $ToolTip specifies the text to be displayed when the mouse is hovering over the control
 * @property string $Top
 * @property string $Width
 * @property string $HorizontalAlign sets the CSS text-align property
 * @property string $VerticalAlign sets the CSS vertical-align property
 * @property-write mixed $Padding sets the CSS padding property. Will accepts a string, which is passed verbatim, or
 *  an array, either numerically indexed, in which case it is in top, right, bottom, left order, or keyed with the
 *  names 'top', 'right', 'bottom', 'left'.
 * @property-write mixed $Margin sets the CSS margin property. Will accepts a string, which is passed verbatim, or
 *  an array, either numerically indexed, in which case it is in top, right, bottom, left order, or keyed with the
 *  names 'top', 'right', 'bottom', 'left'
 * @property-write array $Data a key/value array of data-* items to set. Keys can be in camelCase notation, in which case they will be
 *  converted to dashed notation. Use GetDataAttribute() to retrieve the value of a data attribute.
 * @property boolean $NoWrap sets the CSS white-space  property to nowrap
 * @property boolean $ReadOnly is the "readonly" html attribute (making a textbox "ReadOnly" is  similar to setting the textbox to Enabled
 *  Readonly textboxes are selectedable, and their values get posted. Disabled textboxes are not selectabel and values do not post.
 * @property string $AltText text used for 'alt' attribute in images
 */

class QHtmlAttributeManagerBase extends QBaseClass {
	/** @var  array attributes stored in PHP native format so they can be retrieved. Escaping happens when they are drawn. */
	protected $attributes = array();
	protected $styles = array();

	/**
	 * Sets the given html attribute to the given value.
	 * @param $strName
	 * @param $strValue
	 */
	public function SetHtmlAttribute ($strName, $strValue) {
		if (!is_null($strValue)) {
			if (!isset($this->attributes[$strName]) || $this->attributes[$strName] !== $strValue) {
				// only make a change if it has actually changed value.
				$this->attributes[$strName] = $strValue;
				$this->MarkAsModified();
			}
		} else {
			if (isset($this->attributes[$strName])) {
				unset($this->attributes[$strName]);
				$this->MarkAsModified();
			}
		}
	}

	/**
	 * Removes the given html attribute.
	 *
	 * @param $strName
	 */
	public function RemoveHtmlAttribute ($strName) {
		$this->SetHtmlAttribute($strName, null);
	}

	/**
	 * Gets the Html Attribute, or return null if it does not exist.
	 *
	 * @param $strName
	 * @return null
	 */
	public function GetHtmlAttribute ($strName) {
		if (isset ($this->attributes[$strName])) {
			return $this->attributes[$strName];
		}
		else {
			return null;
		}
	}

	/**
	 * Returns an attribute array, with styles rendered.
	 * @param array | null $selection an array of titles of attributes that you want the result to be limited to
	 * @return array
	 */
	public function GetHtmlAttributes($selection = null) {
		$attributes = $this->attributes;
		if ($this->styles) {
			$attributes['style'] = $this->RenderCssStyles();
		}
		if ($selection) {
			$attributes = array_intersect_key($attributes, array_fill_keys($selection, 1));
		}
		return $attributes;
	}

	/**
	 * Returns true if the given attribute is set.
	 *
	 * @param string $strName
	 * @return bool
	 */
	public function HasHtmlAttribute ($strName) {
		return (isset($this->attributes[$strName]));
	}

	/**
	 * Sets the given value as an html "data-*" attribute. The named value will be retrievable in jQuery by using
	 * '.data("$strName");'
	 *
	 * Note: Data name cases are handled specially in jQuery. Names are supposed to only be lower case. jQuery
	 * converts dashed notation to camelCase.
	 *
	 * For example, if your html looks like this:
	 *
	 * <div id='test1' data-test-case="my test"></div>
	 *
	 * You would get that value in jQuery by doing:
	 *
	 * $j('#test1').data('testCase');
	 *
	 * Conversion to special html data-* name formatting is handled here automatically. So if you SetDataAttribute('testCase') here,
	 * you can get it using .data('testCase') in jQuery
	 *
	 * @param $strName
	 * @param $strValue
	 */
	public function SetDataAttribute ($strName, $strValue) {
		$strName = 'data-' . JavaScriptHelper::dataNameFromCamelCase($strName);
		$this->SetHtmlAttribute($strName, $strValue);
	}

	/**
	 * Gets the data-* attribute value that was set previously in PHP.
	 *
	 * Does NOT call into javascript to return a value that was set on the browser side. You need to use another
	 * mechanism to retrieve that.
	 *
	 * @param $strName
	 * @return null
	 */
	public function GetDataAttribute ($strName) {
		$strName = 'data-' . JavaScriptHelper::dataNameFromCamelCase($strName);
		return $this->GetHtmlAttribute($strName);
	}

	/**
	 * Removes the given data attribute.
	 * @param $strName
	 */
	public function RemoveDataAttribute ($strName) {
		$strName = 'data-' . JavaScriptHelper::dataNameFromCamelCase($strName);
		$this->RemoveHtmlAttribute($strName);
	}



	/**
	 * Sets the Css named value to the given property.
	 *
	 * If blnIsLength is true, we assume the value is a unit length specifier, and so send it to the QHtml helper
	 * function, which has the ability to perform arithmetic operations on the old value.
	 *
	 * Will set the value of the parent classes blnModified value if something changes.
	 *
	 * @param string $strName
	 * @param string $strValue
	 * @param bool $blnIsLength true if this is a unit specifier e.g. 2px
	 * @return bool true if the style was actually changed (vs. it was already set that way)
	 */
	public function SetCssStyle ($strName, $strValue, $blnIsLength = false) {
		$ret = false;
		if (!is_null($strValue)) {
			if ($blnIsLength) {
				if (isset($this->styles[$strName])) {
					$oldValue = $this->styles[$strName];
				} else {
					$oldValue = '';
				}
				if (QHtml::SetLength ($oldValue, $strValue)) {
					$ret = true;
					$this->MarkAsModified();
					$this->styles[$strName] = $oldValue; // oldValue was updated
				}
			}
			elseif (!isset($this->styles[$strName]) || $this->styles[$strName] !== $strValue) {
				$this->styles[$strName] = $strValue;
				$this->MarkAsModified();
				$ret = true;
			}
		} else {
			if (isset($this->styles[$strName])) {
				unset($this->styles[$strName]);
				$this->MarkAsModified();
				$ret = true;
			}
		}
		return $ret;
	}

	/**
	 * Removes the given CSS style property.
	 * @param $strName
	 * @return bool true if the style was removed, false if it wasn't there to begin with
	 */
	public function RemoveCssStyle ($strName) {
		return $this->SetCssStyle ($strName, null);
	}

	/**
	 * Return true if the CSS style has been set.
	 * @param $strName
	 * @return bool
	 */
	public function HasCssStyle ($strName) {
		return isset($this->styles[$strName]);
	}

	/**
	 * Retrieves the given CSS style property value.
	 * @param $strName
	 * @return string|null
	 */
	public function GetCssStyle ($strName) {
		if (isset ($this->styles[$strName])) {
			return $this->styles[$strName];
		}
		else {
			return null;
		}
	}

	/**
	 * Sets a box style, like margin or padding. Can accept:
	 *  - string, which will pass the string verbatim to the shortcut prefix (e.g. margin: 0px 1px)
	 *  - array, which can accept two styles:
	 * 		- [top, right, bottom, left], or
	 * 		- ['top'=>top, 'bottom'=>bottom, etc.]
	 *
	 * When passing an array, missing items or null items will be ignored. Also, you can use arithmetic operations
	 * to change the current value (provided you set a previous value).
	 *
	 * For example:
	 * $obj->setCssBoxStyle ('padding', ['right'=>3]);
	 * $obj->setCssBoxStyle ('padding', ['right'=>'*4']);
	 * Will set the padding-right to 12.
	 *
	 * @param string $strPrefix
	 * @param string|array $mixValue
	 */
	public function SetCssBoxValue($strPrefix, $mixValue) {
		if (is_string($mixValue)) {
			// shortcut
			$this->SetCssStyle($strPrefix, $mixValue);
		} elseif (is_array($mixValue)) {
			if (array_key_exists(0, $mixValue)) {
				// top right bottom left, numerically indexed
				if (isset($mixValue[0])) $this->SetCssStyle($strPrefix. '-top', $mixValue[0], true);
				if (isset($mixValue[1])) $this->SetCssStyle($strPrefix. '-right', $mixValue[1], true);
				if (isset($mixValue[2])) $this->SetCssStyle($strPrefix. '-bottom', $mixValue[2], true);
				if (isset($mixValue[3])) $this->SetCssStyle($strPrefix. '-left', $mixValue[3], true);
			} else {
				// assume key/value
				if (isset($mixValue['top'])) $this->SetCssStyle($strPrefix. '-top', $mixValue['top'], true);
				if (isset($mixValue['right'])) $this->SetCssStyle($strPrefix. '-right', $mixValue['right'], true);
				if (isset($mixValue['bottom'])) $this->SetCssStyle($strPrefix. '-bottom', $mixValue['bottom'], true);
				if (isset($mixValue['left'])) $this->SetCssStyle($strPrefix. '-left', $mixValue['left'], true);
			}
		}
	}



	/**
	 * Adds a css class name to the 'class' property. Prevents duplication.
	 * @param string $strNewClass
	 */
	public function AddCssClass($strNewClass) {
		if (!$strNewClass) return;

		$strClasses = $this->GetHtmlAttribute('class');
		if (is_null($strClasses)) {
			$strClasses= '';
		}
		if (QHtml::AddClass($strClasses, $strNewClass)) {
			$this->SetHtmlAttribute('class', $strClasses);
		}
	}

	/**
	 * This will remove a css class name from the 'class' property (if it exists).
	 * @param string $strCssClass
	 */
	public function RemoveCssClass($strCssClass) {
		if (!$strCssClass) return;
		$strClasses = $this->GetHtmlAttribute('class');
		if ($strClasses && QHtml::RemoveClass($strClasses, $strCssClass)) {
			$this->SetHtmlAttribute('class', $strClasses);
		}
	}

	/**
	 * Return true if the given class is in the attribute list.
	 *
	 * @param $strClass
	 * @return bool
	 */
	public function HasCssClass($strClass) {
		if (!isset($this->attributes['class'])) return false;
		$strClasses = explode (' ', $this->attributes['class']);
		return (in_array($strClass, $strClasses));
	}

	/**
	 * Permanently overrides the current styles with a new set of attributes and styles.
	 *
	 * @param QHtmlAttributeManager $objNewStyles
	 */
	protected function Override(QHtmlAttributeManager $objNewStyles) {
		$this->attributes = array_merge($this->attributes, $objNewStyles->attributes);
		$this->styles = array_merge($this->styles, $objNewStyles->styles);
	}

	/**
	 * Mark the parent class as modified. The host class must implement this if this functionality is desired.
	 */
	protected function MarkAsModified() {}

	/**
	 * Returns the html for the attributes. Allows the given arrays to override the attributes and styles before
	 * rendering.
	 * @param null|string 	$attributeOverrides
	 * @param null|string 	$styleOverrides
	 * @return string
	 */
	public function RenderHtmlAttributes($attributeOverrides = null, $styleOverrides = null) {
		$attributes = $this->attributes;
		if ($this->styles || $styleOverrides) {
			$attributes['style'] = $this->RenderCssStyles($styleOverrides);
		}
		if ($attributeOverrides) {
			$attributes = array_merge($attributes, $attributeOverrides);
		}
		return QHtml::RenderHtmlAttributes($attributes);
	}

	/**
	 * Returns the styles rendered as a css style string.
	 * @param null|string 	$styleOverrides
	 * @return string
	 */
	public function RenderCssStyles($styleOverrides = null) {
		$styles = $this->styles;
		if ($styleOverrides) {
			$styles = array_merge($styles, $styleOverrides);
		}
		return QHtml::RenderStyles($styles);
	}

	/**
	 * Helper function to render the current attributes and styles in a tag. Overrides will be merged in with
	 * current values before creating the output, but they will not affect the current values.
	 *
	 * @param $strTag
	 * @param null|array 		$attributeOverrides	key/value pairs of values for attribute overrides
	 * @param null|array 		$styleOverrides		key/value pairs of values for style overrides
	 * @param null|string 		$strInnerHtml		inner html to render. Will be escaped.
	 * @param bool				$blnIsVoidElement	true if it should not have innerHtml or a closing tag.
	 * @return string			HTML out, escaped with HTML entities as needed.
	 */
	protected function RenderTag($strTag, $attributeOverrides = null, $styleOverrides = null, $strInnerHtml = null, $blnIsVoidElement = false) {
		$strAttributes = $this->RenderHtmlAttributes($attributeOverrides, $styleOverrides);
		return QHtml::RenderTag($strTag, $strAttributes, $strInnerHtml, $blnIsVoidElement);
	}


	/**
	 * PHP magic method.
	 *
	 * The attributes include general attributes that span across most kinds of QControls. Attributes that are specific
	 * to a particular kind of tag might appear just in that control's definition.
	 *
	 * @param string $strName
	 * @return mixed
	 * @throws Exception|QCallerException
	 */
	public function __get($strName) {
		switch ($strName) {
			// Styles
			case "BackColor": return $this->GetCssStyle('background-color');
			case "BorderColor": return $this->GetCssStyle('border-color');
			case "BorderStyle": return $this->GetCssStyle('border-style');
			case "BorderWidth": return $this->GetCssStyle('border-width');
			case "BorderCollapse": return $this->GetCssStyle('border-collapse');
			case "Display": return !($this->GetCssStyle('display') === QDisplayStyle::None);
			case "DisplayStyle": return $this->GetCssStyle('display');
			case "FontBold": return $this->GetCssStyle('font-weight') == 'bold';
			case "FontItalic": return $this->GetCssStyle('font-style') == 'italic';
			case "FontNames": return $this->GetCssStyle('font-family');
			case "FontOverline": return $this->GetCssStyle('text-decoration') == 'overline';
			case "FontStrikeout": return $this->GetCssStyle('text-decoration') == 'line-through';
			case "FontUnderline": return $this->GetCssStyle('text-decoration') == 'underline';
			case "FontSize": return $this->GetCssStyle('font-size');
			case "ForeColor": return $this->GetCssStyle('color');
			case "Opacity": return $this->GetCssStyle('opacity');
			case "Cursor": return $this->GetCssStyle('cursor');
			case "Height": return $this->GetCssStyle('height');
			case "Width": return $this->GetCssStyle('width');
			case "Overflow": return $this->GetCssStyle('overflow');
			case "Position": return $this->GetCssStyle('position');
			case "Top": return $this->GetCssStyle('top');
			case "Left": return $this->GetCssStyle('left');
			case "HorizontalAlign": return $this->GetCssStyle('text-align');
			case "VerticalAlign": return $this->GetCssStyle('vertical-align');
			case "Wrap": throw new Exception ("Wrap is deprecated. Use NoWrap instead");
			case "NoWrap": return $this->GetCssStyle('white-space') == 'nowrap';

			// Attributes
			case "CssClass": return $this->GetHtmlAttribute('class');
			case "AccessKey": return $this->GetHtmlAttribute('accesskey');
			case "Enabled": return $this->GetHtmlAttribute('disabled') === null;
			case "TabIndex": return $this->GetHtmlAttribute('tabindex');
			case "ToolTip": return $this->GetHtmlAttribute('title');
			case "ReadOnly": return $this->HasHtmlAttribute('readonly');
			case "AltText": return $this->HasHtmlAttribute('alt');

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}


	/**
	 * PHP magic method
	 *
	 * @param string $strName
	 * @param string $mixValue
	 *
	 * @return mixed
	 * @throws Exception|QCallerException|QInvalidCastException
	 */
	public function __set($strName, $mixValue) {
		switch ($strName) {
			// Styles
			case "BackColor":
				try {
					$this->SetCssStyle('background-color', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "BorderColor":
				try {
					$this->SetCssStyle('border-color', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "BorderStyle":
				try {
					$this->SetCssStyle('border-style', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "BorderWidth":
				try {
					$this->SetCssStyle('border-width', QType::Cast($mixValue, QType::String), true);
					if (!$this->HasCssStyle ('border-style')) {
						$this->SetCssStyle('border-style', 'solid');
					}
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "BorderCollapse":
				try {
					$this->SetCssStyle('border-collapse', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "Display": // QControl changes the meaning of this to a boolean
			case "DisplayStyle":
				if (is_bool($mixValue)) {
					if ($mixValue) {
						$this->RemoveCssStyle('display'); // do the default
					}
					else {
						$this->SetCssStyle('display', QDisplayStyle::None);
					}
				} else {
					try {
						$this->SetCssStyle('display', QType::Cast($mixValue, QType::String));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				}

			case "FontBold":
				try {
					$this->SetCssStyle('font-weight', QType::Cast($mixValue, QType::Boolean) ? 'bold' : null);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "FontItalic":
				try {
					$this->SetCssStyle('font-style', QType::Cast($mixValue, QType::Boolean) ? 'italic' : null);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "FontNames":
				try {
					$this->SetCssStyle('font-family', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "FontOverline":
				try {
					$this->SetCssStyle('text-decoration', QType::Cast($mixValue, QType::Boolean) ? 'overline' : null);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "FontStrikeout":
				try {
					$this->SetCssStyle('text-decoration', QType::Cast($mixValue, QType::Boolean) ? 'line-through' : null);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "FontUnderline":
				try {
					$this->SetCssStyle('text-decoration', QType::Cast($mixValue, QType::Boolean) ? 'underline' : null);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "FontSize":
				try {
					$this->SetCssStyle('font-size', QType::Cast($mixValue, QType::String), true);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "ForeColor":
				try {
					$this->SetCssStyle('color', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "Opacity":
				try {
					$mixValue = QType::Cast($mixValue, QType::Integer);
					if (($mixValue < 0) || ($mixValue > 100)) {
						throw new QCallerException('Opacity must be an integer value between 0 and 100');
					}
					$this->SetCssStyle('opacity', $mixValue);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "Cursor":
				try {
					$this->SetCssStyle('cursor', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "Height":
				try {
					$this->SetCssStyle('height', QType::Cast($mixValue, QType::String), true);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "Width":
				try {
					$this->SetCssStyle('width', QType::Cast($mixValue, QType::String), true);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "Overflow":
				try {
					$this->SetCssStyle('overflow', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "Position":
				try {
					$this->SetCssStyle('position', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "Top":
				try {
					$this->SetCssStyle('top', QType::Cast($mixValue, QType::String), true);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "Left":
				try {
					$this->SetCssStyle('left', QType::Cast($mixValue, QType::String), true);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "HorizontalAlign":
				try {
					$this->SetCssStyle('text-align', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "VerticalAlign":
				try {
					$this->SetCssStyle('vertical-align', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "Wrap": // Wrap is now an actual attribute. Original developer used Wrap instead of NoWrap, not anticipating future change to HTML
				throw new QCallerException ("Wrap is deprecated. Use NoWrap instead");
				break;

			case "NoWrap":
				try {
					$this->SetCssStyle('white-space', QType::Cast($mixValue, QType::Boolean) ? 'nowrap' : null);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "Padding": // top right bottom left
				$this->SetCssBoxValue('padding', $mixValue);
				break;

			case "Margin": // top right bottom left
				$this->SetCssBoxValue('margin', $mixValue);
				break;

			// Attributes
			case "CssClass":
				try {
					$strCssClass = QType::Cast($mixValue, QType::String);
					if (substr($strCssClass, 0, 1) == '+') {
						$this->AddCssClass(substr($strCssClass, 1));
					} else {
						$this->SetHtmlAttribute('class', QType::Cast($mixValue, QType::String));
					}
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "AccessKey":
				try {
					$this->SetHtmlAttribute('accesskey', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "Enabled":
				try {
					$this->SetHtmlAttribute('disabled',  QType::Cast($mixValue, QType::Boolean) ? null : 'disabled');
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			/* case "Required": Not supported consistently by browsers. We handle this inhouse */

			case "TabIndex":
				try {
					$this->SetHtmlAttribute('tabindex', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			case "ToolTip":
				try {
					$this->SetHtmlAttribute('title', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "Data":
				try {
					$dataArray = QType::Cast($mixValue, QType::ArrayType);
					foreach ($dataArray as $key=>$value) {
						$this->SetDataAttribute($key, $value);
					}
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "ReadOnly":
				try {
					$this->SetHtmlAttribute('readonly',  QType::Cast($mixValue, QType::Boolean) ? 'readonly' : null);
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			case "AltText":
				try {
					$this->SetHtmlAttribute('alt', QType::Cast($mixValue, QType::String));
					break;
				} catch (QInvalidCastException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			default:
				try {
					return parent::__set($strName, $mixValue);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
		return true;
	}
}