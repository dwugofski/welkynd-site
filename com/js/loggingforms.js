
$(document).ready(function(){
	$('#signup_form div.sub').on('click', submit_signup);
	$('#signup_form div.sub').on('keydown', function(e){if (e.keyCode == 13) $('#signup_form div.sub').trigger('click');});
	$('#login_form div.sub').on('click', submit_login);
	$('#login_form div.sub').on('keydown', function(e){if (e.keyCode == 13) $('#login_form div.sub').trigger('click');});

	$('.logintrigger').on('click', render_loginbox);

	$('#screen').on('click', clear_loginbox);
	$('#loginbox .exit').on('click', clear_loginbox);
});

function logout(url){
	$.ajax({
		type: 'POST',
		data: {'clear_session': 'clear'},
		url: url,
		success: function(response){
			location.reload();
		}
	});
}

function clear_loginbox(){
	$('#loginbox').fadeOut(200, 'linear', function(){
		$('#screen').fadeOut(200, 'linear');
	});
}

function render_loginbox(){
	$('#screen').fadeIn(200, 'linear', function(){
		$('#loginbox').fadeIn(200, 'linear');
	});
}

function form_to_array(formid) {
	var olddata = $(formid).serializeArray();
	var data = {};

	$.each(olddata, function(index, value){
		data[value['name']] = value['value'];
	});

	return data;
}

function submit_signup() {
	var data = form_to_array('#signup_form');

	if ($('#loginbox .errorbox').css('display') != 'none') $('#loginbox .errorbox').css({'display': 'none'});

	if (data['password'].localeCompare(data['password2']) != 0) {
		form_error("Passwords do not match", '#loginbox');
		return;
	}

	$('#signup_form div.sub').unbind('click');
	bpulse_start('#signup_form div.sub');
	$.ajax({
		type: 'POST',
		url: data['url'],
		data: data,
		success: function(response){
			bpulse_stop('#signup_form div.sub');
			var json_resp = JSON.parse(response);
			if (json_resp['error'] != false && json_resp['error'] != undefined) form_error(json_resp['error'], '#loginbox');
			else location.reload();
			$('#signup_form div.sub').on('click', submit_signup);
		}
	});
}

function submit_login() {
	var data = form_to_array('#login_form');

	if ($('#loginbox .errorbox').css('display') != 'none') $('#loginbox .errorbox').css({'display': 'none'});

	$('#login_form div.sub').unbind('click');
	bpulse_start('#login_form div.sub');
	$.ajax({
	  type: 'POST',
	  url: data['url'],
	  data: data,
	  success: function(response){
		bpulse_stop('#login_form div.sub');
	  	var json_resp = JSON.parse(response);
	  	if (json_resp['error'] != false && json_resp['error'] != undefined) form_error(json_resp['error'], '#loginbox');
	  	else if (json_resp['success']) location.reload();
		$('#login_form div.sub').on('click', submit_login);
	  }
	});
}

function form_error(errort, wrapper) {
	$(wrapper+' .errorbox').text(errort);
	$(wrapper+' .errorbox').slideDown(400, 'linear');
}