<div class="container">
    <div class="">
        <div class="pull-left">
            <button class="button-menu-mobile open-left waves-effect waves-light">
                <i class="md md-menu"></i>
            </button>
            <span class="clearfix"></span>
        </div>

        <?php /* ?>
        <ul class="nav navbar-nav hidden-xs">
            <li><a href="#" class="waves-effect waves-light">Free Account</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown"
                   role="button" aria-haspopup="true" aria-expanded="false">Currency <span
                        class="caret"></span></a>
                <ul class="dropdown-menu">
                	<li><a href="#"><i class="fa fa-inr" aria-hidden="true"></i> Rupees</a></li>
                    <li><a href="#"><i class="fa fa-usd" aria-hidden="true"></i> Dollar</a></li>
                    <li><a href="#"><i class="fa fa-eur" aria-hidden="true"></i> Euro</a></li>
                    <li><a href="#"><i class="fa fa-yen" aria-hidden="true"></i> Yen</a></li>
                </ul>
            </li>
        </ul>

        <form role="search" class="navbar-left app-search pull-left hidden-xs">
             <input type="text" placeholder="Search..." class="form-control">
             <a href=""><i class="fa fa-search"></i></a>
        </form>

        <?php */ ?>


        <ul class="nav navbar-nav navbar-right pull-right">
            <li class="dropdown top-menu-item-xs">
                <?php /* ?>
                <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo site_url('assets/dashboard/images/users/avatar-8.jpg') ?>" alt="user-img" class="img-circle"> </a>
                <?php */ ?>
                <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><?php echo $this->session->userdata('name'); ?></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo site_url('myprofile'); ?>"><i class="ti-user m-r-10 text-custom"></i> Profile</a></li>
                    <!--<li><a href="javascript:void(0)"><i class="ti-settings m-r-10 text-custom"></i> Settings</a></li>
                    <li class="divider"></li>-->
                    <li><a href="<?php echo site_url('logout'); ?>"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!--/.nav-collapse -->
</div>
