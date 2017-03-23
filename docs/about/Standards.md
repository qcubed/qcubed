#Coding Standards

## Introduction

There are several good reasons to have strict coding standards for a web development project. They include:

* Standards enforce consistency
* Any programmer can easily read the code
* New programmers can ramp up quickly
* Programmers make fewer mistakes

For these reasons, we should use strict coding standards for all code, including CSS, HTML, Javascript, and PHP. Keep in mind, this is a working document. If for any reason a member of the team has a problem with the standard, we should discuss and possibly revamp the requirement.

## v4 Standards
###PHP Standards
Our current standards are out of date. Since the initial introduction of QCubed, parts of the PHP community have come up with a series of coding standards as best practices for PHP code, called PSRs, and a goal of version 4 is to move to these standards. This is going to take a major rework of the structure of QCubed, and will likely require some automated tools to make it easier to transition. At a minimum, v4 will (hopefully) implement the PSR-1, PSR-2 and PSR-4 standards for PHP code. Keep that in mind while you make changes.
###JavaScript Standards
QCubed has some javascript in it, but we do not currently have a javascript standard. JavaScript coding in general has been continually in flux as of late. If you are a JavaScript guru, feel free to post an issue to propose a v4 standard. Strict mode, TypeScript, ES6, JQuery, the JQueryUI widget factory, and backwards compatibility are all issues we need to resolve.

## v3 Standards
The following is are the standards we have been operating under.

Use a single tab for indentation (no spaces please)

Linebreaks are Unix style "\n".

Utilize the traditional UNIX style of including the opening brace on the same line as the expression, with the closing brace on it's own line.
```php
if ($intAssetId == 123) {
  ...
}

switch ($blnAssetFlag) {
  case true:
    ...
    break;
  case false:
    ...
    break;
}
```

### General
No all-uppercase abbreviations. For example, `GetHTMLStatic()` is incorrect. Instead, use `GetHtmlStatic()`.

PHP code must always be delimited by the full-form, standard PHP tags `<?php ... ?>`

Private variables are in camel case Hungarian notation
```php
$strHello = "Hello";
$intIndex = 0;
$arrStuff = array();
```
### Common prefixes
* str = String
* int = Integer
* flt = Float
* obj = Object
* arr = Array
* bln = Boolean
* pnl = QPanel
* lst = QListBox
* dtg = QDataGrid
* dtr = QDataRepeater
* txt = QTextBox
* btn = QButton
* lbl = QLabel
* ctl = QControl (Generic control)

### Global variables

Global Constants will be in all uppercase notation.

```php
define('SERVER_INSTANCE', 'dev');
define('__PREPEND_INCLUDED__', 1);
```

### Classes
PHP files that are a single class should have the extension .class.php

Class names are super-camel cased
```php
class Asset extends AssetBase {...}
```

Public properties are super-camel cased
```php
$objAsset = new Asset();
$objAsset->AssetId = 42;
```

### Documentation and Comments
We use PHPDoc to generate API documentation from the code comments, please thoroughly comment the *why* and not the *what*.

For private variables, use a simple `//` comment for developers.

All public methods and classes must contain a docblock comment

Classes:
```php
/**
* Short description
* Multiple line detailed description.
* The handling of line breaks and HTML is up to the renderer.
* Order: short description - detailed description - doc tags.
*
* @category QCubed
* @package QCodeGen
* @author Your name here
* @version 1.0.0
*/
```

