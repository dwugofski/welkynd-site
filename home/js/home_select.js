
/*$(document).ready(function(){
	$.each($('#lnav a'), function(index, value){
		$(value).on('click', change_section);
	});

	if ($.cookie('sel_sec') == undefined) $.cookie('sel_sec', 'about', {expires: 7, path: '/'});

	var secname = $.cookie('sel_sec');

	if ($('#'+secname+'_sec').length == 0) {
		$.cookie('sel_sec', 'about', {expires: 7, path: '/'})
		secname = $.cookie('sel_sec');
	}

	$('#'+secname+'_lnk').addClass('current');
	$('#'+secname+'_sec').css({'display': 'block'});
});

function change_section(event_object){
	link = event_object.target;
	if(link === undefined) return;
	if($(link).hasClass('current')) return;


	$.each($('#lnav a'), function(index, value){
		if ($(value).hasClass('current')) $(value).removeClass('current');
	});

	var name = $(link).attr('id');
	name = name.replace('_lnk', '');
	$.cookie('sel_sec', name, {expires: 7, path: '/'});
	var $sec = $('#'+name+'_sec');

	$(link).addClass('current');

	$.each($('#cpanel section'), function(index, value){
		if ($(value).css('display') != 'none') {
			$(value).slideUp(400, 'linear');
		};
	});

	if ($sec.css('display') == 'none') {
		$sec.slideDown(400, 'linear');
	}
}*/