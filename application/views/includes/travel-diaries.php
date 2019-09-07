<?php if(count($public_itineraries)){ ?>
<div class="row">
      <div class="col-md-12 travel-blogs">
        <h2 class="text-center">Pre-Planned Itineraries</h2>
        <p class="text-center">As your ideal trip planner, we let you browse through the planned itineraries of trending destinations. Add, delete, rearrange attractions and modify it to create an itinerary according to your preferences.</p>
      </div>
</div>
  <?php foreach($public_itineraries as $list){

        //  echo "<pre>";print_r($list);die;
    //$totalcomments=$this->Forum_m->countTotalComments($list['id']);
    ?>
      <div class="full-forum-details">

  <div class="row">

      <div class="col-sm-3">
          <div class="itinerary-image">

             <?php

                  if($list['image']!='' && file_exists(FCPATH.'/userfiles/images/'.$list['image']))
                  { ?>

                  <a href="<?php echo site_url('planned-itinerary-forum').'/'.$list['slug']; ?>" target="_blank"><img src="<?php echo site_url('userfiles/images').'/'.$list['image'] ?>" /></a>

                 <?php

                  }
                  else if(file_exists(FCPATH.'/userfiles/cities/small/'.$list['image'])){
                  ?>
                  <a href="<?php echo site_url('planned-itinerary-forum').'/'.$list['slug']; ?>" target="_blank"><img src="<?php echo site_url('userfiles/cities/small').'/'.$list['image'] ?>" /></a>
                  <?php
                  }
                  else{
                      ?>
                     <a href="<?php echo site_url('planned-itinerary-forum').'/'.$list['slug']; ?>" target="_blank"><img src="<?php echo site_url('assets/images/image300.jpg') ?>" /></a>
                 <?php }
              ?>

      </div>
  </div>

  <div class="col-sm-7">
      <div class="iti-details">

          <p class="iti-title">
              <a href="<?php echo site_url('planned-itinerary-forum').'/'.$list['slug']; ?>" target="_blank"><?php echo $list['user_trip_name']; ?></a>
              <span class="rating-section">

              <input name="rating" value="<?php echo $list['rating'] ?>" type="text" class="rating input-21b" data-size="lg" readonly>
              <div class="clearfix"></div>
              </span>
          </p>
      </div>


       <?php if($list['trip_type']==2){ ?>
              <p class="iti-destination"><strong>Destination : </strong>
                <?php $explodemain=explode('-',$list['citiorcountries']); ?>
                <?php $explodecities=explode('-',$list['citiesformulticountry']); ?>
                <?php for($i=0;$i<count($explodemain);$i++){ ?>
                <span>
                  <?php echo $explodemain[$i]; ?>
                </span>
                <?php if(isset($explodecities[$i]) && $explodecities[$i]!=''){   ?>
                  (<span class="city-name"><?php echo str_replace(',','</span>,<span class="city-name">',$explodecities[$i]); ?></span>)
                  <?php } ?>
                  <?php if($i!=count($explodemain)-1){ echo "  |  "; } ?>
                <?php } ?>



              </p>
          <?php }else{ ?>
              <p class="iti-destination"><strong>Destination : </strong><span><?php echo $list['countryname'].'</span>(<span class="city-name">'.str_replace('-','</span>,<span class="city-name">',$list['citiorcountries']).'</span>)'; ?></p>
          <?php } ?>


      <p class="iti-published"><strong>Created By</strong> <span list="iti-postername"><?php if(isset($list['name']) && $list['name']!=''){ echo $list['name']; }else{ echo 'Taxidio User'; } ?></span> <span class="iti-forum-time">(<?php echo time_ago(strtotime($list['created'])); ?>)</span> </p> <p> </p>
      <p class="iti-date"><?php echo $list['start_date'].' - '.$list['end_date'].' | <span>'.$list['days'].' Days</span>'; ?>
      </p>

</div>

<div class="col-sm-2">
      <div class="iti-form-count-questions">
          <?php $view='Views';$discussions='Discussions'; if($list['totalquestions']<2){ $discussions='Discussion'; } if($list['views']<2){ $view='View'; } ?>
          <p><?php echo str_pad($list['totalquestions'], 2, '0', STR_PAD_LEFT).' '.$discussions; ?>
          | <?php echo str_pad($list['views'], 2, '0', STR_PAD_LEFT).' '.$view;  ?> </p>
            <a href="<?php echo site_url('planned-itinerary-forum').'/'.$list['slug']; ?>" target="_blank"  class="save-trip">View Trip</a>
      </div>


      </div>


</div>
</div>

  <?php } ?>


      <div class="col-sm-12 text-center">
      <a href="<?php echo site_url('planned-itinerary'); ?>" class="link-button purple" target="_blank" style="width:auto;margin-top:20px;">View All<i class="fa fa-eye" aria-hidden="true"></i></a>
    </div>


<?php } else { ?>

<div class="row">
<div class="col-md-12">


<div class="alert alert-info">
Nothing To Show....
</div>
</div>
</div>

<?php } ?>

<script>

$(document).ready(function(){
    $(".input-21b").rating();
})


</script>