Methods:
```php
/**
* Generates an asset code based on AssetId
*
* @param integer $intAssetId This is the asset_id needed to generate the asset code
* @param boolean $blnAssetFlag This tells us if we're generating an asset code, defaults true
* @return string $strAssetCode - This is the generated asset code
*/
public function GenerateAssetCode($intAssetId, $blnAssetFlag = true) {
  ...
  return $strAssetCode;
}
```
In addition to the above rules, here are a few important points about documentation:
* **Commenting Properties**: Properties are different from variables of an Object. You read and write variable values as well as property values by using the ```->``` operator for an object. However, properties are not declared anywhere in the class and are accessed using the ```__set``` and ```__get``` magic methods of PHP. When commenting properties for PHPDoc, comment them at the top. Properties that are read-only (ones which are present in the ```__get``` method but are not there in the ```__set``` method) should be declared using ```@property-read``` annotation. For a read-write property, use a ```@property``` annotation. The following example shows the format about how to PHPDoc-comment a property for a class: 
```
/**
 * RectangleClass
 * Documentation comment for RectangleClass
 * 
 * @property-read float $Area Get the area of a rectangle ($fltVolume = $objRectangle->Area)
 * @property float $Length Set/Get the Length of a rectangle
 */
class Rectangle extends Shape {
/** @var float Length of the Rectangle */
protected $fltLength;
/** @var float Area of the Rectangle */
protected $fltArea;

/**
 * PHP __set magic method implementation
 * @param string $strName Name of the property
 * @param mixed $mixValue Value of the property
 */
public function __set($strName, $mixValue) {
        switch ($strName) {
                // some code
                case 'Length': $this->fltLength = $mixValue;
                // some code
        }
}

/**
 * PHP __get magic method implementation
 * @param string $strName Name of the property
 */
public function __get($strName, $mixValue) {
        switch ($strName) {
                // some code
                case 'Length': return $this->fltLength;
                case 'Area': return $this->fltArea;
                // some code
        }
}
// Class ends
}
```
* **Commenting Arrays**: PHP is very lenient when it comes to the _type_ of data. This can be good but when trying to determine the data type in code, it can get very difficult to understand. Only the coder can be sure of what he was trying to do with an array variable when he used it in code. In addition to being very clear in code and following naming conventions, ensure that you have commented an array variable well enough. Ensure that you have documented any array parameters and variable declarations well enough and have put comments inside the code where required. When you are sure about the contents of the first dimension of the array, instead of writing ```array``` in front of ```@var``` or ```@param```, use the data type of the entities in the first dimension of the array. An example should help you get started:
```
// Assume that we are declaring these variables inside a class (most QCubed code is OO)
/** @var string[] An array of Names */
protected $arrNames;
/** @var QControl[] An array of controls */
protected $arrControls;
/** @var string[]|int[] An array of IDs which can be either strings or integers */
protected $arrIds;
/** @var array Contents of this array can be of mixed type */
protected $arrRandomContents;

public function DoSomething(){
        foreach($this->arrControls as $ctlQControl) {
                /*
                 * PHPDoc comment above for arrControls will help the IDE and 
                 * other developers here when they try to find about the $ctlQControl
                 * and $this->$arrControls
                 */
                $ctlQControl->Render(false);
        }
}
```

## Javascript

### Comments
Javascript comments should follow the same general guidelines that apply to PHP.

### jQuery
jQuery is in no-conflict mode. Use `jQuery()`, or `$j()`.
An alternative is to work in a self-executing function, passing jQuery as $, this method will be more familiar to some.
```javascript
(function ($){
...
}(jQuery, undefined));
```

A javascript variable that returns a jQuery collection should begin with $ to indicate chainability
```javascript
$divs = jQuery('div');
$divs.hide();
```

## HTML

We don't enforce any particular doctype, although the framework itself strives to be HTML5. It should also validate XHTML 1.0 Transitional.

Close all tags. Even self-closing tags. `<hr />`,`<br />`, `<input />`

## CSS

The framework itself should only contain the CSS for the drafts, codegen, examples, error and start page. This should be consistent with the default jQuery UI theme and should be as slim as possible.

Do not over-qualify selectors, use a unique class name whenever possible. In keeping with jQuery UI, namespace with hyphens.
```css
/* This is good. */
.ui-datagrid-row { ... }

/* This is (extremely) over-qualified. */
body h2.ui-widget-header span { ... }
```