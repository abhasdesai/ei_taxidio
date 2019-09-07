<div class="container our-team-block">
  <h1>Our Team</h1>
  <?php if(count($team)){  ?>
   <!--Row-->
      
      <?php foreach($team as $list){  ?>

    <div class="team-box">
         <div class="row">
		<div class="col-sm-3">
    			<img src="<?php echo site_url('userfiles/team').'/'.$list['image'] ?>" alt="<?php echo $list['person']; ?>" class="img-responsive center-block">
    	</div>
    	<div class="col-sm-9">
    			 <h2><?php echo $list['person']; ?></h2>
            <p> <span><?php echo $list['designation']; ?></span><br>
              <br>
              <?php echo $list['details']; ?> </p>
    		</div>
    	 </div><!--End row-->
    </div>
      <?php } ?>
      
      <?php } ?>
         
</div>
