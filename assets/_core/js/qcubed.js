// BEWARE: this cleares the $ variable!
var $j = jQuery.noConflict();

$j.fn.extend({
	wait: function(time, type) {
		time = time || 1000;
		type = type || "fx";
		return this.queue(type, function() {
			var self = this;
			setTimeout(function() {
				$j(self).dequeue();
			}, time);
		});
	}
});

/*
 * Queued Ajax requests.
 * A new Ajax request won't be started until the previous queued
 * request has finished.
 */
$j.ajaxQueue = function(o){
	if (typeof $j.ajaxq == "undefined") {
		$j.ajax( o );
	} else {
		// see http://code.google.com/p/jquery-ajaxq/ for details
		$j.ajaxq( "qcu.be", o );
	}
};


/*
 * Synced Ajax requests.
 * The Ajax request will happen as soon as you call this method, but
 * the callbacks (success/error/complete) won't fire until all previous
 * synced requests have been completed.
 */
$j.ajaxSync = function(o){
	var fn = $j.ajaxSync.fn, data = $j.ajaxSync.data, pos = fn.length;

	fn[ pos ] = {
		error: o.error,
		success: o.success,
		complete: o.complete,
		done: false
	};

	data[ pos ] = {
		error: [],
		success: [],
		complete: []
	};

	o.error = function(){ data[ pos ].error = arguments; };
	o.success = function(){ data[ pos ].success = arguments; };
	o.complete = function(){
		data[ pos ].complete = arguments;
		fn[ pos ].done = true;

		if ( pos == 0 || !fn[ pos-1 ] )
			for ( var i = pos; i < fn.length && fn[i].done; i++ ) {
				if ( fn[i].error ) fn[i].error.apply( $j, data[i].error );
				if ( fn[i].success ) fn[i].success.apply( $j, data[i].success );
				if ( fn[i].complete ) fn[i].complete.apply( $j, data[i].complete );

				fn[i] = null;
				data[i] = null;
			}
	};

	return $j.ajax(o);
};

$j.ajaxSync.fn = [];
$j.ajaxSync.data = [];

