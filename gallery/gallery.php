<?php 

$javascripts = ['com/js/md5.js', 'com/js/jquery-ui.js', 'gallery/js/plupload.full.min.js', 'gallery/js/jquery.ui.plupload/jquery.ui.plupload.js', 'gallery/js/gallery_upload.js', 'com/js/select2.min.js', 'gallery/js/gallery_tokens.js', 'gallery/js/load_albums.js'];
$css_files = ['com/css/jquery-ui.css', 'gallery/js/jquery.ui.plupload/css/jquery.ui.plupload.css'];

include $compath."php/def_head.php";
?>
				<td id="lpanel">
<?php include 'com/php/def_sidebar.php'; ?>
					<nav id="lnav">
						<a id="gal_lnk">Albums</a>
<?php 
if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'post images')) echo '<a id="upl_lnk">Upload Screenshots</a>';
if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'delete images')) echo '<a id="del_lnk">Delete Album</a>';
?>
					</nav>
				</td>
				<td id="cpanel">
					<section id="gal_sec">
						<div id="gal_disp">
							<div id="img_wrap">
								<img id="img_disp"/>
								<script type="text/javascript">addNewImageViewer('img_disp');</script>
								<div id="img_arrow_r"></div>
								<div id="img_arrow_l"></div>
							</div>
							<div id="picdesc" class="readable">
								<h1></h1>
								<h3></h3>
								<p></p>
							</div>
							<div id="pic_btn_wrap" style="display: none;">
<?php 
// This isn't perfectly safe, since the editing script only checks for users' ability to post images. Ideally,
// we would like to make it so that either the owner of the image, or users who can both post and delete images
// have permission to edit an image.
if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'post images')) : ?>
								<div id="picedit_btn" class="sub fl">
									Edit Image
								</div>
<?php endif; 
// Again, should check if user has ownership of image as well, but may be redundant
if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'delete images')) : ?>
								<div id="picdel_btn" class="sub fr">
									Delete Image
								</div>
<?php endif; ?>
								<div class="clearer"></div>
							</div>
						</div>
						<table id="gallery"></table>
					</section>

<?php if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'post images')) : ?>
					<section id="upl_sec" action="php/dump.php">
						<form id="upl_form" method="post" action="gallery/php/dump.php">
							<label>Album Name:</label>
							<div id="upl_album" class="sugg_input">
								<input id="upl_user_inp" type="hidden" name="user" value="<?=$_SESSION['user']->username?>" />
								<input id="upl_album_inp" type="text" placeholder="Album Name" name="album" maxlength="75" class="spacer" autocomplete="off" />
								<div id="upl_album_sugg" class="list"></div>
							</div>
							<div class="clearer spacer"></div>
							<div id="upl_cont">
								<p>Flash, Silverlight or HTML5 support is required for image upload, but not detected on your browser.</p>
							</div>
							<br />
							<div onclick="gallery_upload.form_sub();" class="sub" />Upload Images</div>
						</form>

						<div class="clearer"></div>
						<div id="upl_load">
							<img src="res/pic/loading.gif" />
						</div>

						<form id="detail_form">
						</form>
						<div class="errorbox"></div>
					</section>
<?php endif; ?>

<?php if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'delete images')) : ?>
					<section id="del_sec">
					</section>
<?php endif; ?>

<?php if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'delete images')) : ?>
					<section id="det_sec" styule="display: block;"><form id="det_form"></form><div class="button sub">Submit</div></section>
<?php endif; ?>
				</td>
<?php include $compath."php/def_tail.php"; ?>