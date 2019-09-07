<?php if($iti['trip_mode']==2){ ?>
<div class="modal-content inv-trip-modal">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal">×</button>
          <h4 class="modal-title">Share this trip via an email</h4>
      </div>
      <div class="modal-body">
            <form class="form-horizontal" method="post" action="<?php echo site_url('itinerary-share-by-email'); ?>">
            <input type="hidden" name="iti_id" value="<?php echo $_POST['iti_id'] ?>">
               <fieldset>
                  <div class="control-group">
                     <label class="control-label" for="url">Link</label> 
                     <div class="controls"> <input id="url" type="text" class="form-control" value="<?php echo site_url('planned-itinerary-forum').'/'.$iti['slug'] ?>" readonly=""> </div>
                  </div>
                  <div class="control-group">
                     <label class="control-label" for="share_email">Email</label> 
                     <div class="controls"> <input name="share_email" type="text" class="form-control" required placeholder="Email" value=""  pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" title="Invalid email address"> </div>
                  </div>
                  <div class="control-group padding-left-20">
                     <div class="controls text-center"> <input type="submit" class="link-button" value="Share"> </div>
                  </div>
                </fieldset>
            </form>
      </div>
</div>
<?php }else{?>
<div class="modal-content">
      <div class="modal-header">
        Share your itinerary through an email
        <button class="close" type="button" data-dismiss="modal">×</button>
      </div>
      <div class="alert alert-danger">
        <strong>Change your trip from Private to Public.&nbsp;&nbsp;<a id="si_edittrip" href="<?php echo site_url('editTrip/'.$iti['id']) ?>#trip_mode_public">Edit Trip</a></strong>
      </div>
</div>
<?php  } ?>
