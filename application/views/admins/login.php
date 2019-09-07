<?php
$username = '';
$password = '';
$checked = 'false';
if ($this->input->cookie('passwordlogin') && $this->input->cookie('passwordlogin') != '' && $this->input->cookie('usernamelogin') && $this->input->cookie('usernamelogin') != '') {
	$username = $this->input->cookie('usernamelogin');
	$password = $this->input->cookie('passwordlogin');
	$checked = 'true';
}

?>
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo site_url() ?>"><strong>Taxidio</strong></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
	 		<?php if ($this->session->flashdata('error')) {?>
	<div class="alert alert-danger fade in">
		<?php echo $this->session->flashdata('error'); ?>
	</div>
	<?php }?>
	<?php if ($this->session->flashdata('success')) {?>
		<div class="alert alert-success fade in">
			<?php echo $this->session->flashdata('success'); ?>
		</div>
	<?php }?>
    <p class="login-box-msg">Sign in to start your session</p>

    <?php echo form_open('admin/login', array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'form1')); ?>
      <div class="form-group has-feedback">
        <input type="text" class="form-control required" name="username" value="<?php echo $username ?>" placeholder="Username" autocomplete="off" autofocus>
     </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control required" name="password" value="<?php echo $password ?>" placeholder="Password" autocomplete="off" >
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck rememberme">
            <label>
              <input type="checkbox" name="remember" <?php if ($username != '') {echo 'checked';}?>> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
 </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- Change Password Popup -->
<!-- Modal -->
<div id="forgotpasswordmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Forgot Password</h4>
      </div>
      <div class="modal-body">

       <div class="box box-primary">
                <!-- form start -->
                <br/>
                <div class="alert alert-success" id="forgotpassordsucdisnonde">
					For further instruction go to your email.
                </div>
                 <div class="alert alert-danger" id="forgotpassorderrdisnonde">
					We didn't found this email in our database.
                </div>
                <form id="forgotpasswordform" role="form">
                  <div class="box-body">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Enter Your Email</label>
                      <input type="text" class="form-control required email" id="email" name="email" placeholder="Email" autocomplete="off" />
                    </div>
                   <div class="box-footer">
                    <button type="button" class="btn btn-primary" id="forgotpasswordbtn">Submit</button>
                  </div>
                   </div><!-- /.box-body -->
				</form>
              </div><!-- /.box -->


      </div>
      
    </div>

  </div>
</div>
<!-- End Change Password Popup -->
<script>

function openpopup()
{
	$("#forgotpasswordmodal").modal("show");
	$("#forgotpasswordform")[0].reset();
	$("#forgotpassorderrdisnonde").hide();
	$("#forgotpassordsucdisnonde").hide();
}

$("#forgotpasswordbtn").click(function(){

if($('#forgotpasswordform').valid())
{
	$.ajax({
		  type:'POST',
		  url:'<?php echo site_url("admin/forgotPassword") ?>',
		  data:'email='+$('#email').val(),
		  success:function(data)
		  {
			 if(data==1)
			 {
				$("#forgotpasswordform")[0].reset();
				$("#forgotpassorderrdisnonde").hide();
				$("#forgotpassordsucdisnonde").show();
			 }
			 else
			 {
				 $("#forgotpassordsucdisnonde").hide();
				 $("#forgotpassorderrdisnonde").show();

			 }
		  }
		  });
}
});

</script>
