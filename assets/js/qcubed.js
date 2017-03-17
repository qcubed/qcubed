// BEWARE: this clears the $ variable!
var $j = jQuery.noConflict(),
    qcubed,
    qc;

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

/**
 * Queued Ajax requests.
 * A new Ajax request won't be started until the previous queued
 * request has finished.
 * @param {function} a function that returns ajax options.
 * @param {boolean} blnAsync true to launch right away.
 */
$j.ajaxQueue = function(o, blnAsync) {
    if (typeof $j.ajaxq === "undefined" || blnAsync) {
        $j.ajax(o()); // fallback in case ajaxq is not here
    } else {
        var p = $j.ajaxq("qcu.be", o);
    }
};
$j.ajaxQueueIsRunning = function() {
    if ($j.ajaxq) {
        return $j.ajaxq.isRunning("qcu.be");
    }
    return false;
}


/**
 * @namespace qcubed
 */
qcubed = {
    /**
     * @param {string} strControlId
     * @param {string} strProperty
     * @param {string|Array|Object} strNewValue
     */
    recordControlModification: function(strControlId, strProperty, strNewValue) {
        if (!qcubed.controlModifications[strControlId]) {
            qcubed.controlModifications[strControlId] = {};
        }
        qcubed.controlModifications[strControlId][strProperty] = strNewValue;
    },
    /**
     * Given a control, returns the correct index to use in the formObjsModified array.
     * @param ctl
     * @private
     */
    _formObjChangeIndex: function (ctl) {
        var id = $j(ctl).attr('id');
        var strType = $j(ctl).prop("type");
        var name = $j(ctl).attr("name");

        if (((strType === 'checkbox') || (strType === 'radio')) &&
           id && ((indexOffset = id.lastIndexOf('_')) >= 0)) { // a member of a control list
            return id.substr(0, indexOffset); // use the id of the group
        }
        else if (id && strType === 'radio' && name !== id) { // a radio button with a group name
            return id; // these buttons are changed individually
        }
        else if (id && strType === 'hidden') { // a hidden field, possibly associated with a different widget
            if ((indexOffset = id.lastIndexOf('_')) >= 0) {
                return id.substr(0, indexOffset); // use the id of the parent control
            }
            return name;
        }
        return id;
    },
    /**
     * Records that a control has changed in order to synchronize the control with
     * the php version on the next request.
     * @param event
     */
    formObjChanged: function (event) {
        var ctl = event.target;
        var id = qc._formObjChangeIndex(ctl);
        var strType = $j(ctl).prop("type");
        var name = $j(ctl).attr("name");

        if (strType === 'radio' && name !== id) { // a radio button with a group name
            // since html does not submit a changed event on the deselected radio, we are going to invalidate all the controls in the group
            var group = $j('input[name=' + name + ']');
            if (group) {
                group.each(function () {
                    id = $j(this).attr('id');
                    qcubed.formObjsModified[id] = true;
                });
            }
        }
        else if (id) {
            qcubed.formObjsModified[id] = true;
        }
    },
    /**
     * Initialize form related scripts
     * @param strFormId
     */
    initForm: function (strFormId) {
        $j('#' + strFormId).on ('qformObjChanged', this.formObjChanged); // Allow any control, including hidden inputs, to trigger a change and post of its data.
        $j('#' + strFormId).submit(function(event) {
            if (!$j('#Qform__FormControl').val()) { // did postBack initiated the submit?
                // if not, prevent implicit form submission. This can happen in the rare case we have a single field and no submit button.
                event.preventDefault();
            }
        });
    },

    /**
     * @param {string} strForm The QForm Id, gets overwritten.
     * @param {string} strControl The Control Id.
     * @param {string} strEvent The Event.
     * @param {null|string|Array|Object} mixParameter
     */
    postBack: function(strForm, strControl, strEvent, mixParameter) {
        if (qc.blockEvents) return;   // We are waiting for a response from the server

        strForm = $j("#Qform__FormId").val();
        var $objForm = $j('#' + strForm);

        var checkableControls = $j('#' + strForm).find('input[type="checkbox"], input[type="radio"]');
        var checkableValues = this._checkableControlValues(strForm, $j.makeArray(checkableControls));

        $j('#Qform__FormControl').val(strControl);
        $j('#Qform__FormEvent').val(strEvent);
        $j('#Qform__FormCallType').val("Server");

        // Notify custom controls that we are about to post
        $objForm.trigger("qposting", "Server");

        if (mixParameter !== undefined) {
            $j('#Qform__FormParameter').val(JSON.stringify(mixParameter));
        }
        if (!$j.isEmptyObject(qcubed.controlModifications)) {
            $j('#Qform__FormUpdates').val(JSON.stringify(qcubed.controlModifications));
        }
        if (!$j.isEmptyObject(checkableValues)) {
            $j('#Qform__FormCheckableControls').val(JSON.stringify(checkableValues));
        }

        // add hidden control for additional values given
        // Will be decoded and assigned to the $_POST var in PHP.
        if (!$j.isEmptyObject(qcubed.additionalPostVars)) {
            var input = $j("<input>")
                .attr("type", "hidden")
                .attr("name", "Qform__AdditionalPostVars").val(JSON.stringify(qcubed.additionalPostVars));
            $objForm.append(input);
        }

        // have $j trigger the submit event (so it can catch all submit events)
        $objForm.trigger("submit");
    },
    /**
     * Custom controls should call this in response to the qposting trigger on the form if they need to add some
     * additional post variables. Multiple sets of the same value will overwrite previous value.
     *
     * @param {string} name Name to post. Should probably be the control id, but can be anything.
     * @param {null|number|string|Array|Object} val  Any value you want to send to PHP. Can be a string, array or simple object. Can also contain null
     * values and these will become nulls in PHP.
     */
    setAdditionalPostVar: function (name, val) {
        qcubed.additionalPostVars[name] = val;
    },
    /**
     * This function resolves the state of checkable controls into postable values.
     *
     * Checkable controls (checkboxes and radio buttons) can be problematic. We have the following issues to work around:
     * - On a submit, only the values of the checked items are submitted. Non-checked items are not submitted.
     * - QCubed may have checkboxes that are part of the form object, but not visible on the html page. In particular,
     *   this can happen when a grid is creating objects at render time, and then scrolls or pages so those objects
     *   are no longer "visible".
     * - Controls can be part of a group, and the group gets the value of the checked control(s), rather than individual
     *   items getting a true or false.
     *
     * To solve all of these issues, we post a value that has all the values of all visible checked items, either
     * true or false for individual items, or an array of values, single value, or null for groups. QCubed controls that
     * deal with checkable controls must look for this special posted variable to know how to update their internal state.
     *
     * Checkboxes that are part of a group will return an array of values, keyed by the group id.
     * Radio buttons that are part of a group will return a single value keyed by group id.
     * Checkboxes and radio buttons that are not part of a group will return a true or false keyed by the control id.
     * Note that for radio buttons, a group is defined by a common identifier in the id. Radio buttons with the same
     * name, but different ids, are not considered part of a group for purposes here, even though visually they will
     * act like they are part of a group. This allows you to create individual QRadioButton objects that each will
     * be updated with a true or false, but the browser will automatically make sure only one is checked.
     *
     * Any time an id has an underscore in it, that control is considered part of a group. The value after the underscore
     * will be the value returned, and before the last underscore will be id that will be used as the key for the value.
     *
     * @param {string} strForm   Form Id
     * @param {array} controls  Array of checkable controls. These must be checkable controls, it will not validate this.
     * @returns {object}  A hash of values keyed by control id
     * @private
     */
    _checkableControlValues: function(strForm, controls) {
        var values = {};

        if (!controls || controls.length == 0) {
            return {};
        }
        $j.each(controls, function() {
            var $element = $j(this),
                id = $element.attr("id"),
                strType = $element.prop("type"),
                index = null,
                offset;

            if (id &&
                (offset = id.lastIndexOf('_')) != -1) {
                // A control group
                index = id.substr(offset + 1);
                id = id.substr(0, offset);
            }
            switch (strType) {
                case "checkbox":
                    if (index !== null) {   // this is a group of checkboxes
                        var a = values[id];
                        if ($element.is(":checked")) {
                            if (a) {
                                a.push(index);
                            } else {
                                a = [index];
                            }
                            values[id] = a;
                        }
                        else {
                            if (!a) {
                                values[id] = null; // empty array to notify that the group has a null value, if nothing gets checked
                            }
                        }
                    } else {
                        values[id] = $element.is(":checked");
                    }
                    break;

                case "radio":
                    if (index !== null) {
                        if ($element.is(":checked")) {
                            values[id] = index;
                        }
                    } else {
                        // control name MIGHT be a group name, which we don't want here, so we use control id instead
                        values[id] = $element.is(":checked");
                    }
                    break;
            }
        });
        return values;
    },

    /**
     * Gets the data to be sent to an ajax call as post data. Note that once you call this, you MUST post this data, as
     * it has the side effect of resetting the cache of changed objects.
     *
     * @param {string} strForm The Form Id
     * @param {string} strControl The Control Id
     * @param {string} strEvent The Event
     * @param {null|string|array|object} mixParameter An array of parameters or a string value.
     * @param {string} strWaitIconControlId Not used, probably legacy code.
     * @return {object} Post Data
     */
    getAjaxData: function(strForm, strControl, strEvent, mixParameter, strWaitIconControlId) {
        var $form = $j('#' + strForm),
            $formElements = $form.find('input,select,textarea'),
            checkables = [],
            controls = [],
            postData = {};

        // Notify controls we are about to post.
        $form.trigger("qposting", "Ajax");

        // Filter and separate controls into checkable and non-checkable controls
        // We ignore controls that have not changed to reduce the amount of data sent in an ajax post.
        $formElements.each(function() {
            var $element = $j(this),
                id = $element.attr("id"),
                blnQform = (id && (id.substr(0, 7) === 'Qform__')),
                strType = $element.prop("type"),
                objChangeIndex = qc._formObjChangeIndex($element);


                if (!qcubed.inputSupport || // if not oninput support, then post all the controls, rather than just the modified ones
                qcubed.ajaxError || // Ajax error would mean that formObjsModified is invalid. We need to submit everything.
                (objChangeIndex && qcubed.formObjsModified[objChangeIndex]) ||
                blnQform) {  // all controls with Qform__ at the beginning of the id are always posted.

                switch (strType) {
                    case "checkbox":
                    case "radio":
                        checkables.push(this);
                        break;

                    default:
                        controls.push(this);
                }
            }
        });


        $j.each(controls, function() {
            var $element = $j(this),
                strType = $element.prop("type"),
                strControlId = $element.attr("id"),
                strControlName = $element.attr("name"),
                strPostValue = $element.val();

            switch (strType) {
                case "select-multiple":
                    var items = $element.find(':selected'),
                        values = [];
                    if (items.length) {
                        values = $j.map($j.makeArray(items), function(item) {
                            return $j(item).val();
                        });
                        postData[strControlId] = values;
                    }
                    else {
                        postData[strControlId] = null;    // mark it as set to nothing
                    }
                    break;

                default:
                    var strPostName = (strControlName ? strControlName: strControlId);
                    postData[strPostName] = strPostValue;
                    break;
            }
        });

        // Update most of the Qform__ parameters explicitly here. Others, like the state and form id will have been handled above.
        if (mixParameter !== undefined) {
            postData.Qform__FormParameter = JSON.stringify(mixParameter);
        }
        postData.Qform__FormControl = strControl;
        postData.Qform__FormEvent = strEvent;
        postData.Qform__FormCallType = "Ajax";

        if (!$j.isEmptyObject(qcubed.controlModifications)) {
            postData.Qform__FormUpdates = JSON.stringify(qcubed.controlModifications);
        }
        postData.Qform__FormCheckableControls = qcubed._checkableControlValues(strForm, checkables);

        if (!$j.isEmptyObject(qcubed.additionalPostVars)) {
            postData.Qform__AdditionalPostVars = JSON.stringify(qcubed.additionalPostVars);
            qcubed.additionalPostVars = {};
        }

        qcubed.ajaxError = false;
        qcubed.formObjsModified = {};
        qcubed.controlModifications = {};

        return postData;
    },

    /**
     * @param {string} strForm The QForm Id
     * @param {string} strControl The Control Id
     * @param {string} strEvent
     * @param {null|string|Object|Array} mixParameter
     * @param {string} strWaitIconControlId The id of the control's spinner.
     * @param {boolean} blnAsync Whether to queue the ajax requests and processes serially (default), or do them async.
     *                  See QAjaxAction comments for more info
     * @return {void}
     * @todo There is an eval() in here. We need to find a way around that.
     */
    postAjax: function(strForm, strControl, strEvent, mixParameter, strWaitIconControlId, blnAsync) {
        var objForm = $j('#' + strForm),
            strFormAction = objForm.attr("action"),
            qFormParams = {};

        if (qc.blockEvents) return;

        qFormParams.form = strForm;
        qFormParams.control = strControl;
        qFormParams.event = strEvent;
        qFormParams.param = mixParameter;
        qFormParams.waitIcon = strWaitIconControlId;

        if (strWaitIconControlId) {
            this.objAjaxWaitIcon = qcubed.getWrapper(strWaitIconControlId);
            if (this.objAjaxWaitIcon) {
                this.objAjaxWaitIcon.style.display = 'inline';
            }
        }

        // Use a modified ajax queue so ajax requests happen synchronously
        $j.ajaxQueue(function() {
            var data = qcubed.getAjaxData(
                qFormParams.form,
                qFormParams.control,
                qFormParams.event,
                qFormParams.param,
                qFormParams.waitIcon);

            return {
                url: strFormAction,
                type: "POST",
                qFormParams: qFormParams,
                data: data,
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    var result = XMLHttpRequest.responseText,
                        objErrorWindow,
                        $dialog;

                    qcubed.ajaxError = true;
                    qcubed.blockEvents = false;
                    if (XMLHttpRequest.status !== 0 || (result && result.length > 0)) {
                        if (result.substr(0, 15) === '<!DOCTYPE html>') {
                            alert("An error occurred.\r\n\r\nThe error response will appear in a new popup.");
                            objErrorWindow = window.open('about:blank', 'qcubed_error', 'menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=700,left=50,top=50');
                            objErrorWindow.focus();
                            objErrorWindow.document.write(result);
                            return false;
                        } else {
                            var resultText = $j('<div>').html(result);
                            $dialog = $j('<div id="Qcubed_AJAX_Error" />')
                                .append('<h1 style="text-transform:capitalize">' + textStatus + '</h1>')
                                .append('<p>' + errorThrown + '</p>')
                                .append(resultText)
                                .append('<button onclick="$j(this).parent().hide()">OK</button>')
                                .appendTo('form');
                            return false;
                        }
                    }
                },
                success: function (json) {
                    qcubed._prevUpdateTime = new Date().getTime();
                    if (json.js) {
                        var deferreds = [];
                        // Load all javascript files before attempting to process the rest of the response, in case some things depend on the injected files
                        $j.each(json.js, function (i, v) {
                            deferreds.push(qcubed.loadJavaScriptFile(v));
                        });
                        qcubed.processImmediateAjaxResponse(json, qFormParams); // go ahead and begin processing things that will not depend on the javascript files to allow parallel processing
                        $j.when.apply($j, deferreds).then(
                            function () {
                                qcubed.processDeferredAjaxResponse(json);
                                qcubed.blockEvents = false;
                            }, // success
                            function () {
                                console.log('Failed to load a file');
                                qcubed.blockEvents = false;
                            } // failed to load a file. What to do?
                        );
                    } else {
                        qcubed.processImmediateAjaxResponse(json, qFormParams);
                        qcubed.processDeferredAjaxResponse(json);
                        qcubed.blockEvents = false;
                    }
                }
            };
        }, blnAsync);
    },

    /**
     * Start me up.
     */
    initialize: function() {

        ////////////////////////////////
        // Browser-related functionality
        ////////////////////////////////

        this.loadJavaScriptFile = function(strScript, objCallback) {
            if (strScript.indexOf("/") === 0) {
                strScript = qc.baseDir + strScript;
            } else if (strScript.indexOf("http") !== 0) {
                strScript = qc.jsAssets + "/" + strScript;
            }
            return $j.ajax({
                url: strScript,
                success: objCallback,
                dataType: "script",
                cache: true
            });
        };

        this.loadStyleSheetFile = function(strStyleSheetFile, strMediaType) {
            if (strStyleSheetFile.indexOf("/") === 0) {
                strStyleSheetFile = qc.baseDir + strStyleSheetFile;
            } else if (strStyleSheetFile.indexOf("http") !== 0) {
                strStyleSheetFile = qc.cssAssets + "/" + strStyleSheetFile;
            }
            if (strMediaType){
                strMediaType = " media="+strMediaType;
            }
            $j('head').append('<link rel="stylesheet"'+strMediaType+' href="' + strStyleSheetFile + '" type="text/css" />');
        };

        /////////////////////////////
        // QForm-related functionality
        /////////////////////////////

        this.wrappers = [];

        $j(window).on ("storage", function (o) {
            if (o.originalEvent.key == "qcubed.broadcast") {
                qcubed.updateForm();
            }
        });

        this.inputSupport = 'oninput' in document;

        // Detect browsers that do not correctly support the oninput event, even though they say they do.
        // IE 9 in particular has a major bug
        var ua = window.navigator.userAgent;
        var intIeOffset = ua.indexOf ('MSIE');
        if (intIeOffset > -1) {
            var intOffset2 = ua.indexOf ('.', intIeOffset + 5);
            var strVersion = ua.substr (intIeOffset + 5, intOffset2 - intIeOffset - 5);
            if (strVersion < 10) {
                this.inputSupport = false;
            }
        }

        $j( document ).ajaxComplete(function( event, request, settings ) {
            if (!$j.ajaxQueueIsRunning()) {
                qcubed.processFinalCommands();
            }
        });


        return this;
    },
    processImmediateAjaxResponse: function(json, qFormParams) {
        if (json.controls) $j.each(json.controls, function() {
            var strControlId = '#' + this.id,
                control = $j(strControlId),
                wrapper = $j(strControlId + '_ctl');

            if (this.value !== undefined) {
                control.val(this.value);
            }

            if (this.attributes !== undefined) {
                control.attr (this.attributes);
            }

            if (this.html !== undefined) {
                if (wrapper.length) {
                    // Control's wrapper was found, so fill it in
                    wrapper.html(this.html);
                }
                else if (control.length) {
                    // control was found without a wrapper, replace it in the same position it was in.
                    // remove related controls (error, name ...) for wrapper-less controls
                    var relSelector = "[data-qrel='" + strControlId + "']",
                        relItems = $j(relSelector),
                        $relParent;

                    if (relItems && relItems.length) {
                        // if the control is wrapped in a related control, we move the control outside the related controls
                        // before deleting the related controls
                        $relParent = control.parents(relSelector).last();
                        if ($relParent.length) {
                            control.insertBefore($relParent);
                        }
                        relItems.remove();
                    }

                    control.before(this.html).remove();
                }
                else {
                    // control is being injected at the top level, so put it at the end of the form.
                    var strForm = $j("#Qform__FormId").val();
                    var $objForm = $j('#' + strForm);

                    $objForm.append(this.html);
                }
            }
        });

        if (json.regc) {
            qcubed.registerControlArray (json.regc);
        }

        if (json.watcher && qFormParams.control) {
            qcubed.broadcastChange();
        }
        if (json.ss) {
            $j.each(json.ss, function (i,v) {
                qc.loadStyleSheetFile(v, "all");
            });
        }
        if (json.alert) {
            $j.each(json.alert, function (i,v) {
                alert(v);
            });
        }
    },
    processDeferredAjaxResponse: function(json) {
        if (json.commands) { // commands
            $j.each(json.commands, function (index, command) {
                if (command.final &&
                    $j.ajaxQueueIsRunning()) {

                    qcubed.enqueueFinalCommand(command);
                } else {
                    qcubed.processCommand(command);
                }
            });
        }
        if (json.winclose) {
            window.close();
        }
        if (json.loc) {
            if (json.loc == 'reload') {
                window.location.reload(true);
            } else {
                document.location = json.loc;
            }
        }

        if (qcubed.objAjaxWaitIcon) {
            $j(qcubed.objAjaxWaitIcon).hide();
        }
    },
    processCommand: function(command) {
        if (command.script) {
            /** @todo eval is evil, do no evil */
            eval (command.script);
        }
        else if (command.selector) {
            var params = qc.unpackArray(command.params);
            var objs;

            if (typeof command.selector === 'string') {
                objs = $j(command.selector);
            } else {
                objs = $j(command.selector[0], command.selector[1]);
            }

            // apply the function on each jQuery object found, using the found jQuery object as the context.
            objs.each (function () {
                var $item = $j(this);
                if ($item[command.func]) {
                    $item[command.func].apply($j(this), params);
                }
            });
        }
        else if (command.func) {
            var params = qc.unpackArray(command.params);

            // Find the function by name. Walk an object list in the process.
            var objs = command.func.split(".");
            var obj = window;
            var ctx = null;

            $j.each (objs, function (i, v) {
                ctx = obj;
                obj = obj[v];
            });
            // obj is now a function object, and ctx is the parent of the function object
            obj.apply(ctx, params);
        }

    },
    enqueueFinalCommand: function(command) {
        qcubed.finalCommands.push(command);
    },
    processFinalCommands: function() {
        while(qcubed.finalCommands.length) {
            var command = qcubed.finalCommands.pop();
            qcubed.processCommand(command);
        }
    },
    /**
     * Convert from JSON return value to an actual jQuery object. Certain structures don't work in JSON, like closures,
     * but can be part of a javascript object.
     * @param params
     * @returns {*}
     */
    unpackArray: function(params) {
        if (!params) {
            return null;
        }
        var newParams = [];

        $j.each(params, function (index, item){
            if ($j.type(item) == 'object') {
                if (item.qObjType) {
                    item = qcubed.unpackObj(item);  // top level special object
                }
                else {
                    // look for special objects inside top level objects.
                    var newItem = {};
                    $j.each (item, function (key, obj) {
                        newItem[key] = qcubed.unpackObj(obj);
                    });
                    item = newItem;
                }
            }
            else if ($j.type(item) == 'array') {
                item = qcubed.unpackArray (item);
            }
            newParams.push(item);
        });
        return newParams;
    },

    /**
     * Given an object coming from qcubed, will attempt to decode the object into a corresponding javascript object.
     * @param obj
     * @returns {*}
     */
    unpackObj: function (obj) {
        if ($j.type(obj) == 'object' &&
                obj.qObjType) {

            switch (obj.qObjType) {
                case 'qClosure':
                    if (obj.params) {
                        params = [];
                        $j.each (obj.params, function (i, v) {
                            params.push(qc.unpackObj(v)); // recurse
                        });

                        return new Function(params, obj.func);
                    } else {
                        return new Function(obj.func);
                    }
                    break;

                case 'qDateTime':
                    return new Date(obj.year, obj.month, obj.day, obj.hour, obj.minute, obj.second);
                    break;

                case 'qVarName':
                    // Find the variable value starting at the window context.
                    var vars = obj.varName.split(".");
                    var val = window;
                    $j.each (vars, function (i, v) {
                        val = val[v];
                    });
                    return val;

                case 'qFunc':
                    // Returns the result of the given function called immediately
                    // Find the function and context starting at the window context.
                    var target = window;
                    var params;
                    if (obj.context) {
                       var objects = obj.context.split(".");
                        $j.each (objects, function (i, v) {
                            target = target[v];
                        });
                    }

                    if (obj.params) {
                        params = [];
                        $j.each (obj.params, function (i, v) {
                            params.push(qc.unpackObj(v)); // recurse
                        });
                    }
                    var func = target[obj.func];

                    return func.apply(target, params);
            }
        }
        else if ($j.type(obj) == 'object') {
            var newItem = {};
            $j.each (obj, function (key, obj2) {
                newItem[key] = qcubed.unpackObj(obj2);
            });
            return newItem;
        }
        else if ($j.type(obj) == 'array') {
            return qcubed.unpackArray(obj);
        }
        return obj; // no change
    },
    setCookie: function(name, val, expires, path, dom, secure) {
            var cookie = name + "=" + encodeURIComponent(val) + "; ";

            if (expires) {
                cookie += "expires=" + expires.toUTCString() + "; ";
            }

            if (path) {
                cookie += "path=" + path + "; ";
            }
            if (dom) {
                cookie += "domain=" + dom + "; ";
            }
            if (secure) {
                cookie += "secure;";
            }

            document.cookie = cookie;
        }
};

