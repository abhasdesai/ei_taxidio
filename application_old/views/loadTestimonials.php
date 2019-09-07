
	





<?php if(count($testimonials)){ 
	
		foreach($testimonials as $list)
		{
			?>
			
			<?php if($list['isvideo']==0){ ?>
				
					<div class="imagewrap grid grid_2">
						<?php if($list['image']!=''){ ?>
						<img src="<?php echo site_url('userfiles/testimonials/small').'/'.$list['image']; ?>" />
						<?php } else { ?>
						<img src="<?php echo site_url('assets/img/dummy.png'); ?>" />						<?php } ?>
					</div>
					<div class="textwrap grid grid_9 testwrp">
						<p><?php echo $list['title']; ?></p>
						<p class="author-name"><strong>-<?php echo $list['name']; ?></strong></p>
					</div>
						<div class="nicdark_space20"></div>
						<div class="grid grid_12">
						<hr style="color:#ddd;"/>
					</div>
					<div class="nicdark_space20"></div>
			

				
			<?php } else { ?>
			
			
				<div class="grid grid_12 video-testimonials">
					
					
					<iframe width="420" height="280"
src="<?php echo $list['title']; ?>">
</iframe>


<?php /* ?><img src="<?php echo site_url('assets/ytttt.png') ?>" /><?php */ ?>
</div>
<div class="grid grid_12">
						<hr style="color:#ddd;"/>
					</div>
<div class="nicdark_space20"></div>
			
			<?php } ?>
			
				
				
			

			
			
			<?php
		}
	?>
	
<?php }else{ ?>
	<div class="nicdark_space20"></div>
	<div class="success nicdark_btn nicdark_bg_blue medium nicdark_shadow nicdark_radius white nicdark_pres" style="width:100%">Not Available.</div>
	</div>
<?php } ?>


<div class="grid grid_12 nicdark_relative" align="center">
 <div class="pagination">
	<?php echo $pagination; ?>
 </div>
 </div>
