<?php 
if($this->session->userdata('fuserid')==''){ ?>

<script>
 
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        testAPI();
      } else {
      }
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1959210047634242',
      appSecret  : 'f190f654deabd8f9b91d5a6c9fabfc1e',
      cookie     : true,  
      xfbml      : true,  
      version    : 'v2.12' 
    });

  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  function testAPI() {
    $('.loader').show();
    FB.api('/me?fields=id,first_name,last_name,email,gender,locale,picture.width(150).height(150)', function(response) {
        $.ajax({ 
          type: 'POST', 
          url: 'https://www.taxidio.com/fblogin', 
          dataType: "json",
          data: { data: JSON.stringify(response) }, 
          success: function (data) { 
              if(data==1){
                location.reload();
              }else{
                $('.loader').hide();
                $("#appendsigninerrors").html('Login failed! Please try again.');
              }
          }
        });
    });
  }
</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.12';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header login-modal">
<button class="close" type="button" data-dismiss="modal">×</button>
</div>
      <div class="bs-example bs-example-tabs">
        <ul id="myTab" class="nav nav-tabs">
          <li id="signintabli" class="active"><a href="#signin" data-toggle="tab">Login</a></li>
          <li id="signuptabli" class=""><a href="#signup" data-toggle="tab">Register</a></li>
        </ul>
      </div>
      <div class="modal-body login-body">
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade" id="why">
            <p>This quick registration will help you navigate deeper into the realm of our website. Don’t worry; your details are safe with us.</p>

          </div>
          <div class="tab-pane fade active in" id="signin">
            <form class="form-horizontal" id="signinForm">
                <fieldset>
                <div class="control-group">
                  <label class="control-label" for="useremail">Email</label>
                  <div class="controls">
                    <input id="useremail" name="useremail" type="text" class="form-control required" placeholder="Email"  value="<?php if(isset($_COOKIE['fusernamelogin']) && $_COOKIE['fusernamelogin']!=''){ echo $_COOKIE['fusernamelogin']; } ?>">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="userpassword">Password</label>
                  <div class="controls">
                    <input id="userpassword" name="userpassword" class="form-control required" type="password" placeholder="********" value="<?php if(isset($_COOKIE['fpasswordlogin']) && $_COOKIE['fpasswordlogin']!=''){ echo $_COOKIE['fpasswordlogin']; } ?>">
                  </div>
                </div>

                  <div id="appendsigninerrors"></div>

                <div class="control-group padding-left-20">


                  <div class="controls remember-label">
                    <label class="checkbox inline" for="rememberme-0">
                      <input type="checkbox" name="rememberme" id="rememberme-0" value="1" <?php if(isset($_COOKIE['fusernamelogin']) && $_COOKIE['fusernamelogin']!='' && isset($_COOKIE['fpasswordlogin']) && $_COOKIE['fpasswordlogin']!=''){ echo 'checked'; } ?>>
                      Remember me </label>
                  </div>

                  <div class="controls">
                    <input type="submit" class="link-button" value="Login" />
                  </div>
                </div>

               <p class="text-center maror">- OR -</p>

                <div class="external-login">
                <a id="googlelogin" href="<?php echo googleLogin(); ?>" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
                </div>

                <div class="external-login">
                <div class="fb-login-button" data-max-rows="2" data-size="large" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" onlogin="checkLoginState();"></div>
                    <!-- <a id="facebooklogin" href="<?php echo $this->facebook->login_url(); ?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a> -->
                </div>

                <div class="control-group forgot-password">
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#forgotpasswordmodal">Forgot Password?</a><br>
                  </div>

              </fieldset>
            </form>
          </div>
          <div class="tab-pane fade" id="signup">
            <form class="form-horizontal" id="registerUserForm">
               <p>This quick registration will help you navigate deeper into the realm of our website. Don’t worry; your details are safe with us.</p>
              <div id="appendregistererrors"></div>

              <fieldset>
                <div class="control-group">
                  <label class="control-label" for="Name">Name</label>
                  <div class="controls">
                    <input id="name" name="name" class="form-control required" type="text" placeholder="Name" minlength="2" maxlength="150">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="Email">Email</label>
                  <div class="controls">
                    <input id="email" name="email" class="form-control required" type="email" minlength="5" maxlength="250">
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="password">Password</label>
                  <div class="controls">
                    <input id="password" name="password" class="form-control required" type="password" placeholder="********"  minlength="6" maxlength="30">
                    </div>
                </div>

               <div class="control-group">
                  <label class="control-label" for="reenterpassword">Confirm Password</label>
                  <div class="controls">
                    <input id="reenterpassword" class="form-control required" name="reenterpassword" type="password" placeholder="********"  minlength="6" maxlength="30" equalTo="#password">
                  </div>
                </div>
              
                <div class="control-group">
                  <label class="control-label" for="confirmsignup"></label>
                  <div class="controls">
                    <input type="submit" value="Submit" class="link-button"/>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
<?php } ?>
