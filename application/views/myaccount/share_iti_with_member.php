<div class="modal-content inv-trip-modal">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal">×</button>
          <h4 class="modal-title">Share this trip with a Taxidio member</h4>
      </div>
      <div class="modal-body">
            <form class="form-horizontal" method="post" action="<?php echo site_url('share-iti-with-member'); ?>">
            <input type="hidden" name="iti_id" value="<?php echo $_POST['iti_id'] ?>">
               <fieldset>
                  <div class="control-group">
                     <label class="control-label" for="email">Email</label> 
                     <div class="controls"> <input name="email" type="text" class="form-control" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" required placeholder="Email" value="" title="Invalid email address"> </div>
                  </div>
                  <div class="control-group padding-left-20">
                     <div class="controls text-center"> <input type="submit" class="link-button" value="Share"> </div>
                  </div>
                </fieldset>
            </form>
      </div>
</div>