///////////////////////////////
// Timers-related functionality
///////////////////////////////

qcubed._objTimers = {};

qcubed.clearTimeout = function(strTimerId) {
    if (qcubed._objTimers[strTimerId]) {
        clearTimeout(qcubed._objTimers[strTimerId]);
        qcubed._objTimers[strTimerId] = null;
    }
};

qcubed.setTimeout = function(strTimerId, action, intDelay) {
    qcubed.clearTimeout(strTimerId);
    qcubed._objTimers[strTimerId] = setTimeout(action, intDelay);
};

///////////////////////////////
// QWatcher support
///////////////////////////////
qcubed._prevUpdateTime = 0;
qcubed.minUpdateInterval = 1000; // milliseconds to limit broadcast updates. Feel free to change this.
qcubed.broadcastChange = function () {
    if ('localStorage' in window && window['localStorage'] !== null) {
        var newTime = new Date().getTime();
        localStorage.setItem("qcubed.broadcast", newTime); // must change value to induce storage event in other windows
    }
};

qcubed.updateForm = function() {
    // call this whenever you generally just need the form to update without a specific action.
    var newTime = new Date().getTime();

    // the following code prevents too many updates from happening in a short amount of time.
    // the default will update no faster than once per second.
    if (newTime - qcubed._prevUpdateTime >= qcubed.minUpdateInterval) {
        //refresh immediately
        var strForm = $j('#Qform__FormId').val();
        qcubed.postAjax (strForm, '', '', '', '');
        qcubed.clearTimeout ('qcubed.update');
    } else if (!qcubed._objTimers['qcubed.update']) {
        // delay to let multiple fast actions only trigger periodic refreshes
        qcubed.setTimeout ('qcubed.update', 'qcubed.updateForm', qcubed.minUpdateInterval);
    }
};

