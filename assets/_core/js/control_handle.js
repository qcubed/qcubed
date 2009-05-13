///////////////////////////////
// Control Handle Functionality
///////////////////////////////

	qcodo.registerControlHandle = function(mixControl, strCursor) {
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
		var objWrapper = objControl.wrapper;

		if (!objWrapper.handle) {
			var objHandle = document.createElement("span");
			objHandle.id = objWrapper.id + "handle";
			objWrapper.parentNode.appendChild(objHandle);

			objWrapper.handle = objHandle;
			objHandle.wrapper = objWrapper;
			objHandle.style.cursor = strCursor;
			objHandle.style.zIndex = 999;
			objHandle.style.backgroundColor = "white";
			if (qcodo.isBrowser(qcodo.IE))
				objHandle.style.filter = "alpha(opacity=0)";
			else
				objHandle.style.opacity = 0.0;
			objHandle.style.position = "absolute";
			
			// Setup height, width, top and left based on parent wrapper
			var objAbsolutePosition = objWrapper.getAbsolutePosition();
			objHandle.style.top = objAbsolutePosition.y * 1.0;
			objHandle.style.left = objAbsolutePosition.x * 1.0;
			objHandle.style.width = objWrapper.offsetWidth + "px";
			objHandle.style.height = objWrapper.offsetHeight + "px";
			
			objHandle.style.fontSize = "1px";
			objHandle.innerHTML = ".";
		};

		objWrapper.updateHandle = function(blnUpdateParent, strCursor) {
			var objHandle = this.handle;

			// Make Sure the Wrapper's Parent owns this Handle
			if (blnUpdateParent) {
				this.parentNode.appendChild(objHandle);
				objHandle.style.top = this.offsetTop + "px";
				objHandle.style.left = this.offsetLeft + "px"; 
			} else {
				var objAbsolutePosition = objWrapper.getAbsolutePosition();
				objHandle.style.top = objAbsolutePosition.y * 1.0;
				objHandle.style.left = objAbsolutePosition.x * 1.0;				
			}

			objHandle.style.width = objWrapper.offsetWidth + "px";
			objHandle.style.height = objWrapper.offsetHeight + "px";
			
			// Update the Cursor
			if (strCursor)
				objHandle.style.cursor = strCursor;
		};
	};



//////////////////
// Qcodo Shortcuts
//////////////////

	qc.regCH = qcodo.registerControlHandle;
