<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Taxidio</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?php echo site_url('assets/admin/css/bootstrap.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/admin/css/style.css'); ?>">
		<link rel="stylesheet" href="<?php echo site_url('assets/admin/css/AdminLTE.min.css'); ?>">
		<script src="<?php echo site_url('assets/admin/js/jQuery-2.1.4.min.js'); ?>"></script>
		<link rel="shortcut icon" href="<?php echo site_url('assets/images/favicon.png') ?>" /><link rel="alternate" />
	</head>

    <body class="hold-transition login-page">
		

    	<div class="login-box">
			  <div class="login-logo">
			    <a href="#"><strong>Taxidio Beta</strong></a>
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

			    <?php echo form_open('temp/login', array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'form1')); ?>
			      <div class="form-group has-feedback">
			        <input type="text" class="form-control required" name="betausername" placeholder="Username" autocomplete="off" autofocus>
			     </div>
			      <div class="form-group has-feedback">
			        <input type="password" class="form-control required" name="betapassword" placeholder="Password" autocomplete="off" >
			      </div>
			      <div class="row">
			        <div class="col-xs-8">
			          <div class="checkbox icheck rememberme">
			            <label>
			            
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


    <script src="<?php echo site_url('assets/admin/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/admin/js/jquery.validate.min.js'); ?>"></script>
	<script>
		$(document).ready(function(){
			$("#form1").validate();
		});

	</script>
	</body>

</html>
