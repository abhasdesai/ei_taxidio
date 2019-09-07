<?php $c=0; if(count($happenings)){ ?>
				
	<?php foreach($happenings as $list){ $c++;?>
		
			<?php 
				if($c==1)
				{
					$class="nicdark_bg_green";
				}
				if($c==2)
				{
					$class="nicdark_bg_blue";
				}
				if($c==3)
				{
					$class="nicdark_bg_yellow";
				}
				if($c==4)
				{
					$class="nicdark_bg_orange";
				}
				if($c==5)
				{
					$class="nicdark_bg_orange";
				}
				if($c==6)
				{
					$class="nicdark_bg_violet";
				}
				
				 $words=substr(strip_tags($list['editorial']),0,130).'...';
			
			?>
			
		  <div class="grid grid_6 nicdark_relative" style="margin:0; margin-top:3%;">

			<div class="nicdark_btn_iconbg <?php echo $class; ?> nicdark_absolute extrabig nicdark_shadow nicdark_radius" style="width:100px; height:100px;">
				<div style="width:100px;">
					<h2 class="white" style="text-align:center; padding-top:25%;font-size:20px;">
						<?php 
							if($list['month']=='may' || $list['month']=='May'){ 
							
							echo "Welcome"; ?><br/><?php echo $list['year'];
							
							}else{
							?>
						
						<?php echo $list['month']; ?><br/><?php echo $list['year']; } ?>
					</h2> 
				</div>
			</div>
			
			<div class="nicdark_activity nicdark_marginleft100">
									  
				
				<p style="padding: 0 8%;text-align: justify;padding-left: 3%; color:#000;"><?php echo $words; ?></p>
				 <a href="<?php echo site_url('userfiles/happenings').'/'.$list['document'] ?>" target="_blank" class="nicdark_btn grey" style="margin-top:5%;"><i class="icon-right-open-outline"></i> More</a>
				<div class="nicdark_space20"></div>
				
				<div class="nicdark_space20"></div>
			</div>
		
		</div>
	
	<?php } ?>

<?php } ?> 
<div class="grid grid_12 nicdark_relative" align="center">
 <div class="pagination">
	<?php echo $pagination; ?>
 </div>
 </div>