/////////////////////////////////////
// Drag and drop support
/////////////////////////////////////

qcubed.draggable = function (parentId, draggableId) {
    // we are working around some jQuery UI bugs here..
    jQuery('#' + parentId).on("dragstart", function () {
        var c = jQuery(this);
        c.data ("originalPosition", c.position());
    }).on("dragstop", function () {
        var c = jQuery(this);
        qcubed.recordControlModification(draggableId, "_DragData", {originalPosition: {left: c.data("originalPosition").left, top: c.data("originalPosition").top}, position: {left: c.position().left, top: c.position().top}});
    });
};

qcubed.droppable = function (parentId, droppableId) {
    jQuery('#' + parentId).on("drop", function (event, ui) {
        qcubed.recordControlModification(droppableId, "_DroppedId", ui.draggable.attr("id"));
    })
};

qcubed.resizable = function (parentId, resizeableId) {
    $j('#' + parentId).on("resizestart", function () {
        var c = jQuery(this);
        c.data ("oW", c.width());
        c.data ("oH", c.height());
    })
    .on("resizestop", function () {
        var c = jQuery(this);
        qcubed.recordControlModification(resizeableId, "_ResizeData", {originalSize: {width: c.data("oW"), height: c.data("oH")} , size:{width: c.width(), height: c.height()}});
    });
}

