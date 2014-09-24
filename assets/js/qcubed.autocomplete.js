/**
 * qcubed.autocomplete.js
 * 
 * Javascript to empower a QAutocomplete to have a variety of behaviors. These javascript routines
 * are in this separate file so that they are included only once.
 * 
 */
 
function qAutocomplete (strControlId) {
	var jqObj = jQuery('#' + strControlId);
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
				qcubed.recordControlModification(this.id, "SelectedId", '');
		}
		else {
			// items might change even when no menu item is selected
			qcubed.recordControlModification(this.id, "SelectedId", ui.item.id);
		}
	});
}