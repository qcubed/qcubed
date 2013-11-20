/**
 * Toggle Image, + -
 * @param {string} strControlId The treenav control id
 */
function treenavToggleImage(strControlId) {
    var blnShow = treenavToggleDisplay(strControlId + "_sub", null, "block"),
        objImage = document.getElementById(strControlId + "_image"),
        strPath = qc.imageAssets + "/treenav_expanded.png",
        strPathNotExpanded = qc.imageAssets + "/treenav_not_expanded.png",
        strActualControlId;

    objImage.src = (blnShow) ? strPath : strPathNotExpanded;
    strActualControlId = strControlId.substr(0, strControlId.indexOf('_'));
    qcubed.recordControlModification(strActualControlId, 'ItemExpanded', strControlId + ((blnShow) ? ' 1' : ' 0'));
}

/**
 * Toggles the display/hiding of the entire control (including any design/wrapper HTML)
 * If ShowOrHide is blank, then we toggle
 * Otherwise, we'll execute a "show" or a "hide"
 * @param {string|Object} mixControl The DOM element or a string id.
 * @param {string} strShowOrHide "show or "hide" the element
 * @param {string} strDisplayStyle The display style to return to (block, inline, inline-block)
 * @returns {undefined|Boolean} True if showing, false if hiding.
 */
function treenavToggleDisplay(mixControl, strShowOrHide, strDisplayStyle) {
    var objControl = qcubed.getControl(mixControl);

    if (!objControl) {
        return;
    }

    if (strShowOrHide) {
        if (strShowOrHide === "show") {
            objControl.style.display = strDisplayStyle;
            return true;
        } else {
            objControl.style.display = "none";
            return false;
        }
    } else {
        if (objControl.style.display === "none") {
            objControl.style.display = strDisplayStyle;
            return true;
        } else {
            objControl.style.display = "none";
            return false;
        }
    }
}

/**
 * Unselect an element.
 * @param {string} strControlId The control's id.
 * @param {string} strStyleName The class name to remove.
 */
function treenavItemUnselect(strControlId, strStyleName) {
    var objControl = document.getElementById(strControlId);
    objControl.className = strStyleName;
    objControl.onmouseout = function() {
        treenavItemSetStyle(strControlId, strStyleName);
    };
}

/**
 * Set the control's style.
 * @param {string} strControlId The control's id.
 * @param {string} strStyleName The class name to apply.
 */
function treenavItemSetStyle(strControlId, strStyleName) {
    var objControl = document.getElementById(strControlId);
    objControl.className = strStyleName;
}

/**
 * Redraw the element.
 * @param {string} strElementId The element's id.
 * @param {string} strHtml The HTML for the element.
 */
function treenavRedrawElement(strElementId, strHtml) {
    document.getElementById(strElementId).innerHTML = strHtml;
}