/////////////////////////////////////
// JQueryUI Support
/////////////////////////////////////

qcubed.dialog = function(controlId) {
    $j('#' + controlId).on ("keydown", "input,select", function(event) {
        // makes sure a return key fires the default button if there is one
        if (event.which == 13) {
            var b = $j(this).closest("[role=\'dialog\']").find("button[type=\'submit\']");
            if (b && b[0]) {
                b[0].click();
            }
            event.preventDefault();
        }
    });
};

qcubed.accordion = function(controlId) {
    $j('#' + controlId).on("accordionactivate", function(event, ui) {
        qcubed.recordControlModification(controlId, "_SelectedIndex", $j(this).accordion("option", "active"));
        $j(this).trigger("change");
    });
};

qcubed.progressbar = function(controlId) {
    $j('#' + controlId).on("progressbarchange", function (event, ui) {
        qcubed.recordControlModification(controlId, "_Value", $j(this).progressbar ("value"));
    });
};

qcubed.selectable = function(controlId) {
    $j('#' + controlId).on("selectablestop", function (event, ui) {
        var strItems;

        strItems = "";
        $j(".ui-selected", this).each(function() {
            strItems = strItems + "," + this.id;
        });

        if (strItems) {
            strItems = strItems.substring (1);
        }
        qcubed.recordControlModification(controlId, "_SelectedItems", strItems);

    });
};

