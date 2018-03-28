
$(document).ready(function(){
	fill_pann_box({'data': {'page': 0}});
});

function fill_pann_box(event) {
	var page = event.data.page;
	//$('#pann_loading').fadein({'display': 'block'});
	$('#pann_loading').fadeIn();
	$('#pann_box').html('');
	$('#pann_new_butt').css({'display': 'none'});
	$('#pann_old_butt').css({'display': 'none'});
	var d = new Date();

	$.ajax({
		type: 'POST',
		url: 'home/php/ann_list.php',
		data: {'start': page, 'tab_pre': "\t\t\t\t\t\t"},
		success: function(response){
			var json_resp = JSON.parse(response);
			if (json_resp.error !== null && json_resp.error !== undefined && json_resp.error != false) {
				$('#pann_loading').css({'display': 'none'});
				console.log(json_resp.error);
				$('#past_sec .errorbox').text('ERROR: ' + json_resp.error);
				$('#past_sec .errorbox').slideDown(400, 'linear');
				return;
			}
			$('#pann_box').html(json_resp.html);
			$('#pann_loading').css({'display': 'none'});

			var npage = page - 1;
			var ppage = page + 1;

			if (json_resp.goback == false) ppage = 0;
			else $('#pann_old_butt').css({'display': 'block'});

			if (json_resp.goforward == false) npage = 0;
			else $('#pann_new_butt').css({'display': 'block'});

			$('#pann_new_butt').off('click', fill_pann_box);
			$('#pann_old_butt').off('click', fill_pann_box);
			$('#pann_new_butt').on('click', null, {'page': npage}, fill_pann_box);
			$('#pann_old_butt').on('click', null, {'page': ppage}, fill_pann_box);
		}
	});
}