///////////////////////////////////////////////////
// The QCubed Object is used for everything in Qcodo
///////////////////////////////////////////////////
	var qcubed = {

		recordControlModification: function (strControlId, strProperty, strNewValue) {
			if (!qcubed.controlModifications[strControlId])
				qcubed.controlModifications[strControlId] = new Object;
			qcubed.controlModifications[strControlId][strProperty] = strNewValue;
		},

		postBack: function(strForm, strControl, strEvent, mixParameter) {
			var strForm = $j("#Qform__FormId").attr("value");
			var objForm = $j('#' + strForm);

			if (mixParameter && (typeof mixParameter !== "string")) {
				mixParameter = $j.param({ "Qform__FormParameter" : mixParameter });
				objForm.append('<input type="hidden" name="Qform__FormParameterType" value="obj">');
			}
			
			$j('#Qform__FormControl').val(strControl);
			$j('#Qform__FormEvent').val(strEvent);
			$j('#Qform__FormParameter').val(mixParameter);
			$j('#Qform__FormCallType').val("Server");
			$j('#Qform__FormUpdates').val(this.formUpdates());
			$j('#Qform__FormCheckableControls').val(this.formCheckableControls(strForm, "Server"));

			// have $j trigger the submit event (so it can catch all submit events)
			objForm.trigger("submit");
		},

		formUpdates: function() {
			var strToReturn = "";
			for (var strControlId in qcubed.controlModifications)
				for (var strProperty in qcubed.controlModifications[strControlId])
					strToReturn += strControlId + " " + strProperty + " " + qcubed.controlModifications[strControlId][strProperty] + "\n";
			qcubed.controlModifications = new Object;
			return strToReturn;
		},

		formCheckableControls: function(strForm, strCallType) {

			// Select the QCubed Form
			var objFormElements = $j('#' + strForm).find('input,select,textarea');
			var strToReturn = "";

			objFormElements.each(function(i) {
				if ((($j(this).attr("type") == "checkbox") ||
					 ($j(this).attr("type") == "radio")) &&
					((strCallType == "Ajax") ||
					(!$j(this).attr("disabled")))) {

					var strControlId = $j(this).attr("id");

					// RadioButtonList or CheckBoxList
					if (strControlId.indexOf('_') >= 0) {
						if (strControlId.indexOf('_0') >= 0)
							strToReturn += " " + strControlId.substring(0, strControlId.length - 2);

					// Standard Radio or Checkbox
					} else {
						strToReturn += " " + strControlId;
					}
				}
			});

			if (strToReturn.length > 0)
				return strToReturn.substring(1);
			else
				return "";
		},
		
		getPostData: function(strForm, strControl, strEvent, mixParameter, strWaitIconControlId) {
			var objFormElements = $j('#' + strForm).find('input,select,textarea');
			var strPostData = '';

			if (mixParameter && (typeof mixParameter !== "string")) {
				strPostData = $j.param({ "Qform__FormParameter" : mixParameter });
				objFormElements = objFormElements.not("#Qform__FormParameter");
			} else {
				$j('#Qform__FormParameter').val(mixParameter);
			}
			
			$j('#Qform__FormControl').val(strControl);
			$j('#Qform__FormEvent').val(strEvent);
			$j('#Qform__FormCallType').val("Ajax");
			$j('#Qform__FormUpdates').val(this.formUpdates());
			$j('#Qform__FormCheckableControls').val(this.formCheckableControls(strForm, "Ajax"));

			objFormElements.each(function () {
				var strType = $j(this).attr("type");
				if (strType == undefined) strType = this.type;
				var strControlId = $j(this).attr("id");
				switch (strType) {
					case "checkbox":
					case "radio":
						if ($j(this).attr("checked")) {
							var strTestName;
							var bracketIndex = $j(this).attr("name").indexOf('[');
							
							if (bracketIndex > 0) {
								strTestName = $j(this).attr("name").substring (0, bracketIndex) + '_';
							} else {
								strTestName = $j(this).attr("name") + "_";
							}
							
							if (strControlId.substring(0, strTestName.length) == strTestName)
								// CheckboxList or RadioButtonList
								strPostData += "&" + $j(this).attr("name") + "=" + strControlId.substring(strTestName.length);
							else
								strPostData += "&" + strControlId + "=" + $j(this).val();
						}
						break;

					case "select-multiple":
						var blnOneSelected = false;
						$j(this).find(':selected').each (function (i) {
							strPostData += "&" + $j(this).parents("select").attr("name") + "=";
							strPostData += $j(this).val();
						});
						break;

					default:
						strPostData += "&" + strControlId + "=";

						// For Internationalization -- we must escape the element's value properly
						var strPostValue = $j(this).val();
						if (strPostValue) {
							strPostValue = strPostValue.replace(/\%/g, "%25");
							strPostValue = strPostValue.replace(/&/g, escape('&'));
							strPostValue = strPostValue.replace(/\+/g, "%2B");
						}
						strPostData += strPostValue;
						break;
				}
			});
			return strPostData;
		},
		
		postAjax: function(strForm, strControl, strEvent, mixParameter, strWaitIconControlId) {
			var objForm = $j('#' + strForm);
			var strFormAction = objForm.attr("action");
			var qFormParams = {};
			var strPostData;
			
			qFormParams['form'] = strForm;
			qFormParams['control'] = strControl;
			qFormParams['event'] = strEvent;
			qFormParams['param'] = mixParameter;
			qFormParams['waitIcon'] = strWaitIconControlId;

			if (strWaitIconControlId) {
				this.objAjaxWaitIcon = this.getWrapper(strWaitIconControlId);
				if (this.objAjaxWaitIcon)
					this.objAjaxWaitIcon.style.display = 'inline';
			}
			
			// Use a modified ajax queue so ajax requests happen synchronously
			$j.ajaxQueue({
				url: strFormAction,
				type: "POST",
				qFormParams: qFormParams,
				fnInit: function (o) {	
					// Get the data at the last possible instant in case the formstate changes between ajax calls
					o.data = qcubed.getPostData(
							o.qFormParams['form'],
							o.qFormParams['control'],
							o.qFormParams['event'],
							o.qFormParams['param'],
							o.qFormParams['waitIcon']);
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					var result = XMLHttpRequest.responseText;
					if (XMLHttpRequest.status != 0 || result.length > 0) {
						if (result.substr(0,6) == '<html>') {
                            alert("An error occurred during AJAX Response parsing.\r\n\r\nThe error response will appear in a new popup.");
                            var objErrorWindow = window.open('about:blank', 'qcubed_error','menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=700,left=50,top=50');
                            objErrorWindow.focus();
                            objErrorWindow.document.write(result);
                            return false;
						} else {
							var dialog = $j('<div id="Qcubed_AJAX_Error"></div>')
									.html(result)
									.dialog({
										modal: true,
										height: 200,
										width: 400,
										autoOpen: true,
										title: 'An Error Occurred',
										buttons: {
											Ok: function() {
												$j(this).dialog("close");
											}
										}
									});
							dialog.dialog('open');
							return false;
						}
					}
				},
				success: function (xml) {
					$j(xml).find('control').each(function() {
						var strControlId = '#' + $j(this).attr("id");
						var strControlHtml = $j(this).text();

						if (strControlId == "#Qform__FormState") {
							$j(strControlId).val(strControlHtml);
						} else {
							control = $j(strControlId);
							if (control.length != 0 && !control.get(0).wrapper) {
								//remove related controls (error, name ...) for wrapper-less controls
								if($j(this).attr("data-hasrel") != undefined) {
									//ensure that the control is not wrapped in an element related to it (it would be removed)
									var relParent = control.parents("[data-rel='" + strControlId + "']").last();
									if(relParent.length > 0)
										control.insertBefore(relParent);
									$j("[data-rel='" + strControlId + "']").remove();
								}
								
								control.replaceWith(strControlHtml); 
							
							} else {
								$j(strControlId + '_ctl').html(strControlHtml);
							}
						}
					});
					var strCommands = [];
					$j(xml).find('command').each(function() {
						strCommands.push($j(this).text());
					});
					eval(strCommands.join(''));
					if (qcubed.objAjaxWaitIcon)
						$j(qcubed.objAjaxWaitIcon).hide();
				}
			});

		},

		initialize: function() {



		////////////////////////////////
		// Browser-related functionality
		////////////////////////////////

			this.loadJavaScriptFile = function(strScript, objCallback) {
				if (strScript.indexOf("/") == 0) {
					strScript = qc.baseDir + strScript;
				} else if (strScript.indexOf("http") != 0) {
					strScript = qc.jsAssets + "/" + strScript;
				}
				$j.ajax({
					url: strScript,
					success: objCallback,
					dataType: "script",
					cache: true
				});
			};

			this.loadStyleSheetFile = function(strStyleSheetFile, strMediaType) {
				if (strStyleSheetFile.indexOf("/") == 0) {
					strStyleSheetFile = qc.baseDir + strStyleSheetFile;
				} else if (strStyleSheetFile.indexOf("http") != 0) {
					strStyleSheetFile = qc.cssAssets + "/" + strStyleSheetFile;
				}

				$j('head').append('<link rel="stylesheet" href="' + strStyleSheetFile + '" type="text/css" />');

			};




		/////////////////////////////
		// QForm-related functionality
		/////////////////////////////

			this.wrappers = new Array();


		}
	};

	///////////////////////////////
	// Timers-related functionality
	///////////////////////////////

		qcubed._objTimers = new Object();

		qcubed.clearTimeout = function(strTimerId) {
			if (qcubed._objTimers[strTimerId]) {
				clearTimeout(qcubed._objTimers[strTimerId]);
				qcubed._objTimers[strTimerId] = null;
			}
		};

		qcubed.setTimeout = function(strTimerId, strAction, intDelay) {
			qcubed.clearTimeout(strTimerId);
			qcubed._objTimers[strTimerId] = setTimeout(strAction, intDelay);
		};



	/////////////////////////////////////
	// Event Object-related functionality
	/////////////////////////////////////

		// You may still use this function but be advised
		// we no longer use it in core.  All event terminations
		// and event bubbling are handled through jQuery.
		// see http://trac.qcu.be/projects/qcubed/ticket/681
		// @deprecated
		qcubed.terminateEvent = function(objEvent) {
			objEvent = qcubed.handleEvent(objEvent);

			if (objEvent) {
				// Stop Propogation
				if (objEvent.preventDefault)
					objEvent.preventDefault();
				if (objEvent.stopPropagation)
					objEvent.stopPropagation();
				objEvent.cancelBubble = true;
				objEvent.returnValue = false;
			}

			return false;
		};


////////////////////////////////
// Qcodo Shortcut and Initialize
////////////////////////////////
	// Make sure we set $j.noConflict() to $j

	var qc = qcubed;
	qc.initialize();

	qc.pB = qcubed.postBack;
	qc.pA = qcubed.postAjax;

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