qcubed.slider = function(controlId) {
    $j('#' + controlId).on("slidechange", function (event, ui) {
        if (ui.values && ui.values.length) {
            qcubed.recordControlModification(controlId, "_Values", ui.values[0] + ',' +  ui.values[1]);
        } else {
            qcubed.recordControlModification(controlId, "_Value", ui.value);
        }
    });
};

qcubed.tabs = function(controlId) {
    $j('#' + controlId).on("tabsactivate", function(event, ui) {
        var i = $j(this).tabs( "option", "active" );
        var id = ui.newPanel ? ui.newPanel.attr("id") : null;
        qcubed.recordControlModification(controlId, "_active", [i,id]);
    });
};

qcubed.datagrid2 = function(controlId) {
    $j('#' + controlId).on("click", "thead tr th a", function(event, ui) {
        var cellIndex = $j(this).parent()[0].cellIndex;
        $j(this).trigger('qdg2sort', cellIndex); // Triggers the QDataGrid_SortEvent
    });
};

qcubed.dialog = function(controlId) {
    $j('#' + controlId).on("tabsactivate", function(event, ui) {
        var i = $j(this).tabs( "option", "active" );
        var id = ui.newPanel ? ui.newPanel.attr("id") : null;
        qcubed.recordControlModification(controlId, "_active", [i,id]);
    });
}

