/**
 * qAutocomplete.js
 * 
 * Javascript to empower a QAutocomplete to have a variety of behaviors. These javascript routines
 * are in this separate file so that they are included only once.
 * 
 */

function qAutocomplete (qOptions) {
	var jqObj = jQuery('#' + qOptions['controlId']);
	jqObj.data('qOptions', qOptions);
	
	if (qOptions['multiValDelim']) {
		// This is an adaptation from code found on the JQuery UI Autocomplete Web site. 
		// Its not the best multi-item selector, but it DOES support Ajax.
		
		// Note that since this is a multi-select, updating SelectedId is not meningful
		// don't navigate away from the field on tab when selecting an item.
		jqObj.bind("keydown", function(event) {
			if (event.keyCode === jQuery.ui.keyCode.TAB && jQuery(this).data("autocomplete").menu.active) {
				event.preventDefault();
			}
		})
		.data("delimExp", new RegExp (qOptions['multiValDelim'] + "\\s*", "g"))
		.data("curTerm", function (el, newVal) {
			// if newVal is present, replaces that term with newVal and returns the full value of the field
			// if no newVal, returns just the current term
			var jqObj = jQuery(el);
			var curVal = jqObj.val();
			var delimExp = jqObj.data('delimExp');
			var delim = jqObj.data('qOptions')['multiValDelim'];
			
			// get caret position
			var caretPos = 0;
			if (document.selection) { // IE
				el.focus();
				var range = document.selection.createRange();
				range.moveStart("character", -curVal.length);
				caretPos = range.text.length;
			} else if (el.selectionStart) { // MOZ
				caretPos = el.selectionStart;
			}
			// find which term the caret is in
			var matches = curVal.substring(0, caretPos).match(delimExp);
			var termIdx = matches ? matches.length : 0;
			var terms = curVal.split(delimExp);
			if (termIdx >= terms.length) {
				termIdx = terms.length;
				terms.push("");
			}
			if (newVal !== undefined) { // setting the value
				if (newVal !== null)
					terms[termIdx] = newVal;
				else
					terms.splice(termIdx, 1);
				if (terms.length && terms[terms.length-1]) {
					terms.push("");
				}
				return terms.join(delim + ' ');
			}
			return terms[termIdx];
		})
		.on("autocompleteselect", function (event, ui) {
			var sel = ui.item ? (ui.item.value ? ui.item.value : ui.item.label) : "";
			this.value = jQuery(this).data("curTerm")(this, sel);
			return false;
		})
		.on("autocompletefocus", function () {
			return false;
		});
	} else {
		jqObj.on("autocompleteselect", function (event, ui) {
		    qcubed.recordControlModification(this.id, "SelectedId", ui.item.id);
		})
		.on("autocompletefocus", function (event, ui) {
			if ( /^key/.test(event.originalEvent.type) ) {
		 		qcubed.recordControlModification(this.id, "SelectedId", ui.item.id);
			}
		})
		.on("autocompletechange", function( event, ui ) {
			var toTest = ui.item ? (ui.item.value ? ui.item.value : ui.item.label) : '';
			if ( !ui.item ||
				jQuery( this ).val() != toTest) {
					// remove invalid value, as no match 
					if (jQuery(this).data("qOptions")["mustMatch"]) {
						jQuery( this ).val( "" );
						jQuery( this ).data( "autocomplete" ).term = '';
					}
					qcubed.recordControlModification(this.id, "SelectedId", '');
			}
		});
	};
	
	if (qOptions['displayHtml']) {
		jqObj.data( "autocomplete" )._renderItem = function( ul, item ) {
            return jQuery( "<li>" )
	            .data( "item.autocomplete", item )
	            .append( jQuery( "<a></a>" ).html(item.label) )
	            .appendTo( ul );
	    };
	};
}