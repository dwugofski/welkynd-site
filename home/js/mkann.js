
$(document).ready(function(){
	$('#mkann_form div.sub').on('click', mk_ann);
});

function mk_ann(){
	var data = form_to_array('#mkann_form');

	$('#mkann_form div.sub').unbind('click');
	bpulse_start('#mkann_form div.sub');
	$.ajax({
		type: 'POST',
		url: 'home/php/mkann_submit.php',
		data: data,
		success: function(response){
			bpulse_stop('#mkann_form div.sub');
			console.log(response);
			var json_resp = JSON.parse(response);
			if (json_resp['error'] != false && json_resp['error'] != undefined) form_error(json_resp['error'], '#make_sec');
			else {
				$.cookie('sel_sec', 'about', {expires: 7, path: '/'});
				location.reload();
			}
			$('#mkann_form div.sub').on('click', mk_ann);
		}
	});
}