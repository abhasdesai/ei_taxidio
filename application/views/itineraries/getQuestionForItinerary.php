<style>
.rating-animate{display:inline-block}
</style>
<div class="container grid-forum">

      <div class="row">

        <?php if($this->session->userdata('id')==''){ ?>

          <?php if($this->session->userdata('fuserid')==''){ ?>

            <div class="col-sm-12">

              <div class="ask-login create-account">
              <a  href="javascript:void(0);" id="showLoginForForum">Sign In</a> or <a href="javascript:void(0);" id="showRegisterForForum">create a account to ask question and rate this itinerary.</a>
              </div>

            </div>


          <?php }else{?>

              <?php if($this->session->userdata('fuserid') != $owner_id){ ?>

            <div class="col-sm-8">

              <div class="ratingdiv">
                <?php $rating=0; if(count($userrating)){ $rating=$userrating['rating']; } ?>
                <form id="ratingform">
                  <span class="rate-this">Rate This Itinerary</span>
                  <input id="input-21b" name="rating" value="<?php echo $rating; ?>" type="text" class="rating" data-min=0 data-max=5 data-step=0.5 data-size="lg"
                         required title="">
                  <input type="hidden" name="iti" value="<?php echo $itineraryid; ?>" />
                  <input class="link-button" value="Submit" type="submit">
                </form>

                <div class="alert alert-info alert-norecommendation" id="ratingmsg" style="display:none">

                </div>

              </div>

            </div>

            <div class="col-sm-4">



              <a href="<?php echo site_url('ask-question').'/'.$itineraryid ?>" class="link-button forum-question">Ask Question</a>


            </div>

            <?php } ?>


          <?php } ?>

          <?php } ?>

          <div class="col-sm-12">
            <h1>Itinerary Forum</h1>
          </div>
      </div>
          <div id="binddiscussion" class="discussion-box"></div>

</div>


<script>

$(document).ready(function(){
       $.ajax({
              type:'POST',
              url:'<?php echo site_url("loadQuestions") ?>',
              data:'iti='+'<?php echo $itineraryid ?>',
              beforeSend: function()
              {
                         $.LoadingOverlay("show");
              },
              complete: function()
              {
                  setTimeout(function(){  $.LoadingOverlay("hide",true); }, 3000);
              },
              success:function(data)
              {
                  if(data==1)
                  {
                      $("#binddiscussion").html('<div class="alert alert-info alert-norecommendation">No conversation available for this itinerary.</div>');
                  }
                  else
                  {
                    $("#binddiscussion").html(data);
                  }

              }
            });
});


$(document).on('click','div.pagination-container ul li a',function(e){
  var this_url=$(this).attr('href');
  var iti=$("#iti").val();
  $.ajax({
          type:'POST',
          url:this_url,
          data:'iti='+iti,
          beforeSend: function()
          {
               $.LoadingOverlay("show");
          },
          complete: function()
          {
              setTimeout(function(){  $.LoadingOverlay("hide",true); }, 1000);
          },
          success:function(data)
          {
              $("#binddiscussion").html(data);
              $("body, html").animate({
               scrollTop: $('.grid-forum').offset().top
             }, 800);
          }
        });

  return false;

});

</script>
