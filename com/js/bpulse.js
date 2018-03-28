
function bpulse_start(elem) {
	$(elem).addClass('bpulse');
	bpulse(elem, false);
}

function bpulse_stop(elem) {
	$(elem).removeClass('bpulse');
}

function bpulse(elem, on) {
	var $elem = $(elem);
	if (!$elem.hasClass('bpulse') && !on) return true;

	var opc = on ? '1' : '0.7';
	$elem.animate({'opacity': opc}, 400, function(){
		bpulse(elem, !on);
	});
}