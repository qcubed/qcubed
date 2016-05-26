/**
 * Functions for support of the $ UI autocomplete widget. These are broken out here, rather than kept in qcubed.js
 * for a couple of reasons:
 * - The code is getting a little long, so its nice to only include it when an autocomplete is on the page
 * - It overrides the global ui attributes. If $UI is not installed, this will cause an error. So, by
 *   putting the javascript here, we can assume $UI is installed.
 */

(function( $, undefined ) {

qcubed.autocomplete = function(controlId) {
    var jqObj = $('#' + controlId);
    jqObj.on("autocompleteselect", function (event, ui) {
        qc.recCM(this.id, "SelectedId", ui.item.id);
        qc.formObjChanged(event);
    })
        .on("autocompletefocus", function (event, ui) {
            if ( /^key/.test(event.originalEvent.type) ) {
                qc.recCM(this.id, "SelectedId", ui.item.id);
                qc.formObjChanged(event);
            }
        })
        .on("autocompletechange", function( event, ui ) {
            var toTest = ui.item ? (ui.item.value ? ui.item.value : ui.item.label) : '';
            if ( !ui.item ||
                $( this ).val() != toTest) {
                // remove invalid value, as no match
                qc.recCM(this.id, "SelectedId", '');
            }
            else {
                // items might change even when no menu item is selected
                qc.recCM(this.id, "SelectedId", ui.item.id);
            }
            qc.formObjChanged(event); // to record the change to the text
        });
}
qcubed.acSourceFunction = function (request, response) {
    this.acResponse = response; // save for later, to be called by responding javascript
    var el = $(this.element);
    el.trigger('QAutocomplete_Source', request.term); // tell ourselves to go get the data
}
qcubed.acSetData = function(controlId, data) {
    var jqObj = $('#' + controlId);
    var i = jqObj.autocomplete("instance");
    var f = i.acResponse;
    f(data);
}
// Autocomplete custom filtering support
qcubed.acUseFilter = function(filter) {
    $.ui.autocomplete.regEx = filter;
}

$.extend( $.ui.autocomplete, {
    escapeRegex: function( value ) {
        return value.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
    },
    regEx: function ( term ) {
        return $.ui.autocomplete.escapeRegex(term);
    },
    filter: function(array, term) {
        var matcher =  new RegExp( this.regEx(term), "i" );
        return $.grep( array, function(value) {
            return matcher.test( value.label || value.value || value );
        });
    }
});

}( jQuery ));
