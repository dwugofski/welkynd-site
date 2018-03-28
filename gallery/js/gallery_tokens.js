$(document).ready(function () {
	sugg.hookup({id: '#upl_album', url: 'gallery/php/album_tokens.php'});
});

var sugg = (function(){
	return {
		hookup: function(details) {
			$(details.id+'_inp').on('input', {id: details.id, url: details.url}, this.update);
			$(details.id).focusout(details, this.unfocus);
			$(details.id).focusin(details, this.refocus);
		},

		unfocus: function(e){
			if(($(e.data.id).is(':hover')) == false && $(e.relatedTarget).parent($(e.data.id + '_sugg')).length == 0) $(e.data.id + '_sugg').slideUp();
		},

		refocus: function(e){
			if ($(e.data.id + '_inp').val() == '') $(e.data.id + '_sugg').empty();
			$(e.data.id + '_sugg').slideDown();
		},

		empty: function(id){
			$(id + '_sugg').empty();
		},

		addsel: function(id, i, text){
			$newtoken = $('<div>').addClass('item').addClass('sel').attr('tabindex', i).text(text);
			$newtoken.on('click', {id: id, text: text}, this.autofill);
			$(id + '_sugg').append($newtoken);
		},

		autofill: function(e){
			$(e.data.id + '_inp').val(e.data.text);
			$(e.data.id + '_inp').trigger("input");
		},

		update: function(e){
			sugg.empty(e.data.id);
			$(e.data.id + '_sugg').append($('<div>').addClass('item').addClass('dialog').text('Searching...'));
			
			$.ajax({
				method: 'GET',
				url: e.data.url,
				data: {id: e.data.id, val: $(e.data.id + '_inp').val()},
				success: sugg.list,
				dataType: 'json'
			});
		},

		list: function(match_data){
			sugg.empty(match_data.id);
			for(var i=0; i<match_data.matches.length; i+=1){
				sugg.addsel(match_data.id, i, match_data.matches[i]);
			}
		}
	};
})();