/////////////////////////////////
// Controls-related functionality
/////////////////////////////////

qcubed.getControl = function(mixControl) {
    if (typeof mixControl === 'string') {
        return document.getElementById(mixControl);
    } else {
        return mixControl;
    }
};

qcubed.getWrapper = function(mixControl) {
    var objControl = qcubed.getControl(mixControl);

    if (!objControl) {
        //maybe it doesn't have a child control, just the wrapper
        if (typeof mixControl === 'string') {
            return this.getControl(mixControl + "_ctl");
        }
        return null;
    } else if (objControl.wrapper) {
        return objControl.wrapper;
    }

    return objControl; //a wrapper-less control, return the control itself
};

/**
 * Radio buttons are a little tricky to set if they are part of a group
 * @param strControlId
 */
qcubed.setRadioInGroup = function(strControlId) {
    var $objControl = $j('#' + strControlId);
    if ($objControl) {
        var groupName = $objControl.prop('name');
        if (groupName) {
            var $radios = $objControl.closest('form').find('input[type=radio][name=' + groupName + ']');
            $radios.val([strControlId]);  // jquery does the work here of setting just the one control
            $radios.trigger('qformObjChanged'); // send the new values back to the form
        }
    }
};

/////////////////////////////
// Register Control - General
/////////////////////////////

