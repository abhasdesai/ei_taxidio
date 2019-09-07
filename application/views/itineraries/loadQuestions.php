<?php  if(count($questions)){ ?>

<input type="hidden" id="iti" value="<?php echo $iti ?>" />

<?php foreach($questions as $list){

//echo "<pre>";print_r($list);die;
//$totalcomments=$this->Forum_m->countTotalComments($list['id']);
   ?>


 <div class="full-forum-details" id="q<?php echo $list['id'] ?>">
  <div class="row">
  <div class="col-sm-2">
    <div class="forum-user-image">


    <?php if($list['socialimage']!=''){ ?>
              <img src="<?php echo $list['socialimage']; ?>" />
          <?php }else{ ?>


    <?php if($list['userimage']!='' || !file_exists(FCPATH.'userfiles/userimages/small/'.$list['userimage'])) { ?>

    <img src="<?php echo site_url('userfiles/userimages/small').'/'.$list['userimage']; ?>" />

    <?php }else{ ?>

    <img src="<?php echo site_url('assets/dashboard/images/no-image.jpg'); ?>" />

    <?php } ?>

    <?php } ?>


    </div>
  </div>
<div class="col-sm-10">
    <div class="forum-short-details">

    <p class="forum-title"><a href="<?php echo site_url('itinerary-discussion').'/'.$list['id']; ?>" target="_blank"><?php echo $list['question']; ?></a></p>
      <p class="published"><span class="forum-time">Asked By</span> <span class="postername"><?php if(isset($list['name']) && $list['name']!=''){ echo $list['name']; }else{ echo 'Taxidio User'; } ?></span> <span class="forum-time">(<?php echo time_ago(strtotime($list['created'])); ?>)</span></p>


    </div>

    <div class="form-count-comments">
      <?php $comment='Comments';if($list['totalcomments']<2){ $comment='Comment';  } ?>
    <p><?php echo str_pad($list['totalcomments'], 2, '0', STR_PAD_LEFT).' '.$comment; ?>    </p>
    </div>


       <?php if($this->session->userdata('id')!='' || $this->session->userdata('fuserid')==$list['user_id'] || $this->session->userdata('fuserid')==$list['posted_by']){ ?>
         <div class="action-btn1">
            <a href="javascript:void(0);" onclick="deleteQuestion('<?php echo $list["id"] ?>')"><i class="fa fa-trash" aria-hidden="true"></i></a>
         </div>
       <?php } ?>
  </div>
  </div>
  </div>

<?php } ?>

<div class="col-md-12" align="center">
 <div class="pagination-container wow zoomIn mar-b-1x" data-wow-duration="0.5s">
   <?php echo $pagination; ?>
 </div>
 </div>

<?php }else{ ?>

   <div class="alert alert-info alert-norecommendation-info">
      There are no questions asked for this itinerary.
   </div>

<?php } ?>
