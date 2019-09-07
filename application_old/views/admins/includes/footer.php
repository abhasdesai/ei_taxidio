<footer class="main-footer">
       <strong>Copyright &copy; <?php echo admin_copyrightyear; ?> <a href="<?php echo site_url() ?>" target="_blank"><?php echo admin_footer ?></a>.</strong> All rights reserved.
</footer>
<!-- Change Password Popup -->
<!-- Modal -->
<div id="changepassword" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Password</h4>
      </div>
      <div class="modal-body">
       
       <div class="box box-primary">
                <!-- form start -->
                <br/>
                <div class="alert alert-success" id="passdisnonealert">
					Your password has been updated.
                </div>
                <form id="changepasswordform" role="form">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Current Password</label>
                      <input type="password" class="form-control required" id="currentpassword" name="currentpassword" placeholder="Current Password" onblur="checkCurrentPassword()">
                      <p class="text-red" id="passdisnone">Your current password is wrong.</p>
                      <input type="hidden" id="websiteurl" value="<?php echo site_url() ?>"/>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">New Password</label>
                      <input type="password" class="form-control required" id="newpassword" name="newpassword" placeholder="New Password" minlength="6" maxlength="15">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputFile">Re-type Password</label>
                      <input type="password" class="form-control required" id="retypepassword" name="retypepassword" equalTo="#newpassword" placeholder="Re-type Password">
                    </div>
                   </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="button" class="btn btn-primary" id="changepasswordbtn">Save</button>
                  </div>
                </form>
              </div><!-- /.box -->

       
      </div>
     
    </div>

  </div>
</div>
<!-- End Change Password Popup -->
