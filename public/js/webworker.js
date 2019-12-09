importScripts('./ajax.js');
let timeReqLastPosition;
let timeReqLastNotif;
function reqLastPosition(address){
	ajax(address, null, 'GET', function(c) {
		self.postMessage({ cmd: 'resLastPosition', val: c.data });
		timeReqLastPosition = setTimeout(function() { reqLastPosition(address); }, 30000);
	});
}
function reqLastNotif(address){
	ajax(address, null, 'GET', function(c) {
		self.postMessage({ cmd: 'resLastNotif', val: c.data });
		timeReqLastNotif = setTimeout(function() { reqLastPosition(address); }, 30000);
	});
}
self.addEventListener('message', function(a) {

	let b = a.data;
	let x = 1;
	switch (b.cmd) {
		case 'endLastPosition':
			clearTimeout(timeReqLastPosition);
		break;
		case 'endLastNotif':
			clearTimeout(timeReqLastNotif);
		break;                
		case 'reqLastPosition':
				reqLastPosition(b.val);
		break;
		case 'reqLastNotif':
			reqLastNotif(b.val);
		break;
		
		default:
			self.postMessage(1/x);
	}
}, false);