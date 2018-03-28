
$(document).ready(function(){
	$('#picdesc').click(Gallery.edit_details.bind(Gallery));
	$('#picedit_btn').click(Gallery.edit_details.bind(Gallery));
	$('#det_sec .button').click(Gallery.sub_details.bind(Gallery));

	$('#picdel_btn').click(Gallery.delete_image.bind(Gallery));

	$('#del_lnk').click(Gallery.render_album_deletion.bind(Gallery));

	$('#img_arrow_r').click({'diff' : 1}, Gallery.arrow_click.bind(Gallery));
	$('#img_arrow_l').click({'diff' : -1}, Gallery.arrow_click.bind(Gallery));

	$.post('gallery/php/get_albums.php', Gallery.generate_links.bind(Gallery));

	$('#lnav a').not('.al_lnk').not('#gal_lnk').click(function(e){
		if (Gallery.albums && Gallery.albums.length > 0) {
			if ($($('#lnav .al_lnk')[0]).css('display') != 'none') Gallery.disappear_links(0);
		}
	}.bind(Gallery));

	$('#gal_lnk').click(function(e){
		if (Gallery.albums && Gallery.albums.length > 0) {
			if ($('[id=\"'+$.cookie('sel_alb')+'_al_lnk\"]').length <= 0) $.cookie('sel_alb', encodeURI($('.al_lnk').attr('sname')), {expires: 7, path: '/'});
			if ($($('#lnav .al_lnk')[0]).css('display') == 'none') Gallery.appear_links(0, function(){});
			$('[id=\"'+$.cookie('sel_alb')+'_al_lnk\"]').addClass('current');
			$('[id=\"'+$.cookie('sel_alb')+'_al_lnk\"]').trigger('click');
		}
	}.bind(Gallery));
});

