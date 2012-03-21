/////////////////////////////////
// Controls-related functionality
/////////////////////////////////

	qcubed.getControl = function(mixControl) {
		if (typeof(mixControl) == 'string')
			return document.getElementById(mixControl);
		else
			return mixControl;
	};

	qcubed.getWrapper = function(mixControl) {
		var objControl;
		if (!(objControl = qcubed.getControl(mixControl))) {
            //maybe it doesn't have a child control, just the wrapper
			if (typeof(mixControl) == 'string')
		    	return this.getControl(mixControl + "_ctl");
			return null;
		} else if (objControl.wrapper) {
			return objControl.wrapper;
		}

		return objControl; //a wrapper-less control, return the control itself
	};



/////////////////////////////
// Register Control - General
/////////////////////////////
	
	qcubed.controlModifications = new Object;
	qcubed.javascriptStyleToQcodo = new Object;
	qcubed.javascriptStyleToQcodo["backgroundColor"] = "BackColor";
	qcubed.javascriptStyleToQcodo["borderColor"] = "BorderColor";
	qcubed.javascriptStyleToQcodo["borderStyle"] = "BorderStyle";
	qcubed.javascriptStyleToQcodo["border"] = "BorderWidth";
	qcubed.javascriptStyleToQcodo["height"] = "Height";
	qcubed.javascriptStyleToQcodo["width"] = "Width";
	qcubed.javascriptStyleToQcodo["text"] = "Text";

	qcubed.javascriptWrapperStyleToQcodo = new Object;
	qcubed.javascriptWrapperStyleToQcodo["position"] = "Position";
	qcubed.javascriptWrapperStyleToQcodo["top"] = "Top";
	qcubed.javascriptWrapperStyleToQcodo["left"] = "Left";

	qcubed.recordControlModification = function(strControlId, strProperty, strNewValue) {
		if (!qcubed.controlModifications[strControlId])
			qcubed.controlModifications[strControlId] = new Object;
		qcubed.controlModifications[strControlId][strProperty] = strNewValue;	
	};

	qcubed.registerControl = function(mixControl) {
		var objControl; 
		objControl = qcubed.getControl(mixControl);

		if (!objControl)
			return;

		// Link the Wrapper and the Control together
		var objWrapper = this.getControl(objControl.id + "_ctl");
		if (!objWrapper) {
			objWrapper = objControl; //wrapper-less control
		} else {
			objWrapper.control = objControl;
    		objControl.wrapper = objWrapper;
    	
			// Add the wrapper to the global qcodo wrappers array
			qcubed.wrappers[objWrapper.id] = objWrapper;
		}
	
	
		// Create New Methods, etc.
		// Like: objWrapper.something = xyz;
	
		// Updating Style-related Things
		objWrapper.updateStyle = function(strStyleName, strNewValue) {
	
			var objControl = (this.control) ? this.control:this;
			
			switch (strStyleName) {
				case "className":
					objControl.className = strNewValue;
					qcubed.recordControlModification(objControl.id, "CssClass", strNewValue);
					break;
					
				case "parent":
					if (strNewValue) {
						var objNewParentControl = qcubed.getControl(strNewValue);
						objNewParentControl.appendChild(this);
						qcubed.recordControlModification(objControl.id, "Parent", strNewValue);
					} else {
						var objParentControl = this.parentNode;
						objParentControl.removeChild(this);
						qcubed.recordControlModification(objControl.id, "Parent", "");
					}
					break;
				
				case "displayStyle":
					objControl.style.display = strNewValue;
					qcubed.recordControlModification(objControl.id, "DisplayStyle", strNewValue);
					break;

				case "display":
					if (strNewValue) {
						$j(this).show();
						qcubed.recordControlModification(objControl.id, "Display", "1");
					} else {
						$j(this).hide();
						qcubed.recordControlModification(objControl.id, "Display", "0");
					}
					break;

				case "enabled":
					if (strNewValue) {
						objControl.disabled = false;
						qcubed.recordControlModification(objControl.id, "Enabled", "1");
					} else {
						objControl.disabled = true;
						qcubed.recordControlModification(objControl.id, "Enabled", "0");
					}
					break;
					
				case "width":
				case "height":
					objControl.style[strStyleName] = strNewValue;
					if (qcubed.javascriptStyleToQcodo[strStyleName])
						qcubed.recordControlModification(objControl.id, qcubed.javascriptStyleToQcodo[strStyleName], strNewValue);
					if (this.handle)
						this.updateHandle();
					break;

				case "text":
					objControl.innerHTML = strNewValue;
					qcubed.recordControlModification(objControl.id, "Text", strNewValue);
					break;

				default:
					if (qcubed.javascriptWrapperStyleToQcodo[strStyleName]) {
						this.style[strStyleName] = strNewValue;
						qcubed.recordControlModification(objControl.id, qcubed.javascriptWrapperStyleToQcodo[strStyleName], strNewValue);
					} else {
						objControl.style[strStyleName] = strNewValue;
						if (qcubed.javascriptStyleToQcodo[strStyleName])
							qcubed.recordControlModification(objControl.id, qcubed.javascriptStyleToQcodo[strStyleName], strNewValue);
					}
					break;
			}
		};

		// Positioning-related functions

		objWrapper.getAbsolutePosition = function() {
			var objControl = (this.control) ? this.control:this;
			var pos = $j(objControl).offset();
			return {x:pos.left, y:pos.top};
		};

		objWrapper.setAbsolutePosition = function(intNewX, intNewY, blnBindToParent) {
			var objControl = this.offsetParent;

			while (objControl) {
				intNewX -= objControl.offsetLeft;
				intNewY -= objControl.offsetTop;
				objControl = objControl.offsetParent;
			}

			if (blnBindToParent) {
				if (this.parentNode.nodeName.toLowerCase() != 'form') {
					// intNewX and intNewY must be within the parent's control
					intNewX = Math.max(intNewX, 0);
					intNewY = Math.max(intNewY, 0);

					intNewX = Math.min(intNewX, this.offsetParent.offsetWidth - this.offsetWidth);
					intNewY = Math.min(intNewY, this.offsetParent.offsetHeight - this.offsetHeight);
				}
			}

			this.updateStyle("left", intNewX + "px");
			this.updateStyle("top", intNewY + "px");
		};

		// Toggle Display / Enabled
		objWrapper.toggleDisplay = function(strShowOrHide) {
			// Toggles the display/hiding of the entire control (including any design/wrapper HTML)
			// If ShowOrHide is blank, then we toggle
			// Otherwise, we'll execute a "show" or a "hide"
			if (strShowOrHide) {
				if (strShowOrHide == "show")
					this.updateStyle("display", true);
				else
					this.updateStyle("display", false);
			} else
				this.updateStyle("display", (this.style.display == "none") ? true : false);
		};

		objWrapper.toggleEnabled = function(strEnableOrDisable) {
			var objControl = (this.control) ? this.control:this;
			if (strEnableOrDisable) {
				if (strEnableOrDisable == "enable")
					this.updateStyle("enabled", true);
				else
					this.updateStyle("enabled", false);
			} else
				this.updateStyle("enabled", (objControl.disabled) ? true : false);
		};

		objWrapper.registerClickPosition = function(objEvent) {			
			var objControl = (this.control) ? this.control:this;
			var intX = objEvent.pageX - objControl.offsetLeft;
			var intY = objEvent.pageY - objControl.offsetTop;
			
			$j('#' + objControl.id + "_x").val(intX);
			$j('#' + objControl.id + "_y").val(intY);
		};
		
		// Focus
		if (objWrapper.control) {
			objWrapper.focus = function() {
				$j(this.control).focus();
			};
		}
		
		// Select All (will only work for textboxes only)
		if (objWrapper.control) {
			objWrapper.select = function() {
				$j(this.control).select();
			};
		} 

		// Blink
		objWrapper.blink = function(strFromColor, strToColor) {		
			var objControl = (this.control) ? this.control:this;
			$j(objControl).css('background-color', '' + strFromColor);
			$j(objControl).animate({backgroundColor: '' + strToColor}, 500);
		};
	};

	qcubed.registerControlArray = function(mixControlArray) {
		var intLength = mixControlArray.length;
		for (var intIndex = 0; intIndex < intLength; intIndex++)
			qcubed.registerControl(mixControlArray[intIndex]);
	};



//////////////////
// Qcodo Shortcuts
//////////////////

	qc.getC = qcubed.getControl;
	qc.getW = qcubed.getWrapper;
	qc.regC = qcubed.registerControl;
	qc.regCA = qcubed.registerControlArray;
