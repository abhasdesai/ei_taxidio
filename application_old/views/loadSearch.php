<?php if (count($search)) {

	foreach ($search as $list) {
		?>
			<a class="fntclr" href="<?php echo site_url($list['url']); ?>"><?php echo $list['webpage']; ?></a>
			<div class="nicdark_space10"></div>
			<p class="pclr"><?php if ($list['cms']) {echo text_cut(strip_tags($list['cms']), 300, true);}?></p>
			<div class="nicdark_space10"></div>
			<?php
}
	?>


<?php } else {?>
	<div class="nicdark_space20"></div>
	<div class="success nicdark_btn nicdark_bg_blue medium nicdark_shadow nicdark_radius white nicdark_pres" style="width:100%">Your search - "<?php echo $keyword; ?>" - did not match any documents.</div>
	</div>
<?php }?>


<div class="grid grid_12 nicdark_relative" align="center">
 <div class="pagination">
	<?php echo $pagination; ?>
 </div>
 </div>
<?php

function text_cut($text, $length = 200, $dots = true) {
	$cnt = 0;
	$strl = strlen($text);
	if ($strl <= 300) {
		return $text;
	}
	for ($i = 300; $i > 0; $i--) {
		if ($text[$i] == '.') {
			return getwords($text, $i);
			break;
		} else {
			return getwords($text, $i);
		}
	}

}

function getwords($text, $pos) {
	return substr($text, 0, $pos) . '......';
}
?>
