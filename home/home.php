<?php 

$javascripts = ['home/js/mkann.js', 'home/js/pann.js'];

include $compath."php/def_head.php";
include "home/php/render_ann.php";
?>
				<td id="lpanel">
<?php include 'com/php/def_sidebar.php'; ?>
					<nav id="lnav">
						<a id="about_lnk">Overview</a>
						<a id="det_lnk">Guild Details</a>
						<a id="past_lnk">Past Anouncements</a>
<?php
	if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'make announcements')) { ?>
						<a id="make_lnk">Make Anouncement</a>
<?php
	}
?>
					</nav>
				</td>
				<td id="cpanel">
<?php
include 'home/php/overview.php';
include 'home/php/details.php';
include 'home/php/pann.php';

if (isset($_SESSION['user']) && Permissions::check_user_permission($_SESSION['user'], 'make announcements')) include 'home/php/mkann.php';
?>
				</td>
<?php include $compath."php/def_tail.php"; ?>