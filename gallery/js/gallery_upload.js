
$(document).ready(function() {

	$('#upl_cont').plupload({
		runtimes : 'html5,flash,silverlight,html4',
		url : 'gallery/php/upload.php',

		max_file_count: 20,
		chunk_size: '1mb',

		filters : {
			max_file_size : '10mb',
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png"}
			]
		},

		rename: false,
		sortable: true,
		dragdrop: true,

		views: {
			list: true,
			thumbs: true,
			active: 'thumbs'
		},

		flash_swf_url : 'gallery/js/Moxie.swf',
		silverlight_xap_url : 'gallery/js/Moxie.xap',

		init: {
			BeforeUpload: function (up, file) {
				var hash = md5.create();
				var num_matches = 0;
				for (var i = 0; i < up.files.length; i++) {
					if (up.files[i].name == file.name) {
						var extension = up.files[i].name.split('.').pop();
						hash.update(up.files[i].name);
						hash.update(num_matches);
						num_matches += 1;
						var today = new Date();
						var newname = today.getTime() + '_' + hash.hex() + '.' + extension;
						up.files[i].target_name = newname;
						up.files[i].name = newname;
					}
				}
			}
		}
	});
});

var gallery_upload = (function(){
	return {
		form_sub: function(){
			var problem = false;

			if ($('#upl_user_inp').val() == '') problem = 'User must be logged in to submit form.';
			if ($('#upl_album_inp').val() == '') problem = 'You must choose an album to upload to.';
			if ($('#upl_cont').plupload('getFiles').length <= 0) problem = 'You must have at least one file in the queue.';

			if (problem == false) {
				$('#upl_cont').on('complete', function() {
					var form_data = $('#upl_form').serializeArray().reduce(function(obj, item) {
						obj[item.name] = item.value;
						return obj;
					}, {});

					$.ajax({
						type: "POST",
						url: 'gallery/php/dump.php',
						data: form_data,
						success: gallery_upload.post_response,
						error: gallery_upload.post_error
					});

					$('#upl_cont').splice();
					$('#upl_form').slideUp("slow");
					$('#upl_load').slideDown("slow");
				});

				$('#upl_cont').plupload('start');
			} else {
				form_error(problem, '#upl_sec');
			}
		},

		post_response: function(data, status, jqXHR){
			$('#upl_form').slideUp("slow");
			$('#upl_load').slideUp("slow");
			data = JSON.parse(data);
			if (data['error'] != false) gallery_upload.post_error(null, null, data['error']);
			else {
				$('#detail_form').html(data['html']);
				var sub_dets = $('<div>').addClass('sub').text('Submit Edits').click(Gallery.sub_details);
				$('#detail_form').append(sub_dets);
				$('#detail_form').slideDown("slow");
			}
		},

		post_error: function(jqXHR, status, error){
			console.log(error);
		}
	};
})();