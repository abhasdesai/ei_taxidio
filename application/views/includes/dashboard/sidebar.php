<div id="sidebar-menu">
  <ul>
	  
         
         
         <li class="has_sub">
          <a href="<?php echo site_url('myaccount') ?>" class="waves-effect <?php if(isset($webpage) && $webpage=='myaccount'){ echo 'active'; } ?>">
            <i class="md md-home"></i><span> Dashboard </span></a>
         </li>

         <li class="has_sub">
            <a href="<?php echo site_url('myprofile') ?>" class="waves-effect <?php if(isset($webpage) && $webpage=='myprofile'){ echo 'active'; } ?>">
              <i class="md md-account-box"></i><span> My Profile </span></a>
         </li>

          <li class="has_sub">
            <a href="<?php echo site_url('trips'); ?>" class="waves-effect <?php if(isset($webpage) && $webpage=='trips'){ echo 'active'; } ?>">
              <i class="md md-airplanemode-on"></i><span> My Trips </span></a>
         </li>

          <li class="has_sub">
            <a href="<?php echo site_url('invited-trips'); ?>" class="waves-effect <?php if(isset($webpage) && $webpage=='invited_trips'){ echo 'active'; } ?>">
              <i class="fa fa-user-plus"></i><span> Invited Trips </span></a>
         </li>
         <li class="has_sub">
            <a href="<?php echo site_url('refer-friend'); ?>" class="waves-effect <?php if(isset($webpage) && $webpage=='refer-friend'){ echo 'active'; } ?>">
              <i class="fa icon-plus"></i><span> Refer A Friend </span></a>
         </li>

          <li class="has_sub">
              <a href="javascript:void(0);" class="waves-effect <?php if(isset($webpage) && $webpage=='Feedback'){ echo 'subdrop'; } ?>"><i class="md md-email"></i> <span>  Help &amp; Feedback </span> <span class="menu-arrow"></span></a>
              <ul class="list-unstyled" style="<?php if(isset($webpage) && $webpage=='Feedback'){ echo 'display:block'; }else{ echo "display:none";  } ?>">
                  <li class="<?php if(isset($webpage) && $webpage=='Feedback'){ echo 'active'; } ?>"><a href="<?php echo site_url('userfeedbacks') ?>">Feedback</a></li>
                  <li><a href="<?php echo site_url('faq') ?>" target="_blank">FAQ</a></li>
              </ul>
          </li>

          <li class="has_sub">
            <a href="<?php echo site_url('myquestions'); ?>" class="waves-effect <?php if(isset($webpage) && $webpage=='forum'){ echo 'active'; } ?>">
              <i class="md md-chat"></i><span>Itinerary Forum</span></a>
         </li>
          <li class="has_sub">
            <a href="<?php echo site_url('my-orders'); ?>" class="waves-effect <?php if(isset($webpage) && $webpage=='forum'){ echo 'active'; } ?>">
              <i class="fa fa-money"></i><span>Order History</span></a>
         </li>
      
       <?php if($this->session->userdata('issocial')==0){ ?>
        <li class="has_sub">
          <a href="<?php echo site_url('changepassword'); ?>" class="waves-effect">
           <i class="ion-locked"></i><span>Change Password</span></a>
       </li>
       <?php } ?>
       
       <li class="has_sub">
          <p class="m-l-15">Last Login:&nbsp;<?php echo date('l M j, Y',strtotime($this->session->userdata('last_login')));?></p>
         </li>

      </ul>
  <div class="clearfix"></div>
</div>
