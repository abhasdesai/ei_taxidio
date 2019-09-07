<div class="container our-team-block">
  
  <?php if(count($team)){  ?>
   <!--Row-->
	
	               <div class="row">
      

            
      <?php foreach($team as $list){ ?>

      
      
      <div class="container our-team">
      	
      	<div class="row">
      		
      		<!--<div class="col-md-12">
      			
      			 <h6 class="text-center team-member"><?php echo $list['person']; ?></h6> 

					 <span class="designation"><?php echo $list['designation']; ?></span> 
      			
      		</div>-->
      		
      		<div class="col-md-3 member-img text-center">
      			<img src="<?php echo site_url('userfiles/team').'/'.$list['image'] ?>" alt="<?php echo $list['person']; ?>">
      		</div>
      		
      		<div class="col-md-9 member-details">
     			 
     			 <h6 class="text-center team-member"><?php echo $list['person']; ?></h6> 

					 <span class="designation"><?php echo $list['designation']; ?></span>
     			 
      			 <?php if($list['details']!=''){ ?>

                        <span class="member-description"><?php echo $list['details']; ?></span>

                        <?php } ?>
      		</div>
      		
      	</div>
      	
      </div>
      
     


     <?php } ?>

	</div>
  
 <?php }  ?>
  

  
</div>