qcubed.controlModifications = {};
qcubed.javascriptStyleToQcubed = {};
qcubed.formObjsModified = {};
qcubed.additionalPostVars = {};
qcubed.ajaxError = false;
qcubed.inputSupport = true;
qcubed.javascriptStyleToQcubed.backgroundColor = "BackColor";
qcubed.javascriptStyleToQcubed.borderColor = "BorderColor";
qcubed.javascriptStyleToQcubed.borderStyle = "BorderStyle";
qcubed.javascriptStyleToQcubed.border = "BorderWidth";
qcubed.javascriptStyleToQcubed.height = "Height";
qcubed.javascriptStyleToQcubed.width = "Width";
qcubed.javascriptStyleToQcubed.text = "Text";

qcubed.javascriptWrapperStyleToQcubed = {};
qcubed.javascriptWrapperStyleToQcubed.position = "Position";
qcubed.javascriptWrapperStyleToQcubed.top = "Top";
qcubed.javascriptWrapperStyleToQcubed.left = "Left";

qcubed.blockEvents = false;
qcubed.finalCommands = [];

qcubed.registerControl = function(mixControl) {
    var objControl = qcubed.getControl(mixControl),
        objWrapper;

    if (!objControl) {
        return;
    }

    // detect changes to objects before any changes trigger other events
    if (objControl.type === 'checkbox' || objControl.type === 'radio') {
        // clicks are equivalent to changes for checkboxes and radio buttons, but some browsers send change way after a click. We need to capture the click first.
        $j(objControl).on ('click', this.formObjChanged);
    }
    $j(objControl).on ('change input', this.formObjChanged);
    $j(objControl).on ('change input', 'input, select, textarea', this.formObjChanged);   // make sure we get to bubbled events before later attached handlers


    // Link the Wrapper and the Control together
    objWrapper = this.getControl(objControl.id + "_ctl");
    if (!objWrapper) {
        objWrapper = objControl; //wrapper-less control
    } else {
        objWrapper.control = objControl;
        objControl.wrapper = objWrapper;

        // Add the wrapper to the global qcubed wrappers array
        qcubed.wrappers[objWrapper.id] = objWrapper;
    }

    // track change events


    // Create New Methods, etc.
    // Like: objWrapper.something = xyz;

    /**
     * This function was originally intended to be used by javascript to manipulate QControl objects and have the result
     * reported back to the PHP side. Modern jQuery objects now have events that can be hooked to catch changes to
     * objects, and using those events is probably a better approach in most cases. Various jQuery UI base QControls
     * use this method. In any case, you can use this as a model for how to use the recordControlModification function
     * to send results to PHP objects.
     *
     * @param strStyleName
     * @param strNewValue
     */
    objWrapper.updateStyle = function(strStyleName, strNewValue) {
        var objControl = (this.control) ? this.control : this,
            objNewParentControl,
            objParentControl,
            $this;

        switch (strStyleName) {
            case "className":
                objControl.className = strNewValue;
                qcubed.recordControlModification(objControl.id, "CssClass", strNewValue);
                break;

            case "parent":
                if (strNewValue) {
                    objNewParentControl = qcubed.getControl(strNewValue);
                    objNewParentControl.appendChild(this);
                    qcubed.recordControlModification(objControl.id, "Parent", strNewValue);
                } else {
                    objParentControl = this.parentNode;
                    objParentControl.removeChild(this);
                    qcubed.recordControlModification(objControl.id, "Parent", "");
                }
                break;

            case "displayStyle":
                objControl.style.display = strNewValue;
                qcubed.recordControlModification(objControl.id, "DisplayStyle", strNewValue);
                break;

            case "display":
                $this = $j(this);
                if (strNewValue) {
                    $this.show();
                    qcubed.recordControlModification(objControl.id, "Display", "1");
                } else {
                    $this.hide();
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
                if (qcubed.javascriptStyleToQcubed[strStyleName]) {
                    qcubed.recordControlModification(objControl.id, qcubed.javascriptStyleToQcubed[strStyleName], strNewValue);
                }
                /* ???
                if (this.handle) {
                    this.updateHandle();
                }*/
                break;

            case "text":
                objControl.innerHTML = strNewValue;
                qcubed.recordControlModification(objControl.id, "Text", strNewValue);
                break;

            default:
                if (qcubed.javascriptWrapperStyleToQcubed[strStyleName]) {
                    this.style[strStyleName] = strNewValue;
                    qcubed.recordControlModification(objControl.id, qcubed.javascriptWrapperStyleToQcubed[strStyleName], strNewValue);
                } else {
                    objControl.style[strStyleName] = strNewValue;
                    if (qcubed.javascriptStyleToQcubed[strStyleName]) {
                        qcubed.recordControlModification(objControl.id, qcubed.javascriptStyleToQcubed[strStyleName], strNewValue);
                    }
                }
                break;
        }
    };

    // Positioning-related functions

    objWrapper.getAbsolutePosition = function() {
        var objControl = (this.control) ? this.control : this,
            pos = $j(objControl).offset();

        return {x: pos.left, y: pos.top};
    };

    objWrapper.setAbsolutePosition = function(intNewX, intNewY, blnBindToParent) {
        var objControl = this.offsetParent;

        while (objControl) {
            intNewX -= objControl.offsetLeft;
            intNewY -= objControl.offsetTop;
            objControl = objControl.offsetParent;
        }

        if (blnBindToParent) {
            if (this.parentNode.nodeName.toLowerCase() !== 'form') {
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
        var strDisplay = "display";
        // Toggles the display/hiding of the entire control (including any design/wrapper HTML)
        // If ShowOrHide is blank, then we toggle
        // Otherwise, we'll execute a "show" or a "hide"
        if (strShowOrHide) {
            if (strShowOrHide === "show") {
                this.updateStyle(strDisplay, true);
            } else {
                this.updateStyle(strDisplay, false);
            }
        } else
            this.updateStyle(strDisplay, (this.style.display === "none"));
    };

    objWrapper.toggleEnabled = function(strEnableOrDisable) {
        var objControl = (this.control) ? this.control : this,
            strEnabled = "enabled";

        if (strEnableOrDisable) {
            if (strEnableOrDisable === "enable") {
                this.updateStyle(strEnabled, true);
            } else {
                this.updateStyle(strEnabled, false);
            }
        } else {
            this.updateStyle(strEnabled, objControl.disabled);
        }
    };

    objWrapper.registerClickPosition = function(objEvent) {
        var objControl = (this.control) ? this.control : this,
            intX = objEvent.pageX - objControl.offsetLeft,
            intY = objEvent.pageY - objControl.offsetTop;

        $j('#' + objControl.id + "_x").val(intX);
        $j('#' + objControl.id + "_y").val(intY);
        $j(objControl).trigger('qformObjChanged');
    };

    // Focus
    if (objWrapper.control) {
        objWrapper.focus = function() {
            $j(this.control).focus();
        };
    }

    // Select All (will only work for textboxes)
    if (objWrapper.control) {
        objWrapper.select = function() {
            $j(this.control).select();
        };
    }

    // Blink
    objWrapper.blink = function(strFromColor, strToColor) {
        var objControl = (this.control) ? this.control : this;

        $j(objControl)
            .css('background-color', '' + strFromColor)
            .animate({backgroundColor: '' + strToColor}, 500);
    };
};

qcubed.registerControlArray = function(mixControlArray) {
    var intLength = mixControlArray.length,
        intIndex;

    for (intIndex = 0; intIndex < intLength; intIndex++) {
        this.registerControl(mixControlArray[intIndex]);
    }
};

////////////////////////////////
// QCubed Shortcuts and Initialize
////////////////////////////////

qc = qcubed;
qc.pB = qc.postBack;
qc.pA = qc.postAjax;
qc.getC = qc.getControl;
qc.getW = qc.getWrapper;
qc.regC = qc.registerControl;
qc.regCA = qc.registerControlArray;
qc.recCM = qc.recordControlModification;

qc.initialize();