var Gallery = {
	name : '',
	id : '',
	IMG_PER_ROW : 4,
	albums : [],
	pics : [],
	i : 0,
	loading_pics : false,
	initialized : false,
	curr_id : '',
	pic_requested : false,

	get_parameter(param_name, encoded=false){
		if (!encoded) param_name = encodeURI(param_name);
		var ret = null;
		var tmp = [];
		window.location.search.substr(1).split('&').forEach(function(item){
			tmp = item.split('=');
			if (tmp[0] == param_name) ret = decodeURI(tmp[1]);
		});
		return ret;
	},

	parse_date : function(date){
		date = new Date(date);
		var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		var day = date.getDate();
		var month = months[date.getMonth()];
		var year = date.getFullYear();
		var hour = (date.getHours()-1) % 12 + 1;
		var minutes = date.getMinutes();
		if (minutes < 10) minutes = "0" + minutes;
		var am = "am";
		var hours = date.getHours();
		if ((hours > 11 && hours < 24) || hours == 0) am = "pm";
		return day + " " + month + ", " + year + " " + hour + ":" + minutes + " " + am;
	},

	mod : function(n, m){
		return ((n%m)+m)%m;
	},

	generate_links : function(albums){
		albums = JSON.parse(albums);
		if (albums === undefined || albums === null) albums = [];
		this.albums = albums;
		if (this.albums.length > 0) $('#pic_btn_wrap').css('display', 'block');
		albums = albums.reverse();
		var gal_lnk = $('#gal_lnk');
		var current_alb = false;
		var get_alb = this.get_parameter('album');
		var pic_req = this.get_parameter('pic');
		if (pic_req) this.pic_requested = pic_req;
		var ids = [];
		for (var i=0; i<albums.length; i+=1){
			var album = albums[i];
			var al_lnk = $('<a>').addClass('al_lnk').addClass('sub');
			if (i == 0) al_lnk.addClass('last');
			if (i == albums.length-1) al_lnk.addClass('first');
			al_lnk.attr('id', album.sname + "_al_lnk");
			al_lnk.attr('name', album.name);
			al_lnk.attr('sname', album.sname);
			al_lnk.click({'name': album.name, 'id': album.sname}, function(e){this.query_album_pics(e.data)}.bind(this));
			al_lnk.css('display', 'none');
			al_lnk.text(album.name);
			gal_lnk.after(al_lnk);

			ids.push(album.sname);

			if (get_alb != null) {
				if (get_alb == album.sname) current_alb = al_lnk;
			}
		}

		if (gal_lnk.hasClass('current') && this.albums.length > 0) {
			if (current_alb != false) $.cookie('sel_alb', encodeURI(current_alb.attr('name')), {expires: 7, path: '/'});
			else if ($.cookie('sel_alb') != undefined && ids.indexOf($.cookie('sel_alb')) >= 0) current_alb = $('[id=\"'+$.cookie('sel_alb')+'_al_lnk\"]');
			else {
				current_alb = $($('#lnav .al_lnk')[0]);
				$.cookie('sel_alb', encodeURI(current_alb.attr('name')), {expires: 7, path: '/'});
			}
			current_alb.addClass('current');
			this.appear_links(0, function(){current_alb.trigger("click");}.bind(this));
		}

		this.initialized = true;

		if ($('#del_lnk').hasClass('current')) $('#del_lnk').trigger('click');
	},

	delete_image : function(e){
		if ($('#del_sec').length == 0) return;
		if (Array.isArray(this.albums) == false || this.albums.length <= 0) return;
		if (!confirm("Are you sure you wish to delete this image? Click 'OK' to delete image.")) return;
		var id = this.curr_id;
		$.post('gallery/php/delete.php', {'subbed' : false, 'ids' : [id]}, function(resp){
			location.reload();
		}.bind(this));
	},

	delete_album : function(e){
		if ($('#del_sec').length == 0) return;
		if (Array.isArray(this.albums) == false || this.albums.length <= 0) return;
		var album = $(e.target).attr('name');
		if (!confirm("Are you sure you wish to delete this album? Click 'OK' to delete '"+album+"'.")) return;
		var id = this.curr_id;
		$.post('gallery/php/delete.php', {'subbed' : false, 'albums' : [album]}, function(resp){
			location.reload();
		}.bind(this));
	},

	edit_details : function(e){
		if ($('#det_form').length == 0) return;
		if (Array.isArray(this.albums) == false || this.albums.length <= 0) return;
		var id = this.curr_id;
		$.post('gallery/php/edit_images.php', {'subbed' : false, 'ids' : [id]}, function(resp){
			$('section').slideUp("slow");
			$('#det_sec').slideDown("slow");
			$('#det_form').html(resp);
		}.bind(this));
	},

	sub_details : function(e){
		var container = $(e.target).parent().attr('id');
		var form_id = '#detail_form';
		if (container == 'det_sec') form_id = '#det_form';
		var form_ser = $(form_id).serializeArray();
		var form = {};
		for (var i=0; i<form_ser.length; i+=1)
			form[form_ser[i]['name']] = form_ser[i]['value'];
		$.post('gallery/php/edit_images.php', {'subbed' : true, 'form' : form}, function(resp){
			$.cookie('sel_sec', 'gal', {expires: 7, path: '/'});
			$.cookie('sel_alb', form['album'], {expires: 7, path: '/'});
			location.reload();
		}.bind(this));
	},

	div_click : function(e){
		this.change_display(e.data.i);
	},

	arrow_click : function(e){
		if (this.pics.length <= 0) return;
		var i = this.mod((this.i + e.data.diff), this.pics.length);
		this.change_display(i);
	},

	query_album_pics : function(data){
		if (this.name == data.name) return;
		if (this.loading_pics) return;
		this.clear();
		this.loading_pics = true;
		this.name = data.name;
		this.id = data.id;
		$('[id=\"'+data.id+'_al_lnk\"]').addClass('current');
		$.cookie('sel_alb', data.id, {expires: 7, path: '/'});
		$.post('gallery/php/load_album_pics.php', {'album': data.name}, this.render_pics.bind(this));
	},

	clear : function(){
		if (this.id == '') return;
		var galry = $("#gallery");
		galry.empty();

		var disp = $("#img_disp");
		disp.attr('src', '');

		$('.al_lnk').removeClass('current');
	},

	render_pics : function(album_pics){
		var disp = 0;
		album_pics = JSON.parse(album_pics);
		if (album_pics.length <= 0) return;
		this.pics = album_pics;
		if (this.pic_requested) {
			for(var j=0; j<this.pics.length; j+=1) {
				if (this.pic_requested == this.pics[j].id) {
					disp = j;
					break;
				}
			}
			this.pic_requested = false;
		}
		var galry = $("#gallery");
		this.render_thumb_row(album_pics, galry, disp);

		this.appear_pics(0);
	},

	render_album_deletion : function(e) {
		var delsec = $('#del_sec');
		delsec.empty();
		for (var i=0; i<this.albums.length; i+=1) {
			var album_ttl = $('<div>');
			album_ttl.addClass('del_albm');
			album_ttl.text(this.albums[i].name);
			album_ttl.attr('name', this.albums[i].name);
			album_ttl.attr('id', this.albums[i].sname+"_del_btn");
			album_ttl.click(this.delete_album.bind(this));
			var album_box = $('<table>');
			album_box.addClass('galbox');
			$.post('gallery/php/load_album_pics.php', {'album': this.albums[i].name}, (function(box){
				return function(album_pics) {
					album_pics = JSON.parse(album_pics);
					if (album_pics.length <= 0) return;
					if (album_pics.length > this.IMG_PER_ROW) album_pics = album_pics.slice(0, this.IMG_PER_ROW);
					this.render_thumb_row(album_pics, box, 0, false);
				}.bind(this);
			}.bind(this))(album_box));
			delsec.append(album_ttl);
			delsec.append(album_box);
		}
	},

	render_thumb_row : function(album_pics, container, disp, preview=true) {
		var row = undefined;
		for(var i=0; i<album_pics.length; i+=1){
			if (i % this.IMG_PER_ROW == 0){
				row = $('<tr>');
				container.append(row);
			}
			var pic = album_pics[i];
			var pic_cell = $('<td>');
			pic_cell.attr('id', pic.id + '_cell');
			if (preview) pic_cell.css('opacity', '0');
			var pic_img = $('<img>');
			pic_img.attr('id', pic.id + "_img");
			pic_img.attr('src', "gallery/php/uploads/"+pic.image);
			pic_img.attr('num', i);
			var pic_div = $('<div>');
			pic_div.attr('id', pic.id + "_div");
			pic_div.text(pic.name);
			if (preview) pic_div.click({'id': pic.id, 'i' : i}, this.div_click.bind(this));

			row.append(pic_cell);
			pic_cell.append(pic_img);
			pic_cell.append(pic_div);
			if (i == disp && preview) pic_div.trigger("click");
		}
		for (var j=i%4; j%4>0; j+=1) {
			row.append($('<td>'));
		}
		container.append(row);
	},

	change_display : function(i){
		var pic = this.pics[i];
		var galry = $("#gallery");
		galry.find('td').removeClass('displayed');
		$("#"+pic.id+"_cell").addClass('displayed');

		var disp = $("#img_disp");
		disp.css("opacity", "0");
		disp.attr("src", "gallery/php/uploads/"+pic.image);
		disp.fadeTo(600, 1, "linear");

		var desc = $("#picdesc");
		desc.find('h1').text(pic.name);
		desc.find('h3').text(this.parse_date(pic.date) + " - Submitted by "+pic.user);
		desc.find('p').text(pic.description);
		this.i = i;
		this.curr_id = pic.id;
	},

	appear_links : function(i, callback){
		album = this.albums[i];
		if (i < this.albums.length - 1) $("[id=\""+album.sname+"_al_lnk\"]").slideDown(10, "linear", function(){this.appear_links(i+1, callback);}.bind(this));
		else {
			$("[id=\""+album.sname+"_al_lnk\"]").slideDown(10, "linear");
			callback();
		}
	},

	disappear_links : function(i){
		album = this.albums[i];
		if (i < this.albums.length - 1) $("[id=\""+album.sname+"_al_lnk\"]").slideUp(10, "linear", function(){this.disappear_links(i+1);}.bind(this));
		else $("[id=\""+album.sname+"_al_lnk\"]").slideUp(10, "linear");
	},

	appear_pics : function(i){
		pic = this.pics[i];
		if (i < this.pics.length - 1) $("#"+pic.id+"_cell").fadeTo(300, 1, "linear", function(){this.appear_pics(i+1);}.bind(this));
		else {
			$("#"+pic.id+"_cell").fadeTo(300, 1, "linear");
			this.loading_pics = false;
		}
	}
};