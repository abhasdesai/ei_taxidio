<style type='text/css'>
    .error {
    border: 1px solid red;
}
  </style>

<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><strong>NIS</strong></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
	 		
    <p class="login-box-msg"><strong>Reset Your Password</strong></p>

    <?php echo form_open('admin/update_reset_password',array('class' => 'form-horizontal','role'=>'form','id'=>'form1')); ?>			
      <div class="form-group has-feedback">
        <input type="password" class="form-control required" minlength="6" maxlength="15" name="password" id="password" placeholder="Password" autofocus />                                        
       <input type="hidden" name="user_id" value="<?php echo $id ?>" />
     </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control required" name="cpassword" id="cpassword" equalTo="#password" placeholder="Confirm Password" />                                        
     </div>
      <div class="row">
        
        <!-- /.col -->
        <div class="col-xs-4">
          <input type="submit" name="btnsubmit" class="btn btn-primary btn-block btn-flat" value="Save" />
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
