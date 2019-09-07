<div class="container">
    <div class="col-md-6 col-sm-6 col-xs-6 text-left no-padding">
      <ul class="loginwrapper">
        
        <?php if($this->session->userdata('fuserid')!=''){ ?>

        <li id="signinli"><a href="<?php echo site_url('myaccount'); ?>"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;My Account</a></li>
        <li id="signupli"><a href="<?php echo site_url('logout'); ?>"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;Log Out</a></li>
        
        <?php } else
        {  ?>

        <li id="signinli"><a href="#signin" data-toggle="modal" data-target=".bs-modal-sm"><i class="fa fa-user" aria-hidden="true"></i> Login</a></li>
     
        <?php } ?> 

      </ul>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 text-right no-padding">
      <ul class="socialwrapper">
        <li><a href="https://www.facebook.com/TaxidioTravel/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
        <li><a href="https://twitter.com/taxidiotravel" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
        <li><a href="https://www.instagram.com/taxidiotravel/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
      </ul>
    </div>
</div>
