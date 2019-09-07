<div class="container-fluid login-page">

 <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <?php
      if($this->session->flashdata('success')){
        echo "<div class='alert alert-success fade in alert-dismissable'>".$this->session->flashdata('success')."</div>";
      }else if($this->session->flashdata('error')){
        echo "<div class='alert alert-danger fade in alert-dismissable'>".$this->session->flashdata('error')."</div>";
      }
    ?>
    </div>
    </div>


    <?php echo form_open('signinUser',array('class' => 'form-horizontal','role'=>'form','id'=>'formLogin')); ?>

    <!-- Name input-->
    <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <label class="control-label" for="password">Email</label>
      <input type="email" class="form-control required email" name="useremail" value="<?php if(set_value('useremail')){ echo set_value('useremail'); }else{ if(isset($_COOKIE['fusernamelogin']) && $_COOKIE['fusernamelogin']!=''){ echo $_COOKIE['fusernamelogin']; } } ?>"/>
      <?php echo form_error('useremail'); ?>

    </div>
    </div>
    <!-- Email input-->
    <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <label class="control-label" for="password">Password</label>

        <input type="password" class="form-control required" name="userpassword" value="<?php if(isset($_COOKIE['fpasswordlogin']) && $_COOKIE['fpasswordlogin']!=''){ echo $_COOKIE['fpasswordlogin']; }  ?>"/>
      <?php echo form_error('userpassword'); ?>

    </div>
	</div>

   <!-- <div class="col-md-4 col-md-offset-4">
      <label class="control-label" for="password">Password</label>
    </div>-->
	<div class="row">
    	<div class="col-md-8 col-md-offset-2 remember-text">
     		<input type="checkbox" name="rememberme" value="1" <?php if(isset($_COOKIE['fusernamelogin']) && $_COOKIE['fusernamelogin']!='' && isset($_COOKIE['fpasswordlogin']) && $_COOKIE['fpasswordlogin']!=''){ echo 'checked'; } ?>>Remember Me?
		</div>
    </div>


    <div class="row">
    	<div class="col-md-8 col-md-offset-2">
			 <div class="captcha-div" style="transform: scale(0.7);transform-origin: 0 0;">
				<div id="example2"></div>
        <p class="errorcaptchafrmserver"><?php echo form_error('g-recaptcha-response'); ?></p>
				<p id="errorcaptchafrm" class="errorp"></p>
			 </div>
		</div>
     </div>


      <!-- Form actions -->
     <div class="row">
    <div class="col-md-8 col-md-offset-2 contact-submit">
        <!--<button type="submit" class="link-button">Submit</button>-->
        <input type="submit" class="link-button" value="Login" name="btnsubmit" >
      </div>
    </div>

      <p class="text-center maror">- OR -</p>
			<div class="row">
                <div class="external-login col-md-8 col-md-offset-2">
					<a href="<?php echo googleLogin(); ?>" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>

					<a href="<?php echo $this->facebook->login_url(); ?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
                </div>
			</div>

                <!-- Forgot Password -->
                <div class="row">
                <div class="external-login col-md-8">
					<div class="control-group forgot-password">
            <a href="javascript:void(0);" class="auth-create-account" data-toggle="modal" id="showRegisterForForum">Create Account</a>
            <span class="auth-or">OR</span>
            <a href="javascript:void(0);" data-toggle="modal" class="auth-forgot-password" data-target="#forgotpasswordmodal">Forgot Password</a><br>
					</div>
					</div>
					</div>


  </form>

</div>
