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
	 $j.ajax( o );
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

		postAjax: function(strForm, strControl, strEvent, mixParameter, strWaitIconControlId) {

			var objForm = $j('#' + strForm);
			var strFormAction = objForm.attr("action");
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
						};
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

			if (strWaitIconControlId) {
				this.objAjaxWaitIcon = this.getWrapper(strWaitIconControlId);
				if (this.objAjaxWaitIcon)
					this.objAjaxWaitIcon.style.display = 'inline';
			};
			$j.ajaxQueue({
				url: strFormAction,
				type: "POST",
				data: strPostData,
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
							var control = $j(strControlId); 
							if (control.length != 0) 
								control.replaceWith(strControlHtml); 
							else 
								// Special case when a control is being changed from hidden to visible. Wrapper exists, but not content.
								$j(strControlId + '_ctl').html(strControlHtml); 						
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
			};
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
			};

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
