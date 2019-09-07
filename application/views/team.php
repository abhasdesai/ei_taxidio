<div class="container our-team-block">
  
  <?php $counter=0; if(count($team)){  ?>
   <!--Row-->
      
      <?php foreach($team as $list){ $counter++; ?>

      <?php if($counter==1 || $flag==1){ $flag=0; ?>
         <div class="row">
      <?php } ?>
   
         <div class="col-sm-4">
            <!--Team block-->
            <div class="team-block">
               <!--Team info-->
               <div class="team-info ">
                   <h6 class="text-center"><?php echo $list['person']; ?>

                         <span><?php echo $list['designation']; ?></span>  

                        <?php if($list['details']!=''){ ?>

                        <span><?php echo $list['details']; ?></span>

                        <?php } ?>
                   </h6>

                  <ul class="team-socials">
                    <?php if($list['facebook']!=''){ ?>
                     <li><a href="<?php echo $list['facebook']; ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                   <?php } ?>  

                   <?php if($list['linkedin']!=''){ ?>
                     <li><a href="<?php echo $list['linkedin']; ?>" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                   <?php } ?>  

                   <?php if($list['twitter']!=''){ ?>
                     <li><a href="<?php echo $list['twitter']; ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                   <?php } ?>  

                   <?php if($list['instagram']!=''){ ?>
                     <li><a href="<?php echo $list['instagram']; ?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                   <?php } ?>  
                  </ul>
               </div>
               <!--End team info-->
                 <img src="<?php echo site_url('userfiles/team').'/'.$list['image'] ?>" alt="<?php echo $list['person']; ?>">
            </div>
            <!--End team block-->
         </div>
      
       <?php if($counter%3==0 || $counter==count($team)){ $flag=1; ?>
          </div><!--End row-->
      <?php } ?>
     


     <?php } ?>


  
 <?php } else { ?>
  
    <div class="alert alert-info">
       Nothing To show.
    </div>

 <?php } ?> 
   
</div>