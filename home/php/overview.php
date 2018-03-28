<?php
include "home/php/gallery_preview.php";
include "home/php/announcements.php";
?>					
					<section id="about_sec">
						<h1>About Welkynd</h1>
						<div class="blurb">
							<p>
								Welkynd is a social guild located in Ebonheart (NA). Consisting of mostly Champion Rank 600 members, we host guild runs every Friday (PVE and PVP) and trials every Monday. Recruits can gain membership simply through participating in an event of any kind. Teamspeak3 is our voice chat of choice, and our guild bank is our pride and joy. Our main goal is to make Elder Scrolls Online an enjoyable social experience; crafters are generous, to say the very least, and there is always someone online willing to help those in need. We pride ourselves in our friendliness, and so will take in anyone who is friendly back!
							</p>
						</div>
						<h1>Recent Gallery Uploads</h1>
						<table id="gallery"><tr>
<?php
foreach ($gallery_preview as $pic) {
	$t = "\t\t\t\t\t\t\t";
	echo $t."<td><img src=\"gallery/php/uploads/".$pic['image']."\"/><div onclick=\"location.href='./?sect=gallery&album=".urlencode($pic['album'])."&pic=".$pic['id']."';\">".$pic['name']."</div></td>\n";
}
?>
						</tr></table>
						<h1>Recent Announcements</h1>
<?php

$announcements = Announcements::recent_announcements(3);
$tab_pre = "\t\t\t\t\t\t";
foreach ($announcements as $ann)
	echo Announcements::render_ann($ann, $tab_pre);
?>
					</section>
