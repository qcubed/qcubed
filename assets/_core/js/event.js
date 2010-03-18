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

	qcubed.handleEvent = function(objEvent) {
		objEvent = (objEvent) ? objEvent : ((typeof(event) == "object") ? event : null);

		if (objEvent) {
			if (typeof(objEvent.clientX) != "undefined") {
				if (qcubed.isBrowser(qcubed.SAFARI)) {
					qcubed.mouse.x = objEvent.clientX - window.document.body.scrollLeft;
					qcubed.mouse.y = objEvent.clientY - window.document.body.scrollTop;
					qcubed.client.x = objEvent.clientX - window.document.body.scrollLeft;
					qcubed.client.y = objEvent.clientY - window.document.body.scrollTop;
				} else {
					qcubed.mouse.x = objEvent.clientX;
					qcubed.mouse.y = objEvent.clientY;
					qcubed.client.x = objEvent.clientX;
					qcubed.client.y = objEvent.clientY;
				};
			};

			if (qcubed.isBrowser(qcubed.IE)) {
				qcubed.mouse.left = ((objEvent.button & 1) ? true : false);
				qcubed.mouse.right = ((objEvent.button & 2) ? true : false);
				qcubed.mouse.middle = ((objEvent.button & 4) ? true : false);
			} else if (qcubed.isBrowser(qcubed.SAFARI)) {
				qcubed.mouse.left = ((objEvent.button && !objEvent.ctrlKey) ? true : false);
				qcubed.mouse.right = ((objEvent.button && objEvent.ctrlKey) ? true : false);
				qcubed.mouse.middle = false;
			} else {
				qcubed.mouse.left = (objEvent.button == 0);
				qcubed.mouse.right = (objEvent.button == 2);
				qcubed.mouse.middle = (objEvent.button == 1);
			};

			qcubed.key.alt = (objEvent.altKey) ? true : false;
			qcubed.key.control = (objEvent.ctrlKey) ? true : false;
			qcubed.key.shift = (objEvent.shiftKey) ? true : false;
			qcubed.key.code = (objEvent.keyCode) ? (objEvent.keyCode) : 0;
			
			if (objEvent.originalTarget)
				qcubed.target = objEvent.originalTarget;
			else if (objEvent.srcElement)
				qcubed.target = objEvent.srcElement;
			else
				qcubed.target = null;
		};
			
		var readScroll = {scrollLeft:0,scrollTop:0};
		var readSize = {clientWidth:0,clientHeight:0};
		var readScrollX = 'scrollLeft';
		var readScrollY = 'scrollTop';
		var readWidth = 'clientWidth';
		var readHeight = 'clientHeight';
		
		function detectWindow(obj){
			if ((document.compatMode) && (document.compatMode == 'CSS1Compat') && (document.documentElement))
				return document.documentElement;
			else if(document.body)
				return document.body;
			else
				return obj;					
		};

		if((typeof this.innerHeight == 'number')&& (typeof this.innerWidth == 'number')){
			readSize = this;
			readWidth = 'innerWidth';
			readHeight = 'innerHeight';
		} else
			readSize = detectWindow(readSize);						
	
		if ((typeof this.pageYOffset == 'number') && (typeof this.pageXOffset == 'number')){
			readScroll = this;
			readScrollY = 'pageYOffset';
			readScrollX = 'pageXOffset';
		} else
			readScroll = detectWindow(readScroll);				
		
			
			
		qcubed.client.width = readSize[readWidth] || 0;
		qcubed.client.height = readSize[readHeight] || 0;

		qcubed.page.width = Math.max(window.document.body.scrollWidth, qcubed.client.width);
		qcubed.page.height = Math.max(window.document.body.scrollHeight, qcubed.client.height);
		
		qcubed.scroll.x = readScroll[readScrollX] || 0;
		qcubed.scroll.y = readScroll[readScrollY] || 0;

		// These Values are "By Definition"
		qcubed.page.x = qcubed.mouse.x + qcubed.scroll.x;
		qcubed.page.y = qcubed.mouse.y + qcubed.scroll.y;

		qcubed.scroll.width = qcubed.page.width - qcubed.client.width;
		qcubed.scroll.height = qcubed.page.height - qcubed.client.height;

		return objEvent;
	};

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



///////////////////////////////
// Event Stats-Releated Objects
///////////////////////////////

	qcubed.key = {
		control: false,
		alt: false,
		shift: false,
		code: null
	};

	qcubed.mouse = {
		x: 0,
		y: 0,
		left: false,
		middle: false,
		right: false
	};

	qcubed.client = {
		x: null,
		y: null,
		width: null,
		height: null
//		width: (qcubed.isBrowser(qcubed.IE)) ? window.document.body.clientWidth : window.innerWidth,
//		height: (qcubed.isBrowser(qcubed.IE)) ? window.document.body.clientHeight : window.innerHeight
	};

	qcubed.page = {
		x: null,
		y: null,
		width: null,
		height: null
//		width: window.document.body.scrollWidth,
//		height: window.document.body.scrollHeight
	};

	qcubed.scroll = {
		x: window.scrollX || (window.document.body) ? window.document.body.scrollLeft : null,
		y: window.scrollY || (window.document.body) ? window.document.body.scrollTop : null,
//		x: null,
//		y: null,
		width: (window.document.body) ? (window.document.body.scrollWidth - qcubed.client.width) : null,
		height: (window.document.body) ? (window.document.body.scrollHeight - qcubed.client.height) : null
//		width: null,
//		height: null
	};