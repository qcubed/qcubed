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
		if (!(objControl = qcubed.getControl(mixControl))) 
		{
            //maybe it doesn't have a child control, just the wrapper
			if (typeof(mixControl) == 'string')
		    	return this.getControl(mixControl + "_ctl");
		    	
		    return;
		}

		if (objControl)
			return this.getControl(objControl.id + "_ctl");			
		else
			return null;
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

		// Link the Wrapper and the Control together
		var objWrapper = this.getWrapper(mixControl);
		if(!objWrapper) return;

	    if(objControl !== null)
    		objControl.wrapper = objWrapper;
    	
		objWrapper.control = objControl;

		// Add the wrapper to the global qcodo wrappers array
		qcubed.wrappers[objWrapper.id] = objWrapper;


		// Create New Methods, etc.
		// Like: objWrapper.something = xyz;

		// Updating Style-related Things
		objWrapper.updateStyle = function(strStyleName, strNewValue) {
		    if(this.control === null)
		    {
			    switch (strStyleName) {
				    case "display":
					    if (strNewValue) {
						    objWrapper.style.display = "inline";
					    } else {
						    objWrapper.style.display = "none";
					    };
					    break;
				    default:
					    if (qcubed.javascriptWrapperStyleToQcodo[strStyleName]) {
						    this.style[strStyleName] = strNewValue;
					    };
					    break;
			    };
    		    return;
		    }
		
			var objControl = this.control;
			
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
					};
					break;
				
				case "displayStyle":
					objControl.style.display = strNewValue;
					qcubed.recordControlModification(objControl.id, "DisplayStyle", strNewValue);
					break;

				case "display":
					if (strNewValue) {
						objWrapper.style.display = "inline";
						qcubed.recordControlModification(objControl.id, "Display", "1");
					} else {
						objWrapper.style.display = "none";
						qcubed.recordControlModification(objControl.id, "Display", "0");
					};
					break;

				case "enabled":
					if (strNewValue) {
						objWrapper.control.disabled = false;
						qcubed.recordControlModification(objControl.id, "Enabled", "1");
					} else {
						objWrapper.control.disabled = true;
						qcubed.recordControlModification(objControl.id, "Enabled", "0");
					};
					break;
					
				case "width":
				case "height":
					objControl.style[strStyleName] = strNewValue;
					if (qcubed.javascriptStyleToQcodo[strStyleName])
						qcubed.recordControlModification(objControl.id, qcubed.javascriptStyleToQcodo[strStyleName], strNewValue);
					if (objWrapper.handle)
						objWrapper.updateHandle();
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
					};
					break;
			};
		};

		// Positioning-related functions

		objWrapper.getAbsolutePosition = function() {
			var intOffsetLeft = 0;
			var intOffsetTop = 0;

			var objControl = this.control;

			while (objControl) {
				// If we are IE, we don't want to include calculating
				// controls who's wrappers are position:relative
				if ((objControl.wrapper) && (objControl.wrapper.style.position == "relative")) {
					
				} else {
					intOffsetLeft += objControl.offsetLeft;
					intOffsetTop += objControl.offsetTop;
				};
				objControl = objControl.offsetParent;
			};

			return {x:intOffsetLeft, y:intOffsetTop};
		};

		objWrapper.setAbsolutePosition = function(intNewX, intNewY, blnBindToParent) {
			var objControl = this.offsetParent;

			while (objControl) {
				intNewX -= objControl.offsetLeft;
				intNewY -= objControl.offsetTop;
				objControl = objControl.offsetParent;
			};

			if (blnBindToParent) {
				if (this.parentNode.nodeName.toLowerCase() != 'form') {
					// intNewX and intNewY must be within the parent's control
					intNewX = Math.max(intNewX, 0);
					intNewY = Math.max(intNewY, 0);

					intNewX = Math.min(intNewX, this.offsetParent.offsetWidth - this.offsetWidth);
					intNewY = Math.min(intNewY, this.offsetParent.offsetHeight - this.offsetHeight);
				};
			};

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
			if (strEnableOrDisable) {
				if (strEnableOrDisable == "enable")
					this.updateStyle("enabled", true);
				else
					this.updateStyle("enabled", false);
			} else
				this.updateStyle("enabled", (this.control.disabled) ? true : false);
		};

		objWrapper.registerClickPosition = function(objEvent) {			
			var intX = objEvent.pageX - this.control.offsetLeft;
			var intY = objEvent.pageY - this.control.offsetTop;
			
			$j('#' + this.control.id + "_x").val(intX);
			$j('#' + this.control.id + "_y").val(intY);
		};
		
		// Focus
		objWrapper.focus = function() {
			$j('#' + this.control.id).focus();	
		};
		
		// Select All (will only work for textboxes only)
		objWrapper.select = function() {
			$j('#' + this.control.id).select();
		};

		// Blink
		objWrapper.blink = function(strFromColor, strToColor) {		
			$j('#' + this.control.id).css('background-color', '' + strFromColor);
			$j('#' + this.control.id).animate({ backgroundColor: '' + strToColor }, 500)
		};
	}

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
