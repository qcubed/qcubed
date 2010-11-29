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
		
		postBack: function(strForm, strControl, strEvent, strParameter) {
			var strForm = $j("#Qform__FormId").attr("value");
			var objForm = $j('#' + strForm);

			$j('#Qform__FormControl').attr("value", strControl);
			$j('#Qform__FormEvent').attr("value", strEvent);
			$j('#Qform__FormParameter').attr("value", strParameter);
			$j('#Qform__FormCallType').attr("value", "Server");
			$j('#Qform__FormUpdates').attr("value", this.formUpdates());
			$j('#Qform__FormCheckableControls').attr("value", this.formCheckableControls(strForm, "Server"));

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
			var objFormElements = $j('#' + strForm + ' input,select,textarea');			
			var strToReturn = "";

			objFormElements.each(function(i) {
				if ((($j(this).attr("type") == "checkbox") ||
					 ($j(this).attr("type") == "radio")) &&
					((strCallType == "Ajax") ||
					(!$j(this).attr("disabled")))) {
					
					var strControlId = $j(this).attr("id");

					// CheckBoxList
					if (strControlId.indexOf('[') >= 0) {
						if (strControlId.indexOf('[0]') >= 0)
							strToReturn += " " + strControlId.substring(0, strControlId.length - 3);
					// RadioButtonList
					} else if (strControlId.indexOf('_') >= 0) {
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
	
		postAjax: function(strForm, strControl, strEvent, strParameter, strWaitIconControlId) {
			
			var strForm = strForm;
			var strControl = strControl;
			var strEvent = strEvent;
			var strParameter = strParameter;
			var strWaitIconControlId = strWaitIconControlId;

			var objForm = $j('#' + strForm);
			var strFormAction = objForm.attr("action");				
			var objFormElements = $j('#' + strForm + ' input,#' + strForm + ' select,#' + strForm + ' textarea');			
			
			$j('#Qform__FormControl').attr("value", strControl);
			$j('#Qform__FormEvent').attr("value", strEvent);
			$j('#Qform__FormParameter').attr("value", strParameter);
			$j('#Qform__FormCallType').attr("value", "Ajax");
			$j('#Qform__FormUpdates').attr("value", this.formUpdates());
			$j('#Qform__FormCheckableControls').attr("value", this.formCheckableControls(strForm, "Ajax"));
		
			var strPostData = '';
			
			objFormElements.each(function () {			
				var strType = $j(this).attr("type");
				var strControlId = $j(this).attr("id");
				switch (strType) {				
					case "checkbox":
					case "radio":
						if ($j(this).attr("checked")) {
							var strTestName = $j(this).attr("name") + "_";
							if (strControlId.substring(0, strTestName.length) == strTestName)
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
					alert("An error occurred during AJAX Response parsing.\r\n\r\nThe error response will appear in a new popup.");
					var objErrorWindow = window.open('about:blank', 'qcodo_error','menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=700,left=50,top=50');
					objErrorWindow.focus();
					objErrorWindow.document.write(XMLHttpRequest.responseText);
					return;
				},
				success: function (xml) {			
					$j(xml).find('control').each(function() {
						var strControlId = '#' + $j(this).attr("id");
						var strControlHtml = $j(this).text();				
						
						if (strControlId == "#Qform__FormState") {
							$j(strControlId).val(strControlHtml);
						} else {
							$j(strControlId + "_ctl").html(strControlHtml);
						}
					});			
					var strCommand = '';
					$j(xml).find('command').each(function() {						
						strCommand += $j(this).text();										
					});				
					eval(strCommand);					
					if (qcubed.objAjaxWaitIcon)
						qcubed.objAjaxWaitIcon.style.display = 'none';
				}
			});
			
		},
			
		initialize: function() {



		////////////////////////////////
		// Browser-related functionality
		////////////////////////////////
					
			this.loadJavaScriptFile = function(strScript, objCallback) {
				strScript = qc.jsAssets + "/" + strScript;
				$j.getScript(strScript, objCallback);
			};

			this.loadStyleSheetFile = function(strStyleSheetFile, strMediaType) {
				strStyleSheetFile = qc.cssAssets + "/" + strStyleSheetFile;
				
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
	