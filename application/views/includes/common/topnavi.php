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

        <?php
        if(!empty($this->data['trip_details']))
        {

         ?>
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-envelope"></i>
                <span class="label label-success"><?php
              echo count($this->data['trip_details']);
               ?></span>
            </a>
            <ul class="dropdown-menu" style="height:80px;padding: 4px 17px 0px;">
              <?php
              //echo '<pre>';print_r(count($this->data['trip_details']));die;
               ?>
                <li class="header">You have <?php
              echo count($this->data['trip_details']);
               ?> messages</li>
                 <li class="" "><a style="color: #797979;" href="<?php echo base_url(); ?>notifications">See All Messages</a></li>
                <li>
                    <!-- inner menu: contains the actual data -->
                   <!--  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                        
                       
                    </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0) none repeat scroll 0% 0%; width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 0px; z-index: 99; right: 1px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 1px;"></div></div> -->
                </li>
               
            </ul>
        </li>
        <?php
        }
        ?>
       
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
