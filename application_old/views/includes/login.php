<div class="modal-dialog modal-sm">
    <div class="modal-content"> <br>
      <div class="bs-example bs-example-tabs">
        <ul id="myTab" class="nav nav-tabs">
          <li class="active"><a href="#signin" data-toggle="tab">Login</a></li>
          <li class=""><a href="#signup" data-toggle="tab">Register</a></li>
          <li class=""><a href="#why" data-toggle="tab">Why?</a></li>
        </ul>
      </div>
      <div class="modal-body">
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade in" id="why">
            <p>We need this information so that you can receive access to the site and its content. Rest assured your information will not be sold, traded, or given to anyone.</p>
            
          </div>
          <div class="tab-pane fade active in" id="signin">
            <form class="form-horizontal">
              <fieldset>
                <!-- Sign In Form --> 
                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="userid">User Name:</label>
                  <div class="controls">
                    <input required id="userid" name="userid" type="text" class="form-control" placeholder="User Name" class="input-medium" required="" value="<?php if(isset($_COOKIE['fusernamelogin']) && $_COOKIE['fusernamelogin']!=''){ echo $_COOKIE['fusernamelogin']; } ?>">
                  </div>
                </div>
                
                <!-- Password input-->
                <div class="control-group">
                  <label class="control-label" for="passwordinput">Password:</label>
                  <div class="controls">
                    <input required id="passwordinput" name="passwordinput" class="form-control" type="password" placeholder="********" class="input-medium" value="<?php if(isset($_COOKIE['fpasswordlogin']) && $_COOKIE['fpasswordlogin']!=''){ echo $_COOKIE['fpasswordlogin']; } ?>">
                  </div>
                </div>
                
                <!-- Multiple Checkboxes (inline) -->
                <div class="control-group padding-left-20">
                  <label class="control-label" for="rememberme"></label>
                  <div class="controls">
                    <label class="checkbox inline" for="rememberme-0">
                      <input type="checkbox" name="rememberme" id="rememberme-0" value="1" <?php if(isset($_COOKIE['fusernamelogin']) && $_COOKIE['fusernamelogin']!='' && isset($_COOKIE['fpasswordlogin']) && $_COOKIE['fpasswordlogin']!=''){ echo 'checked'; } ?>>
                      Remember me </label>
                  </div>
                </div>
                
                <!-- Button -->
                <div class="control-group">
                  <label class="control-label" for="signin"></label>
                  <div class="controls">
                    <button id="signin" name="signin" class="action-button shadow animate blue">Sign In</button>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
          <div class="tab-pane fade" id="signup">
            <form class="form-horizontal">
              <fieldset>
                <!-- Sign Up Form --> 
                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="Email">Email:</label>
                  <div class="controls">
                    <input id="Email" name="Email" class="form-control" type="text" placeholder="JoeSixpack@sixpacksrus.com" class="input-large" required="">
                  </div>
                </div>
                
                <!-- Password input-->
                <div class="control-group">
                  <label class="control-label" for="password">Password:</label>
                  <div class="controls">
                    <input id="password" name="password" class="form-control" type="password" placeholder="********" class="input-large" required="">
                    <em>1-8 Characters</em> </div>
                </div>
                
                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="reenterpassword">Re-Enter Password:</label>
                  <div class="controls">
                    <input id="reenterpassword" class="form-control" name="reenterpassword" type="password" placeholder="********" class="input-large" required="">
                  </div>
                </div>
                
                <!-- Button -->
                <div class="control-group">
                  <label class="control-label" for="confirmsignup"></label>
                  <div class="controls">
                    <button id="confirmsignup" name="confirmsignup" class="action-button shadow animate blue">Sign Up</button>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>  



