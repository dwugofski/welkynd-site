<?php 

$javascripts = [];
$css_files = [];

include $compath."php/def_head.php";
?>
				<td id="lpanel">
<?php include 'com/php/def_sidebar.php'; ?>
					<nav id="lnav">
						<a id="vid_lnk">Albums</a>
<?php 
if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'post videos')) echo '<a id="upl_lnk">Upload Videos</a>';
if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'delete videos')) echo '<a id="del_lnk">Delete Album</a>';
?>
					</nav>
				</td>
				<td id="cpanel">
					<section id="vid_sec">
						<div id="vid_disp">
							<div id="img_wrap">
								<img id="img_disp"/>
								<div id="img_arrow_r"></div>
								<div id="img_arrow_l"></div>
							</div>
							<div id="picdesc" class="readable">
								<h1></h1>
								<h3></h3>
								<p></p>
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

					<section id="del_sec">
					</section>

<?php if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'delete images')) : ?>
					<section id="det_sec" styule="display: block;"><form id="det_form"></form><div class="button sub">Submit</div></section>
<?php endif; ?>
				</td>
<?php include $compath."php/def_tail.php"; ?>